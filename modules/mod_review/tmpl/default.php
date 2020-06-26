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
$document->addScript(JUri::base() . 'modules/mod_review/js/star-rating.js');
//JForm::addFieldPath(__DIR__.'/../models/fields');
// This file collects the data collected by mod_review.php and generates the HTML to be displayed
?>

<div id="testimonials">
    <section style="background-color: black; margin-left: -2.2%; margin-right: -2.2%; padding: 100px;">
        <h1 style="color: #ffc03a; text-align: center; text-transform: uppercase;"><?php echo JText::_('MOD_REVIEW_FORM_TESTIMONIAL_LABEL') ?></h1>
        <?php if(!empty($testimonials)): ?>
            <?php foreach($testimonials as $row): ?>
                <div class="text-center">
                    <table class="table" style="color: white;">
                        <tr>
                            <td>Summary: <?php echo $row->summary; ?></td>
                        </tr>
                        <tr>
                            <td>Rating: <?php echo $row->overall_rating; ?></td>
                        </tr>
                        <tr>
                            <td>Posted by: <?php echo ModReviewHelper::getName($row->uid);?></td>
                        </tr>
                        <tr>
                            <td>Last updated: <?php echo ModReviewHelper::fmtDate($row->updated); ?></td>
                        </tr>
                        <tr>
                            <td>Ease rating: <?php echo $row->ease_rating; ?></td>
                        </tr>
                        <tr>
                            <td>Ease of use: <?php echo $row->ease; ?></td>
                        </tr>
                        <tr>
                            <td>Effectiveness rating: <?php echo $row->effectiveness_rating; ?></td>
                        </tr>
                        <tr>
                            <td>Effectiveness: <?php echo $row->effectiveness; ?></td>
                        </tr>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p style="text-align: center; color: white;"><?php echo JText::_('MOD_REVIEW_TESTIMONIALS_EMPTY'); ?><p>
        <?php endif; ?>
    </section>
</div>
<div id="review-form">
    <section style="background-color: #191919; margin-left: -2.2%; margin-right: -2.2%; padding: 100px;">
        <?php $form = JForm::getInstance('add_review', __DIR__.'/../models/forms/add_review.xml'); ?>
        <div class="text-center">
            <form style="color: white; width:50vw; margin: 0 auto;" action="" method="POST" name="submit_review" id="submit_review">
                <div class="form-vertical">
                    <fieldset class="submit_review">
                        <h1 style="color: #ffc03a; text-align: center; text-transform: uppercase;">
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
                            <p style="text-align: center;"><?php echo JText::_('MOD_REVIEW_FORM_NOT_ALLOWED'); ?></p>
                        <?php endif; ?>
                    </fieldset>
                </div>
            </form>
        </div>
    </section>
</div>
