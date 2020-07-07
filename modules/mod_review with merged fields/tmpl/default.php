<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_review
 * @license     GNU/GPL, see LICENSE.php
 * mod_review is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
**/

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
$document = Factory::getDocument();
$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
$document->addStyleSheet(JUri::base() . 'modules/mod_review/css/star-rating.css');
$document->addStyleSheet(JUri::base() . 'modules/mod_review/css/review.css');
$document->addScript(JUri::base() . 'modules/mod_review/js/star-rating.js');
$document->addScript(JUri::base() . 'modules/mod_review/js/review.js');
//JForm::addFieldPath(__DIR__.'/../models/fields');
// This file collects the data collected by mod_review.php and generates the HTML to be displayed
?>

<div id="testimonials">
    <section>
        <h1><?php echo JText::_('MOD_REVIEW_FORM_TESTIMONIAL_LABEL') . ' (' . count($testimonials) . ')'; ?></h1>
        <?php if(!empty($testimonials)): ?>
            <div class="reviews">
                <?php echo ModReviewHelper::generateReviewStatistics($testimonials); $counter = 0; foreach($testimonials as $row): ?>
                    <div class="review<?php echo ($counter >= 3 ? ' read-all' . ' read-more-' . floor($counter / 3) : '');?>">
                        <div class="review-summary">
                            <?php echo ModReviewHelper::renderStarRating($row->rating); ?><!-- <h2> Summary</h2> -->
                            <div style="padding-top: 30px;">
                                <p><?php echo ModReviewHelper::replaceYTUrl($row->review); ?></p>
                            </div>
                            <div>
                                <span>Posted by: <?php echo ModReviewHelper::getName($row->uid);?> on <?php echo ModReviewHelper::fmtDate($row->created); ?></span><br/>
                                <span>Last updated: <?php echo ModReviewHelper::fmtDate($row->updated); ?></span><br/>
                            </div>
                        </div>
                        <hr/>
                    </div>
                <?php $counter++; endforeach; ?>
                <span>
                    <button class="read-all">Read all</button>
                </span>
            </div>
        <?php else : ?>
            <p class="review-status-msg"><?php echo JText::_('MOD_REVIEW_TESTIMONIALS_EMPTY'); ?><p>
        <?php endif; ?>
    </section>
</div>
<div id="review-form">
    <section>
        <?php $form = JForm::getInstance('add_review', __DIR__.'/../models/forms/add_review.xml'); ?>
        <div class="no-text-center">
            <form action="" method="POST" name="submit_review" id="submit_review">
                <div class="form-vertical">
                    <fieldset class="submit_review">
                        <h1>
                            <?php if(ModReviewHelper::setForm($form))
                                {
                                    echo JText::_('MOD_REVIEW_FORM_EDIT');
                                }
                                else
                                {
                                    echo JText::_('MOD_REVIEW_FORM_SUBMIT');   
                                }
                            ?>
                        </h1>
                        <?php if(JUri::getInstance()->hasVar('submit_status'))
                        {
                            echo '<p>' . ModReviewHelper::displayStatus() . '</p>';
                        }
                        ?>
                        <?php if(ModReviewHelper::hasDeployed()) : ?>
                            <div>
                                <?php
                                    foreach($form->getFieldset() as $field) {
                                        echo $field->renderField();
                                    }
                                ?>
                                <?php echo JHtml::_('form.token'); ?>
                                <input type="submit" value=<?php echo JText::_('MOD_REVIEW_SUBMIT') ?>>
                            </div>
                        <?php else : ?>
                            <p class="review-status-msg"><?php echo JText::_('MOD_REVIEW_FORM_NOT_ALLOWED'); ?></p>
                        <?php endif; ?>
                    </fieldset>
                </div>
            </form>
        </div>
    </section>
</div>
