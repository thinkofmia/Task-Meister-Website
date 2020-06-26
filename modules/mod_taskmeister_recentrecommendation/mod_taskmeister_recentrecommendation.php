<?php
/**
 * Recent Recommendations Module Entry Point
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


$displayHeader = modRecentRecommend::getHeader($params);//set variable of header using helper class
$displayText = modRecentRecommend::getText($params);//set variable of text using helper class
$size = $params->get('size');
require JModuleHelper::getLayoutPath('mod_taskmeister_recentrecommendation');//call out default.php display

//Database code
use Joomla\CMS\Factory;
$db = Factory::getDbo();//Get database

//Querying SQL database for recent recommendations
$query = $db->getQuery(true);
$query->select($db->quoteName(array('*')))//Sets which columns of database
    ->from($db->quoteName('#__customtables_table_recommendationstats'))
    ->order($db->quoteName('id') . ' DESC');//Sets which database
$db->setQuery($query);
$results = $db->loadAssocList();//Save results of external article database query

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
    <th>Date</th>
    <th>Action</th>
</tr>";

$counter = 0;//Initialize counter
foreach ($results as $row) {
    if ($counter<$size){
/*
    Display rows based on main database result
        $row['date'] refers to the record date
        $row['es_uid'] refers to the User ID
        $row['es_aid'] refers to the article ID
        $row['es_action'] refers to the action done
*/
    $user = JFactory::getUser(intval($row['es_uid']));
    $username = $user->name;
    $article =& JTable::getInstance("content");
    $article->load(intval($row['es_aid']));
    $articleTitle= $article->get("title");

    echo "<tr><td>" . $row['es_date'] . "</td>";
    echo "<td> User " . $username ." ". $row['es_action']. " article " .$articleTitle. "</td></tr>";
    $counter = $counter + 1;
    }
}
echo "</table>";
}

else{//If no recent recommendationa actions
    echo "No recent actions. ";

}