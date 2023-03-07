<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Companypartners\Site\View\Partners;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * HTML partners View class for the Company partners component
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	protected $items;
	protected $groups;

    public function display($tpl = null)
    {
	    $this->items = $this->get('Items');
		$this->groups = $this->get('groups');
        return parent::display($tpl);
    }
}
