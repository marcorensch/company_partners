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

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

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
     * @param string|null $tpl A template file to load. [optional]
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    protected $items;

    public function display($tpl = null): void
    {
        $this->items = $this->get('Items');

        if (count($errors = $this->get('Errors'))) { throw new GenericDataException(implode("\n", $errors), 500);}

        if (!count($this->items) && $this->get('IsEmptyState')) {
            $this->setLayout('emptystate');
        }
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');
        ToolbarHelper::title(Text::_('COM_COMPANYPARTNERS_MANAGER_PARTNERS'), 'address foo');

        $toolbar->addNew('partner.add');
        $toolbar->preferences('com_companypartners');
    }
}
