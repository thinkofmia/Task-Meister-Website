<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
use Joomla\CMS\Factory;
$db = Factory::getDbo();
$me = Factory::getUser();

$userID = $me->id;

//Querying
$query = $db->getQuery(true);
$query->select($db->quoteName(array('title','id','hits','featured','catid','likes','dislikes')))
    ->from($db->quoteName('#__content'))
    ->where($db->quoteName('id') . ' = ' . JRequest::getVar('id'));
$db->setQuery($query);
$results = $db->loadAssocList();
foreach ($results as $row) {
    $articleID = $row['id'];
    $articleTitle = $row['title'];
    $articleCat = $row['catid'];
    $articleHits = $row['hits'];
    $articleFeatured =  $row['featured'];
    $articleLikes = $row['likes'];
    $articleDislikes = $row['dislikes'];
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


function setThumbsUp($userID,$articleID){
    if ($userID == 0){
        echo "alert(".modTMLikes::loginFirst().")";
    } 
    else {
        
    if (isset($articleLikes)){//If array for likes in the article even exists
        if (isset($articleLikes[$userID])){//Check if user has liked or disliked
            $articleLikes[$userID] = "Liked";
        }
    }
    else{
        $articleLikes = [
            $userID => "Liked"
        ];
    }
    foreach ($articleLikes as $paramName => $paramValue){
        echo $paramName . " gave ". $paramValue . "<br>";
    }
    echo "Article Selected: " . $articleID;

    // Create and populate an object.
    $articleInfo = new stdClass();
    $articleInfo->likes =  $articleLikes;
    
    // Update the object into the user profile table.
    $result = JFactory::getDbo()->updateObject('#__content', $articleInfo, $articleID);
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
        setThumbsUp($userID,$articleID);
    }
?>
