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

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Language\LanguageHelper;

/**
 * Item Model for a Partner.
 *
 * @since  __BUMP_VERSION__
 */
class PartnerModel extends AdminModel
{
	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	public $typeAlias = 'com_companypartners.partner';

	protected $associationsContext = 'com_companypartners.item';

	private $itemGroupsFromDb = array();
	private $itemGroupIds = array();
	private $itemId = 0;

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  \JForm|boolean  A JForm object on success, false on failure
	 *
	 * @throws  \Exception
	 * @since   __BUMP_VERSION__
	 */
	public function getForm($data = [], $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm($this->typeAlias, 'partner', ['control' => 'jform', 'load_data' => $loadData]);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @throws \Exception
	 * @since   __BUMP_VERSION__
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState($this->option . 'com_companypartners.edit.partner.data', []);

		if (empty($data))
		{
			$data = $this->getItem();
			// Prime some default values.
			if ($this->getState('partner.id') == 0)
			{
				$data->set('catid', $app->input->get('catid', $app->getUserState('com_companypartners.partners.filter.category_id'), 'int'));
			}
		}

		if ($data->get('categories'))
		{
			$data->set('categories', explode(",", $data->get('categories')));
		}


		$this->preprocessData($this->typeAlias, $data);

		return $data;
	}

	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		// Load associated partner items

		if (Associations::isEnabled())
		{
			$item->associations = [];
			if ($item->id != null)
			{
				$associations = Associations::getAssociations('com_companypartners', '#__companypartners_partners', 'com_companypartners.item', $item->id, 'id', null);

				foreach ($associations as $tag => $association)
				{
					$item->associations[$tag] = $association->id;
				}
			}
		}

		return $item;
	}

	protected function preprocessForm($form, $data, $group = 'content')
	{
		if (Associations::isEnabled())
		{
			$languages = LanguageHelper::getContentLanguages(false, true, null, 'ordering', 'asc');

			if (count($languages) > 1)
			{

				$addform = new \SimpleXMLElement('<form />');
				$fields  = $addform->addChild('fields');
				$fields->addAttribute('name', 'associations');

				$fieldset = $fields->addChild('fieldset');
				$fieldset->addAttribute('name', 'item_associations');

				foreach ($languages as $language)
				{
					$field = $fieldset->addChild('field');
					$field->addAttribute('name', $language->lang_code);
					$field->addAttribute('type', 'modal_partner');
					$field->addAttribute('language', $language->lang_code);
					$field->addAttribute('label', $language->title);
					$field->addAttribute('translate_label', 'false');
					$field->addAttribute('select', 'true');
					$field->addAttribute('new', 'true');
					$field->addAttribute('edit', 'true');
					$field->addAttribute('clear', 'true');
				}

				$form->load($addform, false);
			}
			parent::preprocessForm($form, $data, $group);
		}
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   \Joomla\CMS\Table\Table  $table  The Table object
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function prepareTable($table)
	{
		$table->generateAlias();
	}

	public function save($data)
	{
		$this->itemGroupIds = $data['groups'];
		if(is_null($this->itemGroupIds)){
			$this->itemGroupIds = array();
		}

		$data['groups']	= implode(",", $data['groups']);

		Factory::getApplication()->enqueueMessage("Groups: " . var_export($this->itemGroupIds,1));

		if (parent::save($data))
		{
			$this->itemId       = $this->getState($this->getName() . '.id');
			if ($this->updateItemGroups())
			{
				return true;
			}
		}

		return false;
	}

	public function delete(&$pks)
	{
		error_log("delete");
		parent::delete($pks);
	}

	private function updateItemGroups(): bool
	{
		// Get all linked groups for this item from normalized table
		$this->itemGroupsFromDb = $this->getGroupsForItem();

		// unset all still given/found groups from $itemGroupsFromDb
		$this->removeLinkedGroupsFromUpdateList();

		// check for leftover groups in db and delete the link
		if(!$this->deleteItemGroups()){
			return false;
		}

		// if this->itemGroups > 0 --> Add new group link to db
		if(!$this->addNewGroupLinks()){
			return false;
		}

		return true;
	}

	private function getGroupsForItem(): array
	{
		try
		{
			$db    = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id', 'partner_id', 'group_id')))
				->from($db->quoteName('#__companypartners_partner_group'))
				->where($db->quoteName('partner_id') . ' = ' . $db->quote($this->itemId));
			$db->setQuery($query);
		}
		catch (\Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');

			return false;
		}

		return $db->loadObjectList();
	}

	private function removeLinkedGroupsFromUpdateList()
	{
		foreach ($this->itemGroupsFromDb as $key => $group)
		{
			if (in_array($group->group_id, $this->itemGroupIds))
			{
				unset($this->itemGroupsFromDb[$key]);
				unset($this->itemGroupIds[array_search($group->group_id, $this->itemGroupIds)]);
			}
		}
	}

	private function deleteItemGroups(): bool
	{
		Factory::getApplication()->enqueueMessage("Delete: " . var_export($this->itemGroupsFromDb,1));
		try
		{
			if (count($this->itemGroupsFromDb) > 0)

			{
				$db    = Factory::getDbo();
				$query = $db->getQuery(true);
				foreach ($this->itemGroupsFromDb as $itemGroup)
				{
					$query->clear()
						->delete($db->quoteName('#__companypartners_partner_group'))
						->where($db->quoteName('id') . ' = ' . $db->quote($itemGroup->id));
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
		catch (\Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			error_log($e->getMessage());
			return false;
		}

		return true;
	}

	private function addNewGroupLinks(): bool
	{
		try
		{
			if (count($this->itemGroupIds) > 0)
			{
				$db    = Factory::getDbo();
				$query = $db->getQuery(true);
				foreach ($this->itemGroupIds as $itemGroupId)
				{
					if((int) $itemGroupId === 0) continue;
					$columns = array('partner_id', 'group_id');
					$values  = array($db->quote($this->itemId), $db->quote((int)$itemGroupId));
					$query->clear()
						->insert($db->quoteName('#__companypartners_partner_group'))
						->columns($db->quoteName($columns))
						->values(implode(',', $values));
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			error_log($e->getMessage());
			return false;
		}

		return true;
	}
}
