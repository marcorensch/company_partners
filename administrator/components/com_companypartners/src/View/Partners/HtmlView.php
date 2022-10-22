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

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Factory;

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
     * @since   1.0.0
     */
    protected $items;

    public function display($tpl = null): void
    {
        $this->items = $this->get('Items');

        if (count($errors = $this->get('Errors'))) {
			throw new GenericDataException(implode("\n", $errors), 500);
		}

        if (!count($this->items) && $this->get('IsEmptyState')) {
            $this->setLayout('emptystate');
        }
		// We don't need toolbar in the modal window
	    if($this->getLayout() !== 'modal')
	    {
		    $this->addToolbar();
			$this->sidebar = \JHtmlSidebar::render();
	    }else{
		    // In article associations modal we need to remove language filter if forcing a language.
		    // We also need to change the category filter to show categories with All or the forced language.
		    if($forcedLanguage = Factory::getApplication()->input->get('forcedLanguage', '', 'cmd')){
			    // If the language is forced we can't allow to select the language, so transform the language selector filter into an hidden field.
			    $languageXml = new \SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
//			    $this->filterForm->setField($languageXml, 'filter', true);
//			    // Also, unset the active language filter so the search tools is not open by default with this filter.
//			    unset($this->activeFilters['language']);
//			    // One last changes needed is to change the category filter to just show categories with All language or with the forced language.
//			    $this->filterForm->setFieldAttribute('category_id', 'language', '*,' . $forcedLanguage, 'filter');
		    }
	    }
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $canDo = ContentHelper::getActions('com_companypartners');
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');
        ToolbarHelper::title(Text::_('COM_COMPANYPARTNERS_MANAGER_PARTNERS'), 'address foo');

        // Show Buttons only if the user is allowed to do so
        if ($canDo->get('core.create')) {
            $toolbar->addNew('partner.add');
        }
        if($canDo->get('core.options')) {
            $toolbar->preferences('com_companypartners');
        }
    }
}
