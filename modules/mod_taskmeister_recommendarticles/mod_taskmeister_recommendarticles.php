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
require JModuleHelper::getLayoutPath('mod_taskmeister_recommendarticles');

//Call our recommender
$resultsSelected= ModRecommendArticlesHelper::getArticleList($params->get('filter'));
//Print out most liked articles string
echo $params->get('filter').": ". $resultsSelected ."<br>";

//For debug the entire call
$choice = 'choice_random';
$resultsSelected= ModRecommendArticlesHelper::getArticleList($choice);
//Print out most liked articles string
echo $choice.": ". $resultsSelected ."<br>";

$choice = 'choice_liked';
$resultsSelected= ModRecommendArticlesHelper::getArticleList($choice);
//Print out most liked articles string
echo $choice.": ". $resultsSelected ."<br>";

$choice = 'choice_personal';
$resultsSelected= ModRecommendArticlesHelper::getArticleList($choice);
//Print out most liked articles string
echo $choice.": ". $resultsSelected ."<br>";

$choice = 'choice_new';
$resultsSelected= ModRecommendArticlesHelper::getArticleList($choice);
//Print out most liked articles string
echo $choice.": ". $resultsSelected ."<br>";

$choice = 'choice_deployed';
$resultsSelected= ModRecommendArticlesHelper::getArticleList($choice);
//Print out most liked articles string
echo $choice.": ". $resultsSelected ."<br>";