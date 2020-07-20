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
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <!--Check if current directory is on home page-->
      <?php if($urlPath==$this->baseurl."/index.php/home") : ?>
        <li class="nav-item active">
      <?php else : ?>  
        <li class="nav-item">
      <?php endif; ?>
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
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