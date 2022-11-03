<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Companypartners\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

/**
 * CompanyPartners Table class.
 *
 * @since  __BUMP_VERSION__
 */
class PartnerTable extends Table
{
    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     *
     * @since   __BUMP_VERSION__
     */
    public function __construct(DatabaseDriver $db)
    {
        $this->typeAlias = 'com_companypartners.partner';

        parent::__construct('#__companypartners_partners', 'id', $db);
    }

    /**
     * Generate a valid alias from title / date.
     * Remains public to be able to check for duplicated alias before saving
     *
     * @return  string
     * @since  __BUMP_VERSION__
     */
    public function generateAlias()
    {
        if (empty($this->alias)) {
            $this->alias = $this->title;
        }

        $this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

        if (trim(str_replace('-', '', $this->alias)) == '') {
            $this->alias = 'partner_' . Factory::getDate()->format('Y-m-d-H-i-s');
        }
	    $this->alias = $this->createValidAlias($this->alias);

        return $this->alias;
    }

	private function createValidAlias($alias){
		if($count = $this->checkIfAliasExists($alias)){
			$count++;
			$alias .= '-' . $count;
		};
		if($this->checkIfAliasExists($alias) > 0){
			$alias = $this->createValidAlias($alias);
		}
		return $alias;
	}

	private function checkIfAliasExists($alias){
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)')
			->from($db->quoteName('#__companypartners_partners'))
			->where($db->quoteName('alias') . ' = ' . $db->quote($alias));
		$db->setQuery($query);
		return $db->loadResult();
	}

    public function check()
    {

        try {
            parent::check();
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        // Check the publishing down date is not earlier than publish up.
        if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up) {
            $this->setError(Text::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));
            return false;
        }

        // Set publish_up, publish_down to null if not set
        if (!$this->publish_up) {
            $this->publish_up = null;
        }

        if (!$this->publish_down) {
            $this->publish_down = null;
        }
        return true;
    }

    public function store($updateNulls = true)
    {
	    // Transform the params field
	    if (is_array($this->params)) {
		    $registry = new Registry($this->params);
		    $this->params = (string) $registry;
	    }else{
			$this->params = '';
	    }

	    return parent::store($updateNulls);
    }
}
