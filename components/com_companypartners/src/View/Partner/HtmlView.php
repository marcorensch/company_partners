<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Companypartners\Site\View\Partner;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

/**
 * HTML partner View class for the Company partners component
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    protected $item;
	protected $params = null;
	protected $state;

    public function display($tpl = null)
    {
	    $item = $this->item = $this->get('Item');
	    $state = $this->state = $this->get('State');
	    $params = $this->params = $state->get('params');
	    $itemparams = new Registry(json_decode($item->params));
	    $temp = clone $params;

	    /**
	     * $item->params are the foo params, $temp are the menu item params
	     * Merge so that the menu item params take priority
	     *
	     * $itemparams->merge($temp);
	     */
	    // Merge so that partner params take priority
	    $temp->merge($itemparams);
	    $item->params = $temp;

	    Factory::getApplication()->triggerEvent('onContentPrepare', ['com_companypartners.partner', &$item, &$item->params]);

		// Store the events for later
	    $item->event = new \stdClass;

		$results = Factory::getApplication()->triggerEvent('onContentAfterTitle', ['com_companypartners.partner', &$item, &$item->params]);
	    $item->event->afterDisplayTitle = trim(implode("\n", $results));

	    $results = Factory::getApplication()->triggerEvent('onContentBeforeDisplay', ['com_companypartners.partner', &$item, &$item->params]);
	    $item->event->beforeDisplayContent = trim(implode("\n", $results));

	    $results = Factory::getApplication()->triggerEvent('onContentAfterDisplay', ['com_companypartners.partner', &$item, &$item->params]);
	    $item->event->afterDisplayContent = trim(implode("\n", $results));

        return parent::display($tpl);
    }
}
