<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<!-- Display out custom header/text-->
<div class="customtext">
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>

<?php 
//Display selected mode for debug reasons
echo "Selected - ". $params->get('filter').": ". $resultsSelected ."<br>";
//Convert the string results of the recommended articles into an array
$list2 = json_decode($recommendedContents);
?>

<ul class="scrollbarnews<?php echo $moduleclass_sfx; ?> mod-list">
<div class="articlesRow">
  <!-- Arrow button to scroll left-->
  <i class="arrowLeft" onclick="this.closest('div').querySelector('.recommendedArticles').scrollLeft -= 100;"></i>
  <!--Displays list of recommended articles based on the article contents-->
  <div class="recommendedArticles" id= "recommendation">
    <!--For loop for the items in the list-->
    <?php foreach ($list2 as $key => $value) : ?>
    <!--Url link to the article page-->
    <a href="?option=com_content&view=article&id=<?php echo $key; ?>" itemprop="url">
        <div class="article">
          <!--Image of the article-->
          <img src="<?php echo json_decode($value[1])->image_intro; ?>" width="100%" height="100%" />
          <!--Text found on the article-->
          <p><?php echo json_encode($value[0]); ?></p>
        </div>
    </a>
    <?php endforeach; ?>
  </div>
  <!-- Arrow button to scroll right-->
  <i class="arrowRight" onclick="this.closest('div').querySelector('.recommendedArticles').scrollLeft += 100;"></i>
</div>
</ul>