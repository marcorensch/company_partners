<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
    'textPrefix' => 'COM_COMPANYPARTNERS',
    'formURL' => 'index.php?option=com_companypartners',
    'helpURL' => 'https://github.com/marcorensch/company_partners/blob/f1ee6e76261a79dfb12285768191fbdd196843d2/README.md',
    'icon' => 'icon-copy',
];

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com_companypartners') || count($user->getAuthorisedCategories('com_companypartners', 'core.create')) > 0) {
    $displayData['createURL'] = 'index.php?option=com_companypartners&task=partner.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
