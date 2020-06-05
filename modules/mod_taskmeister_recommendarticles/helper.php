<?php
/**
 * Helper class for Recommend Articles
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
class ModRecommendArticlesHelper
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

    function getArticleList($params,$noOfArticles, $selectedTag){//Function to get selection from parameter fields
        //Call our recommender
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        //Initialize result
        $results = array("Calculating... ");
        //If never set no of articles
        if (!$noOfArticles) $noOfArticles=10;
        //Check parameters
        switch($params){//Based on parameters, call out the functions accordingly in the plugin
            case 'choice_myLikedList':
                $results = $dispatcher->trigger( 'getMyList', array("Liked",$noOfArticles,""));
                break;
            case 'choice_myDeployedList':
                $results = $dispatcher->trigger( 'getMyList', array("Deployed",$noOfArticles,""));
                break;
            case 'choice_liked'://If mode selected to be by top likes
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Likes",$noOfArticles,""));
                break;
            case 'choice_personal'://If mode selected to be personal recommendation
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Personal",$noOfArticles,""));
                break;
            case 'choice_deployed'://If mode selected to be by most deployed
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Deployed",$noOfArticles,""));
                break;
            case 'choice_untouched'://If mode selected to be articles that the user never touched before
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Untouched",$noOfArticles,""));
                break;
            case 'choice_selectedTag'://If mode selected to be articles of a particular tag
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Selected Tag",$noOfArticles,$selectedTag));
                break;
            default:
                $results = array("Not implemented yet. Please select another filter. ");
                break;
        }       
        //Return string results of recommended articles
        return json_encode($results[0],JSON_FORCE_OBJECT) ;
    }
}