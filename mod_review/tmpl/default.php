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
<?php if(!empty($testimonials)): ?>
    <?php foreach($testimonials as $row): ?>
        <table>
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