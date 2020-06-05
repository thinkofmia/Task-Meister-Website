<?php
/**
 * Choose Preference Module Entry Point
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


use Joomla\CMS\Factory;
$user = Factory::getUser();
$db = Factory::getDbo();//Gets database

if ($user->guest) {
	echo "<h3>You must login to see the content. Click on the account icon on the right.</h3>";
} else {
    $displayHeader = ModChoosePreferenceHelper::getHeader($params);//Get and save out custom header
    $displayText = ModChoosePreferenceHelper::getText($params);//Get and save our custom text
    
    $tagList = ModChoosePreferenceHelper::findTags();//Get our list of tags using helper method fingTags()
    $currentList = ModChoosePreferenceHelper::getPreferenceLists($user->id, $db);
    //Display debug/str version of tag list below
    /*echo "List of available tags: ";
    foreach ($tagList as $key => $value){
        echo $key." used ".$value." times!<br>";
    }*/ 
    require JModuleHelper::getLayoutPath('mod_taskmeister_choosepreference');//Call out default.php display
    
    if (isset($_POST["submit"])){
    /*
        If user clicks 'Submit Query' or save preference
    */
        ModChoosePreferenceHelper::saveLists($_POST['list1'],$_POST['list2'],$_POST['list3']);//Stores the results via using the helper method saveLists
        Header('Location: '.$_SERVER['PHP_SELF']);//Force Refreshes page - necessary to show the updated results
        Exit();
    }

}
