<?php
/**
 * Hello World! Module Entry Point
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


$displayHeader = modArticleStats::getHeader($params);//invoke helper class method
$displayText = modArticleStats::getText($params);//invoke helper class method
require JModuleHelper::getLayoutPath('mod_taskmeister_articlestats');

//Database code
use Joomla\CMS\Factory;

$db = Factory::getDbo();

$me = Factory::getUser();

$query = $db->getQuery(true);

$query->select($db->quoteName(array('title','id','hits','featured','catid','likes','dislikes')))
    ->from($db->quoteName('#__content'))
    ->where($db->quoteName('id') . ' = ' . JRequest::getVar('id'));

$db->setQuery($query);

//echo $db->replacePrefix((string) $query);

$results = $db->loadAssocList();

//Querying
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_articleid','es_userchoice','es_visited')))
    ->from($db->quoteName('#__customtables_table_articlestats'))
    ->where($db->quoteName('es_articleid') . ' = ' . JRequest::getVar('id'));
$db->setQuery($query);
$results2 = $db->loadAssocList();

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
    <th>Hits</th>
    <th>Featured</th>
</tr>";
foreach ($results as $row) {
    echo "<tr><td>" . $row['id'] . "</td>" . 
    "<td>" . $row['title'] . "</td>" . 
    "<td>" . $row['catid'] . "</td>" .
    "<td>" . $row['hits'] . "</td>" . 
    "<td>" . $row['featured'] . "</td></tr></table>";
    $articleID = $row['id'];
}

function countPreference($userchoice,$preference){
    //Calculate Number of likes and dislikes
    $count = 0;
    foreach ($userchoice as $row){
        if ($row == "Liked" && $preference == "Liked") $count +=1;
        else if ($row == "Disliked" && $preference == "Disliked") $count +=1;
    }
    return $count;
}

foreach ($results2 as $row) {
    if ($articleID==$row['es_articleid']){
        $preferenceList = json_decode($row['es_userchoice']);
        $NoOfLikes = countPreference($preferenceList,"Liked");
        $NoOfDislikes = countPreference($preferenceList,"Disliked");
        echo "<table>
        <tr>
            <th>All Users' Choice</th>
            <th>Number of Unique Registered Visitors</th>
            <th>Total # of Likes</th>
            <th>Total # of Dislikes</th>
        </tr>
        <tr>
            <td>" . $row['es_userchoice'] . "</td>";
        if (isset($row['es_visited'])) $visitors = $row['es_visited'];
        else $visitors = "No one deployed this yet";
        echo "<td> ". $visitors. " </td> 
            <td>" . $NoOfLikes . "</td>
            <td>" . $NoOfDislikes . "</td>
        </tr>"; 
    echo "</table>";
    }
}
