<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_foos
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

if ($this->get('State')->get('params')->get('show_partner_name_label'))
{
    echo Text::_('COM_COMPANYPARTNERS_NAME');
}
echo $this->item->name;
