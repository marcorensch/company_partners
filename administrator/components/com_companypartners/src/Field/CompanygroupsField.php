<?php
/**
 * @package     Joomla.Administrator
 *              Joomla.Site
 * @subpackage  com_companypartners
 * @author      Marco Rensch
 * @since 	    1.0.0
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   Copyright (C) 2022 nx-designs NXD
 *
 */

namespace NXD\Component\CompanyPartners\Administrator\Field;

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

class CompanygroupsField extends ListField{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $type = 'Companygroups';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.0
	 */
	protected function getInput()
	{
		return parent::getInput();

	}

	protected function getOptions()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(['id', 'title']));
		$query->from($db->quoteName('#__companypartners_groups'));
		$db->setQuery($query);
		$groups = $db->loadObjectList();
		$options = array();
		$selected = explode(",", $this->value);
		foreach ($groups as $group) {
			$option = HtmlHelper::_('select.option', $group->id, $group->title);
			if(in_array($group->id, $selected)){
				$option->optionattr = array(
					'selected' => "selected"
				);
			}

			$options[] = $option;
		}

		return $options;
	}
}