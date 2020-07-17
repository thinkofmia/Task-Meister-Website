<?php
/**
 * TaskMeister Deployed Module Entry Point
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


$displayHeader = modTMDeployed::getHeader($params);//Get header var using helper method getHeader()
$displayText = modTMDeployed::getText($params);//Get text var using helper method getText()

//Setup Factory to call database/user info
use Joomla\CMS\Factory;
//Set Database Var
$db = Factory::getDbo();
//Set User Var and save user id
$me = Factory::getUser();
$userID = 0; //By default

//Sets item id
if (JRequest::getVar('view')=='article') $itemID = JRequest::getVar('id');

//Check if its an article
if ($itemID){
    //Query database for articles based on current article id
    $query = $db->getQuery(true);
    $query->select($db->quoteName(array('title','id','hits','featured','catid')))//Get which columns
        ->from($db->quoteName('#__content'))//Sets which database
        ->where($db->quoteName('id') . ' = ' . $itemID);//Set condition of query to find current article ID.
    $db->setQuery($query);
    $results = $db->loadAssocList();//Save results of main article database query
    if ($results){
        $articleID = $itemID;
    }
}

if ($articleID){
//Set User id
$userID = $me->id;
//Initialize variables
$deployedSize = 0;
//Querying for Article stats table for this article
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_articleid','es_deployed')))//Get article id and deployment list
    ->from($db->quoteName('#__customtables_table_articlestats'))//From external table Article Stats
    ->where($db->quoteName('es_articleid') . ' = ' . $articleID);//Where it is this article using article id
$db->setQuery($query);
$results = $db->loadAssocList();//Save results as an array
//Initializing Variables
$deployedList;//List of users who deployed this article
$dataNotExist = true;//Boolean to check if record of article exists in the article stats table
//For loop to check if data exists
foreach ($results as $row) {
    if (JRequest::getVar('id')==$row['es_articleid']){
        //If data exists for this article, save the deployment list
        $deployedList=json_decode($row['es_deployed'],true);//Since in the database is a string, we have to convert it to an array using json_decode
        $dataNotExist = false;//Set boolean to be false since data do exists
        $deployedSize = sizeof($deployedList);
    } 
}

//If no records of this in the Article Stats Database
if ($dataNotExist){
    // Create and populate it
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    // Update the object into the article stats table.
    $result = JFactory::getDbo()->insertObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
}

//Querying for external User Stats table
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_userid','es_pagedeployed')))//Get User id and list of pages deployed
    ->from($db->quoteName('#__customtables_table_userstats'))//From the external user stats database
    ->where($db->quoteName('es_userid') . ' = ' . $userID);//Where it is the current logined user
$db->setQuery($query);
$results = $db->loadAssocList();//Save results of query
//Initialize Variables
$deployedList;//List of articles that the user deployed
$dataNotExist = true;//Boolean to show if a record of the user exists in the user stats table
foreach ($results as $row) {//For loop to find the record
    if ($userID==$row['es_userid']){//If user id matches the one of the records
        $deployedList_user=json_decode($row['es_pagedeployed'],true);//Save the deployment list of user as an array
        $dataNotExist = false;//Set boolean to be false since the data do exists!
    } 
}
//If no record of the user stats for the particular user exists
if ($dataNotExist){
    // Create and populate an object.
    $userInfo = new stdClass();
    $userInfo->es_userid = $userID;
    // Update the object into the user stats table.
    $result = JFactory::getDbo()->insertObject('#__customtables_table_userstats', $userInfo, 'es_userid');
}

}

//If clicked on the deployment button
if(isset($_POST["dButton"])){
    //Run setDeployed()
    setDeployed($userID,$articleID,$deployedList,$deployedList_user);
    header("refresh: 0;");
}

function updateDeployed($userID,$articleID){
    /**
     * Function: Update recommendation database with the user's action on an article
     * Parameter $userID: Refers to the current user id
     * Parameter $articleID: Refers to the current article id
     * Parameter $action: Refers to the user's action
     */
    // Create and populate an object.
    $record = new stdClass();
    $record->es_uid = $userID;
    $record->es_aid=$articleID;
    $record->es_action="deployed";
    $record->es_date=date("Y-m-d");
    // Insert the object into the user profile table.
    $result = JFactory::getDbo()->insertObject('#__customtables_table_recommendationstats', $record);
    }

function setDeployed($userID,$articleID,$list,$deployedList_user){
    /**
     * Function: Update Deployment Lists into both article and user stats database
     * Parameter $userID: Refers to the current user id
     * Parameter $articleID: Refers to the current article id
     * Parameter $list: Refers to the article's deployment list
     * Parameter $deployedList_user: Refers to the user's deployment list
     */
    //Check if the user is a guest
    if ($userID == 0){
        //If the user is indeed a guest, alert them to login first
        echo "alert('Login First!!')";
    } 
    else {//If user has logined or is registered
        //Check if article's deployment list is empty
        if (empty($list)){//If it is really empty,
            $list = array($userID);//save the user id into the list as a new array
        }
        //Else if user id already exists inside the article's deployment list,
        else if (in_array($userID,$list)){
            $key = array_search($userID, $list);//Find the index of the array
            unset($list[$key]);//Removes user from the list
        }
        //Else just push the user id into the article's deployment list
        else {
            $list[] = $userID;
        }
        //Save the new article deployment list as a string
        $array_string=json_encode($list);
        // Create and populate an object to save in database
        $articleInfo = new stdClass();
        $articleInfo->es_articleid = $articleID;
        $articleInfo->es_deployed =  $array_string;
        // Update the object into the article stats table.
        $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
        
        //For user profile
        //Check if user's deployment list is empty
        if (empty($deployedList_user)){//If user's deployment list is really empty
            $deployedList_user = array($articleID);//Create a new array with the article id inside the user's deployment list
            updateDeployed($userID,$articleID);
        }
        //Else if the article id already exists in the user's deployment list,
        else if (in_array($articleID,$deployedList_user)){
            $key = array_search($articleID, $deployedList_user);//Find the index of the key
            unset($deployedList_user[$key]);//And remove the article id from the user's deployment list
        }
        //Else just push the article id into the user's deployment list
        else {
            $deployedList_user[] = $articleID;
            updateDeployed($userID,$articleID);
        }
        //Save the new user deployment list as a string
        $array_string2=json_encode($deployedList_user);
        // Create and populate an user table.
        $userInfo = new stdClass();
        $userInfo->es_userid = $userID;
        $userInfo->es_pagedeployed =  $array_string2;
        // Update the object into the user stats table.
        $result = JFactory::getDbo()->updateObject('#__customtables_table_userstats', $userInfo, 'es_userid');
    }
}

require JModuleHelper::getLayoutPath('mod_taskmeister_deployed');//Display default.php layout