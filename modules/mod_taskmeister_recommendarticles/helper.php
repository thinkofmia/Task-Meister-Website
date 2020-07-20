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
    function getTeachersRecommendations($noOfArticles, $userid, $db){
        //Call our recommender
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        //Initialize result
        $results = array("Calculating... ");
        //If never set no of articles
        if (!$noOfArticles) $noOfArticles=10;
        //Get all the teachers that the student is under
        if ($userid != 0 ){//If user is not a guest
            //Get external teacher table (custom table)
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('es_teacherid','es_students')))
            ->from($db->quoteName('#__customtables_table_teacherstats'));
            $db->setQuery($query);
            $results_ext = $db->loadAssocList();
            //Save information into a list
            $yourTeachers = array();//Your teacher list
            foreach ($results_ext as $row){//For loop
                if (in_array(intval($userid), json_decode($row['es_students']))){//If student exists in teacher's class
                    array_push($yourTeachers, $row['es_teacherid']);
                }
            }
            $yourTeachersContent = array();
            foreach($yourTeachers as $row){
                //Get recommendation results of a particular teacher
                //Get external user table (custom table) To find out list of liked, deployed and disliked articles
                $query = $db->getQuery(true);
                $query->select($db->quoteName(array('es_userid','es_pageliked','es_pagedisliked','es_pagedeployed','es_userpreference')))
                    ->from($db->quoteName('#__customtables_table_userstats'))
                    ->where($db->quoteName('es_userid') . ' = ' . intval($row));
                $db->setQuery($query);
                $results_ext = $db->loadAssocList();
                //Get teacher's liked pages
                foreach ($results_ext as $row_user){
                    if (intval($row_user['es_userid'])==intval($row)){
                        $likedPages = json_decode($row_user['es_pageliked']);
                    }
                }
                $temp_list = array(); //To store the array
                foreach ($likedPages as $row_temp){
                    $temp_list[$row_temp] = 100;//Set weightage to 100
                }
                $teacherList = json_encode($temp_list);//Save as a string
                //Get article contents
                $results2 = $dispatcher->trigger( 'getArticleContents', array($teacherList));
                $teacherContents = json_encode($results2[0]);
                $teacher = JFactory::getUser($row);//Get Teacher Profile
                $teacherName = $teacher->name;//Get Teacher Name
                //Add your teacher into the recommendations content
                $yourTeachersContent[$teacherName] = $teacherContents;
            }
            if ($yourTeachersContent) return $yourTeachersContent;
            else return;
        }

    }
    function getArticleList($params,$noOfArticles, $userid, $selectedTag, $keyword){//Function to get selection from parameter fields
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
                $results = $dispatcher->trigger( 'getMyList', array("Liked",$noOfArticles,$userid));
                break;
            case 'choice_myDeployedList':
                $results = $dispatcher->trigger( 'getMyList', array("Deployed",$noOfArticles,$userid));
                break;
            case 'choice_liked'://If mode selected to be by top likes
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Likes",$noOfArticles,$userid,$keyword));
                break;
            case 'choice_personal'://If mode selected to be personal recommendation
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Personal",$noOfArticles,$userid,$keyword));
                break;
            case 'choice_deployed'://If mode selected to be by most deployed
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Deployed",$noOfArticles,$userid,$keyword));
                break;
            case 'choice_untouched'://If mode selected to be articles that the user never touched before
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Untouched",$noOfArticles,$userid,$keyword));
                break;
            case 'choice_selectedTag'://If mode selected to be articles of a particular tag
                $results = $dispatcher->trigger( 'recommendPersonalArticles', array("Selected Tag",$noOfArticles,$userid,$selectedTag));
                break;
            case 'choice_trending'://If mode selected to be the top trending articles
                $results = $dispatcher->trigger( 'recommendTrendingArticles', array($noOfArticles,$userid,$keyword));
                break;
            default:
                $results = array("Not implemented yet. Please select another filter. ");
                break;
        }       
        //Return string results of recommended articles
        return json_encode($results[0],JSON_FORCE_OBJECT) ;
    }
}