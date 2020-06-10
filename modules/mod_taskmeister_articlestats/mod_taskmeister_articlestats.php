<?php
/**
 * Article Stats Module Entry Point
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


$displayHeader = modArticleStats::getHeader($params);//set variable of header using helper class
$displayText = modArticleStats::getText($params);//set variable of text using helper class
require JModuleHelper::getLayoutPath('mod_taskmeister_articlestats');//call out default.php display

//Database code
use Joomla\CMS\Factory;
$db = Factory::getDbo();//Get database
$me = Factory::getUser();//Ger User
if (JRequest::getVar('view')=='article') $articleID = JRequest::getVar('id');

if ($articleID){
//Query database for articles based on current article id
$query = $db->getQuery(true);
$query->select($db->quoteName(array('title','id','hits','featured','catid')))//Get which columns
    ->from($db->quoteName('#__content'))//Sets which database
    ->where($db->quoteName('id') . ' = ' . $articleID);//Set condition of query to find current article ID.
$db->setQuery($query);
$results = $db->loadAssocList();//Save results of main article database query

//Querying SQL database for articles (external database) based on current article id
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_articleid','es_userchoice','es_deployed','es_totallikes','es_totaldislikes','es_tags')))//Sets which columns of database
    ->from($db->quoteName('#__customtables_table_articlestats'))//Sets which database
    ->where($db->quoteName('es_articleid') . ' = ' . $articleID);//Set condition of query to find current article ID.
$db->setQuery($query);
$results2 = $db->loadAssocList();//Save results of external article database query
}

if ($results){//If inside article table

//Display table, table header and css style
echo "
<style>
    table, tr, th, td {
    border: 2px solid black;
    }
</style>
<table style='border: 2px solid black; border-collapse: collapse;'>
<tr>
    <th>Id</th>
    <th>Title</th>
    <th>Category</th>
    <th>Views</th>
    <th>Featured</th>
</tr>";


foreach ($results as $row) {
/*
    Display rows based on main database result
        $row['id'] refers to the article id
        $row['title'] refers to the article title
        $row['catid'] refers to the article category
        $row['hits'] refers to the article hits
        $row['featured'] refers whether the article is featured or not
*/
    echo "<tr><td>" . $row['id'] . "</td>" . 
    "<td>" . $row['title'] . "</td>" . 
    "<td>" . $row['catid'] . "</td>" .
    "<td>" . $row['hits'] . "</td>" . 
    "<td>" . $row['featured'] . "</td></tr></table>";
    $articleID = $row['id'];
}

function countPreference($userchoice,$preference){
/*
    Function: To count total likes or dislikes
    Parameter $userchoice: List of all user's choices, format array
    Parameter $preference: Mode (Count like or dislike), format string
*/
    $count = 0;//Initialize
    foreach ($userchoice as $row){//For Loop for each user
        if ($row == "Liked" && $preference == "Liked") $count +=1;//If mode == user choice, then count +1
        else if ($row == "Disliked" && $preference == "Disliked") $count +=1;//If mode == user choice, then count +1
    }
    return $count;//Return final count
}

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
        $preferenceList = json_decode($row['es_userchoice']);
        $deploymentList = json_decode($row['es_deployed']);
        $NoOfLikes = $row['es_totallikes'];
        $NoOfDislikes = $row['es_totaldislikes'];
        $NoOfDeployment = sizeof($deploymentList);
        $tags = $row['es_tags'];
        //Display table with the above variables
        echo "<table>
        <tr>
            <th>All Users' Choice</th>
            <th>Who has deployed</th>
            <th>Total # of Likes</th>
            <th>Total # of Dislikes</th>
            <th>Total # of Deployment</th>
            <th>Tags</th>
        </tr>
        <tr>
            <td>" . $row['es_userchoice'] . "</td>";
        //Check if any deployment in the article, otherwise display msg.
        if (isset($row['es_deployed'])&&$row['es_deployed']!="[]") $deployed = $row['es_deployed'];
        else $deployed = "No one deployed this yet";
        //Display the rest of the variables
        echo "<td> ". $deployed. " </td> 
            <td>" . $NoOfLikes . "</td>
            <td>" . $NoOfDislikes . "</td>
            <td>" . $NoOfDeployment . "</td>
            <td>" . $tags . "</td>
        </tr>"; 
    echo "</table>";
    }

}
}