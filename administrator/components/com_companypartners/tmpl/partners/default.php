<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Session\Session;

$wa = $this->document->getWebAssetManager();
$wa->useScript('table.columns');

$canChange = true;
$assoc = Associations::isEnabled();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder === 'a.ordering';

if ($saveOrder && !empty($this->items))
{
    $saveOrderingUrl = 'index.php?option=com_companypartners&task=partners.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}

?>

<?php $editIcon = '<span class="fa fa-pencil-square mr-2" aria-hidden="true"></span>'; ?>
<form action="<?php echo Route::_('index.php?option=com_companypartners'); ?>" method="post" name="adminForm"
      id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
				<?php if (empty($this->items)) : ?>
                    <div class="alert alert-warning">
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
				<?php else : ?>
                    <table class="table" id="partnerList">
                        <caption class="visually-hidden">
		                    <?php echo Text::_('COM_COMPANYPARTNERS_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                        <tr>
                            <td style="width:1%" class="text-center">
								<?php echo HTMLHelper::_('grid.checkall'); ?>
                            </td>

                            <th scope="col" style="width:1%" class="text-center d-none d-md-table-cell">
	                            <?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
                            </th>

                            <th scope="col" style="width: 1%; min-width: 85px" class="text-center">
		                        <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS','a.published', $listDirn, $listOrder) ?>
                            </th>

                            <th scope="col" style="min-width:150px" class="d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_COMPANYPARTNERS_TABLEHEAD_NAME', 'a.title', $listDirn, $listOrder); ?>
                            </th>

                            <th scope="col" style="width:1%" class="text-center d-none d-md-table-cell">
		                        <?php echo Text::_('COM_COMPANYPARTNERS_TABLE_TABLEHEAD_GROUPS'); ?>
                            </th>
                            <th scope="col" style="width:10%" class="d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS','access_level', $listDirn, $listOrder) ?>
                            </th>
							<?php if ($assoc) : ?>
                                <th scope="col" style="width:10%" class="d-none d-md-table-cell">
	                                <?php echo HTMLHelper::_('searchtools.sort', 'COM_COMPANYPARTNERS_HEADING_ASSOCIATION','association', $listDirn, $listOrder) ?>
                                </th>
							<?php endif; ?>
							<?php if (Multilanguage::isEnabled()) : ?>
                                <th scope="col" style="width:10%" class="d-none d-md-table-cell">
	                                <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE','language', $listDirn, $listOrder) ?>
                                </th>
							<?php endif; ?>
                            <th scope="col">
	                            <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID','a.id', $listDirn, $listOrder) ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody <?php if ($saveOrder) :
	                        ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="true"<?php
                        endif; ?>>
						<?php
						$n = count($this->items);
						foreach ($this->items as $i => $item) :
							$ordering  = ($listOrder == 'ordering');
							$item->cat_link = Route::_('index.php?option=com_categories&extension=com_companypartners&task=edit&type=other&cid[]=' . $item->catid);
                            ?>
                            <tr class="row<?php echo $i % 2; ?>" data-draggable-group="<?php echo $item->catid; ?>">
                                <td class="text-center">
		                            <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td class="text-center d-none d-md-table-cell">
		                            <?php
		                            $iconClass = '';
		                            if (!$canChange) {
			                            $iconClass = ' inactive';
		                            } elseif (!$saveOrder) {
			                            $iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
		                            }
		                            ?>
                                    <span class="sortable-handler <?php echo $iconClass ?>">
                                            <span class="icon-ellipsis-v" aria-hidden="true"></span>
                                        </span>
		                            <?php if ($canChange && $saveOrder) : ?>
                                        <input type="text" name="order[]" size="5"
                                               value="<?php echo $item->ordering; ?>" class="width-20 text-area-order hidden">
		                            <?php endif; ?>
                                </td>

                                <td class="text-center">
		                            <?php
		                            echo HTMLHelper::_('jgrid.published', $item->published, $i, 'partners.', $canChange , 'cb', $item->publish_up, $item->publish_down);
		                            ?>
                                </td>
                                <th scope="row" class="has-context">
                                    <div style="display:none">
										<?php echo $this->escape($item->title); ?>
                                    </div>
                                    <a class="hasTooltip"
                                       href="<?php echo Route::_('index.php?option=com_companypartners&task=partner.edit&id=' . (int) $item->id); ?>"
                                       title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape(addslashes($item->title)); ?>">
										<?php echo $editIcon; ?><?php echo $this->escape($item->title); ?>
                                    </a>
                                    <div class="small">
	                                    <span><?php echo Text::_('JALIAS') . ': ' . $this->escape($item->alias); ?></span><br />
	                                    <span><?php echo Text::_('JCATEGORY') . ': ' . $this->escape($item->category_title); ?></span>
                                    </div>
                                </th>
                                <td class="small d-none d-md-table-cell">
	                                <?php foreach ($item->groups as $group) : ?>
                                        <span class="badge bg-primary" style="font-weight: 400; margin-bottom:5px;"><?php echo $this->escape($group->title); ?></span>
	                                <?php endforeach; ?>
                                </td>

                                <td class="small d-none d-md-table-cell">
									<?php echo $item->access_level; ?>
                                </td>
								<?php if ($assoc) : ?>
                                    <td class="d-none d-md-table-cell">
										<?php if ($item->association) : ?>
											<?php echo HTMLHelper::_('partnersadministrator.association', $item->id); ?>
										<?php endif; ?>
                                    </td>
								<?php endif; ?>
								<?php if (Multilanguage::isEnabled()) : ?>
                                    <td class="small d-none d-md-table-cell">
										<?php echo LayoutHelper::render('joomla.content.language', $item); ?>
                                    </td>
								<?php endif; ?>
                                <td class="d-none d-md-table-cell">
									<?php echo $item->id; ?>
                                </td>
                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
					<?php echo $this->pagination->getListFooter(); ?>
				<?php endif; ?>
                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
	            <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
