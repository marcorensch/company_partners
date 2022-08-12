<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Companypartners\Administrator\View\Partners;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * View class for a list of Partners.
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Method to display the view.
     *
     * @param string|null $tpl  A template file to load. [optional]
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    protected $items;

    public function display($tpl = null): void
    {
        $this->items = $this->get('Items');
        parent::display($tpl);
    }
}
