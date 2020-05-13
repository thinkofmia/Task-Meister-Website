<?php
// no direct access
defined( '_JEXEC' ) or die;
use Joomla\CMS\Factory;

class plgTaskMeisterTM_recommender extends JPlugin
{
    
	/**
	 * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
	 * If you want to support 3.0 series you must override the constructor
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
    //Test Function
	 function calculateMetrics($number)
	 {
		/*
		 * Plugin code goes here.
		 * You can access database and application objects and parameters via $this->db,
		 * $this->app and $this->params respectively
		 */
        return "Wonderful ".($number*2);
    }
    /* Function: Personal Recommend Articles
    Recommend articles for a particular user
    Used only for articles module
    Maybe just 10 articles
     */
    function recommendPersonalArticles($userid){//WIP
        $db = Factory::getDbo();//Gets database
        //Get external user table (custom table) To find out list of liked, deployed and disliked articles
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_userid','es_pageliked','es_pagedisliked','es_pagedeployed')))
            ->from($db->quoteName('#__customtables_table_userstats'))
            ->where($db->quoteName('es_userid') . ' = ' . $userid);
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();

        //Save information into a list
        foreach ($results_ext as $row){
            if ($row['es_userid']==$userid){//Just to be sure if user id is same
                $likedlist = $row['es_pageliked'];
                $blacklist = $row['es_pagedisliked'];
                $deployedlist = $row['es_pagedeployed'];
            }
            
        }
        return array($likedlist,$blacklist,$deployedlist);////WIP Currently displaying three lists
    }

    /* Function: Create List
    This function creates an unordered array from an existing one.
    Can be used anywhere
     */
    function createList($array_str){//Parameters used must be an array string
        $array = json_decode($array_str,true);//Decodes string into array
        $list = array_values($array);//Get the values of the array and store into an array (Removes the presence of keys)
        $list_str = json_encode($list);//Stores into a string
        if (!isset($list_str)) $list_str = '[]';//If null, set as empty instead
        return $list_str;//Return new unordered array
    }
    
    /* Function: Fix Article Statistics
    This function automatically updates/refreshes the article statistics.
     It should be used when adding new articles
     */
    function fixArticleStats(){
        $db = Factory::getDbo();//Gets database
        //Get article bank from database query
        $query2 = $db->getQuery(true);
        $query2->select($db->quoteName(array('id','title','state')))
            ->from($db->quoteName('#__content'));
        $db->setQuery($query2);
        $results_bank = $db->loadAssocList();

        //Get external article table (custom table)
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('a.*')))
            ->from($db->quoteName('#__customtables_table_articlestats','a'));
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();

        //Store the current articles in external table into an array
        $curr_articles =array();
        foreach ($results_ext as $row2) { 
            //Add to store
            array_push($curr_articles, $row2['es_articleid']);
            //Count total likes/dislikes
            $results = $this->countArticleLikes($row2['es_userchoice']);
            $totalLikes = $results[0][0];
            $totalDislikes = $results[0][1];
            //Update total if not same
            if ($totalLikes!=$row2['es_totallikes']||$totalDislikes!=$row2['es_totaldislikes']){
                // Create and populate an object.
                $articleInfo = new stdClass();
                $articleInfo->es_articleid = $row2['es_articleid'];
                $articleInfo->es_totallikes = $totalLikes;
                $articleInfo->es_totaldislikes = $totalDislikes;
                    
                // Update the object into the article profile table.
                $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
            }
        }

        //Add in new articles if any
        foreach ($results_bank as $row){
            //Update article class
            $articleInfo = new stdClass();
            $articleInfo->es_articleid = $row['id'];
            $articleInfo->es_title = $row['title'];
            if (($row['state']!=1)){// delete if article is unpublished
                $query3 = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('es_articleid') . ' = ' . $row['id']
                );
                $query3->delete($db->quoteName('#__customtables_table_articlestats'));
                $query3->where($conditions);
                $db->setQuery($query3);
                $result = $db->execute();
            }
            else if (in_array($row['id'], $curr_articles)&& ($row['state']==1)){
                // Update article info if exists.
                $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
            }
            else if ($row['state']==1){//Insert article info if doesn't exists
                $result = JFactory::getDbo()->insertObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
            }
        }   
        return false;
    }
    
    /* Function: Fix User Statistics
    This function automatically updates/refreshes the user statistics.
     It should be used when adding a new user/updating user profiles
     */
    function fixUserStats(){
        $db = Factory::getDbo();//Gets database
        $me = Factory::getUser();//Gets user 
        //Get user bank from database query
        $query2 = $db->getQuery(true);
        $query2->select($db->quoteName(array('id','name','email')))
            ->from($db->quoteName('#__users'));
        $db->setQuery($query2);
        $results_bank = $db->loadAssocList();

        //Get external user table (custom table)
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('a.*')))
            ->from($db->quoteName('#__customtables_table_userstats','a'));
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();

        //Store the current users in external table into an array
        $ext_users =array();
        foreach ($results_ext as $row2) { 
            array_push($ext_users, $row2['es_userid']);
        }
        //Add in new users if any
        foreach ($results_bank as $row){
            //Update user class
            $userInfo = new stdClass();
            $userInfo->es_userid = $row['id'];
            $userInfo->es_name = $row['name'];
            $userInfo->es_email = $row['email'];
            if (in_array($row['id'], $ext_users)){
                // Update user info if exists.
                $result = JFactory::getDbo()->updateObject('#__customtables_table_userstats', $userInfo, 'es_userid');
            }
            else{//Insert user info if doesn't exists
                $result = JFactory::getDbo()->insertObject('#__customtables_table_userstats', $userInfo, 'es_userid');
            }
        }   

        //Update more statistics elements
            foreach ($results_ext as $row) { 
                //For deployment list
                if (isset($row['es_pagedeployed'])) $deployedList = $this->createList($row['es_pagedeployed']);
                else $deployedList = "[]";
                //For Liked pages
                if (isset($row['es_pageliked'])) $likedList = $this->createList($row['es_pageliked']);
                else $likedList = "[]";
                //For Disliked pages
                if (isset($row['es_pagedisliked'])) $dislikedList = $this->createList($row['es_pagedisliked']);
                else $dislikedList = "[]";
                //For user preference
                if (isset($row['es_userpreference'])) $preferenceList = $this->createList($row['es_userpreference']);
                else $preferenceList = "[]";
                //Create user class
                $userInfo = new stdClass();
                $userInfo->es_userid = $row['es_userid'];
                $userInfo->es_pageliked =  $likedList;
                $userInfo->es_pagedisliked =  $dislikedList;
                $userInfo->es_pagedeployed =  $deployedList;
                $userInfo->es_userpreference =  $preferenceList;
                // Update the object into the custom user table.
                $result = JFactory::getDbo()->updateObject('#__customtables_table_userstats', $userInfo, 'es_userid');
        }
        return true;//Can return anything. Used true to see if succeeded.
    }
    /* Function: Count Article Likes
    This function calculates the total likes and dislikes of an article.
    Can be used anywhere.
     */
    function countArticleLikes($list){//Parameters: Requires Article's user choice list
        //Initialize variables
        $NoLikes = 0;
        $NoDislikes = 0;
        //For loop counting
        foreach ($list as $row){
            if ($row == "Liked") $NoLikes +=1;
            else if ($row == "Disliked") $NoDislikes +=1;
        }
        //Return an array of numbers. To access it, its something like $result[0][0] or $result[0][1]
        return array($NoLikes,$NoDislikes);
    }
    /* Function: Welcome Text
    Welcome message of the plugin.
    Can be used anywhere.
     */
    function welcomeText(){
        $db = Factory::getDbo();
        $me = Factory::getUser();
        $username = $me->username;
        return "Welcome ".$username."!";
    }
}
?>