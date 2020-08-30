<?php
/**
 * Home Articles Module Entry Point
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

$displayHeader = ModHomeArticlesHelper::getHeader($params);//invoke helper class method
$displayText = ModHomeArticlesHelper::getText($params);//invoke helper class method
$displayMode = $params->get('display');//Get the display mode of the home articles
$checkMayTry = $params->get('maytry');//Get the boolean if to include may try preferences

//Use the factory
use Joomla\CMS\Factory;
$me = Factory::getUser();//Sets the variable for user
$db = Factory::getDbo();//Sets the variable for database
$userid = $me->id;//Gets the user id

//Set Default tags to be Physics, Chemistry, Mathematics and Biology
if(!isset($tagList)) $tagList = array("Physics","Chemistry","Mathematics","Biology");

if ($userid==0){//If the user is a guest
    //Display guest msg, hide by request of Lawrence
    //echo "Because you have yet to login, your default tags are: Physics, Chemistry, Mathematics, Chinese and Biology";
    //Set default tags
    $tagList = array("Physics","Chemistry","Mathematics","Chinese Language","Biology");//Sets default tag list
}
else {//Get User preference
    //Query the database
    $query = $db->getQuery(true);
    $query->select($db->quoteName(array('es_userid','es_userpreference')))//Get the user id and their preferences
        ->from($db->quoteName('#__customtables_table_userstats'))//From the custom user statistics table
        ->where($db->quoteName('es_userid') . ' = ' . $userid);//Where the user id matches the current user
    $db->setQuery($query);
    $results_ext = $db->loadAssocList();//Save results as $results_ext
    foreach ($results_ext as $row){//Loop for each user found (should only return 1 row)
        if ($row['es_userid']==$userid){//Just to be sure if user id is same
            $preferenceList = json_decode($row['es_userpreference']);//Gets the preference list
            if ($preferenceList)$tagList = array();//If preference list exists, initialize $taglist as an array
            foreach ($preferenceList as $tag=>$value){//Loop for each preference in the list
                if ($value==2) array_push($tagList,$tag);//If the user prefers the tag, add to list
                else if ($value==1 && $checkMayTry =="display_yes") array_push($tagList,$tag);//If the user may try the tag and the settings allow for may try preferences, also add to the list
            }
            
        }
    }
}

//Call our recommender to get the article list
$resultsSelected= ModHomeArticlesHelper::getArticleList($params->get('noOfArticles'), $userid, $tagList);
//Get Contents
$recommendedContents = array();
foreach ($resultsSelected as $tag => $result){//Loop for each article list found
    $recommendedContents[$tag] = ModHomeArticlesHelper::getArticles($result);//Get the contents for the relevant lists
}

//Show debug message of the article lists
echo "<script>console.log('".json_encode($resultsSelected)."')</script>";
//Display html view of homearticles
require JModuleHelper::getLayoutPath('mod_taskmeister_homearticles');
