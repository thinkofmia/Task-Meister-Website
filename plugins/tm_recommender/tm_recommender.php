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
    //Test Function (Unused)
	 function calculateMetrics($number)
	 {
		/*
		 * Plugin code goes here.
		 * You can access database and application objects and parameters via $this->db,
		 * $this->app and $this->params respectively
		 */
        return "Wonderful ".($number*2);
    }
    //Function: Save User Preference
    function saveUserPreference($preferredList, $notPreferredList, $mayTryList){
        //Set database and user
        $db = Factory::getDbo();
        $me = Factory::getUser();
        $userID = $me->id;
        $username = $me->username;
        $newPreferenceList = array();
        //Loop items in preferred list
        foreach (json_decode($preferredList) as $row){//2 means preferred
            $newPreferenceList[$row] = 2;
        }
        foreach (json_decode($notPreferredList) as $row){//0 means preferred
            $newPreferenceList[$row] = 0;
        }
        foreach (json_decode($mayTryList) as $row){//1 means preferred
            $newPreferenceList[$row] = 1;
        }
        // Create and populate an user table.
        $userInfo = new stdClass();
        $userInfo->es_userid = $userID;
        $userInfo->es_userpreference = json_encode($newPreferenceList);
        // Update the object into the article profile table.
        $result = JFactory::getDbo()->updateObject('#__customtables_table_userstats', $userInfo, 'es_userid');
        return "Saved!";
    }
    //Function: Save a class's modifier
    function saveClassModifiers($data){
        //Set database and user
        $db = Factory::getDbo();
        $me = Factory::getUser();
        $userID = $me->id;
        $username = $me->username;
        //Query
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('*')))
        ->from($db->quoteName('#__customtables_table_teacherstats'))
        ->where($db->quoteName('es_teacherid') . ' = ' . $userID);
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();
        //Check if its really a teacher
        if ($results_ext){//It is a teacher
            // Create and populate an user table.
            $teacherInfo = new stdClass();
            $teacherInfo->es_teacherid = $userID;
            if ($data['likesWeight']) $teacherInfo->es_weightagelikes = $data['likesWeight'];
            if ($data['deployedWeight']) $teacherInfo->es_weightagedeployment = $data['deployedWeight'];
            if ($data['touchedWeight']) $teacherInfo->es_weightagetouched = $data['touchedWeight'];
            if ($data['preferredWeight']) $teacherInfo->es_weightagepreferred = $data['preferredWeight'];
            if ($data['unpreferredWeight']) $teacherInfo->es_weightagenotpreferred = $data['unpreferredWeight'];
            if ($data['mayTryWeight']) $teacherInfo->es_weightagemaytry = $data['mayTryWeight'];
            if ($data['togglePreferenceLinkage']) $teacherInfo->es_preferencelink = $data['togglePreferenceLinkage'];
            if ($data['bonusTags']) $teacherInfo->es_bonustags = $data['bonusTags'];
            else $teacherInfo->es_bonustags = "[]";
            // Update the object into the teacher stats table.
            $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
            return true;
        }
        else{//Else end program
            return false; 
        }
    }
    //Function: Save a particular user's teachers
    function saveOurTeachers($teacherList_str){
        //Set database and user
        $db = Factory::getDbo();
        $me = Factory::getUser();
        $userID = $me->id;
        $username = $me->username;
        //Get teachers list
        $teacherList = json_decode($teacherList_str);
        //Query
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_teacherid','es_students')))
        ->from($db->quoteName('#__customtables_table_teacherstats'));
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();
        //Save information into a list
        foreach ($results_ext as $row){
            $newStudentList = json_decode($row['es_students']); //Teacher's List that stores all the students inside
            //Check if needs update
            if (in_array($row['es_teacherid'], $teacherList)&& !in_array($userID, $newStudentList)){//If student exists in teacher's class but not in database
                array_push($newStudentList, intval($userID));
                // Create and populate an user table.
                $teacherInfo = new stdClass();
                $teacherInfo->es_teacherid = $row['es_teacherid'];
                $teacherInfo->es_students = json_encode($newStudentList);
                // Update the object into the article profile table.
                $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
            }
            //If student is in database but not in teacher list
            else if (!in_array($row['es_teacherid'], $teacherList) && in_array(intval($userID), $newStudentList)){
                $key = array_search(intval($userID), $newStudentList);
                if ($key !== false) {
                    unset($newStudentList[$key]);
                }
                // Create and populate an user table.
                $teacherInfo = new stdClass();
                $teacherInfo->es_teacherid = $row['es_teacherid'];
                $teacherInfo->es_students = json_encode($newStudentList);
                // Update the object into the article profile table.
                $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
            }
        }
        return "Saved!";
    }
    //Function: Save a particular user's students
    function saveOurStudents($studentList_str){
        //Set database and user
        $db = Factory::getDbo();
        $me = Factory::getUser();
        $userID = $me->id;
        $username = $me->username;
        $studentList = "[]";
        if ($studentList_str) $studentList = $studentList_str;
        //Save information into a list
        $teacherInfo = new stdClass();
        $teacherInfo->es_teacherid = $me->id;
        $teacherInfo->es_students = $studentList;
        // Update the object into the article profile table.
        $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
        return "Saved!";
    }
    /* Function: Get list of tags that are currently in used. */
    function getTagList(){
        //Gets Database
        $db = Factory::getDbo();
        //Get tags info database
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('*')))
            ->from($db->quoteName('#__tags'));
        $db->setQuery($query);
        $results_tags = $db->loadAssocList();
        //Create list
        $tagList = array();
        //For loop to populate tag list
        foreach($results_tags as $row){
            $tagList[$row['title']] = $row['hits'];
        }
        arsort($tagList);
        //Add default tag list based on pptx
        $tagNames_pptx = array(
            "Board Games"=> 0,
            "Cosplay" => 0,
            "Current Affairs" => 0,
            "Dance" => 0,
            "Digital Manipulatives" => 0,
            "Drama" => 0,
            "Escape Rooms" => 0,
            "Fashion" => 0,
            "Food" => 0,
            "Model Making" => 0,
            "Movies" => 0,
            "Music" => 0,
            "Nature" => 0,
            "Online Games" => 0,
            "Outdoor Tasks" => 0,
            "Physical Manipulatives" => 0,
            "Poems" => 0,
            "Puzzles"=> 0,
            "Science" => 0,
            "Simulations" => 0,
            "Sports" => 0,
            "Statistics" => 0,
            "Stories" => 0,
            "Travelling" => 0,
            "Treasure Hunts" => 0,
            "VR" => 0
        );
        //Add old tag list into default list.
        foreach ($tagList as $key => $value){
            $tagNames_pptx[$key] = $value;
        }
        return $tagNames_pptx;
    }
    /* Function: Get list of teachers available. */
    function getTeachersList(){
        //Gets Database
        $db = Factory::getDbo();
        //Get tags info database
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('*')))
            ->from($db->quoteName('#__customtables_table_teacherstats'));
        $db->setQuery($query);
        $results_teachers = $db->loadAssocList();
        //Create list
        $teacherList = array();
        //For loop to populate teacher list
        foreach($results_teachers as $row){
            $teacher = JFactory::getUser($row['es_teacherid']);
            $teacherList[$row['es_teacherid']] = $teacher->name;
        }
        return $teacherList;
    }
    /* Function: Get Article Contents
    Gets all the selected articles to display from a list
    Can be used after having a list of recommended article
    */
    function getArticleContents($list_str){//Parameter is a string ver of the list
        //Convert the string to a list
        $list = json_decode($list_str,true);
        //Gets Database
        $db = Factory::getDbo();
        //Get article info database
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id','title','images')))
            ->from($db->quoteName('#__content'));
        $db->setQuery($query);
        $results_art = $db->loadAssocList();
        //For loop
        $contentCollection = array();
        //array
        $result_array = array();
        //foreach ($list as $key => $val){
          //  $result_array[$key] = $val;
        //}
        foreach ($results_art as $row){
            $key_str = "".$row['id'];
            if(array_key_exists($key_str,$list)){
                if ($row['title']) $contentCollection[$row['id']] = array($row['title'],$row['images'],$list[$key_str]);
            }
        
        }
        //Set up an array to store all the information into a collection
        $displayCollection = array();
        foreach ($list as $key => $val){
            $displayCollection[intval($key)] = $contentCollection[intval($key)];
        }
        return $displayCollection;
    }
    /* Function: Get My List
    Get the list of user's deployed or liked articles into a string.
     */
    function getMyList($mode, $noOfArticles, $userid){
        $db = Factory::getDbo();//Gets database
        //Get external user table (custom table) To find out list of liked, deployed and disliked articles
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_userid','es_pageliked','es_pagedisliked','es_pagedeployed','es_userpreference')))
            ->from($db->quoteName('#__customtables_table_userstats'))
            ->where($db->quoteName('es_userid') . ' = ' . $userid);
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();
        //Save information into a list
        foreach ($results_ext as $row){
            if ($row['es_userid']==$userid){//Just to be sure if user id is same and below number of indicated articles
                $likedlist = json_decode($row['es_pageliked']);
                $blacklist = json_decode($row['es_pagedisliked']);
                $deployedlist = json_decode($row['es_pagedeployed']);
                $preferencelist = json_decode($row['es_userpreference']);
            }
        }
        //Initialization
        $resultList = array();
        $count = 0;
        if ($mode == "Deployed"){
            foreach ($deployedlist as $row){
                if ($count<$noOfArticles){
                    $resultList[$row] = 100;
                    $count = $count + 1;
                }
            }
        }
        else if ($mode == "Teacher"){
            foreach ($likedlist as $row){
                if ($count<$noOfArticles){
                    $resultList[$row] = 100;
                    $count = $count + 1;
                }
            }
            foreach ($deployedlist as $row){
                if ($count<$noOfArticles){
                    $resultList[$row] = 100;
                    $count = $count + 1;
                }
            }
        }
        else {
            foreach ($likedlist as $row){
                if ($count<$noOfArticles){
                    $resultList[$row] = 100;
                    $count = $count + 1;
                }
            }
        }
        return $resultList;
    }
    /* Function: Personal Recommended Articles
    Recommend personal articles that excludes what is already liked/disliked by the targeted  user
    Used only for articles module
    Returns a string of recommended articles
     */
    function recommendPersonalArticles($mode,$noOfArticles, $userid, $parameter1){
        $db = Factory::getDbo();//Gets database
        //Set Additional Filter Mode if has keyword
        if ($mode!="Selected Tag" && strlen($parameter1)>0){
                $searchMode = true;
        }
        //Get external user table (custom table) To find out list of liked, deployed and disliked articles
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('*')))
            ->from($db->quoteName('#__customtables_table_userstats'))
            ->where($db->quoteName('es_userid') . ' = ' . $userid);
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();
        //Save information into a list
        foreach ($results_ext as $row){
            if ($row['es_userid']==$userid){//Just to be sure if user id is same
                $likedlist = json_decode($row['es_pageliked']);
                $blacklist = json_decode($row['es_pagedisliked']);
                $deployedlist = json_decode($row['es_pagedeployed']);
                $preferencelist = json_decode($row['es_userpreference']);
            }
        }
        //Get article info database
        $query2 = $db->getQuery(true);
        $query2->select($db->quoteName(array('*')))
            ->from($db->quoteName('#__customtables_table_articlestats'));
        $db->setQuery($query2);
        $results_art = $db->loadAssocList();
        //Get teacher info database
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('es_teacherid','es_students')))
        ->from($db->quoteName('#__customtables_table_teacherstats'));
        $db->setQuery($query);
        $results_teacher = $db->loadAssocList();
        //Save information into a list
        //Set default weightages value
        $notPreferredModifier = 100*((($this->params)->get('notpreferredweightage'))/100);
        $mayTryModifier = 10*((($this->params)->get('maytryweightage'))/100);
        $preferredModifier = 20*((($this->params)->get('preferredweightage'))/100);
        $likesBonus = 1;
        $deployedBonus = 1;
        $touchedBonus = 1;
        $bonusPreferences = array();//For additional tags influenced by teachers
        //Update modifier based on teachers' influences
        foreach ($results_teacher as $row5){
            if (in_array(intval($userid), json_decode($row5['es_students']))){//If student exists in teacher's class
                if ($row5['es_weightagenotpreferred']) $notPreferredModifier = $notPreferredModifier/100*intval($row5['es_weightagepreferred']);
                if ($row5['es_weightagepreferred'])$preferredModifier = $preferredModifier/100*intval($row5['es_weightagenotpreferred']);
                if ($row5['es_weightagemaytry'])$mayTryModifier = $mayTryModifier/100*intval($row5['es_weightagemaytry']);
                if ($row5['es_weightagelikes'])$likesBonus = $likesBonus*(intval($row5['es_weightagelikes']))/100;
                if ($row5['es_weightagedeployment'])$deployedBonus = $deployedBonus*(intval($row5['es_weightagedeployment']))/100;
                if ($row5['es_weightagetouched'])$touchedBonus = $touchedBonus*(intval($row5['es_weightagetouched']))/100;
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
        //Set up weightage list of articles
        $weighArticlesList = array();
        $highestWeighValue = 0;
        //Weigh articles
        foreach ($results_art as $row){
            if (in_array($row['es_articleid'],$blacklist)){//If blacklisted or liked already
                //Do nothing
            }
            else{//If articles collected is less than 10
                //Initializes vars
                $weightage = 0; //Weightage Value
                $checkIfSelectedTag = false; //Only for selected tag mode
                //Weightage for tags
                $articleTags = json_decode($row['es_tags']);
                if ($mode == "Selected Tag"){//If its just for one tag
                    if (in_array($parameter1,$articleTags)){
                        $weightage = 90;
                    }
                    else{
                        $weightage = -999999;
                    }
                }
                foreach ($preferencelist as $key => $value){
                    //Else check if within array of user's favourited tags
                    if (in_array($key,$articleTags)){
                        switch($value){
                            default://generic
                                $weightage += 0;
                                break;
                            case 0://If not preferred
                                $weightage -= $notPreferredModifier;
                                break;
                            case 1://May Try
                                $weightage += $mayTryModifier;
                                break;
                            case 2://Preferred
                                $weightage += $preferredModifier;
                                break;
                        }
                    }
                }
                if ($bonusPreferences){
                    foreach ($bonusPreferences as $row6){
                        //Also check in bonus preferences if any
                        if (in_array($row6,$articleTags)){//If exists inside bonus tag, add additional modifier
                            $weightage += $preferredModifier;
                        }
                    }
                }
                //Modifiers based on config
                $deployedModifier = ((($this->params)->get('deployedweightage'))/100)*$deployedBonus;
                $likedModifier = ((($this->params)->get('likesweightage'))/100)*$likesBonus;
                $touchBeforeModifier = ((($this->params)->get('touchbeforeweightage'))/100)*$touchedBonus;
                switch($mode){
                    case "Untouched":
                        if(in_array($row['es_articleid'],$likedlist)||in_array($row['es_articleid'],$deployedlist))
                            $touchBeforeModifier = 99999;
                        break;
                    case "Deployed":
                        $deployedModifier = $deployedModifier*100;
                        break; 
                    case "Likes":
                        $likedModifier = $likedModifier*100; 
                        break;
                    case "Personal":
                        $likedModifier = $likedModifier*15; 
                        $deployedModifier = $deployedModifier*15;
                        break;
                    default:
                        break; 
                }
                //Store weightage
                $weighingValue = $weightage - $touchBeforeModifier + $likedModifier*($row['es_totallikes'] - $row['es_totaldislikes']) + $row['es_totaldeployed']*$deployedModifier;
                //Bonus if search mode
                if (isset($searchMode)){
                    $counter = 0; //Counter to find str searches
                    //Based on number of times searched, add to counter
                    if (stristr($row['es_title'], $parameter1)) $counter = $counter + 1;
                    foreach($articleTags as $row_tag){
                        if (stristr($row_tag, $parameter1)) $counter = $counter + 1;
                    }
                    //Check counter
                    if ($counter=0) $weighingValue = -1;//If not inside query, remove it
                    elseif ($counter>0 && $weighingValue<0) $weighingValue = $counter;//If not recommended yet within query
                    else{//If recommended and within query, add recommendation
                        $weighingValue = $weighingValue + 20*$counter;
                    }
                }
                //Only if weightage is higher or equal to 0
                if ($weighingValue>=0) $weighArticlesList[$row['es_articleid']] = $weighingValue; 
                if ($highestWeighValue<$weighingValue) $highestWeighValue = $weighingValue;
            }
        }
        arsort($weighArticlesList);//Sort articles in descending order
        //Return articles
        $finalList = array();
        $count = 0;
        foreach ($weighArticlesList as $key => $val){
            if ($count<$noOfArticles){
                $finalList[intval($key)] = floor($val/$highestWeighValue*100);
                //array_push($finalList, $key);
                $count+=1;
            }
        }
        $weighArticlesList_str = json_encode($finalList);
        return $finalList;
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
            $totalDeployed = sizeof(json_decode($row2['es_deployed']));
            //Find tags map DB
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('a.*')))
                ->from($db->quoteName('#__contentitem_tag_map','a'))
                ->where($db->quoteName('content_item_id') . ' = ' . $row2['es_articleid']);
            $db->setQuery($query);
            $results_tagsID = $db->loadAssocList();
            //Store tags id in array
            $tagsID_arr = array();
            foreach ($results_tagsID as $row){
                array_push($tagsID_arr, intval($row['tag_id']));
            }
            //Get tags info database
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('*')))
                ->from($db->quoteName('#__tags'));
            $db->setQuery($query);
            $results_tags = $db->loadAssocList();
            //Create list
            $tagList = array();
            //For loop to populate tag list
            foreach($results_tags as $row){
                if (in_array(intval($row['id']), $tagsID_arr))
                array_push($tagList, $row['title']);
            }
            $tagList_json = json_encode($tagList);
            if ($tagList_json != $row2['es_tags'] ||$totalLikes!=$row2['es_totallikes']||$totalDislikes!=$row2['es_totaldislikes']||$totalDeployed!=$row2['es_totaldeployed']){
                // Create and populate an object.
                $articleInfo = new stdClass();
                $articleInfo->es_articleid = $row2['es_articleid'];
                $articleInfo->es_totallikes = $totalLikes;
                $articleInfo->es_totaldislikes = $totalDislikes;
                $articleInfo->es_totaldeployed = $totalDeployed;
                $articleInfo->es_tags = $tagList_json;
                    
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
    /* Function: Fix Teacher Statistics
    This function automatically updates/refreshes the teacher statistics.
     It should be used when adding a new teacher/updating teacher profiles
     */
    function fixTeacherStats(){
        $db = Factory::getDbo();//Gets database
        $me = Factory::getUser();//Gets user 
        //Get user bank from database query
        $query2 = $db->getQuery(true);
        $query2->select($db->quoteName(array('user_id','group_id')))
            ->from($db->quoteName('#__user_usergroup_map'));
        $db->setQuery($query2);
        $results_bank = $db->loadAssocList();
        //Get external teacher table (custom table)
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('a.*')))
            ->from($db->quoteName('#__customtables_table_teacherstats','a'));
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();
        //Store the current teachers in external table into an array
        $teachers = array();
        foreach ($results_ext as $row) {
            array_push($teachers, $row['es_teacherid']);//Change group id based on the one that fits the teacher. In our case, its 11.
        }
        //Add in new teachers if any
        foreach ($results_bank as $row2){
            if ($row2['group_id']==11){
                //Update teacher class
                $teacherInfo = new stdClass();
                $teacherInfo->es_teacherid = $row2['user_id'];
                //Check in already exists
                if (in_array($row2['user_id'], $teachers)){
                    // Update teacher info if exists.
                    $result = JFactory::getDbo()->updateObject('#__customtables_table_teacherstats', $teacherInfo, 'es_teacherid');
                }
                else{//Insert teacher info if doesn't exists
                    $teacherInfo->es_code = $row2['user_id'];
                    $teacherInfo->es_students = "[]";
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