<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$app = Factory::getApplication();
$input = $app->input;

$assoc = Associations::isEnabled();

$this->ignore_fieldsets = ['item_associations'];
$this->useCoreUI = true;

$isModal = $input->get('layout') === 'modal';

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate')
    ->useScript('com_companypartners.admin-partners-letter');

$layout  = 'edit';
$tmpl = $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>

<form action="<?php echo Route::_('index.php?option=com_companypartners&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="partner-form" class="form-validate">
    <div>
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'details']); ?>
		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', empty($this->item->id) ? Text::_('COM_COMPANYPARTNERS_NEW_PARTNER') : Text::_('COM_COMPANYPARTNERS_EDIT_PARTNER')); ?>
        <div class="row">
            <div class="col-md-9">
                <div class="row">
	                <div class="col-md-8">
                        <div class="row">
		                <?php echo $this->getForm()->renderField('id'); ?>
		                <?php echo $this->getForm()->renderField('title'); ?>
		                <?php echo $this->getForm()->renderField('alias'); ?>
		                <?php echo $this->getForm()->renderField('catid'); ?>
		                <?php echo $this->getForm()->renderField('groups'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
	            <?php echo $this->getForm()->renderField('access'); ?>
	            <?php echo $this->getForm()->renderField('published'); ?>

	            <?php echo $this->getForm()->renderField('publish_up'); ?>
	            <?php echo $this->getForm()->renderField('publish_down'); ?>
	            <?php echo $this->getForm()->renderField('language'); ?>
            </div>
        </div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

	    <?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

        <?php if(!$isModal && $assoc) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'associations', Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
                <fieldset id="fieldset-associations" class="options-form">
                    <legend><?php echo Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS') ?></legend>
                    <div>
	                    <?php echo LayoutHelper::render('joomla.edit.associations', $this); ?>
                    </div>
                </fieldset>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php elseif ($isModal && $assoc) : ?>
            <div class="hidden">
                <div class="hidden"><?php echo LayoutHelper::render('joomla.edit.associations', $this); ?></div>
            </div>
        <?php endif; ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>
    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
