<!-- This prevents naughty peeps from looking at code  -->
<?php defined( '_JEXEC' ) or die( 'Restricted access' );

//Database code
use Joomla\CMS\Factory;
//Set database variable
$db = Factory::getDbo();
$me = Factory::getUser();
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
    <ul id="navbar">
        <li><a class = "logolink" href="<?php echo $this->baseurl; ?>/index.php/home"><!-- Set clickable logo-->
            <img class = "logo"  src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/TaskMeisterLogo.JPG" alt="Task Meister Logo" class="logo" />
        </a></li>
        <li class="navitem"><a href="<?php echo $this->baseurl; ?>/index.php/choosepreference"><!--Choose Preference-->
                <p>Preference</p>            
            </a>
        </li>
        <li class="navitem"><a href="<?php echo $this->baseurl; ?>/index.php/chooseclass"><!--Choose Class-->
                <p>Class</p>            
            </a>
        </li>
        <li class="navitem"><a href="<?php echo $this->baseurl; ?>/index.php/mylist"><!--My List-->
                <p>List</p>            
            </a>
        </li>
        <li class="navitem"><a href="<?php echo $this->baseurl; ?>/index.php/resources"><!--Subjects-->
                <p>Subjects</p>            
            </a>
        </li>
        <li class="navitem"><a href="<?php echo $this->baseurl; ?>/index.php/schoollevels"><!--My List-->
                <p>Levels</p>            
            </a>
        </li>
        <li class="navitem"><a href="<?php echo $this->baseurl; ?>/index.php/interactive-resources"><!--My List-->
                <p>Archives</p>            
            </a>
        </li>
        <li>
            
        </li>
        <li>
            <a class= "loginIcon" href="<?php echo $this->baseurl; ?>/index.php/login"><!--User-->
                <p><?php echo $username; ?></p>
                <!--<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/accountIcon.jpg" class = "accountIcon" alt="User Page" title="Click here to go to your user page. "/>-->            
            </a> 
        </li>
    </ul>
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