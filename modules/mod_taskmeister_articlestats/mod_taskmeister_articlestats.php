<?php
/**
 * Article Stats Module Entry Point
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @link       http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC') or die; // ensures that this file is being invoked from the Joomla! application. This is necessary to prevent variable injection and other security vulnerabilities. 
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';//used because our helper functions are defined within a class, and we only want the class defined once. 

$displayHeader = modArticleStats::getHeader($params);//sets variable of header using helper class
$displayText = modArticleStats::getText($params);//sets variable of text using helper class

//Database code
use Joomla\CMS\Factory;
//Sets the variables for the database and user
$db = Factory::getDbo();//Get database
$me = Factory::getUser();//Ger User
//If the page is an article, get the id
if (JRequest::getVar('view')=='article') $articleID = JRequest::getVar('id');

if ($articleID){//If article id exists
//Query database for articles based on current article id
$query = $db->getQuery(true);
$query->select($db->quoteName(array('title','id','hits','featured','catid')))//Get the article title, id, hits, featured and category id
    ->from($db->quoteName('#__content'))//From the article database
    ->where($db->quoteName('id') . ' = ' . $articleID);//Sets condition of the query where the id is equal to the target article id
$db->setQuery($query);
$results = $db->loadAssocList();//Save results as $results

//Querying SQL database for articles (external database) based on current article id
$query = $db->getQuery(true);
//Gets the article id, the users' opinions of the article (es_userchoice), list of users that deployed the article, the total likes and dislikes of the article and the tags of the article
$query->select($db->quoteName(array('es_articleid','es_userchoice','es_deployed','es_totallikes','es_totaldislikes','es_tags')))
    ->from($db->quoteName('#__customtables_table_articlestats'))//From the article statistics table
    ->where($db->quoteName('es_articleid') . ' = ' . $articleID);//Sets condition of the query where the id is equal to the target article id
$db->setQuery($query);
$results2 = $db->loadAssocList();//Save results as $results2
}

if ($results){//If article found
    foreach ($results as $row) {//Loop the article (only 1 though)
    /*
    Display rows based on main database result
        $row['id'] refers to the article id
        $row['title'] refers to the article title
        $row['catid'] refers to the article category
        $row['hits'] refers to the article hits
        $row['featured'] refers whether the article is featured or not
    */
    $articleID = $row['id'];//Save the article id
    $articleTitle = $row['title'];//Save the article title
    $articleCategory = $row['catid'];//Save the article's category id
}

/*
    Function: To count total likes or dislikes
    Parameter $userchoice: List of all user's choices, format array
    Parameter $preference: Mode (Count like or dislike), format string
*/
function countPreference($userchoice,$preference){
    $count = 0;//Initialize the counter for likes/dislikes
    foreach ($userchoice as $row){//For Loop for each user
        if ($row == "Liked" && $preference == "Liked") $count +=1;//If mode == user choice, then increment the counter by 1
        else if ($row == "Disliked" && $preference == "Disliked") $count +=1;//If mode == user choice, then increment the counter by 1
    }
    return $count;//Return outcomes of the counter
}

//Loop for each article found in $results2 (Should only consists of 1 article though)
foreach ($results2 as $row) {
/*
    For loop to display second table of data - Article External Database
    var $preferenceList refers to the array of all the user's opinion of this article
    var $deploymentList refers to the array of all the users that deployed this article
    var $NoOfLikes refers to the total likes of this article
    var $NoOfDislikes refers to the total dislikes of this article
    var $NoOfDeployment refers to the total deployment of this article
    var $tags refers to all the tags used in this article
*/
    if ($articleID==$row['es_articleid']){//Check if article id is the same just to be sure.
        //Save the bottom vars with the results from the database
        $preferenceList = json_decode($row['es_userchoice']);//Gets the users' opinions 
        $deploymentList = json_decode($row['es_deployed']);//Gets the list of users that deployed the article
        $NoOfLikes = $row['es_totallikes'];//Gets the total likes of the article
        $NoOfDislikes = $row['es_totaldislikes'];//Gets the total dislikes of the article
        $NoOfDeployment = sizeof($deploymentList);//Gets the total deployment of the article
        $tags = json_decode($row['es_tags']);//Gets the tags of the article
        }
    }
    //Display the html layout of the article stats
    require JModuleHelper::getLayoutPath('mod_taskmeister_articlestats');
}