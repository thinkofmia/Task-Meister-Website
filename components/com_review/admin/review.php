<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_review
 * 
 * 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Get an instance of the controller prefixed by Review
$controller = JControllerLegacy::getInstance('Review');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();