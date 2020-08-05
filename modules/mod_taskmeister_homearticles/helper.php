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
    /**
     * getArticles()
     * Function: Get the articles' contents of the list of id provided
     * Parameter: The list of articles id to take from
     */
    function getArticles($list){
        //Call our recommender
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger( 'getArticleContents', array($list));
        //Return string results of the article contents
        return $results[0] ;
    }
    /**
     * getArticleList()
     * Function: Get the list of article ids from the user preferences
     * Parameter: 
     *  - $noOfArticles: Maximum number of articles to get
     *  - $userid: Current id of the user
     *  - $tagList: List of tags that the user prefers (and may try)
     */
    function getArticleList($noOfArticles, $userid, $tagList){
        //Call our recommender
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        //Initialize result
        $results = array("Calculating... ");
        //If never set no of articles, sets default to 10
        if (!$noOfArticles) $noOfArticles=10;
        //Set up Dictionary array
        $dict = array();
        foreach ($tagList as $tag){//Loop for each tag found in the list
            //Gets the list of articles for the particular tag
            $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Selected Tag",$noOfArticles,$userid,$tag));
            $dict[$tag] = json_encode($results[0],JSON_FORCE_OBJECT);//Save the tag results into the dictionary
        }
        //Return the whole dictionary
        return $dict;
    }
}