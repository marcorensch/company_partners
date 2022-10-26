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
		$html = parent::getInput();
		$html .= '<div class="alert alert-info">Hello World</div>';
		return $html;
	}
}