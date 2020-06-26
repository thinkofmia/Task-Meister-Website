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
<!-- Creates links to two system style sheets and to our style sheet-->
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
<script src="https://www.google.com/jsapi"></script>
</head>

<!-- Contain the website code which controls the layout-->
<body>
    <div class="topnav" id="myTopnav">
    <a id = "logolink" href="<?php echo $this->baseurl; ?>/index.php/home"><!-- Set clickable logo-->
        <img class = "logo"  src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/TaskMeisterLogo.JPG" alt="Task Meister Logo" class="logo" />
    </a>
    <!--Home-->
    <?php if($urlPath==$this->baseurl."/index.php/home") : ?>
        <a class="active" href="<?php echo $this->baseurl; ?>/index.php/home">
    <?php else: ?>
        <a href="<?php echo $this->baseurl; ?>/index.php/home">
    <?php endif; ?>
        Home          
    </a>
    <!--Choose Preference-->
    <?php if($urlPath==$this->baseurl."/index.php/choosepreference") : ?>
        <a class="active" href="<?php echo $this->baseurl; ?>/index.php/choosepreference">
    <?php else: ?>
        <a href="<?php echo $this->baseurl; ?>/index.php/choosepreference">
    <?php endif; ?>
        Preference          
    </a>
    <!--Choose Class-->
    <?php if($urlPath==$this->baseurl."/index.php/chooseclass") : ?>
        <a class="active" href="<?php echo $this->baseurl; ?>/index.php/chooseclass">
    <?php else: ?>
    <a href="<?php echo $this->baseurl; ?>/index.php/chooseclass">
    <?php endif; ?>
        Class            
    </a>
    <!--My List-->
    <?php if($urlPath==$this->baseurl."/index.php/mylist") : ?>
        <a class="active" href="<?php echo $this->baseurl; ?>/index.php/mylist">
    <?php else: ?>
    <a href="<?php echo $this->baseurl; ?>/index.php/mylist">
    <?php endif; ?>
        List          
    </a>
    <!--Subjects-->
    <?php if($urlPath==$this->baseurl."/index.php/resources") : ?>
        <a class="active" href="<?php echo $this->baseurl; ?>/index.php/resources">
    <?php else: ?>
    <a href="<?php echo $this->baseurl; ?>/index.php/resources">
    <?php endif; ?>
        Subjects         
    </a>
    <!--My List-->
    <?php if($urlPath==$this->baseurl."/index.php/schoollevels") : ?>
        <a class="active" href="<?php echo $this->baseurl; ?>/index.php/schoollevels">
    <?php else: ?>
    <a href="<?php echo $this->baseurl; ?>/index.php/schoollevels">
    <?php endif; ?>
        Levels        
    </a>
    <!--Archives-->
    <?php if($urlPath==$this->baseurl."/index.php/interactive-resources") : ?>
        <a class="active" href="<?php echo $this->baseurl; ?>/index.php/interactive-resources">
    <?php else: ?>
    <a href="<?php echo $this->baseurl; ?>/index.php/interactive-resources">
    <?php endif; ?>
        Archives      
    </a>
    <!--User-->
    <?php if($urlPath==$this->baseurl."/index.php/login") : ?>
        <a class="active" id="loginNav" href="<?php echo $this->baseurl; ?>/index.php/login">
    <?php else: ?>
    <a id="loginNav" href="<?php echo $this->baseurl; ?>/index.php/login">
    <?php endif; ?>
        <?php echo $username; ?>  
    </a> 
    <a href="javascript:void(0);" class="icon" onclick="hamburgerMenu();">
        🍔
    </a>
    </div>
    <script>
    //Script for hamburger
    function hamburgerMenu() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
    }
    </script>

    <div id="topRow">
        <jdoc:include type="modules" name="top-left"/><!-- Module Position: 'top-left'-->
        <div class = "columnCenter">
            <jdoc:include type="modules" name="top"/><!-- Module Position: 'top'-->
        </div>
        <div id = "topRight">
            <jdoc:include type="modules" name="top-right"/><!-- Module Position: 'top-right'-->
        </div>
    </div>
    <div id="centerRow">
        <div class="left">
            <jdoc:include type="modules" name="center-left"/><!-- Module Position: 'center-left'-->
        </div>
        <div class="center">
            <jdoc:include type="modules" name="center"/><!-- Module Position: 'center'-->
        </div>
        <jdoc:include type="modules" name="center-right"/><!-- Module Position: 'center-right'-->
    </div>
    <div id="footer"><!--Unused-->
        <jdoc:include type="modules" name="bottom-left"/><!-- Module Position: 'bottom-left'-->
        <div class="center">
            <jdoc:include type="modules" name="bottom"/><!-- Module Position: 'bottom'-->
        </div>
        <jdoc:include type="modules" name="bottom-right"/><!-- Module Position: 'bottom-right'-->
        <jdoc:include type="modules" name="footer" /><!-- Module Position: 'footer'-->
    </div>
</body>

<!-- End-->
</html>