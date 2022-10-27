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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$app = Factory::getApplication();
$input = $app->input;

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

<form action="<?php echo Route::_('index.php?option=com_companypartners&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="group-form" class="form-validate">
	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>
    <div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'details']); ?>
		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', empty($this->item->id) ? Text::_('COM_COMPANYPARTNERS_NEW_GROUP') : Text::_('COM_COMPANYPARTNERS_EDIT_GROUP')); ?>
        <div class="row">
            <div class="col-md-9">
                <div class="row">
	                <div class="col-md-12">
                        <div class="row">
		                <?php echo $this->getForm()->renderField('id'); ?>
		                <?php echo $this->getForm()->renderField('description'); ?>
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

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>
    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>