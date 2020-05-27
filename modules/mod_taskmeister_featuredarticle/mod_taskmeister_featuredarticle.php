<?php
/**
 * Featured Article Module Entry Point
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

$displayHeader = ModFeaturedArticleHelper::getHeader($params);//Set variable of inputtable header
$displayText = ModFeaturedArticleHelper::getText($params);//Set variable of inputtable text
$videoLink = ModFeaturedArticleHelper::getVideo($params);//Set variable of video link
$articleID = $params->get('articleID');

//Article Contents
$articleContents = ModFeaturedArticleHelper::getArticle($articleID);
if ($articleContents == "No article found. "){
    $articleTitle = "No article found. ";
    $articleImage = "No article found. ";
}
else{
    $articleTitle = $articleContents['title'];
    $articleImage = json_decode($articleContents['images'])->image_intro;
}


require JModuleHelper::getLayoutPath('mod_taskmeister_featuredarticle');//Opens up default.php