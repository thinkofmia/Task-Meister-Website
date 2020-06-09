<?php
/**
 * Your Class Stats Module Entry Point
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


$displayHeader = modYourClassesStats::getHeader($params);//invoke helper class method
$displayText = modYourClassesStats::getText($params);//invoke helper class method
$showTable = modYourClassesStats::checkTable($params->get('tablestats'));

//Database code
use Joomla\CMS\Factory;
//Set database variable
$db = Factory::getDbo();
$me = Factory::getUser();
$userID = $me->id;

if ($userID!=0){//if User id isnt a guest
    //Querying for stats of the entire database of the external teacher stats
    $query = $db->getQuery(true);
    $query->select($db->quoteName(array('*')))//Get everything from
        ->from($db->quoteName('#__customtables_table_teacherstats'))
        ->where($db->quoteName('es_teacherid') . ' = ' . $userID);//From our external teacher stats table
    $db->setQuery($query);
    $results = $db->loadAssocList();//Save results as $results2

    if ($results){//If teacher data exists
        foreach ($results as $row){//Extract teacher data
            $teacher = JFactory::getUser($row['es_teacherid']);//Get Teacher Profile
            $teacherName = $teacher->name;//Get Teacher Name
            //Get the student list
            $studentsList = json_decode($row['es_students']);
        }
        //Create Preferences Score Array
        $fullPreferencesScore = array();
        $dislikedPreferencesScore = array();
        //Loop base on students list
        foreach ($studentsList as $row){
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('es_userid','es_userpreference')))//Get user id, user preference
                ->from($db->quoteName('#__customtables_table_userstats'))//From our external user stats table
                ->where($db->quoteName('es_userid') . ' = ' . $row);//Where it is the current user's userid
            $db->setQuery($query);
            $results2 = $db->loadAssocList();//Save results as $results2
            foreach ($results2 as $row2){
                $studentPreferences = json_decode($row2['es_userpreference']);
                foreach ($studentPreferences as $key => $value){
                    if (isset($fullPreferencesScore[$key])) $fullPreferencesScore[$key] += $value;
                    else $fullPreferencesScore[$key] = $value;
                    if ($value == 0){//If user dislikes this
                        if ($dislikedPreferencesScore[$key]) $dislikedPreferencesScore[$key] += 1;
                        else $dislikedPreferencesScore[$key] = 1;
                    }
                }
            }
        }
        //Sort Preference Score by highest first
        arsort($fullPreferencesScore);
        require JModuleHelper::getLayoutPath('mod_taskmeister_yourclassstats');
    }
    else{
        echo "<br><h3>You have to be a teacher to see this feature</h3>";
    }   
}
else{
    echo "<br><h3>You have to be a teacher to see this feature</h3>";
}
