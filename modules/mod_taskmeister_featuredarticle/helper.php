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
    function getVideo($params, $articleId)
    {
        /**
         * Function Get Video Link: Crawl video link from article
         * Parameter: $params
         */
        
        //Set up Database Variable
        $db =& JFactory::getDbo();
        //Query for SQL for external article states
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id','fulltext','introtext')))// Get all of the contents
            ->from($db->quoteName('#__content'))//From the external article stats table
            ->where($db->quoteName('id') . ' = ' . intval($articleId));//Where the article id is equal to the chosen article
        $db->setQuery($query);
        $results = $db->loadAssocList(); //Save results as a list
        //if(!strlen(trim($results))) return null;
        //For each item in the restuls
        foreach($results as $row){
            if ($row['id']==$articleId){
                $fullText = ($row['fulltext']);//Save the full text
                $introText = $row['introtext'];//Save the intro text
            } 
        }
        //Check/Crawl within intro text
        if ($introText){
            //$crawledLink = "GC_9w9IV3CI"; Test value
            $vartext = $introText;
            //Keep crawling until no text is left
            while (($vartext=strstr($vartext,"youtube.com/watch?v="))!= NULL ){//If default link
                //Find the below substring
                $crawledLink = strstr($vartext,"youtube.com/watch?v=");
                //Replace it with embed
                $crawledLink = str_replace("watch?v=","embed/",$crawledLink);
                //Filter out the back of the links
                if (strstr($crawledLink,">", true)) $crawledLink = strstr($crawledLink,">", true);
                if (strstr($crawledLink,"\"", true)) $crawledLink = strstr($crawledLink,"\"", true);
                if (strstr($crawledLink,";", true)) $crawledLink = strstr($crawledLink,";", true);
                //Append the front of the link
                $url = "https://www.".$crawledLink;
                //If not invalid, return the url
                if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                    return $url;
                }
                else {//Display invalid url msg
                    echo "<script>console.log('Picked up invalid url: ".$url."')</script>";
                }
            }
            $vartext = $introText;
            //Keep crawling until no text is left
            while (($vartext=strstr($vartext,"youtu.be/")) != NULL ){//If sharing link
                //Search for the below substring
                $crawledLink = strstr($vartext,"youtu.be/");
                //Replace the substring with embed
                $crawledLink = str_replace("youtu.be/","youtube.com/embed/",$crawledLink);
                //Filter out the back of the youtube link
                if (strstr($crawledLink,">", true)) $crawledLink = strstr($crawledLink,">", true);
                if (strstr($crawledLink,"\"", true)) $crawledLink = strstr($crawledLink,"\"", true);
                if (strstr($crawledLink,";", true)) $crawledLink = strstr($crawledLink,";", true);
                //Append the front of the youtube link
                $url = "https://www.".$crawledLink;
                //If the link is a valid url
                if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                    return $url;//Return it
                }
                else {//Else display invalid url msg
                    echo "<script>console.log('Picked up invalid url: ".$url."')</script>";
                }
            }
            $vartext = $introText;
            //Keep crawling until no text is left
            while (($vartext=strstr($vartext,"youtube.com/embed/")) != NULL ){//If embeded link
                //Search for the substring below
                $crawledLink = strstr($vartext,"youtube.com/embed/");
                //Filter the back of the link
                if (strstr($crawledLink,">", true)) $crawledLink = strstr($crawledLink,">", true);
                if (strstr($crawledLink,"\"", true)) $crawledLink = strstr($crawledLink,"\"", true);
                if (strstr($crawledLink,";", true)) $crawledLink = strstr($crawledLink,";", true);
                //Append to the front of the link
                $url = "https://www.".$crawledLink;
                //Check if the link is valid
                if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                    return $url;//If so, return it
                }
                else {//Display invalid url msg
                    echo "<script>console.log('Picked up invalid url: ".$url."')</script>";
                }
            }
        }
        //Check within full text
        if ($fullText){
            //$crawledLink = "GC_9w9IV3CI"; Test value
            $vartext = $fullText;
            //Keep crawling until no text is left
            while (($vartext=strstr($vartext,"youtube.com/watch?v="))!= NULL){//If default link
                //Search through the substrings of the link
                $crawledLink = strstr($fullText,"youtube.com/watch?v=");
                //Replace the string with embed
                $crawledLink = str_replace("watch?v=","embed/",$crawledLink);
                //Filter out the back of the links
                if (strstr($crawledLink,">", true)) $crawledLink = strstr($crawledLink,">", true);
                if (strstr($crawledLink,"\"", true)) $crawledLink = strstr($crawledLink,"\"", true);
                if (strstr($crawledLink,";", true)) $crawledLink = strstr($crawledLink,";", true);
                //Append to the front of the links
                $url = "https://www.".$crawledLink;
                //If the url is valid
                if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                    return $url;//Then return it
                }
                else {//Else display invalid url msg
                    echo "<script>console.log('Picked up invalid url: ".$url."')</script>";
                }
            }
            $vartext = $fullText;
            //Keep crawling until no text is left
            while (($vartext=strstr($vartext,"youtu.be/"))!=NULL){//If sharing link
                //Search for the substring
                $crawledLink = strstr($fullText,"youtu.be/");
                //Replace the substring
                $crawledLink = str_replace("youtu.be/","youtube.com/embed/",$crawledLink);
                //Filter out the back of the link
                if (strstr($crawledLink,">", true)) $crawledLink = strstr($crawledLink,">", true);
                if (strstr($crawledLink,"\"", true)) $crawledLink = strstr($crawledLink,"\"", true);
                if (strstr($crawledLink,";", true)) $crawledLink = strstr($crawledLink,";", true);
                //Append to the front of the link
                $url = "https://www.".$crawledLink;
                //If the link is valid
                if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                    return $url;//Return the link
                }
                else {//Else display invalid url msg
                    echo "<script>console.log('Picked up invalid url: ".$url."')</script>";
                }
            }
            $vartext = $fullText;
            //Keep crawling until no text is left
            while (($vartext=strstr($vartext,"youtube.com/embed/"))!=NULL){//If embeded link
                //Search for the below substring
                $crawledLink = strstr($fullText,"youtube.com/embed/");
                //Filter out the back of the link
                if (strstr($crawledLink,">", true)) $crawledLink = strstr($crawledLink,">", true);
                if (strstr($crawledLink,"\"", true)) $crawledLink = strstr($crawledLink,"\"", true);
                if (strstr($crawledLink,";", true)) $crawledLink = strstr($crawledLink,";", true);
                //Append to the front of the link
                $url = "https://www.".$crawledLink;
                //If the link is valid
                if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                    return $url;//Return the link
                }
                else {//Else display invalid url msg
                    echo "<script>console.log('Picked up invalid url: ".$url."')</script>";
                }
            }
        }
    }
    function recommendArticles($userid, $keyword){
        /**
         * Function Recommend Article: Recommend an article for the user
         * Parameter: None
         */
        //Call our recommender
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        //Initialize result
        $results = array("Calculating... ");
        //Set Number of articles to 4
        $noOfArticles=4;
        //Call recommender engine function
        $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Personal",$noOfArticles,$userid,$keyword));
        //Return string results of recommended articles
        $articleList = array();
        foreach ($results[0] as $key => $value){
            array_push($articleList, $key);
        }
        return $articleList;
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
        $query->select($db->quoteName(array('es_articleid','es_totallikes','es_deployed','es_userchoice')))// Get all of the contents
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
        /**
         * Function Get Article: Get article contents from the database
         * Parameter $articleId: Refers to the id of the chosen article
         */
        $db =& JFactory::getDbo();
        //Query the database
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id','title','images')))//Get the article id, title and images
            ->from($db->quoteName('#__content'))//From the contents table
            ->where($db->quoteName('id') . ' = ' . intval($articleId));//Where id matches the article id
        $db->setQuery($query);
        $results = $db->loadAssocList();//Save the results in $results
        foreach($results as $row){//Loop for each row in the $result
            $fullArticle = $row;//Save the full article as the row
        }
        if (!$fullArticle) $fullArticle = "No article found. ";//If nothing is found, set default msg
        return $fullArticle;//Return the full article
    }
}