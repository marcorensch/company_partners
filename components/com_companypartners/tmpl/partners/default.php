<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$params = Factory::getApplication()->getParams();

$wa = $this->document->getWebAssetManager();
if(intval($params->get('load_uikit','1'))){
    $wa->useScript('com_companypartners.uikitjs')
        ->useScript('com_companypartners.uikitIconsjs')
        ->useStyle('com_companypartners.uikitcss');
}

$containerThemeCls = "";
if($params->get('theme','light') === 'dark'){
	$containerThemeCls = " uk-light";
}

$wa->useStyle('com_companypartners.main');

// Limit Contact Information to defined groups if active:
$canSeeContactInfo = false;
$allowedUserGroups = $params->get('limit_contactoptions', array());
if(!empty($allowedUserGroups)){
    $user = Factory::getUser();
    $thisUsersGroups = array_intersect($allowedUserGroups, $user->getAuthorisedViewLevels());
    if(!empty($thisUsersGroups)){
        $canSeeContactInfo = true;
    }
}else{
    $canSeeContactInfo = true;
}

$filterCls = "";
switch($params->get('filter_type', 'tabs')){
    case 'subnav':
        $filterCls = "uk-subnav";
        break;
    case 'subnav-pills':
        $filterCls = "uk-subnav uk-subnav-pill";
        break;
    case 'tabs':
    default:
        $filterCls = "uk-tab";
}

$uikitListCls = 'uk-list-' . $params->get('items_list_type', 'divider') . ' uk-list-' . $params->get('items_list_size', 'large');

?>

<div class="uk-section <?php echo $containerThemeCls;?>">
	<div class="nxd-companypartners-list-container">
        <div uk-filter="target: .js-filter">

            <ul class="<?php echo $filterCls;?>">
                <?php include_once __DIR__ . '/default.filters.php'; ?>
            </ul>

            <ul class="js-filter uk-list <?php echo $uikitListCls;?>">
                <?php foreach ($this->items as $partner):
	                include(__DIR__ . '/default.item.php');
                ?>
                <?php endforeach; ?>
            </ul>

        </div>
	</div>
</div>