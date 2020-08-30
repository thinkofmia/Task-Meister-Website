<!-- This prevents naughty peeps from looking at code  -->
<?php defined( '_JEXEC' ) or die( 'Restricted access' );

//This updated template is based on the personalized changes requested by Lawrence

//Database code
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

//Set database variable
$db = Factory::getDbo();
$me = Factory::getUser();
$doc = JFactory::getDocument();

//Set URL var
$uri = Uri::getInstance();
$urlPath = $uri->getPath();
//$uri->getScheme() . ", Host: " . $uri->getHost() . " , Path: " . $uri->getPath() . "<br>";

$userID = $me->id;//Get user id
$user = JFactory::getUser($userID);//Get user
if (isset($user->name))$username = $user->name; //Get user's name
else $username = "Sign in";//Replace user's name with login

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
//Add Chai Seng's JavaScripts File
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/template.js');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/keen-slider.js');
?>

<!-- Tells the browser which flavor of HTML the page is using. In this case HTML5.-->
<!DOCTYPE html>
<!-- Begins HTML document and describes what language the website is in-->
<html xmlns="http://www.w3.org/1999/xhtml" 
   xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

<!-- Contain the information about the document-->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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

<!--For Google Chart.js-->
<link href="https://fonts.googleapis.com/css2?family=BioRhyme&display=swap" rel="stylesheet">
<script src="https://www.google.com/jsapi"></script>

<!-- Puts the correct header information in (eg. page title, meta information, JavaScript)-->
<jdoc:include type="head" />
<!--Importing Chai Seng's CSS files-->
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/keen-slider.min.css" type="text/css" />
</head>

<!-- Contain the website code which controls the layout-->
<body>
<!--Scripts-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

<!--Bootstrap 4 Navigation Bar-->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <!--Taskmeister Logo Link-->
  <a class="navbar-brand" href="<?php echo $this->baseurl; ?>">
      <!--Taskmeister Logo Image-->
      <img id="navLogo" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/TaskMeisterLogo.JPG" alt="Logo">
  </a>
  <!--Hamburger Menu Toggle-->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <!--Check if current directory is on home page-->
      <?php if(($urlPath==$this->baseurl."/index.php/home/")||($urlPath==$this->baseurl."/index.php/home")||($urlPath==$this->baseurl."/index.php/")||($urlPath==$this->baseurl."/index.php")||($urlPath==$this->baseurl."/")) : ?>
        <!--If so, highlight the nav-item-->
        <li class="nav-item bgAlt">
            <a class="nav-link" href="<?php echo $urlPath==$this->baseurl; ?>">Home</a>
      <?php else : ?>  
        <!--Else, leave it as a default nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $urlPath==$this->baseurl; ?>">Home</a>
      <?php endif; ?>
        </li>
      <!--Check if current directory is on activity page-->
    <?php if(($urlPath==$this->baseurl."/index.php/mylist/")||($urlPath==$this->baseurl."/index.php/mylist")) : ?>
        <!--If so, highlight the nav-item-->
        <li class="nav-item bgAlt">
            <a class="nav-link" href="<?php echo $this->baseurl."/index.php/mylist/"; ?>">
              My Activities
            </a>
      <?php else : ?>  
        <!--Else, leave it as a default nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $this->baseurl."/index.php/mylist/"; ?>">My Activities</a>
      <?php endif; ?>
        </li>   
        <!--Check if current directory is on preferences page-->
    <?php if(($urlPath==$this->baseurl."/index.php/preferences/")||($urlPath==$this->baseurl."/index.php/preferences")) : ?>
        <!--If so, highlight the nav-item-->
        <li class="nav-item bgAlt">
            <a class="nav-link" href="<?php echo $this->baseurl."/index.php/preferences/"; ?>">My Preferences</a>
      <?php else : ?>  
        <!--Else, leave it as a default nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $this->baseurl."/index.php/preferences/"; ?>">My Preferences</a>
      <?php endif; ?>
        </li>    
        <!--Check if current directory is on class page-->
    <?php if(($urlPath==$this->baseurl."/index.php/class/")||($urlPath==$this->baseurl."/index.php/class")) : ?>
        <!--If so, highlight the nav-item-->
        <li class="nav-item bgAlt">
            <a class="nav-link" href="<?php echo $this->baseurl."/index.php/class/"; ?>">My Class</a>
      <?php else : ?>  
        <!--Else, leave it as a default nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $this->baseurl."/index.php/class/"; ?>">My Class</a>
      <?php endif; ?>
        </li>      
      <!--Dropdown menu for themes-->  
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="themeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Themes
        </a>
         <!--Dropdown tab for account-->
         <div class="dropdown-menu" aria-labelledby="themeDropdown">
        <!--Dropdown tab for themes-->
            <!--Choosing one of the options below will set the theme. JavaScript has to be enabled for it to work.-->
            <a class="dropdown-item" onclick="setTheme('Default'); checkTheme();">Default</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" onclick="setTheme('sky'); checkTheme();">Ice</a>
            <a class="dropdown-item" onclick="setTheme('flix'); checkTheme();">Netflix</a>
            <a class="dropdown-item" onclick="setTheme('tree'); checkTheme();">Tree</a>
        </div>
      </li>
    </ul>
    <!--Navigation bar's search bar-->
    <form class="form-inline my-2 my-lg-0" action="<?php echo JUri::base(); ?>index.php/search">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" value = "<?php echo $_REQUEST["keyword"]; ?>" name="keyword">
      <button class="btn bgAlt my-2 my-sm-0" type="submit">🔍</button>
    </form>
    <!--Check if current directory is on user page-->
    <?php if(($urlPath==$this->baseurl."/index.php/user/")||($urlPath==$this->baseurl."/index.php/user")) : ?>
        <!--If so, highlight the nav-item-->
        <li class="nav-item bgAlt" style="list-style-type: none;">
            <a class="nav-link" href="<?php echo $this->baseurl."/index.php/user/"; ?>">
              <?php echo $username; ?>
            </a>
      <?php else : ?>  
        <!--Else, leave it as a default nav-item-->
        <li class="nav-item" style="list-style-type: none;">
            <a class="nav-link" href="<?php echo $this->baseurl."/index.php/user/"; ?>"><?php echo $username; ?></a>
      <?php endif; ?>
        </li>
  </div>
</nav>

<!--Modules that would be place in the 'Banner' position-->
<jdoc:include type="modules" name="banner"/>

<jdoc:include type="modules" name="advertisement"/>

<!--Container for the Taskmeister Contents-->
<div id="tm_contents" class="container-fluid">
  <!--Top of the container-->
  <!--Modules that would be place in the 'Top' position-->
  <jdoc:include type="modules" name="top"/>

  <!--Center of the container-->
  <!--Splitting into left-right row-->
  <div class="row">
    <!--Left column in the row, occupies 8/12 space-->
    <div class="col-sm-8">
      <div class="container pr-1">
        <!--Components are placed here. Examples of components are Articles and Review-->
        <jdoc:include type="component" />
      </div>
    </div>
    <!--Right column in the row, occupies 4/12 space-->
    <div class="col-sm-4">
      <div class="container-sm">
        <!--Modules that would be place in the 'Right' position-->
        <jdoc:include type="modules" name="right"/>
      </div>
    </div>
  </div>

  <!--Bottom of the Container-->
  <!--Modules that would be place in the 'Center' position-->
  <jdoc:include type="modules" name="center"/>
  <!--Modules that would be place in the 'Bottom' position-->
  <jdoc:include type="modules" name="bottom"/>
</div>

<!--Modules that would be place in the 'Footer' position-->
<jdoc:include type="modules" name="footer" />

<!--Script to set theme of page-->
<script>
  /**
   * setTheme(theme)
   * JavaScript function to set the theme of the webpage using cookies. 
   * Expiry: One year
  */
  function setTheme(theme) {
  //Get current date
  var d = new Date();
  //Set date to be one year later
  d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
  //Set expiry date
  var expires = "expires="+d.toUTCString();
  //Save the selected theme as a cookie with a year expiry
  document.cookie = "theme=" + theme + ";" + expires + ";path=/";
}

  /**
   * getTheme()
   * JavaScript function to get the theme of the webpage using cookies.
   * If not found, return default theme.
  */
  function getTheme() {
    //Set target of cookie to be theme
    var name = "theme=";
    //Split the cookie documents
    var ca = document.cookie.split(';');
    //Loop through the contents by ';'
    for(var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        //If found, return the theme
        return c.substring(name.length, c.length);
      }
    }
    //Else return nothing
    return "";
  }

  /**
   * checkTheme()
   * JavaScript Function to check the theme of the page
   */
  function checkTheme() {
    //Remove all the theme classes
    document.body.classList.remove("default");
    document.body.classList.remove("day");
    document.body.classList.remove("red");
    document.body.classList.remove("tree");
    //Get theme based on the cookie stored
    var theme = getTheme();
    //Set the theme by adding the CSS class
    if (theme == "sky") {//Set Sky theme
      document.body.classList.add("day");
    }
    else if (theme=="flix") {//Set Netflix theme
      document.body.classList.add("red");
    }
    else if (theme=="tree") {//Set Tree Theme
      document.body.classList.add("tree");
    }
    else {//Set Default theme
      document.body.classList.add("default");
    }
  } 

//Run script
checkTheme();
</script>

<!--End of Body-->
</body>

<!-- End-->
</html>