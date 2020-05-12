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
require JModuleHelper::getLayoutPath('mod_taskmeister_userstats');

//Database code
use Joomla\CMS\Factory;

$db = Factory::getDbo();
$me = Factory::getUser();
$userID = $me->id;
$username = $me->username;

//Querying
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_userid','es_preference','es_pagedeployed','es_pageliked','es_pagedisliked')))
    ->from($db->quoteName('#__customtables_table_userstats'))
    ->where($db->quoteName('es_userid') . ' = ' . $userID);
$db->setQuery($query);
$results2 = $db->loadAssocList();

//Style for table
echo "<style> 
    table, tr, th, td {
    border: 2px solid black;
    }
</style>";

if ($userID==0){
    echo "You have to login first to see this stats. ";
}
//Display Results
else{//If logined 
    foreach ($results2 as $row) {
        if ($userID==$row['es_userid']){
            echo "<table>
            <tr>
                <th>Username</th>
                <th>ID</th>
                <th>Preference List</th>
                <th>Deployed Pages</th>
                <th>Liked Pages</th>
                <th>Disliked Pages</th>
            </tr>
            <tr>
                <td>" . $username . "</td>
                <td>" . $userID . "</td>
                <td>" . $row['es_preference'] . "</td>
                <td> ". $row['es_pagedeployed']. " </td> 
                <td>" . $row['es_pageliked'] . "</td>
                <td>" . $row['es_pagedisliked'] . "</td>
            </tr>"; 
            echo "</table>";
            }
    }
}
