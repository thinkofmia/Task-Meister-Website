<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_review
 * 
 * @copyright   (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('formbehavior.chosen', 'select');

$listOrder  = $this->escape($this->filter_order);
$listDirn   = $this->escape($this->filter_order_Dir);
?>
<form action="index.php?option=com_review&view=reviews" method="post" id="adminForm" name="adminForm">
    <div class="row-fluid">
        <div class="span6">
            <?php
                echo JLayoutHelper::render(
                    'joomla.searchtools.default',
                    array('view' => $this)
                );
            ?>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_REVIEW_NUM'); ?></th>
                <th width="2%">
                    <?php echo JHtml::_('grid.checkall'); ?>
                </th>
                <th width="3%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_REVIEWS_AID', 'aid', $listDirn, $listOrder); ?>
                </th>
                <th width="3%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_REVIEWS_UID', 'uid', $listDirn, $listOrder); ?>   
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_REVIEWS_UPDATED', 'updated', $listDirn, $listOrder); ?>   
                </th>
                <th width="11%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_REVIEWS_SUMMARY', 'summary', $listDirn, $listOrder); ?>   
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_REVIEWS_EASE_RATING', 'ease_rating', $listDirn, $listOrder); ?>
                </th>
                <th width="29%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_REVIEWS_EASE', 'ease', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_REVIEWS_EFFECT_RATING', 'effectiveness_rating', $listDirn, $listOrder); ?>
                </th>
                <th width="29%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_REVIEWS_EFFECT', 'effectiveness', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_REVIEWS_PUBLISHED', 'published', $listDirn, $listOrder); ?>
                </th>
                <th width="2%">
                    <?php echo JHtml::_('grid.sort', 'COM_REVIEW_ID', 'id', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="9">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php if (!empty($this->items)) : ?>
                <?php foreach ($this->items as $i => $row) :
                    $link = JRoute::_('index.php?option=com_review&task=review.edit&id=' . $row->id);
                ?>
                
                    <tr>
                        <td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REVIEW_EDIT_REVIEW'); ?>">
                                <?php echo $row->aid; ?>
                            </a>
						</td>
						<td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REVIEW_EDIT_REVIEW'); ?>">
                                <?php echo $row->uid; ?>
                            </a>
						</td>
						<td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REVIEW_EDIT_REVIEW'); ?>">
                                <?php echo $row->updated; ?>
                            </a>
						</td>
						<td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REVIEW_EDIT_REVIEW'); ?>">
                                <?php echo $row->summary; ?>
                            </a>
						</td>
						<td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REVIEW_EDIT_REVIEW'); ?>">
                                <?php echo $row->ease_rating; ?>
                            </a>
						</td>
						<td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REVIEW_EDIT_REVIEW'); ?>">
                                <?php echo $row->ease; ?>
                            </a>
						</td>
						<td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REVIEW_EDIT_REVIEW'); ?>">
                                <?php echo $row->effectiveness_rating; ?>
                            </a>
						</td>
						<td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REVIEW_EDIT_REVIEW'); ?>">
                                <?php echo $row->effectiveness; ?>
                            </a>
						</td>
						<td align="center">
							<?php echo JHtml::_('jgrid.published', $row->published, $i, 'reviews.', true, 'cb'); ?>
						</td>
						<td align="center">
							<?php echo $row->id; ?>
						</td>
                    </tr>
                <?php endforeach; ?>
			<?php endif; ?>
        </tbody>
    </table>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>