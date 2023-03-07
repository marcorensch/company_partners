<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_foos
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Companypartners\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Foo model for the Joomla Company partners component.
 *
 * @since  __BUMP_VERSION__
 */
class PartnersModel extends BaseDatabaseModel
{
	/**
	 * @var string items
	 */
	protected $_items = null;

	protected $_groups = null;

	public function __construct($config = array(), MVCFactoryInterface $factory = null)
	{
		parent::__construct($config, $factory);
		$this->_groups = $this->getAllGroups();
	}

	/**
	 * Gets a list of partners
	 *
	 * @return  mixed Object or null
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getItems()
	{
		$app = Factory::getApplication();
		$pk  = $app->input->getInt('id');

		$categories_filter = Factory::getApplication()->getParams()->get('category_filter');

		if ($this->_items === null)
		{
			$this->_items = [];
		}

		if (!isset($this->_items[$pk]))
		{
			try
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select(array('a.*', 'c.title AS category_title'))
					->from($db->quoteName('#__companypartners_partners', 'a'))
					->where('a.published = 1');
				if ($categories_filter)
				{
					$query->where('a.catid IN (' . implode(',', $categories_filter) . ')');
				}
				$query->join(
					'LEFT',
					$db->quoteName('#__categories', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid')
				)
					->order('a.title ASC');

				$db->setQuery($query);
				$partners = $db->loadObjectList();


				if (empty($partners))
				{
					return [];
				}

				$this->setFilters($partners);

				$this->_items[$pk] = $partners;
			}
			catch (\Exception $e)
			{
				$this->setError($e->getMessage());

				return false;
			}
		}


		return $this->_items[$pk];
	}

	private function getAllGroups()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select("a.*")
			->from($db->quoteName('#__companypartners_groups', 'a'))
			->where('a.published = 1')
			->order('a.ordering ASC');
		$db->setQuery($query);
		$data = $db->loadObjectList('id');

		foreach ($data as $group)
		{
			$group->itemsCount = 0;
		}

		return $data;
	}

	public function getGroups()
	{
		return $this->_groups;
	}

	private function setFilters(&$partners)
	{
		foreach ($partners as $key => $partner)
		{
			$partner->filters = array();
			$groups           = explode(',', $partner->groups);
			foreach ($groups as $group)
			{
				if (array_key_exists($group, $this->_groups))
				{
					$partner->filters[] = $this->_groups[$group]->alias;
					$this->_groups[$group]->itemsCount++;
				}

			}
		}
	}
}
