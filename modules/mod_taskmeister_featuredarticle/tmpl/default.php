<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>
<div class="wrapper">
    <div class="featuredArticle">
        <!--If exists video, play video. Else show image of article.-->
        <?php if ($videoLink) : ?>
            <iframe src="<?php echo $videoLink; ?>">
            </iframe>
        <?php else : ?>
            <img src="<?php echo $articleImage; ?>" onerror="this.src='<?php echo $dummyArticleImg; ?>';"></img>
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
            <p><span style="cursor: context-menu;" title="Number of likes: <?php echo $articleTotalLikes; ?>"><?php echo $articleTotalLikes; ?>👍 </span> 
            <!--Displays Number of Deployment-->
            <span style="cursor: context-menu;" title="Number of deployment: <?php echo $articleTotalDeployed; ?>"><?php echo $articleTotalDeployed; ?>👨‍💻</span><br>
            <!--Displays who has liked it-->
            <b>Liked by: </b><?php echo $articleLikedUsers; ?><br>
            <!--Displays those in your school that has deployed it-->
            <b>Deployed by: </b><?php echo $articleDeployedUsers; ?></p>
            <!--Displays play button-->
            <p>
                <a href="?option=com_content&view=article&id=<?php echo $articleID; ?>" itemprop="url" title="Go to the article site">▶ Play</a>
            </p>
        </div>
    </div>
</div>