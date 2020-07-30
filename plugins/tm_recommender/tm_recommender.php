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
    /**
     * Test Function (Unused)
     * */
	 function functionName($functionParameters)
	 {
		/*
		 * Plugin code goes here.
		 * You can access database and application objects and parameters via $this->db,
		 * $this->app and $this->params respectively
		 */
        return "Function works: ".$functionParameters;
    }
    /***
     * saveUserPreference()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: Saves the user's preference list into the database
     * Parameters: List of Preferred Tags, List of Against Tags, List of May Try Tags 
     */
    function saveUserPreference($preferredList, $notPreferredList, $mayTryList){
        //Set the variables for database and user
        $db = Factory::getDbo();
        $me = Factory::getUser();
        //Set the user id
        $userID = $me->id;
        //Sets the username
        $username = $me->username;
        //Create a new array for the latest preference list
        $newPreferenceList = array();
        //Loop all the items in the list of Preferred Tags
        foreach (json_decode($preferredList) as $row){//Json Decode to convert string to array
            $newPreferenceList[$row] = 2;//Save the Preferred Tags with the value 2 into the new array
        }
        //Loop all the items in the list of Against Tags
        foreach (json_decode($notPreferredList) as $row){//Json Decode to convert string to array
            $newPreferenceList[$row] = 0;//Save the Against Tags with the value 0 into the new array
        }
        //Loop all the items in the list of May Try Tags
        foreach (json_decode($mayTryList) as $row){//Json Decode to convert string to array
            $newPreferenceList[$row] = 1;//Save the May Try Tags with the value 1 into the new array
        }
        //Save the data into a Class
        $userInfo = new stdClass();//Create a new class as userInfo
        $userInfo->es_userid = $userID;//Set the user id of the class to be the target user id
        $userInfo->es_userpreference = json_encode($newPreferenceList);//Save the new preference list as a string version
        // Update the object into the user statistics table by their user id
        $result = JFactory::getDbo()->updateObject('#__customtables_table_userstats', $userInfo, 'es_userid');
        return "Saved!";//Return success message
    }
    /***
     * saveClassModifiers()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: Saves the teacher's class modifiers into the database
     * Parameters: $data refers to the array of weightages the teacher has set for the class 
     */
    function saveClassModifiers($data){
        //Set the variables of the database and user
        $db = Factory::getDbo();
        $me = Factory::getUser();
        //Get the user id
        $userID = $me->id;
        //Get the user name
        $username = $me->username;
        //Search the database for the particular teacher
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_teacherid')))//Select the teacher's id
        ->from($db->quoteName('#__customtables_table_teacherstats'))//From the teacher statistics table
        ->where($db->quoteName('es_teacherid') . ' = ' . $userID);//Where the teacher id is equal to the target user id
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();//Save the results as $results_ext
        //Check if the user is indeed a teacher
        if ($results_ext){//If the user is a teacher
            // Create and populate the teacher's info.
            $teacherInfo = new stdClass();//Create a new class called $teacherInfo
            $teacherInfo->es_teacherid = $userID;//Set the teacher id as the current user id
            if ($data['likesWeight']) $teacherInfo->es_weightagelikes = $data['likesWeight'];//Save the likes weightage
            if ($data['deployedWeight']) $teacherInfo->es_weightagedeployment = $data['deployedWeight'];//Save the deployed weightage
            if ($data['touchedWeight']) $teacherInfo->es_weightagetouched = $data['touchedWeight'];//Save the touched before weightage
            if ($data['preferredWeight']) $teacherInfo->es_weightagepreferred = $data['preferredWeight'];//Save the preferred tag weightage
            if ($data['unpreferredWeight']) $teacherInfo->es_weightagenotpreferred = $data['unpreferredWeight'];//Save the against tag weightage
            if ($data['mayTryWeight']) $teacherInfo->es_weightagemaytry = $data['mayTryWeight'];//Save the may try tag weightage
            if ($data['togglePreferenceLinkage']) $teacherInfo->es_preferencelink = $data['togglePreferenceLinkage'];//Save the toggle preference link (Unused)
            if ($data['bonusTags']) $teacherInfo->es_bonustags = $data['bonusTags'];//Save the bonus tags weightage
            else $teacherInfo->es_bonustags = "[]";//If there is no bonus tags, save it as an empty array instead
            // Update the object into the teacher stats table by the teacher's id
            $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
            return true;//Return success
        }
        else{//Else if the user is not a teacher
            return false; //Return failed
        }
    }
    /***
     * saveOurTeachers()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: Saves the student's teachers into the database
     * Parameters: $teacherList_str refers to the string version of the list of teachers the student has selected
     */
    function saveOurTeachers($teacherList_str){
        //Set the variables of the database and user
        $db = Factory::getDbo();
        $me = Factory::getUser();
        //Gets the user id
        $userID = $me->id;
        //Gets the user name
        $username = $me->username;
        //Convert the list of teachers from a string to an array
        $teacherList = json_decode($teacherList_str);
        //Query from the database
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_teacherid','es_students')))//Get the teacher id and their list of students
        ->from($db->quoteName('#__customtables_table_teacherstats'));//From the teacher statistics table
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();//Save results as $results_ext
        //For each teacher found in the results, loop
        foreach ($results_ext as $row){
            $newStudentList = json_decode($row['es_students']); //Gets the list of students the teacher has
            /*
            * Check if needs any update: 
                - If the teacher is one of the teachers in the user's selection, and
                - If the user is not in the teacher's class in the database
            */
            if (in_array($row['es_teacherid'], $teacherList)&& !in_array($userID, $newStudentList)){
                //Push the user into the teacher's list of students
                array_push($newStudentList, intval($userID));
                // Create a class to store into the database
                $teacherInfo = new stdClass();
                $teacherInfo->es_teacherid = $row['es_teacherid'];//Set the teacher id to be that of the current teacher
                $teacherInfo->es_students = json_encode($newStudentList);//Save the new list of students into a string
                // Update the object into the teacher statistics table.
                $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
            }
            /**
             * Else check if need remove the student from the teacher's list of students
             *  - If the teacher is no longer within the student's selection of teachers, and
             *  - If the student is in the teacher's class in the current database
             */
            else if (!in_array($row['es_teacherid'], $teacherList) && in_array(intval($userID), $newStudentList)){
                //Find the position of the student in the class array
                $key = array_search(intval($userID), $newStudentList);
                //If position is found
                if ($key !== false) {
                    unset($newStudentList[$key]);//Removes the student from the class
                }
                // Create and populate the table.
                $teacherInfo = new stdClass();//Set up the teacher's class
                $teacherInfo->es_teacherid = $row['es_teacherid'];//Set the teacher id 
                $teacherInfo->es_students = json_encode($newStudentList);//Save the new list of students in their class
                // Update the object into the teacher statistics table.
                $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
            }
        }
        return "Saved!";//Return confirmation result
    }
    /***
     * saveOurStudents()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: Saves the teacher's new clas into the database
     * Parameters: $studentList_str refers to the string version of the list of students the teacher has selected
     */
    function saveOurStudents($studentList_str){
        //Set variable database and user
        $db = Factory::getDbo();
        $me = Factory::getUser();
        $userID = $me->id;//Gets the user id
        $username = $me->username;//Gets the user name
        $studentList = "[]";//Sets the class to be an empty stringified array
        if ($studentList_str) $studentList = $studentList_str;//If there exists a new list (from the parameter), replace it instead
        //Setup the new teacher class
        $teacherInfo = new stdClass();
        $teacherInfo->es_teacherid = $userID;//Set the teacher id to user id
        $teacherInfo->es_students = $studentList;//Save the new class into the selection
        // Update the row in the teacher statistics table by the teacher id
        $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
        return "Saved!";//Return confirmation message
    }
    /***
     * getTagList()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: Gets all the tags that are currently in used
     */
    function getTagList(){
        //Sets the variable of the database
        $db = Factory::getDbo();
        //Query the database (For the tag names)
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('title')))//Get the name of the tag
            ->from($db->quoteName('#__tags'))//From the tags table
            ->order($db->quoteName('title') . ' ASC');//By ordering it in ascending order
        $db->setQuery($query);
        $results_tags = $db->loadAssocList();//Save results into $results_tags
        //Query the database again (For the tag statistics)
        $query2 = $db->getQuery(true);
        $query2->select($db->quoteName(array('es_tags','es_totallikes','es_totaldislikes','es_totaldeployed')))//Get the tags of the article, total likes, dislikes and deployments
            ->from($db->quoteName('#__customtables_table_articlestats'));//From the custom article statistics table
        $db->setQuery($query2);
        $results_art = $db->loadAssocList();//Save results as $results_art
        //Initialize new tag list
        $tagList = array();
        //Add the default tags here
        $defaultTags = array(//As requested from the taskmeister prototype in the pptx
            "Board Games",
            "Cosplay", "Current Affairs", 
            "Dance", "Digital Manipulatives", "Drama",
            "Escape Rooms",
            "Fashion", "Food",
            "Model Making", "Movies", "Music",
            "Nature",
            "Online Games", "Outdoor Tasks",
            "Physical Manipulatives", "Poems", "Puzzles",
            "Science", "Simulations", "Sports", "Statistics", "Stories",
            "Travelling", "Treasure Hunts",
            "Virtual Reality");
        //Add default tags array into tag list, places the tags at the start of the array (as requested)
        foreach ($defaultTags as $row){//Loop for each default tag
            $tagList[$row] = array(//Save initialized stats of likes, deployed and dislikes
                "likes" => 0,//Total number of likes
                "deployed" => 0,//Total number of deployments
                "dislikes" => 0 //Total number of dislikes
            );
        }
        //Populate the rest of the tag list with those found in the database
        foreach($results_tags as $row){//Loop for each tag found in the database
            $tagList[$row['title']] = array(//Save initialized stats of likes, deployed and dislikes
                "likes" => 0,//Total number of likes
                "deployed" => 0,//Total number of deployments
                "dislikes" => 0 //Total number of dislikes
            );
        }
        //Append the statistics (likes/deployed/dislikes) to each tag based on the article statistices
        foreach ($results_art as $row){//Loop for each article
            //Get tags of each article
            $articleTags = json_decode($row['es_tags']);//Convert it from a string to an array
            $noOfLikes = $row['es_totallikes'];//Get the article's total likes
            $noOfDislikes = $row['es_totaldislikes'];//Gets the article's total dislikes
            $noOfDeployed = $row['es_totaldeployed'];//Gets the article's total deployment
            //Update the tag with the article statistics
            foreach ($articleTags as $row2){//Loop for each tag in the article
                $tagList[$row2]["likes"] += $noOfLikes;//Add to the number of likes for the tag
                $tagList[$row2]["dislikes"] += $noOfDislikes;//Add to the number of dislikes for the tag
                $tagList[$row2]["deployed"] += $noOfDeployed;//Add to the number of deployment for the tag
            }
        }
        return $tagList;//Returns the tag list found
    }
    /***
     * getTeachersList()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: Gets the list of teachers set in the database
     * Parameter: None
     */
    function getTeachersList(){
        //Sets database variable
        $db = Factory::getDbo();
        //Query the database
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_teacherid')))//Get the teacher id
            ->from($db->quoteName('#__customtables_table_teacherstats'));//From the custom teacher statistics stable
        $db->setQuery($query);
        $results_teachers = $db->loadAssocList();//Save the results as $results_teachers
        //Initialize the list of teachers as a new array
        $teacherList = array();
        //Loop each teacher found in the database query
        foreach($results_teachers as $row){
            //Get the user by the teacher's id
            $teacher = JFactory::getUser($row['es_teacherid']);
            //Save the teacher into the array along with their id and name
            $teacherList[$row['es_teacherid']] = $teacher->name;
        }
        return $teacherList;//Returns list of teachers
    }
    /***
     * getArticleContents()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: Gets all the relevant data of the selected articles to display
     * Parameter: $list_str refers to the stringified list of article ids to get the data of
     */
    function getArticleContents($list_str){
        //Convert the string to an array
        $list = json_decode($list_str,true);
        //Sets the database variable
        $db = Factory::getDbo();
        //Query the database
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id','title','images')))//Get the article id, title and images
            ->from($db->quoteName('#__content'));//From the content table
        $db->setQuery($query);
        $results_art = $db->loadAssocList();//Save results as $results_art
        //Initialize a new array to store the contents
        $contentCollection = array();
        //Loop for each article found
        foreach ($results_art as $row){
            $key_str = "".$row['id'];//Convert the id to a string
            if(array_key_exists($key_str,$list)){//If the string/id exists in the list
                //If there is a title found, save the article's title, image and the similarity percentage
                if ($row['title']) $contentCollection[$row['id']] = array($row['title'],$row['images'],$list[$key_str]);
            }
        
        }
        //Initialize a new array to store all the information into a collection
        $displayCollection = array();
        //This array is just an addition precaution if we want to add additional things to play around
        //Sadly never got the time to do so
        //Loop for each value in the list 
        foreach ($list as $key => $val){
            //Save the content collections into the display collections
            $displayCollection[intval($key)] = $contentCollection[intval($key)];
        }
        return $displayCollection;//Return the list of article contents
    }
    /***
     * getMyList()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: Get the user's list of liked/deployed articles
     * Parameter:
     *  - $mode: check if get liked or deployed articles
     *  - $noOfArticles: check the maximum number of articles to get
     *  - $userid: Set the target user to take from
     */
    function getMyList($mode, $noOfArticles, $userid){
        //Sets the database variable
        $db = Factory::getDbo();
        //Query the database
        $query = $db->getQuery(true);
        //Get the user id, list of liked pages, list of disliked pages, list of deployed pages and their preferences
        $query->select($db->quoteName(array('es_userid','es_pageliked','es_pagedisliked','es_pagedeployed','es_userpreference')))
            ->from($db->quoteName('#__customtables_table_userstats'))//From the custom user statistics table
            ->where($db->quoteName('es_userid') . ' = ' . $userid);//Where the user id is equal to the one in the parameter
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();//Save results in $results_ext
        //Loop for each user found in results (should be just one)
        foreach ($results_ext as $row){
            if ($row['es_userid']==$userid){//Just to double confirm that the user is the same user as in the parameters
                $likedlist = json_decode($row['es_pageliked']);//Convert and save the liked pages to an array
                $blacklist = json_decode($row['es_pagedisliked']);//Convert and save the disliked pages to an array
                $deployedlist = json_decode($row['es_pagedeployed']);//Convert and save the deployed pages to an array
                $preferencelist = json_decode($row['es_userpreference']);//Convert and save the user preference to an array
            }
        }
        //Initialize the results as an array
        $resultList = array();
        $count = 0;//Initialize counter
        //Check which mode it is to get the list of
        if ($mode == "Deployed"){//If mode is deployed
            foreach ($deployedlist as $row){//Loop through the list of deployed pages
                if ($count<$noOfArticles){//If the count is lesser than the max number of articles
                    $resultList[$row] = 100;//Save the article into resulting list
                    $count = $count + 1;//Increment the counter by 1
                }
            }
        }
        else if ($mode == "Teacher"){//If mode is teacher: Getting what the teacher recommends
            //Liked pages take priority over deployed pages
            foreach ($likedlist as $row){//Loop through the list of liked pages
                if ($count<$noOfArticles){//If the count is lesser than the max number of articles
                    $resultList[$row] = 100;//Save the article into the resulting list
                    $count = $count + 1;//Increment the counter by 1
                }
            }
            foreach ($deployedlist as $row){//Loop through the list of deployed pages
                if ($count<$noOfArticles){//If the count is lesser than the max number of articles
                    $resultList[$row] = 100;//Save the article into the resulting list, also accounts if the article already exists in the list
                    $count = $count + 1;//Increment the counter by 1
                }
            }
        }
        else {//If mode is likes
            foreach ($likedlist as $row){//Loop through the list of liked pages
                if ($count<$noOfArticles){//If the count is lesser than the max number of articles
                    $resultList[$row] = 100;//Save the article into the resulting list
                    $count = $count + 1;//Increment the counter by 1
                }
            }
        }
        return $resultList;//Return the resulting list
    }
    /***
     * recommendTrendingArticles()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: 
     *  - Recommend the user the current trending articles,
     *  - Takes only in account of articles that are active in the last month
     *  - Regardless of the user preferences
     * Parameter:
     *  - $noOfArticles: check the maximum number of articles to get
     *  - $userid: Set the target user to take from
     *  - $parameter1: Additional parameter if any
     */
    function recommendTrendingArticles($noOfArticles, $userid, $parameter1){
        //Sets the database variable
        $db = Factory::getDbo();
        //Set Additional Filter Mode if has keyword (Currently not in use)
        //if (strlen($parameter1)>0){//Check if additional parameter exists
            //$searchMode = true; //If so, set search mode to be true
        //    $keywords = explode(" ",$parameter1);//Divide up the strings into individual keywords
        //}

        $today = date("Y-m-d");//Get today's date
        date_sub($today,date_interval_create_from_date_string("30 days"));//Get last month's date
        $lastmonth = date_format($today,"Y-m-d");//Set last month's date to year-month-day format
        //Query the database
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_date','es_aid','es_action')))//Get the date of action, the target article and corresponding action
            ->from($db->quoteName('#__customtables_table_recommendationstats'));//From the custom recommendation statistics table
        $db->setQuery($query);
        $results_recent = $db->loadAssocList();//Save results into $results_recent
        //Initialize array of the trending articles
        $trendingArticles = array();
        //Initialize the highest weighing counter
        $highest = 0;
        //Set up additional database variable (to check if article is trashed/unpublished)
        $content_db =& JTable::getInstance("content");
        //Loop for each action found in the results
        foreach ($results_recent as $row){
            //Check if the date of action was within last month
            if (strcmp($row['es_date'],$lastmonth)){//If so
                $aid = intval($row['es_aid']);//Get the article id
                //Load the article from the database by id
                $content_db->load($aid);
                $article_state = $content_db->get("state");//Get the state of the target article
                if ($article_state==1){//If it is published
                    if (!isset($trendingArticles[$aid])){//If the article doesn't exist inside the array
                        $trendingArticles[$aid] = 0;//Initialize the article data in it
                    } 
                    switch ($row['es_action']){//Check the different cases of the action
                            case "liked"://If the user liked the article
                                $trendingArticles[$aid] += 20;//Increase the value of the article by 10
                                break;
                            case "deployed"://If the user deployed the article
                                $trendingArticles[$aid] += 10;//Increase the value of the article by 10
                                break;
                            case "disliked"://If the user disliked the article
                                $trendingArticles[$aid] += 1;//Increase the value of the article by 1
                                break;
                            case "updated their review for"://If the user updated the review for the article
                                $trendingArticles[$aid] += 3;//Increase the value of the article by 3
                                break;
                            case "submitted a review for"://If the user submitted a review for the article
                                $trendingArticles[$aid] += 5;//Increase the value of the article by 5
                                break;
                            default://If any other actions
                                $trendingArticles[$aid] += 1;//Increment value by 1
                                break;
                        }
                    //If the highest counter is lesser than the value of the target article    
                    if ($highest < intval($trendingArticles[$aid])){
                            $highest = intval($trendingArticles[$aid]);//Set the new highest
                        }
                }
            }
        }
        //Sort articles in descending order
        arsort($trendingArticles);
        //Return articles after counting and setting similarity values
        $finalList = array();//Initialize the resultant array
        $count = 0;//Initialize the counter
        foreach ($trendingArticles as $key => $val){//Loop for each article saved in the previous steps
            if ($count<$noOfArticles){//If the counter is lesser than the maximum number of articles
                /*//Check search mode (Disabled)
                if (isset($searchMode)){//If Search Mode is on
                    //Query for database article contents (to check within text)
                    $query = $db->getQuery(true);
                    $query->select($db->quoteName(array('id','introtext','title','fulltext')))
                        ->from($db->quoteName('#__content'))
                        ->where($db->quoteName('id') . ' = ' . intval($key));
                    $db->setQuery($query);
                    $articleContents = $db->loadAssoc();
                    //Get article info database (to check within tags)
                    $query2 = $db->getQuery(true);
                    $query2->select($db->quoteName(array('es_tags')))
                        ->from($db->quoteName('#__customtables_table_articlestats'))
                        ->where($db->quoteName('es_articleid') . ' = ' . intval($key));
                    $db->setQuery($query2);
                    $results_tag = $db->loadAssocList();
                    //Set Variables
                    $counter = 0; //Counter to find all str searches
                    $totalSearchModifier = 0;
                    //Loop for each keyword
                    foreach ($keywords as $keyword){
                        $needle = false;//Needle to find if keyword is found
                        $searchModifier = 0;//Reset this word modifier
                        //Check insider title
                        if (stristr($articleContents['title'], $keyword)){
                            $needle = true;
                            $searchModifier += 10;
                        }
                        //Check inside texts
                        if (stristr($articleContents['introtext'], $keyword)){
                            $needle = true;
                            $searchModifier += substr_count($articleContents['introtext'], $keyword);
                        } 
                        if (stristr($articleContents['fulltext'], $keyword)){
                            $needle = true;
                            $searchModifier += substr_count($articleContents['fulltext'], $keyword);
                        } 
                        foreach($results_tag as $row_tag){
                            if (stristr($row_tag, $keyword)){
                                $searchModifier +=2;
                                $needle = true;
                            }
                        }
                        //If needle exists add to counter
                        if ($needle){
                            $counter+=1;
                            $totalSearchModifier += $searchModifier;
                        } 
                    }
                    //Check counter
                    if ($counter<sizeof($keywords)) $val = -1;//If not inside query, remove it
                }*/
                
                if ($val > 0){//If the value of the article is larger than 0
                    $finalList[intval($key)] = floor($val/$highest*100);//Store the percentage similarity match into the resultant list
                    $count+=1;//Increment the number of articles in the resulting list by 1
                } 
            }
        }
        return $finalList;//Return the final list
    }
    /***
     * recommendPersonalArticles()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: 
     *  - Recommend the user the articles
     *  - Matches to the user's preferences and their blacklist
     * Parameter:
     *  - $mode: checks which mode to sort the articles by
     *  - $noOfArticles: check the maximum number of articles to get
     *  - $userid: Set the target user to take from
     *  - $parameter1: Additional parameter if any
     * Returns a stringified array of article ids
     */
    function recommendPersonalArticles($mode,$noOfArticles, $userid, $parameter1){
        $db = Factory::getDbo();//Sets the database variable
        //Set Additional Filter Mode if has keyword
        if ($mode!="Selected Tag" && strlen($parameter1)>0){//If the mode is not for a specific tag and the additional parameter exists
            //Activate search mode
            $searchMode = true;
            //Split the additional parameters into individual keywords
            $keywords = explode(" ",$parameter1);
            //Debug message to show in the inspector what are the keywords found
            echo "<script>console.log('Getting keywords: ".json_encode($keywords)."')</script>";
            //Initialize the Debug counters
            $allKeywordCounter = 0;//This counter is to check the number of articles with all the keywords
            $anyKeywordCounter = 0;//This counter is to check the number of articles with any of the keywords
            //Print out debug message: Current set it to search results only with all keywords that exists
            echo "<script>console.log('Mode Selected: Only show results if all keywords exist. ')</script>";
        }
        //Query the database for the user info
        $query = $db->getQuery(true);
        //Gets the user id, their liked pages, their disliked pages, their deployed pages and their preferences
        $query->select($db->quoteName(array('es_userid','es_pageliked','es_pagedisliked','es_pagedeployed','es_userpreference')))
            ->from($db->quoteName('#__customtables_table_userstats'))//From the custom user statistics table
            ->where($db->quoteName('es_userid') . ' = ' . $userid);//Where the user id is the same as indicated in the parameters
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();//Save results into $results_ext
        //Loop for each user id found
        foreach ($results_ext as $row){
            if ($row['es_userid']==$userid){//Just double confirm if it is the target user
                //If there exists a list of liked pages
                if ($row['es_pageliked']) $likedlist = json_decode($row['es_pageliked']);//Save the converted string as an array of liked pages
                else $likedlist = [];//Else initialize the array as empty
                //If there exists a list of disliked pages
                if ($row['es_pagedisliked']) $blacklist = json_decode($row['es_pagedisliked']);//Save the converted string as an array of disliked pages
                else $blacklist = [];//Else initialize the array as empty
                //If there exists a list of deployed pages
                if ($row['es_pagedeployed']) $deployedlist = json_decode($row['es_pagedeployed']);//Save the converted string as an array of deployed pages
                else $deployedlist = [];//Else initialize the array as empty
                //If there exists a list of preferences
                if ($row['es_userpreference']) $preferencelist = json_decode($row['es_userpreference']);//Save the converted string as an array of preferences
                else $preferencelist = [];//Else initialize the array as empty
            }
        }
        //Query the database to get the article information
        $query2 = $db->getQuery(true);
        //Gets the article id, title, its tags, all the users' opinions of it, list of users that deployed it, total number of likes, dislikes and deployment
        $query2->select($db->quoteName(array('es_articleid','es_title','es_tags','es_userchoice','es_deployed','es_totallikes','es_totaldislikes','es_totaldeployed')))
            ->from($db->quoteName('#__customtables_table_articlestats'));//From the custom article statistics table
        $db->setQuery($query2);
        $results_art = $db->loadAssocList();//Save the results into $results_art
        //Query the database for the teachers' information (This is used to allow the teachers to influence the students' searches)
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_teacherid','es_students')))//Gets the teacher id and their class
        ->from($db->quoteName('#__customtables_table_teacherstats'));//From the custom teacher statistics table
        $db->setQuery($query);
        $results_teacher = $db->loadAssocList();//Save results into $results_teacher
        //Get and Set default weightages value (See the readME for the recommended weightage values)
        $notPreferredModifier = 100*((($this->params)->get('notpreferredweightage'))/100);//Sets the Against Modifier with the plugin's weightage
        $mayTryModifier = 100*((($this->params)->get('maytryweightage'))/100);//Sets the May Try Modifier with the plugin's weightage
        $preferredModifier = 200*((($this->params)->get('preferredweightage'))/100);//Sets the Preferred Modifier with the plugin's weightage
        $likesBonus = 1;//Set the liked bonus to be a default 1
        $deployedBonus = 1;//Set the deployed bonus to be a default 1
        $touchedBonus = 1;//Set the touched before bonus to be a default 1
        $bonusPreferences = array();//Initialize an array For additional tags influenced by teachers
        //Update modifier based on teachers' influences
        foreach ($results_teacher as $row5){//Loop for each teacher
            if (in_array(intval($userid), json_decode($row5['es_students']))){//If student exists in teacher's class
                //Update all the modifiers accordingly
                if ($row5['es_weightagenotpreferred']) $notPreferredModifier = $notPreferredModifier/100*intval($row5['es_weightagepreferred']);
                if ($row5['es_weightagepreferred'])$preferredModifier = $preferredModifier/100*intval($row5['es_weightagenotpreferred']);
                if ($row5['es_weightagemaytry'])$mayTryModifier = $mayTryModifier/100*intval($row5['es_weightagemaytry']);
                if ($row5['es_weightagelikes'])$likesBonus = $likesBonus*(intval($row5['es_weightagelikes']))/100;
                if ($row5['es_weightagedeployment'])$deployedBonus = $deployedBonus*(intval($row5['es_weightagedeployment']))/100;
                if ($row5['es_weightagetouched'])$touchedBonus = $touchedBonus*(intval($row5['es_weightagetouched']))/100;
                //Add in the bonus tags into the bonus tag array
                if ($row5['es_bonustags'] && $row5['es_bonustags']!="[]"){
                    $bonusTagsArray = json_decode($row5['es_bonustags']);
                    if (is_array($bonusTagsArray)){
                        foreach ($bonusTagsArray as $row){
                            if(!in_array($row, $bonusPreferences)) array_push($bonusPreferences,$row);
                        }
                    }
                }
            }
        }
        //Initializes the weighed list of articles
        $weighArticlesList = array();
        $highestWeighValue = 0;//Initializes the highest weighed value of the articles
        //Sets the variable for the content table (to check if the article is published or not)
        $content_db =& JTable::getInstance("content");
        //Loop through the list of article in the database
        foreach ($results_art as $row){
            //Load the article by id
            $content_db->load(intval($row['es_articleid']));//Gets the content of the article
            $article_state = $content_db->get("state");//Gets the state of the article
            if (in_array($row['es_articleid'],$blacklist)||$article_state!=1){
                //If blacklisted, unpublished or trashed
                //Do nothing
            }
            else{//If article is published
                $weightage = 0; //Initialize the Weighed Value
                $checkIfSelectedTag = false; //Initialize the boolean for selected tag
                //Gets the tags of the article
                $articleTags = json_decode($row['es_tags']);
                if ($mode == "Selected Tag"){//If the mode selected is for a specific tag
                    if (in_array($parameter1,$articleTags)){//If the tag (additional parameter) exists in the article
                        $weightage = 90;//Give it a high weightage
                    }
                    else{//If it doesn't exists in the article
                        $weightage = -999999;//Remove it from the weighing scale
                    }
                }
                //Loop through for each tag indicated in the user's preferences
                foreach ($preferencelist as $key => $value){
                    //If the tag exists inside the article
                    if (in_array($key,$articleTags)){
                        switch($value){
                            default://If some strange value
                                $weightage += 0;//Do nothing
                                break;
                            case 0://If Against - Represented as a 0
                                $weightage -= $notPreferredModifier;//Lowers the weightage by the above modifier
                                break;
                            case 1://If May Try - Represented as a 1
                                $weightage += $mayTryModifier;//Increases the weightage by the above modifier
                                break;
                            case 2://If Preferred - Represented as a 2
                                $weightage += $preferredModifier;//Increases the weightage by the above modifier
                                break;
                        }
                    }
                }
                //If bonus preferences exists (This is from the teacher's influence on the students)
                if ($bonusPreferences){
                    foreach ($bonusPreferences as $row6){
                        //Loop through the bonus preferences
                        if (in_array($row6,$articleTags)){//If also exists inside bonus tag
                            $weightage += $preferredModifier;//increment the weightage by the modifier
                        }
                    }
                }
                //Deployed, Liked and Touched Before Modifiers based on configurations (See readME for recommended values)
                $deployedModifier = ((($this->params)->get('deployedweightage'))/100)*$deployedBonus;//Sets the modifier based on plugin settings
                $likedModifier = ((($this->params)->get('likesweightage'))/100)*$likesBonus;//Sets the modifier based on plugin settings
                $touchBeforeModifier = ((($this->params)->get('touchbeforeweightage'))/100)*$touchedBonus;//Sets the modifier based on plugin settings
                //Check what is the mode for the recommendation
                switch($mode){
                    case "Untouched"://If the mode is based on articles that are never liked, disliked or deployed before
                        if(in_array($row['es_articleid'],$likedlist)||in_array($row['es_articleid'],$deployedlist))
                            $touchBeforeModifier = 99999;//Fix the touched before modifier to a high value
                        break;
                    case "Deployed"://Else if the mode is based on how often the articles are deployed
                        $deployedModifier = $deployedModifier*100;//Multiply its modifier
                        break; 
                    case "Likes"://Else if the mode is based on how much likes the articles have
                        $likedModifier = $likedModifier*100; //Multiply its modifier
                        break;
                    case "Personal"://Else if the mode is based on our personal recommendation
                        $likedModifier = $likedModifier*15; //Personalized multiplier to modifier
                        $deployedModifier = $deployedModifier*15;//Personalized multipler to modifier
                        break;
                    default://Else if nothing
                        break; //Do nothing
                }
                //Sets the weightage of the article: based on all of the other modifiers (The algorithm of the whole recommendation)
                $weighingValue = $weightage - $touchBeforeModifier + $likedModifier*($row['es_totallikes'] - $row['es_totaldislikes']) + $row['es_totaldeployed']*$deployedModifier;
                //If search mode is activated 
                if (isset($searchMode)){
                    //Query for database article contents (to check within text)
                    $query = $db->getQuery(true);
                    $query->select($db->quoteName(array('id','introtext','fulltext')))//Gets the article id, intro text and full text
                        ->from($db->quoteName('#__content'))//From the content table
                        ->where($db->quoteName('id') . ' = ' . $row['es_articleid']);//Where the id is equal to the article id
                    $db->setQuery($query);
                    $articleContents = $db->loadAssoc();//Saves the result into $articleContents
                    $counter = 0; //Initialize counter for the number of searches found
                    $totalSearchModifier = 0;//Initializes the modifier for the searched results
                    //Loop for each keyword
                    foreach ($keywords as $keyword){
                        $needle = false;//Needle to find if keyword is found
                        $searchModifier = 0;//Reset this word modifier
                        //Based on number of times searched, add to counter
                        if (stristr($row['es_title'], $keyword)){//If found inside the title
                            $searchModifier +=40;//Increment the search modifier
                            $needle = true;//Keyword is found!
                        }
                        foreach($articleTags as $row_tag){//Loop for each tag in the article
                            if (stristr($row_tag, $keyword)){//If the keyword exists in the tag
                                $searchModifier +=2;//Increment the search modifier
                                $needle = true;//Keyword is found!
                            }
                        }
                        //Check inside texts
                        if (stristr($articleContents['introtext'], $keyword)){//If keyword exists inside the intro text
                            $searchModifier += substr_count($articleContents['introtext'], $keyword);//Increment based on the number of times the keyword appears in the intro text
                            $needle = true;//Keyword is found!
                        } 
                        if (stristr($articleContents['fulltext'], $keyword)){//If keyword exists in the full text
                            $searchModifier += substr_count($articleContents['fulltext'], $keyword);//Increment based on the number of times the keyword is found in the full text
                            $needle = true;//Keyword is found!
                        } 
                        //Check counters
                        if ($needle){//If keyword is indeed found
                            $counter += 1;//Increment the counter by 1
                            $totalSearchModifier += $searchModifier;//Update the overall search modifier
                        } 
                    }
                    
                    if ($counter>=sizeof($keywords)){//If the search matches all the keywords
                        $weighingValue += $totalSearchModifier*20*$counter;//Update the weightage value with the overal search modifier
                        if ($weighingValue<1) $weighingValue = $totalSearchModifier;//If the weighing value however is still less than 1, set it as the same number as the modifier
                        //Debug counters
                        $allKeywordCounter +=1;//Increment the debug counter
                        $anyKeywordCounter +=1;//Increment the debug counter
                    }
                    else if ($counter>0) {//Else if it doesnt match ALL of the keywords
                        $anyKeywordCounter +=1;//Increment the debug counter
                    }
                    else {//Else exclude it out of the weighed list
                        $weighingValue = -1;
                    }
                }
                //Save the article only if its weighed value is larger or equal to 0
                if ($weighingValue>=0) $weighArticlesList[$row['es_articleid']] = $weighingValue; 
                //If this weighed value is higher than the highest weiged counter, update
                if ($highestWeighValue<$weighingValue) $highestWeighValue = $weighingValue;
            }
        }
        if (isset($searchMode)){//If search mode is active
            //Display the below debug messages
            echo "<script>console.log('Total articles with any of the keywords: ".$anyKeywordCounter."')</script>";
            echo "<script>console.log('Total articles with all the keywords: ".$allKeywordCounter."')</script>";
        }
        //Sort the resulting list in descending order (based on their weighed value)
        arsort($weighArticlesList);
        $finalList = array();//Initialize the list of articles as an array
        $count = 0;//Initailize the counter of number of articles stored
        foreach ($weighArticlesList as $key => $val){//Loop for each article in the list
            if ($count<$noOfArticles){//If the counter is lesser than the maximum number of articles to get
                $finalList[intval($key)] = floor($val/$highestWeighValue*100);//Save the article with the similarity percentage
                if ($finalList[intval($key)]==0) $finalList[intval($key)] = 1;//If the weighed value however is 0, put it as 1 instead
                $count+=1;//Increment the counter
            }
        }
        return $finalList;//Return the resultant list of articles
    }

    
    /***
     * createList()
     * Old function to check if the list is empty or not
     * If so, update as a stringified empty array
     */
    function createList($array_str){//Parameters used must be an array string
        $array = json_decode($array_str,true);//Decodes string into array
        $list = array_values($array);//Get the values of the array and store into an array (Removes the presence of keys)
        $list_str = json_encode($list);//Stores into a string
        if (!isset($list_str)) $list_str = '[]';//If null, set as empty instead
        return $list_str;//Return new unordered array
    }
    /***
     * fixArticleStats()
     * Last Updated: 29/07/2020
     * Created by: Fremont Teng
     * Function: 
     *  - Automatic fixes/refreshes the statistics of the custom article database
     * Parameter: None
     * Notes: Should be run only on specific pages to reduce lagging
     */
    function fixArticleStats(){
        $db = Factory::getDbo();//Sets the database variable
        //Query the database
        $query2 = $db->getQuery(true);
        $query2->select($db->quoteName(array('id','title','state')))//Gets the artice id, title and state
            ->from($db->quoteName('#__content'));//From the content table
        $db->setQuery($query2);
        $results_bank = $db->loadAssocList();//Saves the results into $results_bank

        //Query the database
        $query = $db->getQuery(true);
        //Gets the article id, users' opinions of the article, the list of users that deployed it, the total number of likes, dislikes and deployed, and the article tags
        $query->select($db->quoteName(array('es_articleid','es_userchoice','es_deployed','es_totallikes','es_totaldislikes','es_tags','es_totaldeployed')))
            ->from($db->quoteName('#__customtables_table_articlestats'));//From the custom article statistics table
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();//Saves the results into $results_ext

        //Initializes the current articles into an array
        $curr_articles =array();
        foreach ($results_ext as $row2) { //Loop for each article in the custom article statistics table
            array_push($curr_articles, $row2['es_articleid']);//Update the current articles array with the article id
            //Count total likes/dislikes
            $results = $this->countArticleLikes($row2['es_userchoice']);//Runs internal function to count number of likes
            $totalLikes = $results[0][0];//Sets the total likes based on the users' opinions
            $totalDislikes = $results[0][1];//Sets the total dislikes based on the users' opinions
            $totalDeployed = sizeof(json_decode($row2['es_deployed']));//Sets the total deployed based on the size of the deployed users list
            //Query the database for the article tags
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('content_item_id','tag_id')))//Get the article id and tag id
                ->from($db->quoteName('#__contentitem_tag_map','a'))//From the article-tag map table
                ->where($db->quoteName('content_item_id') . ' = ' . $row2['es_articleid']);//Where article id is equal to the target article
            $db->setQuery($query);
            $results_tagsID = $db->loadAssocList();//Saves results into $results_tagsID
            //Initializes the array (to store all the tag ids)
            $tagsID_arr = array();
            foreach ($results_tagsID as $row){//Loop for each tag id found
                array_push($tagsID_arr, intval($row['tag_id']));//Push into the array
            }
            //Query the database for all the tags
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id','title')))//Gets the tag id and title
                ->from($db->quoteName('#__tags'));//From the tags table
            $db->setQuery($query);
            $results_tags = $db->loadAssocList();//Save the results into $results_tags
            //Initialize array of the tag list
            $tagList = array();
            foreach($results_tags as $row){//Loop for each tag found
                if (in_array(intval($row['id']), $tagsID_arr))//If tag id is in array
                array_push($tagList, $row['title']);//Push the tag name into the new tag list
            }
            $tagList_json = json_encode($tagList);//Convert the tag list to a string
            //If the taglist/total likes/total dislikes/total deployed is different
            if ($tagList_json != $row2['es_tags'] ||$totalLikes!=$row2['es_totallikes']||$totalDislikes!=$row2['es_totaldislikes']||$totalDeployed!=$row2['es_totaldeployed']){
                //Update the database
                $articleInfo = new stdClass();//Set up the article class
                $articleInfo->es_articleid = $row2['es_articleid'];//Sets the article id
                $articleInfo->es_totallikes = $totalLikes;//Saves the new total likes
                $articleInfo->es_totaldislikes = $totalDislikes;//Saves the new total dislikes
                $articleInfo->es_totaldeployed = $totalDeployed;//Saves the new total deployment
                $articleInfo->es_tags = $tagList_json;//Saves the new list of tags
                    
                // Update the object into the custom article statistics table.
                $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
            }
        }

        //Loop for each article found in the database
        foreach ($results_bank as $row){
            //Update article class
            $articleInfo = new stdClass();//Sets up the article class
            $articleInfo->es_articleid = $row['id'];//Sets the article id
            $articleInfo->es_title = $row['title'];//Sets the article title
            if (($row['state']!=1)){//If the article is not published (aka Deleted/Trashed/Unpublished)
                //Query the database
                $query3 = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('es_articleid') . ' = ' . $row['id']
                );//Sets the condition as the target article
                $query3->delete($db->quoteName('#__customtables_table_articlestats'));//Removes the article from the custom article stats table
                $query3->where($conditions);//Following the above conditions
                $db->setQuery($query3);
                $result = $db->execute();//Execute the sql code
            }
            //Else if the article exists in the database and is published
            else if (in_array($row['id'], $curr_articles)&& ($row['state']==1)){
                // Update article info
                $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
            }
            //Else if the article doesn't exists in the database but is published
            else if ($row['state']==1){
                //Insert the article info instead
                $result = JFactory::getDbo()->insertObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
            }
        }   
        return false;//Return a false message
    }
    /***
     * fixTeacherStats()
     * Last Updated: 30/07/2020
     * Created by: Fremont Teng
     * Function: 
     *  - Automatic fixes/refreshes the statistics of the custom teachers database
     * Parameter: None
     * Notes: Should be run only on specific pages to reduce lagging
     */
    function fixTeacherStats(){
        //Sets the database and user variables
        $db = Factory::getDbo();
        $me = Factory::getUser();
        //Query from the database for the user group mapping
        $query2 = $db->getQuery(true);
        $query2->select($db->quoteName(array('user_id','group_id')))//Gets the user id and their respective groups
            ->from($db->quoteName('#__user_usergroup_map'));//from the user group map table
        $db->setQuery($query2);
        $results_bank = $db->loadAssocList();//Save the results into $results_bank
        //Query the database for the teacher statistics
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_teacherid')))//Gets the teacher id
            ->from($db->quoteName('#__customtables_table_teacherstats'));//from the teacher statistics table
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();//Saves the results as $results_ext
        //Initialize the array of teachers
        $teachers = array();
        foreach ($results_ext as $row) {//Loop for each teacher found in the table
            array_push($teachers, $row['es_teacherid']);//Push into the teacher array
        }
        //Loop for each user found in the usergroup match
        foreach ($results_bank as $row2){
            if ($row2['group_id']==11){//If user's group belongs to teacher: Note that the id of the teacher group can differ. In our case, is 11
                //Creates a teacher class
                $teacherInfo = new stdClass();
                $teacherInfo->es_teacherid = $row2['user_id'];//Sets the user id of the teacher
                //Check if already exists in the list of current teachers
                if (in_array($row2['user_id'], $teachers)){
                    // Update teacher info if exists
                    $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
                }
                else{//Else: Insert teacher info if doesn't exists
                    $teacherInfo->es_code = $row2['user_id'];//Sets code to be the same as user id
                    $teacherInfo->es_students = "[]";//Sets the teacher's class to be empty
                    //Insert teacher info
                    $result = JFactory::getDbo()->insertObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
                }
            }
            
        }   

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
        $query->select($db->quoteName(array('es_userid','es_pagedeployed','es_pageliked','es_pagedisliked','es_userpreference')))
            ->from($db->quoteName('#__customtables_table_userstats'));
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
                if (isset($row['es_userpreference'])) $preferenceList = $row['es_userpreference'];
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