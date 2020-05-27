<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<div class="featureArticle">
    <!--If exists video, play video. Else show image of article.-->
    <?php if ($videoLink) : ?>
        <iframe width="300" height="300" src="<?php echo $videoLink; ?>">
        </iframe>
    <?php else : ?>
        <img width="300" height="300" src="<?php echo $articleImage; ?>"></img>
    <?php endif; ?>
    <!--Displays Custom Header if exists, else use article title-->
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php else : ?>
        <h3><?php echo $articleTitle; ?></h3>
    <?php endif; ?>
    <!--Displays Text if exists-->
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>