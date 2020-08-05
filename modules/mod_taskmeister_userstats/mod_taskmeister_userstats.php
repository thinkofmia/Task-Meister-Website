<?php
/**
 * User Stats Module Entry Point
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


$displayHeader = modUserStats::getHeader($params);//invoke helper class method
$displayText = modUserStats::getText($params);//invoke helper class method

function createList($array_str){
    /**
     * Function: Create a list based on the array (string) and remove their keys
     * Parameter $array_str: String version of the array
     */
    //Convert string to array
    $array = json_decode($array_str,true);
    //Take out the array values and stores it into $list
    $list = array_values($array);
    //Returns the list with only the array values
    return json_encode($list);
}

//Database code
use Joomla\CMS\Factory;
//Set database variable
$db = Factory::getDbo();
//Set current user variable
$me = Factory::getUser();
//Set user id and username
$userID = $me->id;
$name = $me->name;
$username = $me->username;

//Querying for stats of a particular user
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_userid','es_userpreference','es_pagedeployed','es_pageliked','es_pagedisliked')))//Get user id, user preference, page deployed, likes and dislikes
    ->from($db->quoteName('#__customtables_table_userstats'))//From our external user stats table
    ->where($db->quoteName('es_userid') . ' = ' . $userID);//Where it is the current user's userid
$db->setQuery($query);
$results2 = $db->loadAssocList();//Save results as $results2

//If user is a guest, hide the table instead
if ($userID==0){
    echo "You have to login first to see this stats. ";
}
else{//If user has already login, show the external user stats
    foreach ($results2 as $row) {//For loop for each item in $results2
        if ($userID==$row['es_userid']){//Double confirmation that $userID is equal to the current user's
            //Create a list for deployed pages
            $deployedList = json_decode($row['es_pagedeployed']);
            //Create a list for liked pages
            $likedList = json_decode($row['es_pageliked']);
            //Create a list for disliked pages
            $dislikedList = json_decode($row['es_pagedisliked']);
            //Set the preference list as the one in the results
            $preferenceList = json_decode($row['es_userpreference']);
        }
    }
    //Display html view of user stats
    require JModuleHelper::getLayoutPath('mod_taskmeister_userstats');
}
