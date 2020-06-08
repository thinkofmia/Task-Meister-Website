<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 * 
 * @copyright   (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // No direct access to this file
 defined('_JEXEC') or die('Restricted access');

 // Get an instance of the controller prefixed by HelloWorld; HelloWorldController
 // Joomla automatically looks for the declaration of HelloWorldController in './controller.php'
 $controller = JControllerLegacy::getInstance('HelloWorld');

 // Perfrom the Request task
 $input = JFactory::getApplication()->input;
 $controller->execute($input->getCmd('task'));

 // Redirect if set by the controller
 $controller->redirect();