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

if(is_null($partner)){
    return;
}
?>

<li class="partner" data-group="<?php echo implode(" ", $partner->filters);?>">
	<div class="uk-grid uk-grid-small uk-flex uk-flex-middle">
		<div class="uk-width-expand">
			<?php echo $partner->title;?>
		</div>
		<?php if($canSeeContactInfo):?>
			<div class="nxd-contact-link-container">
				<?php if($partner->phone):?>
					<a href="tel:<?php echo htmlspecialchars($partner->phone);?>">
						<span uk-icon="icon: receiver; ratio: 1.1"></span>
					</a>
				<?php endif;?>
			</div>
			<div class="nxd-contact-link-container">
				<?php if($partner->email):?>
					<a href="mailto:<?php echo htmlspecialchars($partner->email);?>">
						<span uk-icon="icon: mail; ratio: 1.1"></span>
					</a>
				<?php endif;?>
			</div>
			<div class="nxd-contact-link-container">
				<?php if($partner->web):?>
					<a href="<?php echo htmlspecialchars($partner->web);?>" target="_blank">
						<span uk-icon="icon: world; ratio: 1.1"></span>
					</a>
				<?php endif;?>
			</div>
		<?php endif;?>
	</div>
</li>
