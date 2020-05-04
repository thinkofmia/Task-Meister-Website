<!-- This prevents naughty peeps from looking at code  -->
<?php defined( '_JEXEC' ) or die( 'Restricted access' );

//Variables - Edit this to change the respective names
$rightBannerText = "THE MOE LEARNING TASK GENERATOR"; //Set the right banner text for the animated BG

$noHeaders = 11; //Set the total number of headers to display

////Set the header's names and text
$header1 = "RECOMMENDED";
$summary1 = "Used by 1200 teachers this month and  average rating of 4.5 ⭐⭐⭐⭐ stars and in your school's Math Scheme of Work";

$header2 = "MY LIKED LIST";
$summary2 = "What you have clicked it to add to your Liked List";

$header3 = "POPULAR";
$summary3 = "Lessons with the highest viewed rates since forever";

$header4 = "Trending";
$summary4 = "Most viewed lessons in the past week";

$header5 = "STUFF YOU'VE DEPLOYED BEFORE";
$summary5 = "Ready to deploy them again?";

$header6 = "EFFORTLESS";
$summary6 = "No time to prep? Try out these plug and play materials ";

$header7 = "PHYSICAL MANIPULATIVE";
$summary7 = "Give your students a chance use their hands!";

$header8 = "FUN";
$summary8 = "Give your students a chance have fun through digital games";

$header9 = "OUT-OF-THE-ORDINARY";
$summary9 = "Not the usual practice of most Math teachers but you never know if it may work for your kiddos";

$header10 = "SCAFFOLD FOR LOW ABILITY STUDENTS";
$summary10 = "No way the students cannot substitute and solve quadratic equations now! ";

$header11 = "EXPERT /ARTIFICIAL INTELLIGENT TUTOR";
$summary11 = "Expert tutor to guide students how to solve quadratic equations";
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

</head>

<!-- Contain the website code which controls the layout-->
<body>
    <div id = "topWrapper"><!-- Top Wrapper -->
        <div id = "topLeft">
            <a href = "/taskmeister/index.php"><!-- Put clickable logo below-->
                <img  src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/TaskMeisterLogo.JPG" alt="Task Meister Logo" class="logo" />
                <jdoc:include type="modules" name="left" /> <!-- Module Position: 'left'-->
            </a>
        </div>
        <div id = "topRight">
            <a href = "/taskmeister/index.php">Browse </a>
            <jdoc:include type="modules" name="right" /> <!-- Module Position: 'right'-->
        </div>
    </div>
<jdoc:include type="modules" name="top" /> <!-- Module Position: 'top'-->
<jdoc:include type="modules" name="breadcrumb" /> <!-- Module Position: 'breadcrumb'-->
<div id="contentArea">
    <div id="centerWrapper"><!-- Center Content: Includes animated background and banner-->
        <!-- Animated Background-->
        <img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/animatedPreview.gif" alt="fake background" class="background" />
        <!-- Right Banner-->
        <div id="rightBanner">
            <p><?php echo $rightBannerText; ?></p>
        </div>
    </div>

    <div class = "wrapper"><!-- Display wrapper for Header 1-->
        <h1><?php echo $header1; ?></h1>
        <p><?php echo $summary1; ?></p>
    </div>
    <jdoc:include type="modules" name="Recommended" /><!-- Module Position: 'Recommended', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 2-->
        <h1><?php echo $header2; ?></h1>
        <p><?php echo $summary2; ?></p>
    </div>
    <jdoc:include type="modules" name="LikedList" /><!-- Module Position: 'LikedList', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 3-->
        <h1><?php echo $header3; ?></h1>
        <p><?php echo $summary3; ?></p>
    </div>
    <jdoc:include type="modules" name="Popular" /><!-- Module Position: 'Popular', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 4-->
        <h1><?php echo $header4; ?></h1>
        <p><?php echo $summary4; ?></p>
    </div>
    <jdoc:include type="modules" name="Trending" /><!-- Module Position: 'Trending', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 5-->
        <h1><?php echo $header5; ?></h1>
        <p><?php echo $summary5; ?></p>
    </div>
    <jdoc:include type="modules" name="DeployedBefore" /><!-- Module Position: 'DeployedBefore', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 6-->
        <h1><?php echo $header6; ?></h1>
        <p><?php echo $summary6; ?></p>
    </div>
    <jdoc:include type="modules" name="Effortless" /><!-- Module Position: 'Effortless', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 7-->
        <h1><?php echo $header7; ?></h1>
        <p><?php echo $summary7; ?></p>
    </div>
    <jdoc:include type="modules" name="PhysicalManipulative" /><!-- Module Position: 'PhysicalManipulative', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 8-->
        <h1><?php echo $header8; ?></h1>
        <p><?php echo $summary8; ?></p>
    </div>
    <jdoc:include type="modules" name="Fun" /><!-- Module Position: 'Fun', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 9-->
        <h1><?php echo $header9; ?></h1>
        <p><?php echo $summary9; ?></p>
    </div>
    <jdoc:include type="modules" name="OutOfOrdinary" /><!-- Module Position: 'Out of Ordinary', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 10-->
        <h1><?php echo $header10; ?></h1>
        <p><?php echo $summary10; ?></p>
    </div>
    <jdoc:include type="modules" name="Scaffold" /><!-- Module Position: 'Scaffold', insert articles here-->

    <div class = "wrapper"><!-- Display wrapper for Header 11-->
        <h1><?php echo $header11; ?></h1>
        <p><?php echo $summary11; ?></p>
    </div>
    <jdoc:include type="modules" name="AI" /><!-- Module Position: 'AI', insert articles here-->
</div>


<jdoc:include type="modules" name="bottom" /><!-- Module Position: 'bottom'-->
<jdoc:include type="modules" name="footer" /><!-- Module Position: 'footer'-->
</body>

<!-- End-->
</html>