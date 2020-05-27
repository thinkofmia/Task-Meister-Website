<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>
<div class="wrapper">
    <div class="featuredArticle">
        <!--If exists video, play video. Else show image of article.-->
        <?php if ($videoLink) : ?>
            <iframe width="300" height="300" src="<?php echo $videoLink; ?>">
            </iframe>
        <?php else : ?>
            <img width="300" height="300" src="<?php echo $articleImage; ?>"></img>
        <?php endif; ?>
        <div class="featuredArticleText">
            <!--Displays Custom Header if exists, else use article title-->
            <?php if ($displayHeader) : ?>
                <h3><?php echo $displayHeader; ?></h3>
            <?php else : ?>
                <h3><?php echo $articleTitle; ?></h3>
            <?php endif; ?>
            <!--Displays Text if exists-->
            <?php if ($displayText) : ?>
                <p><?php echo $displayText; ?></p>
            <?php endif; ?>
            <!--Displays Number of likes-->
            <p><b># of Likes: </b><?php echo $articleTotalLikes; ?><br>
            <!--Displays who has liked it-->
            <b>Users who like this: </b><?php echo $articleLikedUsers; ?><br>
            <!--Displays those in your school that has deployed it-->
            <b>Users who deploy this: </b><?php echo $articleDeployedUsers; ?></p>
            <!--Displays play button-->
            <p>
                <a href="?option=com_content&view=article&id=<?php echo $articleID; ?>" itemprop="url" title="Go to the article site">▶ Play</a>
            </p>
        </div>
    </div>
</div>