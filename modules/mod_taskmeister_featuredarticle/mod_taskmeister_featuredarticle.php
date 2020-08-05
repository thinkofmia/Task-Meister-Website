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

use Joomla\CMS\Factory;//Use Joomla's Factory
$displayHeader = ModFeaturedArticleHelper::getHeader($params);//Set variable of inputtable header
$displayText = ModFeaturedArticleHelper::getText($params);//Set variable of inputtable text
$keyword = "";//By default, set keyword as none

//Code to check for keyword
if (isset($_REQUEST["keyword"])&& strlen($_REQUEST["keyword"])>0){
    //Echo message to show search result, for debug
    echo "<h3>You have searched for ".$_REQUEST["keyword"]."...</h3>";
    //Sets keyword
    $keyword = $_REQUEST["keyword"];
} 


$me = Factory::getUser();//Gets user
$userid = $me->id;//Gets user id
//Get the top articles for the particular user
$articleList = ModFeaturedArticleHelper::recommendArticles($userid, $keyword);

//Set global dummy image
$dummyArticleImg = "/taskmeisterx/modules/mod_taskmeister_featuredarticle/images/noimagefound.png";
//Initialize dictionary
$articlesDict = array();
$counter_dict = 0;
//Loop for each article found
foreach ($articleList as $articleID){
    $counter_dict += 1;//Increment the counter by 1
    $articlesDict[$counter_dict] = array();//Create an array in the dict
    //Display debug msg
    echo "<script>console.log('Getting video for article #".$counter_dict."')</script>";
    //Get video link for the article
    $videoLink = ModFeaturedArticleHelper::getVideo($params, $articleID);//Set variable of video link
    //Initialize the rest of the variables
    $articleLikedUsers = "None";
    $articleDeployedUsers = "None";
    $articleTotalLikes = 0;
    
    //Check if video exists: Display debug msg accordingly
    if ($videoLink) echo "<script>console.log('Crawling Video link: " . $videoLink . "' );</script>";
    else echo "<script>console.log('Debug Objects: No Video Found' );</script>";
    
    //Article Contents
    $articleContents = ModFeaturedArticleHelper::getArticle($articleID);//Get article content
    if ($articleContents == "No article found. "){//If nothing is found
        $articleTitle = "No article found. ";//Show default article title: not found
        $articleImage = "No article found. ";//Show default article image: not found
    }
    else{//Else if article content exists
        //Save variables accordingly
        $articleTitle = $articleContents['title'];
        if (json_decode($articleContents['images'])->image_intro) $articleImage = json_decode($articleContents['images'])->image_intro;
        elseif (json_decode($articleContents['images'])->image_fulltext) $articleImage = json_decode($articleContents['images'])->image_fulltext;
        elseif (json_decode($articleContents['images'])->image_intro_alt) $articleImage = json_decode($articleContents['images'])->image_intro_alt;
        elseif (json_decode($articleContents['images'])->image_fulltext_alt) $articleImage = json_decode($articleContents['images'])->image_fulltext_alt;
        else $articleImage = $dummyArticleImg;
    }
    //Get external contents of the article
    $externalContents = ModFeaturedArticleHelper::getArticleExternalStats($articleID);
    //If external contents of the article is found
    if ($externalContents != "Nothing is found. "){
        //Save total likes if exists
        if ($externalContents['es_totallikes']) $articleTotalLikes = $externalContents['es_totallikes'];
        //Save delployed users list
        $deployedUsersList = json_decode($externalContents['es_deployed']);
        //Get number of users that deployed
        $articleTotalDeployed = sizeof($deployedUsersList);
        //Save the user opinions as a list
        $usersPreferenceList = json_decode($externalContents['es_userchoice']);
        if ($deployedUsersList){//If there exists the deployed users list
            $newArray = array();//Initialize the array
            foreach($deployedUsersList as $row){
                //Loop for each user that deploys the article
                $user = JFactory::getUser(intval($row))->name;//Get the user name
                array_push($newArray, $user);//Push the user into the list
            }
            //Implode all the deployed users into a string
            $articleDeployedUsers = implode(", ", $newArray);
        } 
        //If the total likes of article is more than 0
        if ($articleTotalLikes>0){
            $newArray = array();//Initialize the array
            foreach($usersPreferenceList as $key => $value){
                //Loop for each user's opinion
                if ($value=="Liked"){//If their opinion== liked
                    $user = JFactory::getUser(intval($key))->name;//Get their user name
                    array_push($newArray, $user);//Push the user into the list
                }
            }
            //Implode all the liked users into a string
            $articleLikedUsers = implode(", ", $newArray);
            }
        }
        //Save all the variables of the article
        $articlesDict[$counter_dict]["videoLink"] = $videoLink;
        $articlesDict[$counter_dict]["likedUsers"] = $articleLikedUsers;
        $articlesDict[$counter_dict]["deployedUsers"] = $articleDeployedUsers;
        $articlesDict[$counter_dict]["noOfLikes"] =  $articleTotalLikes;
        $articlesDict[$counter_dict]["noOfDeployed"] =  $articleTotalDeployed;
        $articlesDict[$counter_dict]["title"] =  $articleTitle;
        $articlesDict[$counter_dict]["image"] =  $articleImage;
        $articlesDict[$counter_dict]["id"] = $articleID;
    }

//Displays the necessary html view
require JModuleHelper::getLayoutPath('mod_taskmeister_featuredarticle');