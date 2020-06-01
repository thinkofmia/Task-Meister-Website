<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
//Get Factory
use Joomla\CMS\Factory;
$db = Factory::getDbo();//Sets Database variable
$me = Factory::getUser();//Sets User variable

$userID = 0;

//Sets item id
$itemID = JRequest::getVar('id');

//Check if its an article
if ($itemID){
    //Query database for articles based on current article id
    $query = $db->getQuery(true);
    $query->select($db->quoteName(array('title','id','hits','featured','catid')))//Get which columns
        ->from($db->quoteName('#__content'))//Sets which database
        ->where($db->quoteName('id') . ' = ' . $itemID);//Set condition of query to find current article ID.
    $db->setQuery($query);
    $results = $db->loadAssocList();//Save results of main article database query
    if ($results){
        $articleID = $itemID;
    }
}

if ($articleID){
    //Sets user id from User var
    $userID = $me->id;
    //Querying for article stats table based on article id
    $query = $db->getQuery(true);
    $query->select($db->quoteName(array('es_articleid','es_userchoice')))//Get the article id and the user choice list
        ->from($db->quoteName('#__customtables_table_articlestats'))//From the Article Stats Database
        ->where($db->quoteName('es_articleid') . ' = ' . $articleID);//Where it is this article id
    $db->setQuery($query);
    $results = $db->loadAssocList();//Save under var results
    //Initialize variables for users choice list.
$userchoice;//List of all user choices/opinion of an article
$dataNotExist = true;//Boolean to determine if any record is found in the database
//For loop to check if record exists
foreach ($results as $row) {
    //If article do exists
    if (JRequest::getVar('id')==$row['es_articleid']){
        //Save var $userchoice with the array version from the database
        $userchoice=json_decode($row['es_userchoice'],true);
        //Toggle boolean that record do exist
        $dataNotExist = false;
    } 
}
//If record doesn't exists in the database
if ($dataNotExist){
    // Create and populate the record.
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    // Insert the record into the article stats table.
    $result = JFactory::getDbo()->insertObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
    }
}

if ($userID != 0 ){
//Querying for User Stats table
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_userid','es_pageliked','es_pagedisliked')))//Get the user id, their list of liked and disliked pages
    ->from($db->quoteName('#__customtables_table_userstats'))//From the external database table User Stats
    ->where($db->quoteName('es_userid') . ' = ' . $userID);//Where user id is the current login user 
$db->setQuery($query);
$results = $db->loadAssocList();//Save results
//Intialization for record exists check
$dataNotExist = true;//Boolean to check if a record of the user exists inside the User Stats table.
//For loop to check through the current database query
foreach ($results as $row) {
    //If user do exists as a record in the User Stats Table
    if ($userID==$row['es_userid']){
        //Save the liked list from the database as an array into $userLikedList
        $userLikedList=json_decode($row['es_pageliked'],true);
        //Save the disliked list from the database as an array into $userDisLikedList
        $userDislikedList=json_decode($row['es_pagedisliked'],true);
        //Toggle boolean that record do exists in the User Stats Table
        $dataNotExist = false;
    } 
}
//If user record doesn't exists in the User Stats Table
if ($dataNotExist){
    // Create and populate new record.
    $userInfo = new stdClass();
    $userInfo->es_userid = $userID;
    // Insert record into the User Stats table.
    $result = JFactory::getDbo()->insertObject('#__customtables_table_userstats', $userInfo, 'es_userid');
}

}

//If thumbs down button is pressed and user has logined
if(isset($_POST["tDown"])&&$userID!=0){
    /**
     * Run setThumbsDown()
     * Parameter $userID: Refers to current user id
     * Paramater $articleID: Refers to current article id
     * Parameter $userchoice: List of all users decision of an article (Article-side)
     * Parameter $userLikedList: List of the user liked pages (User-side)
     * Parameter $userDislikedList: List of the user disliked pages (User-side)
    */
    setThumbsDown($userID,$articleID,$userchoice,$userLikedList,$userDislikedList);
}

if(isset($_POST["tUp"])&&$userID!=0){
    /**
     * Run setThumbsUp()
     * Parameter $userID: Refers to current user id
     * Paramater $articleID: Refers to current article id
     * Parameter $userchoice: List of all users decision of an article (Article-side)
     * Parameter $userLikedList: List of the user liked pages (User-side)
     * Parameter $userDislikedList: List of the user disliked pages (User-side)
    */
    setThumbsUp($userID,$articleID,$userchoice,$userLikedList,$userDislikedList);
}

function disableSwitch($list,$articleID){
    /**
     * Function: Disable Switch removes articles from list
     * Parameter $list: Refers to the list of articles stored for a particular user
     * Parameter $articleID: Refers to the current article ID
     */
    if (in_array($articleID,$list)){
        //If article id exists in the list, removes it
        $key = array_search($articleID, $list);
        unset($list[$key]);
    }
    //Return the updated list
    return $list;
}

function updateUserDB($userID,$likedList,$dislikedList){
    /**
     * Function: Update user database with the new liked and disliked list
     * Parameter $userID: Refers to the current user id
     * Parameter $likedList: Refers to the user's liked list
     * Parameter $dislikedList: Refers to the user's disliked list
     */
    //Save both lists as strings - impt to store the array into the database
    $likedList_str=json_encode($likedList);
    $dislikedList_str=json_encode($dislikedList);
    // Create and populate the user record.
    $userInfo = new stdClass();
    $userInfo->es_userid = $userID;
    $userInfo->es_pageliked =  $likedList_str;
    $userInfo->es_pagedisliked =  $dislikedList_str;
    // Update the object into the user stats table.
    $result = JFactory::getDbo()->updateObject('#__customtables_table_userstats', $userInfo, 'es_userid');
}

function setThumbsDown($userID,$articleID,$userchoice,$userLikedList,$userDislikedList){
    /**
     * Function: Gives thumbs down of an article
     * Parameter $userID: Refers to the user id of the user doing the action
     * Parameter $articleID: Refers to the article id of the article to be thumbs downed.
     * Parameter $userchoice: Refers to the list of preferences of all the users made on the particular article
     * Parameter $userLikedList: Refers to the list of articles the user likes.
     * Parameter $userDisLikedList: Refers to the list of articles the user dislikes.
     */
    if ($userID == 0||!isset($userID)){//If the user has yet to login
        echo "alert('Login First!!!')";
    } 
    else {//If user has logined
    $userID_Str = "".$userID."";
        if (empty($userchoice)){//If no one made any opinion on the article
            $userchoice = array($userID_Str=>"Disliked");//Create a new array and set the user's preference to dislike
        }
        else if ($userchoice[$userID_Str] == "Disliked"){//If the user has already disliked the article
            unset($userchoice[$userID_Str]);//Remove it from the array to set the user as neutral
        }
        else{
            $userchoice[$userID_Str] = "Disliked";//Set the user's choice to dislike
        }
    $array_string=json_encode($userchoice);//Save the all-users preference list as a string for storage
    
    //Extension for counting article likes and dislikes
    JPluginHelper::importPlugin('taskmeister','tm_recommender');
    $dispatcher = JDispatcher::getInstance();
    $results = $dispatcher->trigger('countArticleLikes', array($userchoice));
    //Save results from the plugin into variables $totalLikes and $totalDislikes
    $totalLikes = $results[0][0];
    $totalDislikes = $results[0][1];

    // Create and populate an object.
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    $articleInfo->es_userchoice =  $array_string;
    $articleInfo->es_totallikes = $totalLikes;
    $articleInfo->es_totaldislikes = $totalDislikes;
    // Update the object into the external article table.
    $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');

    //Check for individual profiles
    if (empty($userDislikedList)){//If the user's dislike list is empty
        $userDislikedList = array($articleID);//Add the article into the user dislike list as a new array
        $userLikedList = disableSwitch($userLikedList,$articleID);//Update the liked list
    }
    else if (in_array($articleID,$userDislikedList)){//If user has already disliked the article
        $userDislikedList = disableSwitch($userDislikedList,$articleID);//Remove from disliked list
    }
    else {
        $userDislikedList[] = $articleID;//Update disliked list
        $userLikedList = disableSwitch($userLikedList,$articleID);//Update liked list as well
    }
    updateUserDB($userID,$userLikedList,$userDislikedList);//Update the external user database
    }
}

function setThumbsUp($userID,$articleID,$userchoice,$userLikedList,$userDislikedList){
    /**
     * Function: Gives thumbs up of an article
     * Parameter $userID: Refers to the user id of the user doing the action
     * Parameter $articleID: Refers to the article id of the target article.
     * Parameter $userchoice: Refers to the list of preferences of all the users made on the particular article
     * Parameter $userLikedList: Refers to the list of articles the user likes.
     * Parameter $userDisLikedList: Refers to the list of articles the user dislikes.
     */
    if ($userID == 0||!isset($userID)){//If user is a guest/yet to login
        echo "alert('Login First!!!')";
    } 
    else {//If user has already logined
    $userID_Str = "".$userID."";
    if (empty($userchoice)){//If array/dictionary doesn't exists/List of all users' opinions doesn't exist
        $userchoice = array($userID_Str=>"Liked");//Create a new array and save the user's choice as Liked.
    }
    else if ($userchoice[$userID_Str] == "Liked"){//If the user has already liked the article
        unset($userchoice[$userID_Str]);//Remove it from the users' opinions, set user's decision to be neutral
    }
    else{
            $userchoice[$userID_Str] = "Liked";//Set the user's opinion as liked
    }

    //Extension for counting article likes and dislikes
    JPluginHelper::importPlugin('taskmeister','tm_recommender');
    $dispatcher = JDispatcher::getInstance();
    $results = $dispatcher->trigger('countArticleLikes', array($userchoice));
    $totalLikes = $results[0][0];
    $totalDislikes = $results[0][1];

    $array_string=json_encode($userchoice);//Save the users' opinion array as a string
    // Create and populate an object.
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    $articleInfo->es_userchoice =  $array_string;
    $articleInfo->es_totallikes = $totalLikes;
    $articleInfo->es_totaldislikes = $totalDislikes;
        
    // Update the object into the external article table.
    $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');

    //For user profile
    if (empty($userLikedList)){//If the liked list is empty
        $userLikedList = array($articleID);//Create a new array with the article id inside
        $userDislikedList = disableSwitch($userDislikedList,$articleID);//Update disliked list as well
    }
    else if (in_array($articleID,$userLikedList)){//If the article already exists in the liked list
        $userLikedList = disableSwitch($userLikedList,$articleID);//Remove from liked list
    }
    else {
        $userLikedList[] = $articleID;//Update liked list
        $userDislikedList = disableSwitch($userDislikedList,$articleID);//Update disliked list as well
    }    
    updateUserDB($userID,$userLikedList,$userDislikedList);//Update to the database for the external user stats table
    }
}

?>

<!--Display for custom text and header-->
<div class="customtext">
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>
<?php if ($articleID) : ?>
    <div id="thumbsBox">
        <!--If user is a guest, create fake buttons with alert messages-->
        <?php if ($userID==0) : ?>
            <button name= "fakeButton1" id= "thumbsUp" onclick="alert('Login First!!')" title ="Like Button">👍</button>
            <button name= "fakeButton2" id = "thumbsDown" onclick="alert('Login First!!')" title ="Dislike Button">👎</button>  
        <!--If user has logined, create buttons with actual functions-->
        <?php else : ?>
            <form method="post">
                <button name= "tUp" id= "thumbsUp" title ="Like Button">👍</button>
                <button name= "tDown" id = "thumbsDown" title ="Dislike Button">👎</button>  
            </form>
        <?php endif; ?>
    </div>
<?php endif; ?>
