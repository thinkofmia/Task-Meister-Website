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
    <div id = "topWrapper"><!-- Top Wrapper -->
        <div id = "topRight">
            <a href = "homePage">X</a>
            </div>
    </div>
<div id="contentArea">
    <jdoc:include type="modules" name="login" />
</div>

<jdoc:include type="modules" name="bottom" /><!-- Module Position: 'bottom'-->
<jdoc:include type="modules" name="footer" /><!-- Module Position: 'footer'-->
</body>

<!-- End-->
</html>