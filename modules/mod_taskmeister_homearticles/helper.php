<?php
/**
 * Helper class for Home Articles
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModHomeArticlesHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getText($params)//Function to get text from the parameter fields
    {
        return $params->get('customtext');
    }
    public static function getHeader($params)//Function to get text from the parameter fields
    {
        return $params->get('customheader');
    }
    function getArticles($list){
        //Call our recommender
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger( 'getArticleContents', array($list));
        //Return string results of the article contents
        return json_encode($results[0]) ;
    }
    function getArticleList($noOfArticles, $userid, $selectedTag){//Function to get selection from parameter fields
        //Call our recommender
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        //Initialize result
        $results = array("Calculating... ");
        //If never set no of articles
        if (!$noOfArticles) $noOfArticles=10;
        //Get Selected Tag
        $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Selected Tag",$noOfArticles,$userid,$selectedTag));
        //Return string results of recommended articles
        return json_encode($results[0],JSON_FORCE_OBJECT) ;
    }
}