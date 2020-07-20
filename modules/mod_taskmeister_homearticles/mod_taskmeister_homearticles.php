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
$displayMode = $params->get('display');
$checkMayTry = $params->get('maytry');

use Joomla\CMS\Factory;
$me = Factory::getUser();//Gets user
$db = Factory::getDbo();//Gets database
$userid = $me->id;

//Set Default tags
if(!isset($tagList)) $tagList = array("Physics","Chemistry","Mathematics","Biology");

if ($userid==0){
    $tagList = array("Physics","Chemistry","Mathematics","Chinese","Biology");
}
else {//Get User preference
    //Get external user table (custom table) To find out list of liked, deployed and disliked articles
    $query = $db->getQuery(true);
    $query->select($db->quoteName(array('es_userid','es_userpreference')))
        ->from($db->quoteName('#__customtables_table_userstats'))
        ->where($db->quoteName('es_userid') . ' = ' . $userid);
    $db->setQuery($query);
    $results_ext = $db->loadAssocList();
    //Save information into a list
    foreach ($results_ext as $row){
        if ($row['es_userid']==$userid){//Just to be sure if user id is same
            $preferenceList = json_decode($row['es_userpreference']);
            if ($preferenceList)$tagList = array();
            foreach ($preferenceList as $tag=>$value){
                if ($value==2) array_push($tagList,$tag);
                else if ($value==1 && $checkMayTry =="display_yes") array_push($tagList,$tag);
            }
            
        }
    }
}

//Call our recommender
$resultsSelected= ModHomeArticlesHelper::getArticleList($params->get('noOfArticles'), $userid, $tagList);
//Get Contents
$recommendedContents = array();
foreach ($resultsSelected as $tag => $result){
    $recommendedContents[$tag] = ModHomeArticlesHelper::getArticles($result);
}

echo "<script>console.log('".json_encode($resultsSelected)."')</script>";
require JModuleHelper::getLayoutPath('mod_taskmeister_homearticles');//Calls out default.php
