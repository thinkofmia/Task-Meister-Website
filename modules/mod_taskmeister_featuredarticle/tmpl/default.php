<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<head>

<!--Use BootStrap 4 carousel slider-->
<!--Wrapper for the carousel-->
<div id="featuredArticlesWrapper" class="carousel slide" data-ride="carousel">
  <!--Carousel Indiciators-->
  <ol class="carousel-indicators bgAlt">
    <?php $counter = 0; //Counter to count number of stuffs in dict ?>
    <?php foreach ($articlesDict as $key => $value) : ?>
      <?php if ($counter==0) : ?>
        <li data-target="#featuredArticlesWrapper" data-slide-to="0" class="active"></li>
      <?php else : ?>
        <li data-target="#featuredArticlesWrapper" data-slide-to="<?php echo $counter; ?>"></li>
      <?php endif; ?>
      <?php $counter+=1; //Increase counter by 1 ?>
    <?php endforeach; ?>
  </ol>
  <!--Carousel display-->
  <div class="carousel-inner">
    <?php $counter = 0; //Counter to count number of stuffs in dict ?>
    <!--Carousel Items-->
    <?php foreach ($articlesDict as $key => $value) : ?>
      <?php if ($counter==0) : ?>
        <div class="carousel-item active">
      <?php else : ?>
        <div class="carousel-item">
      <?php endif; ?>
        <img class="d-block w-100" src="<?php echo $value["image"]; ?>" onerror="this.src='<?php echo $dummyArticleImg; ?>';"></img>
        <!--Outer Containter-->
        <div class="container">
          <div class="row">
            <div class="col-sm-6">
              <div class="carousel-caption d-none d-md-block mb-5">
                <!-- Card-->
                <div class="card" style="width: 18rem; height:100%;">
                  <div class="card-body">
                    <h5 class="card-title textAlt"><?php echo $value["title"]; ?></h5>
                    <p class="card-text">
                      <!--Displays Number of likes-->
                      <span style="cursor: context-menu;" title="Number of likes: <?php echo $value["noOfLikes"]; ?>"><?php echo $value["noOfLikes"]; ?>👍 </span> 
                      <!--Displays Number of Deployment-->
                      <span style="cursor: context-menu;" title="Number of deployment: <?php echo $value["noOfDeployed"]; ?>"><?php echo $value["noOfDeployed"]; ?>👨‍💻</span><br>
                      <!--Displays who has liked it-->
                      <b>Liked by: </b><?php echo $value["likedUsers"]; ?><br>
                      <!--Displays those in your school that has deployed it-->
                      <b>Deployed by: </b><?php echo $value["deployedUsers"]; ?>
                    </p>
                    <a class="card-link" href="?option=com_content&view=article&id=<?php echo $value["id"]; ?>" itemprop="url" title="Go to the article site">
                      <button type="button" class="btn bgAlt btn-lg">▶ Play</button>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <!--Video-->
              <div class="carousel-caption d-none d-md-block mb-5 featuredArticleVideo pr-1">
                <!--If exists video, show video.\-->
                <?php if ($value["videoLink"]) : ?>
                  <iframe title="Crawled link = <?php echo $value["videoLink"]; ?>" src="<?php echo $value["videoLink"]; ?>" 
                  allowfullscreen="allowfullscreen"
                  mozallowfullscreen="mozallowfullscreen" 
                  msallowfullscreen="msallowfullscreen" 
                  oallowfullscreen="oallowfullscreen">
                  </iframe>
                <?php else: ?>
                  <p>No Video is available</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <!--End of outer container-->
      </div>
      <?php $counter+=1; //Increase counter by 1 ?>
    <?php endforeach; ?>  
  </div>
  <!--Hide Left-right buttons
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <button class="btn bgAlt btn-circle btn-circle-lg m-1">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </button>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <button class="btn bgAlt btn-circle btn-circle-lg m-1">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </button>
  </a>
      -->
</div>

