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

// This file collects the data collected by mod_review.php and generates the HTML to be displayed
?>

<div>
    <section style="background-color: black; margin-left: -2.2%; margin-right: -2.2%; padding: 100px;">
        <h1 style="color: #ffc03a; text-align: center; text-transform: uppercase;"><?php echo JText::_('MOD_REVIEW_FORM_TESTIMONIAL_LABEL') ?></h1>
        <?php if(!empty($testimonials)): ?>
            <?php foreach($testimonials as $row): ?>
                <table style="color: white;">
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
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
<div>
    <section style="background-color: #191919; margin-left: -2.2%; margin-right: -2.2%; padding: 100px;">
        <h1 style="color: #ffc03a; text-align: center; text-transform: uppercase;"><?php echo JText::_('MOD_REVIEW_FORM_REVIEW_LABEL'); ?></h1>
        <p style="text-align: center;"><a style="color: white;" href="#" target="_blank" rel="nofollow noopener noreferrer">Upload your video and photo</a></p>
        <div style="text-align: left;">
            <form style="color: white;">
                <p><label for="ease"><?php echo JText::_('MOD_REVIEW_FORM_EASE_LABEL'); ?> ⭐⭐⭐⭐⭐</label><br />
                <input id="ease" type="text" placeholder="<?php echo JText::_('MOD_REVIEW_FORM_EASE_PLACEHOLDER'); ?>"/></p>
                <p><label for="effectiveness"><?php echo JText::_('MOD_REVIEW_FORM_EFFECTIVENESS_LABEL'); ?> ⭐⭐⭐⭐⭐</label><br />
                <input id="ease" type="text" placeholder="<?php echo JText::_('MOD_REVIEW_FORM_EFFECTIVENESS_PLACEHOLDER'); ?>" /></p>
            </form>
        </div>
    </section>
</div>