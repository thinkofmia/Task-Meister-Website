<?php
/**
 * Choose Class Module Entry Point
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

//Set up factory
use Joomla\CMS\Factory;
$user = Factory::getUser();//Sets the user variable
$db = Factory::getDbo();//Sets the database variable

if ($user->guest) {//If user is a guest, show default message
	echo "<h3>You must login to see the content. Click on the account tab to begin.</h3>";
} else {//Else if the user has logined
    $displayHeader = ModChooseClassHelper::getHeader($params);//Get the custom header
    $displayText = ModChooseClassHelper::getText($params);//Gets the custom text
    
    $teacherList = ModChooseClassHelper::findTeachers();//Get our list of teachers using helper method 
    $studentList = ModChooseClassHelper::findStudents($teacherList, $db);//Get our list of students using helper method
    
    //Checks if the user is a teacher
    $isTeacher = array_key_exists(intval($user->id), $teacherList);//By looking through the id in the list of teachers
    if ($isTeacher){//If indeed teacher
        $currentList = ModChooseClassHelper::getYourStudents($user->id, $db);//Sets the current list to be list of students currently selected
    }
    else{//Else if the user is a student
        $currentList = ModChooseClassHelper::getYourTeachers($user->id, $db);//Sets the current list to be the list of teachers currently selected 
    }
    /*
    *   RandImg()
    *   Function: Generate random images/portrait
    *   Parameter: $dir refers to the input directory to look from
    */
    function RandImg($dir){
        //Sets images as anything with jpg, jpeg, png or gif files
        $images = glob($dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        //Picks randomly from the images
        $randomImage = $images[array_rand($images)];
        return $randomImage;//Return the picked image
    }
    //Display the html view of the module
    require JModuleHelper::getLayoutPath('mod_taskmeister_chooseclass');
    
    //If a POST request of the submit button is found (to save the new list of teachers)
    //If user clicks 'Submit Query' or save the new list of teachers
    if (isset($_POST["submit"])){
        ModChooseClassHelper::saveListOfTeachers($_POST['list1']);//Stores the results via using the helper method saveLists
        Header('Location: '.$_SERVER['PHP_SELF']);//Force Refreshes page - necessary to show the updated results
        Exit();
    }
    //Else if another POST request is found (to save the new list of students)
    else if (isset($_POST["submit2"])){
            ModChooseClassHelper::saveListOfStudents($_POST['list2']);//Stores the results via using the helper method saveLists
            Header('Location: '.$_SERVER['PHP_SELF']);//Force Refreshes page - necessary to show the updated results
            Exit();
        }
}