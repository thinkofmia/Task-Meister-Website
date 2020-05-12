<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
use Joomla\CMS\Factory;
$db = Factory::getDbo();
$me = Factory::getUser();

$userID = $me->id;

$articleID = JRequest::getVar('id');
//Querying for Article stats table
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_articleid','es_deployed')))
    ->from($db->quoteName('#__customtables_table_articlestats'))
    ->where($db->quoteName('es_articleid') . ' = ' . $articleID);
$db->setQuery($query);
$results = $db->loadAssocList();
$deployedList;
$dataNotExist = true;
foreach ($results as $row) {
    if (JRequest::getVar('id')==$row['es_articleid']){
        $deployedList=json_decode($row['es_deployed'],true);
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
$query->select($db->quoteName(array('es_userid','es_pagedeployed')))
    ->from($db->quoteName('#__customtables_table_userstats'))
    ->where($db->quoteName('es_userid') . ' = ' . $userID);
$db->setQuery($query);
$results = $db->loadAssocList();
$deployedList;
$dataNotExist = true;
foreach ($results as $row) {
    if ($userID==$row['es_userid']){
        $deployedList_user=json_decode($row['es_pagedeployed'],true);
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
if(isset($_POST["dButton"])){
    setDeployed($userID,$articleID,$deployedList,$deployedList_user);
}

function setDeployed($userID,$articleID,$list,$deployedList_user){
    if ($userID == 0){//If User yet to login
        echo "alert('Login First!!')";
    } 
    else {//If user logined
        $userID_Str = "".$userID."";
        if (empty($list)){//If empty array
            $list = array($userID);
        }
        else if (in_array($userID,$list)){
            $key = array_search($userID, $list);
            unset($list[$key]);
        }
        else {
            $list[] = $userID;
        }
        $array_string=json_encode($list);
        // Create and populate an object.
        $articleInfo = new stdClass();
        $articleInfo->es_articleid = $articleID;
        $articleInfo->es_deployed =  $array_string;
        
        // Update the object into the article profile table.
        $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
        
        //For user profile
        if (empty($deployedList_user)){//If empty array
            $deployedList_user = array($articleID);
        }
        else if (in_array($articleID,$deployedList_user)){
            $key = array_search($articleID, $deployedList_user);
            unset($deployedList_user[$key]);
        }
        else {
            $deployedList_user[] = $articleID;
        }
        $array_string2=json_encode($deployedList_user);

        // Create and populate an user table.
        $userInfo = new stdClass();
        $userInfo->es_userid = $userID;
        $userInfo->es_pagedeployed =  $array_string2;
        
        // Update the object into the article profile table.
        $result = JFactory::getDbo()->updateObject('#__customtables_table_userstats', $userInfo, 'es_userid');
        
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
<div id="deployedBox">
    <?php if ($userID==0) : ?>
        <button name= "fakeButton" id= "deployedButton" onclick="alert('Login First!!')" title="Deployment Button">👨‍💻</button> 
    <?php else : ?>
        <form method="post">
            <button name= "dButton" id= "deployedButton" title="Deployment Button">👨‍💻</button> 
        </form>
    <?php endif; ?>
</div>

