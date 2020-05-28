<?php 
// No direct access
defined('_JEXEC') or die; 

//Setup Factory to call database/user info
use Joomla\CMS\Factory;
//Set Database Var
$db = Factory::getDbo();
//Set User Var and save user id
$me = Factory::getUser();
$userID = 0; //By default

//Get article id
$articleID = JRequest::getVar('id');

if ($articleID){

//Set User id
$userID = $me->id;
//Querying for Article stats table for this article
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_articleid','es_deployed')))//Get article id and deployment list
    ->from($db->quoteName('#__customtables_table_articlestats'))//From external table Article Stats
    ->where($db->quoteName('es_articleid') . ' = ' . $articleID);//Where it is this article using article id
$db->setQuery($query);
$results = $db->loadAssocList();//Save results as an array
//Initializing Variables
$deployedList;//List of users who deployed this article
$dataNotExist = true;//Boolean to check if record of article exists in the article stats table
//For loop to check if data exists
foreach ($results as $row) {
    if (JRequest::getVar('id')==$row['es_articleid']){
        //If data exists for this article, save the deployment list
        $deployedList=json_decode($row['es_deployed'],true);//Since in the database is a string, we have to convert it to an array using json_decode
        $dataNotExist = false;//Set boolean to be false since data do exists
    } 
}

//If no records of this in the Article Stats Database
if ($dataNotExist){
    // Create and populate it
    $articleInfo = new stdClass();
    $articleInfo->es_articleid = $articleID;
    // Update the object into the article stats table.
    $result = JFactory::getDbo()->insertObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
}

//Querying for external User Stats table
$query = $db->getQuery(true);
$query->select($db->quoteName(array('es_userid','es_pagedeployed')))//Get User id and list of pages deployed
    ->from($db->quoteName('#__customtables_table_userstats'))//From the external user stats database
    ->where($db->quoteName('es_userid') . ' = ' . $userID);//Where it is the current logined user
$db->setQuery($query);
$results = $db->loadAssocList();//Save results of query
//Initialize Variables
$deployedList;//List of articles that the user deployed
$dataNotExist = true;//Boolean to show if a record of the user exists in the user stats table
foreach ($results as $row) {//For loop to find the record
    if ($userID==$row['es_userid']){//If user id matches the one of the records
        $deployedList_user=json_decode($row['es_pagedeployed'],true);//Save the deployment list of user as an array
        $dataNotExist = false;//Set boolean to be false since the data do exists!
    } 
}
//If no record of the user stats for the particular user exists
if ($dataNotExist){
    // Create and populate an object.
    $userInfo = new stdClass();
    $userInfo->es_userid = $userID;
    // Update the object into the user stats table.
    $result = JFactory::getDbo()->insertObject('#__customtables_table_userstats', $userInfo, 'es_userid');
}

}



//If clicked on the deployment button
if(isset($_POST["dButton"])){
    //Run setDeployed()
    setDeployed($userID,$articleID,$deployedList,$deployedList_user);
}

function setDeployed($userID,$articleID,$list,$deployedList_user){
    /**
     * Function: Update Deployment Lists into both article and user stats database
     * Parameter $userID: Refers to the current user id
     * Parameter $articleID: Refers to the current article id
     * Parameter $list: Refers to the article's deployment list
     * Parameter $deployedList_user: Refers to the user's deployment list
     */
    //Check if the user is a guest
    if ($userID == 0){
        //If the user is indeed a guest, alert them to login first
        echo "alert('Login First!!')";
    } 
    else {//If user has logined or is registered
        //Check if article's deployment list is empty
        if (empty($list)){//If it is really empty,
            $list = array($userID);//save the user id into the list as a new array
        }
        //Else if user id already exists inside the article's deployment list,
        else if (in_array($userID,$list)){
            $key = array_search($userID, $list);//Find the index of the array
            unset($list[$key]);//Removes user from the list
        }
        //Else just push the user id into the article's deployment list
        else {
            $list[] = $userID;
        }
        //Save the new article deployment list as a string
        $array_string=json_encode($list);
        // Create and populate an object to save in database
        $articleInfo = new stdClass();
        $articleInfo->es_articleid = $articleID;
        $articleInfo->es_deployed =  $array_string;
        // Update the object into the article stats table.
        $result = JFactory::getDbo()->updateObject('#__customtables_table_articlestats', $articleInfo, 'es_articleid');
        
        //For user profile
        //Check if user's deployment list is empty
        if (empty($deployedList_user)){//If user's deployment list is really empty
            $deployedList_user = array($articleID);//Create a new array with the article id inside the user's deployment list
        }
        //Else if the article id already exists in the user's deployment list,
        else if (in_array($articleID,$deployedList_user)){
            $key = array_search($articleID, $deployedList_user);//Find the index of the key
            unset($deployedList_user[$key]);//And remove the article id from the user's deployment list
        }
        //Else just push the article id into the user's deployment list
        else {
            $deployedList_user[] = $articleID;
        }
        //Save the new user deployment list as a string
        $array_string2=json_encode($deployedList_user);
        // Create and populate an user table.
        $userInfo = new stdClass();
        $userInfo->es_userid = $userID;
        $userInfo->es_pagedeployed =  $array_string2;
        // Update the object into the user stats table.
        $result = JFactory::getDbo()->updateObject('#__customtables_table_userstats', $userInfo, 'es_userid');
    }
}
?>

<!--HTML display for the custom text-->
<div class="customtext">
    <!--Show custom header if exists-->
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <!--Show custom text if exists-->
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>

<!--Display for the deployment box/icon-->
<div id="deployedBox">
    <!--If the user is a guest, set the onclick button to login first -->
    <?php if ($userID==0) : ?>
        <button name= "fakeButton" id= "deployedButton" onclick="alert('Login First!!')" title="Deployment Button">👨‍💻</button> 
    <!--Else if already logined, set post button for the deployment -->
    <?php else : ?>
        <form method="post">
            <button name= "dButton" id= "deployedButton" title="Deployment Button">👨‍💻</button> 
        </form>
    <?php endif; ?>
</div>

