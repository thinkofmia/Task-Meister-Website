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
?>
<form action="index.php?option=com_review&view=reviews" method="post" id="adminForm" name="adminForm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_REVIEW_NUM'); ?></th>
                <th width="2%">
                    <?php echo JHtml::_('grid.checkall'); ?>
                </th>
                <th width="3%">
                    <?php echo JText::_('COM_REVIEW_REVIEWS_AID') ;?>
                </th>
                <th width="3%">
                    <?php echo JText::_('COM_REVIEW_REVIEWS_UID') ;?>
                </th>
                <th width="5%">
                    <?php echo JText::_('COM_REVIEW_REVIEWS_EASE_RATING') ;?>
                </th>
                <th width="37%">
                    <?php echo JText::_('COM_REVIEW_REVIEWS_EASE') ;?>
                </th>
                <th width="5%">
                    <?php echo JText::_('COM_REVIEW_REVIEWS_EFFECT_RATING') ;?>
                </th>
                <th width="37%">
                    <?php echo JText::_('COM_REVIEW_REVIEWS_EFFECT') ;?>
                </th>
                <th width="5%">
                    <?php echo JText::_('COM_REVIEW_PUBLISHED'); ?>
                </th>
                <th width="2%">
                    <?php echo JText::_('COM_REVIEW_ID'); ?>
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
                                <?php $auid = explode(':', $row->auid); echo $auid[0]; ?>
                            </a>
						</td>
						<td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REVIEW_EDIT_REVIEW'); ?>">
                                <?php echo $auid[1]; ?>
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
							<?php echo JHtml::_('jgrid.published', $row->published, $i, 'helloworlds.', true, 'cb'); ?>
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