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

        if (empty($form)) {
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

	    if (empty($data)) {
		    $data = $this->getItem();
		    // Prime some default values.
		    if ($this->getState('partner.id') == 0) {
			    $data->set('catid', $app->input->get('catid', $app->getUserState('com_companypartners.partners.filter.category_id'), 'int'));
		    }
	    }

	    if($data->get('categories')) {
			$data->set('categories', explode(",", $data->get('categories')));
	    }


        $this->preprocessData($this->typeAlias, $data);

        return $data;
    }

	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		// Load associated partner items

		if (Associations::isEnabled()) {
			$item->associations = [];
			if ($item->id != null) {
				$associations = Associations::getAssociations('com_companypartners', '#__companypartners_partners', 'com_companypartners.item', $item->id,'id', null);

				foreach ($associations as $tag => $association) {
					$item->associations[$tag] = $association->id;
				}
			}
		}
		return $item;
	}

	protected function preprocessForm($form, $data, $group = 'content')
	{
		if(Associations::isEnabled()){
			$languages = LanguageHelper::getContentLanguages(false,true,null,'ordering','asc');

			if(count($languages) > 1){

				$addform = new \SimpleXMLElement('<form />');
				$fields = $addform->addChild('fields');
				$fields->addAttribute('name','associations');

				$fieldset = $fields->addChild('fieldset');
				$fieldset->addAttribute('name','item_associations');

				foreach ($languages as $language){
					$field = $fieldset->addChild('field');
					$field->addAttribute('name',$language->lang_code);
					$field->addAttribute('type','modal_partner');
					$field->addAttribute('language',$language->lang_code);
					$field->addAttribute('label',$language->title);
					$field->addAttribute('translate_label','false');
					$field->addAttribute('select','true');
					$field->addAttribute('new','true');
					$field->addAttribute('edit','true');
					$field->addAttribute('clear','true');
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

	public function save($data){

		if(isset($data['categories'])){
			$data['categories'] = implode("," , $data['categories']);
		}

		if(parent::save($data)){
			$itemId = $this->getState($this->getName() . '.id');

			if($this->updateItemCategories($itemId, $data['categories'])){
				return true;
			}
		}

		return false;
	}

	public function delete(&$pks){
		error_log("delete");
		parent::delete($pks);
	}

	private function categoriesToArray($categories): array
	{
		$categories = explode(',', $categories);
		return !is_array($categories) ? [$categories] : $categories;
	}

	private function updateItemCategories($itemId, $formCategories){

		$formCategories = $this->categoriesToArray($formCategories);

		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		// Get all registered categories for this item from normalized table
		$query->select($db->quoteName(array('id', 'category_id')))
			->from($db->quoteName('#__companypartners_partner_category'))
			->where($db->quoteName('partner_id') . ' = ' . $db->quote($itemId));
		$db->setQuery($query);
		$storedCategories = $db->loadObjectList();

		// unset all still given/found categories from storedCategories
		foreach ($storedCategories as $key => $category){
			if(in_array($category->category_id, $formCategories)){
				unset($storedCategories[$key]);
				unset($formCategories[array_search($category->category_id, $formCategories)]);
			}
		}

		// check for leftover categories in db and delete them
		if($storedCategories){
			foreach ($storedCategories as $storedCategory){
				try{
					$query->clear()
						->delete($db->quoteName('#__companypartners_partner_category'))
						->where($db->quoteName('id') . ' = ' . $db->quote($storedCategory->id));
					$db->setQuery($query);
					$db->execute();
				}catch (Exception $e){
					$this->setError($e->getMessage());
					return false;
				}
			}
		}

		// if categories > 0 --> Add new categories to db
		if($formCategories){
			foreach ($formCategories as $formCategory){
				try
				{
					$query->clear()
						->insert($db->quoteName('#__companypartners_partner_category'))
						->columns($db->quoteName(['partner_id', 'category_id']))
						->values($db->quote($itemId) . ', ' . $db->quote($formCategory));
					$db->setQuery($query);
					$db->execute();
				}
				catch (Exception $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
		}

		return true;
	}
}
