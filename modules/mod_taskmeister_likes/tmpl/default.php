<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
use Joomla\CMS\Factory;
$db = Factory::getDbo();
$me = Factory::getUser();

$userID = $me->id;

$articleID = JRequest::getVar('id');
//Querying
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

//Set Alert Message
if ($me->id == 0){
    $thumbsUp = modTMLikes::loginFirst();//Invoke thumbs up method
    $thumbsDown = modTMLikes::loginFirst();//Invoke thumbs down method
} 
else {
    $thumbsUp = modTMLikes::giveThumbsUp();//Invoke thumbs up method
    $thumbsDown = modTMLikes::giveThumbsDown();//Invoke thumbs down method
    }


//Functions
if(isset($_POST["tDown"])){
    setThumbsDown($userID,$articleID,$userchoice);
    $NoLikes = getLikes($userchoice);
    $NoDislikes = getDislikes($userchoice);
}

if(isset($_POST["tUp"])){
    setThumbsUp($userID,$articleID,$userchoice);
    $NoLikes = getLikes($userchoice);
    $NoDislikes = getDislikes($userchoice);
}

function setThumbsDown($userID,$articleID,$userchoice){
    if ($userID == 0){//If User yet to login
        echo "alert(".modTMLikes::loginFirst().")";
    } 
    else {//If user logined
    $userID_Str = "".$userID."";
    if (empty($userchoice)){//If empty dict
        $userchoice = array($userID_Str=>"Disliked");
    }
    else{
            $userchoice[$userID_Str] = "Disliked";
    }
    $array_string=json_encode($userchoice);
    /*Debug Messages
    echo "<br>Article Selected: " . $articleID . "<br>";
    echo "Encoded: " . $array_string . "<br>";
    $decoded = json_decode($array_string);
    echo "<ul>Decoded: <br>";
    foreach ($decoded as $paramName => $paramValue){
        echo "<li>Key: " . $paramName . " Value: ". $paramValue . "</li>";
    }
    echo "</ul>";  */
    // Create and populate an object.
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    $articleInfo->es_userchoice =  $array_string;
    
    // Update the object into the article profile table.
    $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
    }
}

function setThumbsUp($userID,$articleID,$userchoice){
    if ($userID == 0){//If User yet to login
        echo "alert(".modTMLikes::loginFirst().")";
    } 
    else {//If user logined
    $userID_Str = "".$userID."";
    if (empty($userchoice)){//If empty dict
        $userchoice = array($userID_Str=>"Liked");
    }
    else{
            $userchoice[$userID_Str] = "Liked";
    }
    $array_string=json_encode($userchoice);
    /*Debug Messages
    echo "<br>Article Selected: " . $articleID . "<br>";
    echo "Encoded: " . $array_string . "<br>";
    $decoded = json_decode($array_string);
    echo "<ul>Decoded: <br>";
    foreach ($decoded as $paramName => $paramValue){
        echo "<li>Key: " . $paramName . " Value: ". $paramValue . "</li>";
    }
    echo "</ul>";*/    
    // Create and populate an object.
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    $articleInfo->es_userchoice =  $array_string;
    
    // Update the object into the article profile table.
    $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
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
<form method="post">
    <button name= "tUp" id= "thumbsUp">👍</button>
    <button name= "tDown" id = "thumbsDown">👎</button>  
</form>
</div>

