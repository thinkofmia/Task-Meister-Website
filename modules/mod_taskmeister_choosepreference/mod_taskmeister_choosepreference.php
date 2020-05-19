<?php
/**
 * Choose Prefence Module Entry Point
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

$displayHeader = ModChoosePreferenceHelper::getHeader($params);//invoke helper class method
$displayText = ModChoosePreferenceHelper::getText($params);//invoke helper class method

$tagList = ModChoosePreferenceHelper::findTags();
//Display debug
/*echo "List of available tags: ";
foreach ($tagList as $key => $value){
    echo $key." used ".$value." times!<br>";
}*/ 
require JModuleHelper::getLayoutPath('mod_taskmeister_choosepreference');

if (isset($_POST["submit"])){
    ModChoosePreferenceHelper::saveLists($_POST['list1'],$_POST['list2'],$_POST['list3']);
    Header('Location: '.$_SERVER['PHP_SELF']);//Refreshes page
    Exit();
}