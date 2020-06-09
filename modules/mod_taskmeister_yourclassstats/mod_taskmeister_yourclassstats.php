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


$displayHeader = modYourClassesStats::getHeader($params);//invoke helper class method
$displayText = modYourClassesStats::getText($params);//invoke helper class method

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


require JModuleHelper::getLayoutPath('mod_taskmeister_yourclassstats');