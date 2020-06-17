<?php
/**
 * Your Class Stats Module Entry Point
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


$displayHeader = modClassModifier::getHeader($params);//invoke helper class method
$displayText = modClassModifier::getText($params);//invoke helper class method
$showTable = modClassModifier::checkTable($params->get('tablestats'));

//Database code
use Joomla\CMS\Factory;
//Set database variable
$db = Factory::getDbo();
$me = Factory::getUser();
$userID = $me->id;

if ($userID!=0){//if User id isnt a guest
    //Querying for stats of the entire database of the external teacher stats
    $query = $db->getQuery(true);
    $query->select($db->quoteName(array('*')))//Get everything from
        ->from($db->quoteName('#__customtables_table_teacherstats'))
        ->where($db->quoteName('es_teacherid') . ' = ' . $userID);//From our external teacher stats table
    $db->setQuery($query);
    $results = $db->loadAssocList();//Save results as $results2

    //Set Defauly Values of Weightages
    $likesWeightage = 100;
    $deploymentWeightage = 100;
    $touchedWeightage = 100;
    $preferredWeightage = 100;
    $notPreferredWeightage = 100;
    $mayTryWeightage = 100;

    if ($results){//If teacher data exists
        foreach ($results as $row){//Extract teacher data
            $teacher = JFactory::getUser($row['es_teacherid']);//Get Teacher Profile
            $teacherName = $teacher->name;//Get Teacher Name
            //Get the relevant saved data
            if ($row['es_weightagelikes'])$likesWeightage = intval($row['es_weightagelikes']);
            if ($row['es_weightagedeployment'])$deploymentWeightage = intval($row['es_weightagedeployment']);
            if ($row['es_weightagetouched'])$touchedWeightage = intval($row['es_weightagetouched']);
            if ($row['es_weightagepreferred'])$preferredWeightage = intval($row['es_weightagepreferred']);
            if ($row['es_weightagenotpreferred'])$notPreferredWeightage = intval($row['es_weightagenotpreferred']);
            if ($row['es_weightagemaytry'])$mayTryWeightage = intval($row['es_weightagemaytry']);
            if ($row['es_preferencelink'])$preferenceLinked = intval($row['es_preferencelink']);
        }
       
        require JModuleHelper::getLayoutPath('mod_taskmeister_classmodifier');
    }
    else{
        echo "<br><h3>You have to be a teacher to see this feature</h3>";
    }   
}
else{
    echo "<br><h3>You have to be a teacher to see this feature</h3>";
}

if (isset($_POST["submitClassModifier"])){
    /*
        If user clicks 'Save' button
    */
        //Save the list, call helper func here!
        modClassModifier::saveSelection($_POST);
        Header('Location: '.$_SERVER['PHP_SELF']);//Force Refreshes page - necessary to show the updated results
        Exit();
    }