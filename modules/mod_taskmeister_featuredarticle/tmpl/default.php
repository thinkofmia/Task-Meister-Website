<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<head>

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <?php $counter = 0; //Counter to count number of stuffs in dict ?>
    <?php foreach ($articlesDict as $key => $value) : ?>
      <?php if ($counter==0) : ?>
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
      <?php else : ?>
        <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $counter; ?>"></li>
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
      </div>
      <?php $counter+=1; //Increase counter by 1 ?>
    <?php endforeach; ?>  
  </div>
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
</div>

