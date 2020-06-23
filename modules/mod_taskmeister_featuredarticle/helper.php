<?php
/**
 * Helper class for Featured Article module
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
class ModFeaturedArticleHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getText($params)
    {
        /**
         * Function Get Text: Get text input from Joomla Interface
         * Parameter: $params
         */
        return $params->get('customtext');
    }
    public static function getHeader($params)
    {
        /**
         * Function Get Header: Get header input from Joomla Interface
         * Parameter: $params
         */
        return $params->get('customheader');
    }
    function getVideo($params, $mode, $articleId)
    {
        /**
         * Function Get Video Link: Get video link input from Joomla Interface
         * Parameter: $params
         */
        if ($mode == "choice_no") return $params->get('videolink');//Gives back set video link
        //Else crawl for the video link
        else {
            //Set up Database Variable
            $db =& JFactory::getDbo();
            //Query for SQL for external article states
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id','fulltext')))// Get all of the contents
                ->from($db->quoteName('#__content'))//From the external article stats table
                ->where($db->quoteName('id') . ' = ' . intval($articleId));//Where the article id is equal to the chosen article
            $db->setQuery($query);
            $results = $db->loadAssocList(); //Save results as a list
            //if(!strlen(trim($results))) return null;
            //For each item in the restuls
            foreach($results as $row){
                if ($row['id']==$articleId) $fullText = ($row['fulltext']);//Save the full text
            }
            //return $fullText;
            if ($fullText){
                //$crawledLink = "GC_9w9IV3CI"; Test value
                if(strstr($fullText,"youtube.com/watch?v=")){//If default link
                    $crawledLink = strstr($fullText,"youtube.com/watch?v=");
                    $crawledLink = str_replace("watch?v=","embed/",$crawledLink);
                    $crawledLink = strstr($crawledLink,">", true);
                    $crawledLink = strstr($crawledLink,"\"", true);
                    return "https://www.".$crawledLink;
                }
                if (strstr($fullText,"youtu.be/")){//If sharing link
                    $crawledLink = strstr($fullText,"youtu.be/");
                    $crawledLink = str_replace("youtu.be/","youtube.com/embed/",$crawledLink);
                    $crawledLink = strstr($crawledLink,">", true);
                    $crawledLink = strstr($crawledLink,"\"", true);
                    return "https://www.".$crawledLink;
                }
                if (strstr($fullText,"youtube.com/embed/")){//If embeded link
                    $crawledLink = strstr($fullText,"youtube.com/embed/");
                    $crawledLink = strstr($crawledLink,">", true);
                    $crawledLink = strstr($crawledLink,"\"", true);
                    return "https://www.".$crawledLink;
                }
            }
        }
    }
    function recommendArticle($userid, $keyword){
        /**
         * Function Recommend Article: Recommend an article for the user
         * Parameter: None
         */
        //Call our recommender
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        //Initialize result
        $results = array("Calculating... ");
        //Set Number of articles to 1
        $noOfArticles=3;
        //Call recommender engine function
        $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Personal",$noOfArticles,$userid,$keyword));
        //Return string results of recommended articles
        $articleList = array();
        foreach ($results[0] as $key => $value){
            array_push($articleList, $key);
        }
        return $articleList[rand(0,sizeof($articleList)-1)];
    }
    function getArticleExternalStats($articleId){
        /**
         * Function Get External Article Stats: Get article stats from an external database
         * Parameter $articleId: Refers to the id of the chosen article
         */
        //Set up Database Variable
        $db =& JFactory::getDbo();
        //Query for SQL for external article states
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('*')))// Get all of the contents
            ->from($db->quoteName('#__customtables_table_articlestats'))//From the external article stats table
            ->where($db->quoteName('es_articleid') . ' = ' . intval($articleId));//Where the article id is equal to the chosen article
        $db->setQuery($query);
        $results = $db->loadAssocList(); //Save results as a list
        //For each item in the restuls
        foreach($results as $row){
            $contents = $row;//Save the item as the contents
        }
        //If no contents is found, return default message
        if (!$contents) $contents = "Nothing is found. ";
        //Else return contents
        return $contents;
    }
    function getArticle($articleId){
        $db =& JFactory::getDbo();
        //Query
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('*')))
            ->from($db->quoteName('#__content'))
            ->where($db->quoteName('id') . ' = ' . intval($articleId));
        $db->setQuery($query);
        $results = $db->loadAssocList();
        foreach($results as $row){
            $fullArticle = $row;
        }
        if (!$fullArticle) $fullArticle = "No article found. ";
        return $fullArticle;
    }
}