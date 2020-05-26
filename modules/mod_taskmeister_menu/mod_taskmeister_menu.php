<?php
/**
 * Custom Menu Module Entry Point
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @link       http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC') or die; // ensures that this file is being invoked from the Joomla! application. This is necessary to prevent variable injection and other security vulnerabilities. 
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';//used because our helper functions are defined within a class, and we only want the class defined once. 

$displayHeader = ModCustomMenuHelper::getHeader($params);//Set variable of inputtable header
$displayText = ModCustomMenuHelper::getText($params);//Set variable of inputtable text

//Get labels and alias/links of the menu
$baseSite = ModCustomMenuHelper::getWebsite($params);
$label1 = ModCustomMenuHelper::getCustomLabel($params,1);
$label2 = ModCustomMenuHelper::getCustomLabel($params,2);
$label3 = ModCustomMenuHelper::getCustomLabel($params,3);
$label4 = ModCustomMenuHelper::getCustomLabel($params,4);
$label5 = ModCustomMenuHelper::getCustomLabel($params,5);
$alias1 = ModCustomMenuHelper::getCustomAlias($params,1);
$alias2 = ModCustomMenuHelper::getCustomAlias($params,2);
$alias3 = ModCustomMenuHelper::getCustomAlias($params,3);
$alias4 = ModCustomMenuHelper::getCustomAlias($params,4);
$alias5 = ModCustomMenuHelper::getCustomAlias($params,5);
require JModuleHelper::getLayoutPath('mod_taskmeister_menu');//Opens up default.php