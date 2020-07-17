<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<section class="carousel" aria-label="Gallery">
  <ol class="carousel__viewport">
    <?php foreach ($articlesDict as $key => $value) : ?>
    <li id="carousel__slide<?php echo $key; ?>"
        tabindex="0"
        class="carousel__slide">
        <div class="wrapper">
    <div class="featuredArticle">
        <!--If exists video, play video. Else show image of article.-->
        <?php if ($value["videoLink"]) : ?>
            <iframe src="<?php echo $value["videoLink"]; ?>" 
            allowfullscreen="allowfullscreen"
            mozallowfullscreen="mozallowfullscreen" 
            msallowfullscreen="msallowfullscreen" 
            oallowfullscreen="oallowfullscreen">
            </iframe>
        <?php else : ?>
            <img src="<?php echo $value["image"]; ?>" onerror="this.src='<?php echo $dummyArticleImg; ?>';"></img>
        <?php endif; ?>
        <div class="featuredArticleText">
            <!--Article title-->
            <h3><?php echo $value["title"]; ?></h3>
            <!--Displays Number of likes-->
            <p><span style="cursor: context-menu;" title="Number of likes: <?php echo $value["noOfLikes"]; ?>"><?php echo $value["noOfLikes"]; ?>👍 </span> 
            <!--Displays Number of Deployment-->
            <span style="cursor: context-menu;" title="Number of deployment: <?php echo $value["noOfDeployed"]; ?>"><?php echo $value["noOfDeployed"]; ?>👨‍💻</span><br>
            <!--Displays who has liked it-->
            <b>Liked by: </b><?php echo $value["likedUsers"]; ?><br>
            <!--Displays those in your school that has deployed it-->
            <b>Deployed by: </b><?php echo $value["deployedUsers"]; ?></p>
            <!--Displays play button-->
            <p>
                <a class="button_tm" href="?option=com_content&view=article&id=<?php echo $value["id"]; ?>" itemprop="url" title="Go to the article site">▶ Play</a>
            </p>
                </div>
            </div>
        </div>
        </li>
    <?php endforeach; ?>
    <? for ($x = $counter_dict; $x < 4; $x++) : ?>
        <li id="carousel__slide<?php echo $x; ?>"
        tabindex="0"
        class="carousel__slide">
        </li>
    <?php endfor; ?>
  </ol>
  <aside class="carousel__navigation">
    <ol class="carousel__navigation-list">
      <li class="carousel__navigation-item">
        <a href="<?php echo JUri::getInstance(); ?>#carousel__slide1"
           class="carousel__navigation-button">Go to slide 1</a>
      </li>
      <li class="carousel__navigation-item">
        <a href="<?php echo JUri::getInstance(); ?>#carousel__slide2"
           class="carousel__navigation-button">Go to slide 2</a>
      </li>
      <li class="carousel__navigation-item">
        <a href="<?php echo JUri::getInstance(); ?>#carousel__slide3"
           class="carousel__navigation-button">Go to slide 3</a>
      </li>
      <li class="carousel__navigation-item">
        <a href="<?php echo JUri::getInstance(); ?>#carousel__slide4"
           class="carousel__navigation-button">Go to slide 4</a>
      </li>
    </ol>
  </aside>
</section>