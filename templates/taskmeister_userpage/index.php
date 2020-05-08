<!-- This prevents naughty peeps from looking at code  -->
<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
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
</head>

<!-- Contain the website code which controls the layout-->
<body>
    <div id="topRow">
        <a class = "left" href = "<?php echo $this->baseurl; ?>/index.php/homepage"><!-- Set clickable logo-->
            <img class = "logo"  src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/TaskMeisterLogo.JPG" alt="Task Meister Logo" class="logo" />
            <jdoc:include type="modules" name="top-left"/><!-- Module Position: 'top-left'-->
        </a>
        <div class = "columnCenter">
            <jdoc:include type="modules" name="top"/><!-- Module Position: 'top'-->
        </div>
        <div id = "topRight">
            <jdoc:include type="modules" name="top-right"/><!-- Module Position: 'top-right'-->
            <a class= "right" href="<?php echo $this->baseurl; ?>/index.php/your-profile"><!--User-->
                <img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/accountIcon.jpg" class = "accountIcon" alt="User Page" title="Click here to go to your user page. "/>            
            </a>
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
    <div id="bottomRow">
        <jdoc:include type="modules" name="bottom-left"/><!-- Module Position: 'bottom-left'-->
        <div class="center">
            <jdoc:include type="modules" name="bottom"/><!-- Module Position: 'bottom'-->
        </div>
        <jdoc:include type="modules" name="bottom-right"/><!-- Module Position: 'bottom-right'-->
    </div>

<jdoc:include type="modules" name="footer" /><!-- Module Position: 'footer'-->
</body>

<!-- End-->
</html>