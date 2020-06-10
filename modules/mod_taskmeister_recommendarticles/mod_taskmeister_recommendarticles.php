<?php
/**
 * Recommend Articles Module Entry Point
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

$displayHeader = ModRecommendArticlesHelper::getHeader($params);//invoke helper class method
$displayText = ModRecommendArticlesHelper::getText($params);//invoke helper class method

use Joomla\CMS\Factory;
$me = Factory::getUser();//Gets user
$userid = $me->id;

//Call our recommender
$resultsSelected= ModRecommendArticlesHelper::getArticleList($params->get('filter'),$params->get('noOfArticles'), $userid, $params->get('selectedtag'));
$recommendedContents = ModRecommendArticlesHelper::getArticles($resultsSelected);

require JModuleHelper::getLayoutPath('mod_taskmeister_recommendarticles');//Calls out default.php

//Debug purposes, display rest of the possible rows
/*$selection_arr = array('choice_random','choice_liked','choice_personal','choice_untouched','choice_deployed');

foreach ($selection_arr as $row){
    if ($row != $params->get('filter')){
        $resultsSelected= ModRecommendArticlesHelper::getArticleList($row);
        //Print out most liked articles string
        echo $row.": ". $resultsSelected ."<br>";
    }
}*/