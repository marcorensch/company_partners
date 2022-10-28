<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_foos
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

$wa = $this->document->getWebAssetManager();
$wa->useScript('com_companypartners.uikitjs')
	->useStyle('com_companypartners.uikitcss')
    ->useStyle('com_companypartners.main');
?>

<div class="uk-section">
	<div class="nxd-companypartners-list-container">
        <div uk-filter="target: .js-filter">

            <ul class="uk-subnav uk-subnav-pill">
                <li class="uk-active" uk-filter-control><a href="#"><?php echo Text::_('COM_NXDCP_SITE_LABEL_ALL');?></a></li>
                <?php foreach($this->groups as $group) : ?>
                    <li class="" uk-filter-control="[data-group*='<?php echo $group->alias; ?>']">
                        <a href="#"><?php echo $group->title; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <ul class="js-filter uk-list uk-list-striped uk-list-large">
                <?php foreach ($this->items as $partner):?>
                    <li data-group="<?php echo implode(" ", $partner->filters);?>"><?php echo $partner->title;?></li>
                <?php endforeach; ?>
            </ul>

        </div>
	</div>
</div>