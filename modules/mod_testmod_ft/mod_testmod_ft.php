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


$displayHeader = modTest::getHeader($params);//invoke helper class method
$displayText = modTest::getText($params);//invoke helper class method
require JModuleHelper::getLayoutPath('mod_testmod_ft');

//Database code
use Joomla\CMS\Factory;

$db = Factory::getDbo();

$me = Factory::getUser();

$query = $db->getQuery(true);

//Search all users code
//$query->select($db->quoteName(array('name', 'email')))
//	->from($db->quoteName('#__users'))
//	->where($db->quoteName('id') . ' != ' . $db->quote($me->id))
//	->order($db->quoteName('name') . ' ASC');

$query->select($db->quoteName(array('title','id','hits','featured','catid')))
    ->from($db->quoteName('#__content'));

$db->setQuery($query);

echo $db->replacePrefix((string) $query);

$results = $db->loadAssocList();

foreach ($results as $row) {
	echo "<p> Id: " . $row['id'] . ", Title: " . $row['title'] . ", Category: " . $row['catid'] . ", Hits: " . $row['hits'] . ", Featured?: " . $row['featured'] . "<br></p>";
}