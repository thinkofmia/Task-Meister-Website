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
foreach ($results as $row) {
    //if (JRequest::getVar('id')==$row['es_articleID']){
        $userchoice=json_decode($row['es_userchoice'],true);
        echo "Current List of ".$row['es_articleid']." : " . $row['es_userchoice'];
    //} 
}

echo $userchoice;

//Set Alert Message
if ($me->id == 0){
    $thumbsUp = modTMLikes::loginFirst();//Invoke thumbs up method
    $thumbsDown = modTMLikes::loginFirst();//Invoke thumbs down method
} 
else {
    $thumbsUp = modTMLikes::giveThumbsUp();//Invoke thumbs up method
    $thumbsDown = modTMLikes::giveThumbsDown();//Invoke thumbs down method
    }


function setThumbsUp($userID,$articleID,$userchoice){
    if ($userID == 0){//If User yet to login
        echo "alert(".modTMLikes::loginFirst().")";
    } 
    else {//If user logined
    $userID_Str = "".$userID."";
    if (!isset($userchoice)||$userchoice = "{}"){//If empty dict
        $userchoice = array($userID_Str=>"Liked");
    }
    else{
            $userchoice[$userID_Str] = "Liked";
    }
    foreach ($userchoice as $paramName => $paramValue){
        echo "Key: " . $paramName . " Value: ". $paramValue . "<br>";
    }
    echo "Article Selected: " . $articleID . "<br>";
    $array_string=json_encode($userchoice);
    echo "Encoded: " . $array_string . "<br>";
    echo "Decoded: " . json_decode($array_string) . "<br>";
    // Create and populate an object.
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    $articleInfo->es_userchoice =  $array_string;
    
    // Update the object into the article profile table.
    $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
    }
}
    
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
<?php 
    if(isset($_POST["tUp"])){
        setThumbsUp($userID,$articleID,$userchoice);
    }
?>
