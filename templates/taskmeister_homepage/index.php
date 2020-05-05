<!-- This prevents naughty peeps from looking at code  -->
<?php defined( '_JEXEC' ) or die( 'Restricted access' );

//Variables - Edit this to change the respective names
$rightBannerText = "THE MOE LEARNING TASK GENERATOR"; //Set the right banner text for the animated BG

$animatedImg = "animatedPreview.gif";//Sets animated Image

$noHeaders = 11; //Set the total number of headers to display

////Set the header's names and text
$header = [];
$header[0] = "RECOMMENDED";
$summary[0] = "Used by 1200 teachers this month and  average rating of 4.5 ⭐⭐⭐⭐ stars and in your school's Math Scheme of Work";

$header[1] = "MY LIKED LIST";
$summary[1] = "What you have clicked it to add to your Liked List";

$header[2] = "POPULAR";
$summary[2] = "Lessons with the highest viewed rates since forever";

$header[3] = "Trending";
$summary[3] = "Most viewed lessons in the past week";

$header[4] = "STUFF YOU'VE DEPLOYED BEFORE";
$summary[4] = "Ready to deploy them again?";

$header[5] = "EFFORTLESS";
$summary[5] = "No time to prep? Try out these plug and play materials ";

$header[6] = "PHYSICAL MANIPULATIVE";
$summary[6] = "Give your students a chance use their hands!";

$header[7] = "FUN";
$summary[7] = "Give your students a chance have fun through digital games";

$header[8] = "OUT-OF-THE-ORDINARY";
$summary[8] = "Not the usual practice of most Math teachers but you never know if it may work for your kiddos";

$header[9] = "SCAFFOLD FOR LOW ABILITY STUDENTS";
$summary[9] = "No way the students cannot substitute and solve quadratic equations now! ";

$header[10] = "EXPERT /ARTIFICIAL INTELLIGENT TUTOR";
$summary[10] = "Expert tutor to guide students how to solve quadratic equations";
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
            <a href = "/taskmeister/index.php/userPage">Browse</a>
            <a href="/taskmeister/index.php/loginPage"><!--Login-->
                <img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/accountIcon.jpg" class = "accountIcon" alt="Login Account" title="Click here to Login. "/>
                Account
            </a>
            </div>
    </div>
<jdoc:include type="modules" name="top" /> <!-- Module Position: 'top'-->
<jdoc:include type="modules" name="breadcrumb" /> <!-- Module Position: 'breadcrumb'-->
<div id="contentArea">
    <div id="centerWrapper"><!-- Center Content: Includes animated background and banner-->
        <!-- Animated Background-->
        <img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/<?php echo $animatedImg; ?>" alt="fake background" class="background" />
        <!-- Right Banner-->
        <div id="rightBanner">
            <p><?php echo $rightBannerText; ?></p>
        </div>
    </div>

    <!--Loop based on the number of wrappers to display-->
    <?php for ($x = 0; $x < 11; $x++) {
    echo "<div class = 'wrapper'><h1>".$header[$x]."</h1><p>".$summary[$x]."</p></div>"; //Display header and summary text
            $module = &JModuleHelper::getModule('mod_articles_scrollbar'); //Find the articles module
            $articles = JModuleHelper::renderModule($module); //Render the article module
            echo $articles; //Display module
    } ?> 

<jdoc:include type="modules" name="bottom" /><!-- Module Position: 'bottom'-->
<jdoc:include type="modules" name="footer" /><!-- Module Position: 'footer'-->
</body>

<!-- End-->
</html>