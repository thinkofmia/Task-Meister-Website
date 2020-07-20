<!-- This prevents naughty peeps from looking at code  -->
<?php defined( '_JEXEC' ) or die( 'Restricted access' );

//Database code
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

//Set database variable
$db = Factory::getDbo();
$me = Factory::getUser();

//Set URL var
$uri = Uri::getInstance();
$urlPath = $uri->getPath();
//$uri->getScheme() . ", Host: " . $uri->getHost() . " , Path: " . $uri->getPath() . "<br>";
//Set Userid and username
$userID = $me->id;
$user = JFactory::getUser($userID);
if (isset($user->name))$username = $user->name;
else $username = "Login";
?>
<!-- Tells the browser which flavor of HTML the page is using. In this case HTML5.-->
<!DOCTYPE html>
<!-- Begins HTML document and describes what language the website is in-->
<html xmlns="http://www.w3.org/1999/xhtml" 
   xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

<!-- Contain the information about the document-->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Puts the correct header information in (eg. page title, meta information, JavaScript)-->
<jdoc:include type="head" />
<!--Bootstrap 4-->
<!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<!-- Creates links to two system style sheets and to our style sheet-->
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
<link href="https://fonts.googleapis.com/css2?family=BioRhyme&display=swap" rel="stylesheet">
<script src="https://www.google.com/jsapi"></script>
</head>

<!-- Contain the website code which controls the layout-->
<body>
<!--Scripts-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

<!--Bootstrap Nav Bar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <!--Logo-->
  <a class="navbar-brand" href="<?php echo $this->baseurl; ?>">
      <!--Logo Image-->
      <img id="navLogo" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/TaskMeisterLogo.JPG" alt="Logo">
  </a>
  <!--Hamburger toggler-->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <!--Check if current directory is on home page-->
      <?php if(($urlPath==$this->baseurl."/index.php/home/")||($urlPath==$this->baseurl."/index.php/home")||($urlPath==$this->baseurl."/index.php/")||($urlPath==$this->baseurl."/index.php")||($urlPath==$this->baseurl."/")) : ?>
        <li class="nav-item bgAlt">
            <a class="nav-link text-dark" href="<?php echo $urlPath==$this->baseurl; ?>">Home</a>
      <?php else : ?>  
        <li class="nav-item text-light">
            <a class="nav-link text-light" href="<?php echo $urlPath==$this->baseurl; ?>">Home</a>
      <?php endif; ?>
        </li>
      <!--Check if current directory is on school levels page-->
      <?php if(($urlPath==$this->baseurl."/index.php/schoollevels/")||($urlPath==$this->baseurl."/index.php/schoollevels")) : ?>
        <li class="nav-item bgAlt">
            <a class="nav-link text-dark" href="<?php echo $this->baseurl."/index.php/schoollevels/"; ?>">By School Levels</a>
      <?php else : ?>  
        <li class="nav-item text-light">
            <a class="nav-link text-light" href="<?php echo $this->baseurl."/index.php/schoollevels/"; ?>">By School Levels</a>
      <?php endif; ?>
        </li>
      <!--Check if current directory is on subjects page-->
      <?php if(($urlPath==$this->baseurl."/index.php/resources/")||($urlPath==$this->baseurl."/index.php/resources")) : ?>
        <li class="nav-item bgAlt">
            <a class="nav-link text-dark" href="<?php echo $this->baseurl."/index.php/resources/"; ?>">By Subjects</a>
      <?php else : ?>  
        <li class="nav-item text-light">
            <a class="nav-link text-light" href="<?php echo $this->baseurl."/index.php/resources/"; ?>">By Subjects</a>
      <?php endif; ?>
        </li>
      <!--Check if current directory is on archives page-->
      <?php if(($urlPath==$this->baseurl."/index.php/interactive-resources/")||($urlPath==$this->baseurl."/index.php/interactive-resources")) : ?>
        <li class="nav-item bgAlt">
            <a class="nav-link text-dark" href="<?php echo $this->baseurl."/index.php/interactive-resources/"; ?>">Archives</a>
      <?php else : ?>  
        <li class="nav-item text-light">
            <a class="nav-link text-light" href="<?php echo $this->baseurl."/index.php/interactive-resources/"; ?>">Archives</a>
      <?php endif; ?>
        </li>
      <!--Dropdown menu for account-->  
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Account
        </a>
        <!--Dropdown tab for account-->
        <div class="dropdown-menu bg-dark" aria-labelledby="navbarDropdown">
          <a class="dropdown-item text-light" href="#"><?php echo $username; ?></a>
          <div class="dropdown-divider"></div>
          <!--If Guest, disable the below-->
          <?php if ($userID==0) : ?>
            <a class="dropdown-item disabled" href="#">My List</a>
            <a class="dropdown-item disabled" href="#">My Preferences</a>
            <a class="dropdown-item disabled" href="#">My Class</a>
          <?php else: ?>
            <a class="dropdown-item text-light" href="#">My List</a>
            <a class="dropdown-item text-light" href="#">My Preferences</a>
            <a class="dropdown-item text-light" href="#">My Class</a>
          <?php endif; ?>  
        </div>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn bgAlt my-2 my-sm-0" type="submit">🔍</button>
    </form>
  </div>
</nav>

<!--Banner-->
<jdoc:include type="modules" name="banner"/><!-- Module Position: 'banner'-->
<!--Body-->
<jdoc:include type="modules" name="top"/><!-- Module Position: 'top'-->
<jdoc:include type="modules" name="center"/><!-- Module Position: 'center'-->
<jdoc:include type="modules" name="bottom"/><!-- Module Position: 'bottom'-->
<!--Right-->
<jdoc:include type="modules" name="right"/><!--Module Position: 'right'-->
<!-- Module Position: 'footer'-->
<jdoc:include type="modules" name="footer" /><!-- Module Position: 'footer'-->
</body>

<!-- End-->
</html>