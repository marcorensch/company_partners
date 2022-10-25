<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Companypartners\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;


/**
 * Methods supporting a list of foo records.
 *
 * @since  __BUMP_VERSION__
 */
class PartnersModel extends ListModel
{
	private $categories = null;
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     \JControllerLegacy
     *
     * @since   __BUMP_VERSION__
     */
    public function __construct($config = [])
    {

        parent::__construct($config);
	    $this->categories = $this->getAllCategories();
    }
    /**
     * Build an SQL query to load the list data.
     *
     * @return  \Joomla\Database\QueryInterface
     *
     * @since   __BUMP_VERSION__
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $db->quoteName(
				[
					'a.id', 'a.name', 'a.alias', 'a.access',
					'a.catid','a.categories','a.published', 'a.publish_up', 'a.publish_down',
					'a.language'
				]
            )
        );
        $query->from($db->quoteName('#__companypartners_partners','a'));

	    // Join over the asset groups.
	    $query->select($db->quoteName('ag.title', 'access_level'))
		    ->join(
			    'LEFT',
			    $db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName('ag.id') . ' = ' . $db->quoteName('a.access')
		    );

	    // Join over the language
	    $query->select($db->quoteName('l.title', 'language_title'))
		    ->select($db->quoteName('l.image', 'language_image'))
		    ->join(
				'LEFT',
				$db->quoteName('#__languages', 'l') . ' ON ' . $db->quoteName('l.lang_code') . ' = ' . $db->quoteName('a.language')
		    );

		// Join over the Associations.
	    if(Associations::isEnabled()){
			$subQuery = $db->getQuery(true)
				->select('COUNT(' . $db->quoteName('asso1.id') . ') > 1')
				->from($db->quoteName('#__associations', 'asso1'))
				->join('INNER', $db->quoteName('#__associations', 'asso2') . ' ON ' . $db->quoteName('asso1.key') . ' = ' . $db->quoteName('asso2.key'))
				->where(
					[
						$db->quoteName('asso1.id') . ' = ' . $db->quoteName('a.id'),
						$db->quoteName('asso1.context') . ' = ' . $db->quote('com_companypartners.item')
					]
				);
			$query->select('(' . $subQuery . ') AS ' . $db->quoteName('association'));
	    }

		// Filter the language
	    if($language = $this->getState('filter.language')){
			$query->where($db->quoteName('a.language') . ' = ' . $db->quote($language));
	    }

        return $query;
    }

	/**
	 * @throws \Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication();
		$forcedLanguage = $app->input->get('forcedLanguage', '', 'cmd');

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		// Adjust the context to support forced languages.
		if (!empty($forcedLanguage))
		{
			$this->context .= '.' . $forcedLanguage;
		}

		// List state information
		parent::populateState($ordering, $direction);

		// Force a language
		if(!empty($forcedLanguage)){
			$this->setState('filter.language', $forcedLanguage);
		}
	}

	public function getItems()
	{
		$items = parent::getItems();

		echo '<pre>' . var_export($this->categories, true) . '</pre>';
		foreach($items as $item){
			$item->categories = self::mapCategories($item->categories);
		}

		return $items;
	}

	private function getAllCategories(){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select(array('id','alias','title'));
		$query->from($db->quoteName('#__categories'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote('com_companypartners'));
		$db->setQuery($query);

		return $db->loadObjectList('id');
	}

	private function mapCategories($categories){
		$categories = explode(',', $categories);
		$categoryObjects = array();
		foreach($categories as $category){
			if(isset($this->categories[$category])){
				$categoryObjects[] = $this->categories[$category];
			}
		}

		return $categoryObjects;
	}
}
