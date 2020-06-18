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

// Main entry point of the module, to perform any necessary initialisation routines
//  call helper routines to collect data, and include the template which will display the module output

// include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

// Get testimonials
$testimonials = ModReviewHelper::getTestimonials();

if(empty($testimonials))
{
    $testimonials = array();
}

require JModuleHelper::getLayoutPath('mod_review');