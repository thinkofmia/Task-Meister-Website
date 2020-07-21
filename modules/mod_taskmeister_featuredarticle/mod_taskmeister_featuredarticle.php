<?php
/**
 * Featured Article Module Entry Point
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
$displayHeader = ModFeaturedArticleHelper::getHeader($params);//Set variable of inputtable header
$displayText = ModFeaturedArticleHelper::getText($params);//Set variable of inputtable text
$keyword = "";//By default, set keyword as none

//Code to check for keyword
if (isset($_REQUEST["keyword"])&& strlen($_REQUEST["keyword"])>0){
    //Echo message to show search result, for debug
    echo "<h3>You have searched for ".$_REQUEST["keyword"]."...</h3>";
    //Set keyword
    $keyword = $_REQUEST["keyword"];
} 

//Check if its to be automated or manual
if ($params->get('automated')=="choice_no"){
    $articleID = $params->get('articleID');
}
else {
    $me = Factory::getUser();//Gets user
    $userid = $me->id;
    //Get the top articles
    $articleList = ModFeaturedArticleHelper::recommendArticles($userid, $keyword);
}

//Set global dummy image
$dummyArticleImg = "/taskmeisterx/modules/mod_taskmeister_featuredarticle/images/noimagefound.png";
//Set up dictionary
$articlesDict = array();
$counter_dict = 0;
//Loop the list
foreach ($articleList as $articleID){
    $counter_dict += 1;
    $articlesDict[$counter_dict] = array();
    //Set variables
    echo "<script>console.log('Getting video for article #".$counter_dict."')</script>";
    $videoLink = ModFeaturedArticleHelper::getVideo($params, $params->get('automated'), $articleID);//Set variable of video link
    $articleLikedUsers = "None";
    $articleDeployedUsers = "None";
    $articleTotalLikes = 0;
    
    //Check if video exists
    if ($videoLink) echo "<script>console.log('Crawling Video link: " . $videoLink . "' );</script>";
    else echo "<script>console.log('Debug Objects: No Video Found' );</script>";
    
    //Article Contents
    $articleContents = ModFeaturedArticleHelper::getArticle($articleID);
    if ($articleContents == "No article found. "){
        $articleTitle = "No article found. ";
        $articleImage = "No article found. ";
    }
    else{
        $articleTitle = $articleContents['title'];
        if (json_decode($articleContents['images'])->image_intro) $articleImage = json_decode($articleContents['images'])->image_intro;
        elseif (json_decode($articleContents['images'])->image_fulltext) $articleImage = json_decode($articleContents['images'])->image_fulltext;
        elseif (json_decode($articleContents['images'])->image_intro_alt) $articleImage = json_decode($articleContents['images'])->image_intro_alt;
        elseif (json_decode($articleContents['images'])->image_fulltext_alt) $articleImage = json_decode($articleContents['images'])->image_fulltext_alt;
        else $articleImage = $dummyArticleImg;
    }
    //External contents
$externalContents = ModFeaturedArticleHelper::getArticleExternalStats($articleID);
if ($externalContents != "Nothing is found. "){
    if ($externalContents['es_totallikes']) $articleTotalLikes = $externalContents['es_totallikes'];
    $deployedUsersList = json_decode($externalContents['es_deployed']);
    $articleTotalDeployed = sizeof($deployedUsersList);
    $usersPreferenceList = json_decode($externalContents['es_userchoice']);
    //For Loops
    if ($deployedUsersList){
        $newArray = array();
        foreach($deployedUsersList as $row){
            $user = JFactory::getUser(intval($row))->name;
            array_push($newArray, $user);
        }
        $articleDeployedUsers = implode(", ", $newArray);
    } 
    if ($articleTotalLikes>0){
        $newArray = array();
        foreach($usersPreferenceList as $key => $value){
            if ($value=="Liked"){
                $user = JFactory::getUser(intval($key))->name;
                array_push($newArray, $user);
            }
        }
        $articleLikedUsers = implode(", ", $newArray);
        }
    }
    //Save variables
    $articlesDict[$counter_dict]["videoLink"] = $videoLink;
    $articlesDict[$counter_dict]["likedUsers"] = $articleLikedUsers;
    $articlesDict[$counter_dict]["deployedUsers"] = $articleDeployedUsers;
    $articlesDict[$counter_dict]["noOfLikes"] =  $articleTotalLikes;
    $articlesDict[$counter_dict]["noOfDeployed"] =  $articleTotalDeployed;
    $articlesDict[$counter_dict]["title"] =  $articleTitle;
    $articlesDict[$counter_dict]["image"] =  $articleImage;
    $articlesDict[$counter_dict]["id"] = $articleID;
}

require JModuleHelper::getLayoutPath('mod_taskmeister_featuredarticle');//Opens up default.php