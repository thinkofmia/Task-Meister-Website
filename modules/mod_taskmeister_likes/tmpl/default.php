<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
use Joomla\CMS\Factory;
$db = Factory::getDbo();
$me = Factory::getUser();

$userID = $me->id;

$articleID = JRequest::getVar('id');
//Querying for article stats
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_articleid','es_userchoice')))
    ->from($db->quoteName('#__customtables_table_articlestats'))
    ->where($db->quoteName('es_articleid') . ' = ' . $articleID);
$db->setQuery($query);
$results = $db->loadAssocList();
$userchoice;
$dataNotExist = true;
foreach ($results as $row) {
    if (JRequest::getVar('id')==$row['es_articleid']){
        $userchoice=json_decode($row['es_userchoice'],true);
        $dataNotExist = false;
    } 
}
if ($dataNotExist){//If no record exists
    // Create and populate an object.
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;

    // Update the object into the article profile table.
    $result = JFactory::getDbo()->insertObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
}

//Querying for User Stats table
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_userid','es_pageliked','es_pagedisliked')))
    ->from($db->quoteName('#__customtables_table_userstats'))
    ->where($db->quoteName('es_userid') . ' = ' . $userID);
$db->setQuery($query);
$results = $db->loadAssocList();
$dataNotExist = true;
foreach ($results as $row) {
    if ($userID==$row['es_userid']){
        $userLikedList=json_decode($row['es_pageliked'],true);
        $userDislikedList=json_decode($row['es_pagedisliked'],true);
        $dataNotExist = false;
    } 
}
if ($dataNotExist){//If no record exists
    // Create and populate an object.
    $userInfo = new stdClass();
    $userInfo->es_userid = $userID;

    // Update the object into the article profile table.
    $result = JFactory::getDbo()->insertObject('#__customtables_table_userstats', $userInfo, 'es_userid');
}

//Functions
if(isset($_POST["tDown"])&&$userID!=0){
    setThumbsDown($userID,$articleID,$userchoice,$userLikedList,$userDislikedList);
    $NoLikes = getLikes($userchoice);
    $NoDislikes = getDislikes($userchoice);
}

if(isset($_POST["tUp"])&&$userID!=0){
    setThumbsUp($userID,$articleID,$userchoice,$userLikedList,$userDislikedList);
    $NoLikes = getLikes($userchoice);
    $NoDislikes = getDislikes($userchoice);
}

function disableSwitch($list,$articleID){
    if (in_array($articleID,$list)){
        $key = array_search($articleID, $list);
        unset($list[$key]);
    }
    return $list;
}

function updateUserDB($userID,$likedList,$dislikedList){//Updates user database
    $likedList_str=json_encode($likedList);
    $dislikedList_str=json_encode($dislikedList);

    // Create and populate an user table.
    $userInfo = new stdClass();
    $userInfo->es_userid = $userID;
    $userInfo->es_pageliked =  $likedList_str;
    $userInfo->es_pagedisliked =  $dislikedList_str;
    
    // Update the object into the article profile table.
    $result = JFactory::getDbo()->updateObject('#__customtables_table_userstats', $userInfo, 'es_userid');
}

function setThumbsDown($userID,$articleID,$userchoice,$userLikedList,$userDislikedList){
    if ($userID == 0||!isset($userID)){//If User yet to login
        echo "alert('Login First!!!')";
    } 
    else {//If user logined
    $userID_Str = "".$userID."";
        if (empty($userchoice)){//If empty dict
            $userchoice = array($userID_Str=>"Disliked");
        }
        else if ($userchoice[$userID_Str] == "Disliked"){
            unset($userchoice[$userID_Str]);//Remove from $userchoice
        }
        else{
            $userchoice[$userID_Str] = "Disliked";
        }
    $array_string=json_encode($userchoice);
    // Create and populate an object.
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    $articleInfo->es_userchoice =  $array_string;
    
    // Update the object into the article profile table.
    $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');

    //For user profile
    if (empty($userDislikedList)){//If empty array
        $userDislikedList = array($articleID);//Update disliked list
        $userLikedList = disableSwitch($userLikedList,$articleID);//Update liked list as well
    }
    else if (in_array($articleID,$userDislikedList)){
        $userDislikedList = disableSwitch($userDislikedList,$articleID);//Remove from disliked list
    }
    else {
        $userDislikedList[] = $articleID;//Update disliked list
        $userLikedList = disableSwitch($userLikedList,$articleID);//Update liked list as well
    }
    updateUserDB($userID,$userLikedList,$userDislikedList);
    }
}

function setThumbsUp($userID,$articleID,$userchoice,$userLikedList,$userDislikedList){
    if ($userID == 0||!isset($userID)){//If User yet to login
        echo "alert('Login First!!!')";
    } 
    else {//If user logined
    $userID_Str = "".$userID."";
    if (empty($userchoice)){//If empty dict
        $userchoice = array($userID_Str=>"Liked");
    }
    else if ($userchoice[$userID_Str] == "Liked"){
        unset($userchoice[$userID_Str]);//Remove from $userchoice
    }
    else{
            $userchoice[$userID_Str] = "Liked";
    }
    $array_string=json_encode($userchoice);
    // Create and populate an object.
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    $articleInfo->es_userchoice =  $array_string;
    
    // Update the object into the article profile table.
    $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
    
    //For user profile
    if (empty($userLikedList)){//If empty array
        $userLikedList = array($articleID);//Update liked list
        $userDislikedList = disableSwitch($userDislikedList,$articleID);//Update disliked list as well
    }
    else if (in_array($articleID,$userLikedList)){
        $userLikedList = disableSwitch($userLikedList,$articleID);//Remove from liked list
    }
    else {
        $userLikedList[] = $articleID;//Update liked list
        $userDislikedList = disableSwitch($userDislikedList,$articleID);//Update disliked list as well
    }    
    updateUserDB($userID,$userLikedList,$userDislikedList);
    }
}

function getLikes($userchoice){
    //Calculate Number of likes and dislikes
    $NoLikes = 0;
    foreach ($userchoice as $row){
        if ($row == "Liked") $NoLikes +=1;
    }
    return $NoLikes;
}

function getDislikes($userchoice){
    //Calculate Number of likes and dislikes
    $count = 0;
    foreach ($userchoice as $row){
        if ($row == "Disliked") $count +=1;
    }
    return $count;
}

$NoLikes = getLikes($userchoice);
$NoDislikes = getDislikes($userchoice);
?>

<div class="customtext">
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>
<div id="thumbsBox">
    <?php if ($userID==0) : ?>
        <h3><?php echo "Login to like/dislike"; ?></h3>
    <?php else : ?>
        <form method="post">
            <button name= "tUp" id= "thumbsUp" title ="Like Button">👍</button>
            <button name= "tDown" id = "thumbsDown" title ="Dislike Button">👎</button>  
        </form>
    <?php endif; ?>
</div>
