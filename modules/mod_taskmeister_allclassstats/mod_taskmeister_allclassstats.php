<?php
/**
 * All Class Stats Module Entry Point
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


$displayHeader = modAllClassesStats::getHeader($params);//invoke helper class method
$displayText = modAllClassesStats::getText($params);//invoke helper class method
require JModuleHelper::getLayoutPath('mod_taskmeister_allclassstats');

//Database code
use Joomla\CMS\Factory;
//Set database variable
$db = Factory::getDbo();

//Querying for stats of the entire database of the external teacher stats
$query = $db->getQuery(true);
$query->select($db->quoteName(array('*')))//Get everything from
    ->from($db->quoteName('#__customtables_table_teacherstats'));//From our external teacher stats table
$db->setQuery($query);
$results = $db->loadAssocList();//Save results as $results2

//Style for table
echo "<style> 
    table, tr, th, td {
    border: 2px solid black;
    }
</style>";
//Show the external teacher stats
foreach ($results as $row) {//For loop for each item in $results
    //Set Teacher
    $teacher = JFactory::getUser($row['es_teacherid']);
    $teacherName = $teacher->name;
    //Set the student list for each teacher
    $studentsList = json_decode($row['es_students']);
    //Print out the data
    echo "<table style='border: 2px solid black; border-collapse: collapse;'>
    <tr>
        <th>Teacher</th>
        <th>Students</th>
        <th>Analysis</th>
    </tr>
    <tr>
        <td>" . $teacherName . "</td>";
    echo "<td>";
    if ($studentsList){
        echo "<ul>";
        foreach ($studentsList as $row){
            $student = JFactory::getUser(intval($row));
            $studentName = $student->name;
            echo "<li>".$studentName."</li>";
        }
        echo "</ul>";
    }
    else{
        echo "No Students in your class";
    }
    echo "</td>
        <td>In Progress</td>
    </tr>"; 
    echo "</table>";
}