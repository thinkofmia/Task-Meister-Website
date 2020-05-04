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
        <div id = "topLeft">
            <a href = "/taskmeister/index.php"><!-- Put clickable logo below-->
                <img  src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/TaskMeisterLogo.JPG" alt="Task Meister Logo" class="logo" />
                <jdoc:include type="modules" name="left" /> <!-- Module Position: 'left'-->
            </a>
        </div>
        <div id = "topRight">
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
            <p>THE MOE LEARNING TASK GENERATOR</p>
        </div>
    </div>

    <div id = "recommendedWrapper" class = "wrapper"><!-- Display wrapper for RECOMMENDED-->
        <h1>RECOMMENDED</h1>
        <p>Used by 1200 teachers this month and  average rating of 4.5 ⭐⭐⭐⭐ stars and in your school's Math Scheme of Work</p>
    </div>
    <jdoc:include type="modules" name="Recommended" /><!-- Module Position: 'Recommended', insert articles here-->

    <div id = "likedWrapper" class = "wrapper"><!-- Display wrapper for Liked List-->
        <h1>MY LIKED LIST</h1>
        <p>What you have clicked it to add to your Liked List</p>
    </div>

    <jdoc:include type="modules" name="LikedList" /><!-- Module Position: 'LikedList', insert articles here-->
    <div id = "popularWrapper" class = "wrapper"><!-- Display wrapper for Popular-->
        <h1>POPULAR</h1>
        <p>Lessons with the highest viewed rates since forever</p>
    </div>
    <jdoc:include type="modules" name="Popular" /><!-- Module Position: 'Popular', insert articles here-->

    <div id = "trendingWrapper" class = "wrapper"><!-- Display wrapper for Trending-->
        <h1>Trending</h1>
        <p>Most viewed lessons in the past week</p>
    </div>
    <jdoc:include type="modules" name="Trending" /><!-- Module Position: 'Trending', insert articles here-->

    <div id = "deployedBeforeWrapper" class = "wrapper"><!-- Display wrapper for Deployed Before-->
        <h1>STUFF YOU'VE DEPLOYED BEFORE</h1>
        <p>Ready to deploy them again?</p>
    </div>
    <jdoc:include type="modules" name="DeployedBefore" /><!-- Module Position: 'DeployedBefore', insert articles here-->

    <div id = "effortlessWrapper" class = "wrapper"><!-- Display wrapper for Effortless-->
        <h1>EFFORTLESS</h1>
        <p>
            No time to prep?<br>
            Try out these plug and play materials
        </p>
    </div>
    <jdoc:include type="modules" name="Effortless" /><!-- Module Position: 'Effortless', insert articles here-->

    <div id = "physicalManipulativeWrapper" class = "wrapper"><!-- Display wrapper for Physical Manipulative-->
        <h1>PHYSICAL MANIPULATIVE</h1>
        <p>Give your students a chance use their hands!</p>
    </div>
    <jdoc:include type="modules" name="PhysicalManipulative" /><!-- Module Position: 'PhysicalManipulative', insert articles here-->

    <div id = "funWrapper" class = "wrapper"><!-- Display wrapper for Fun-->
        <h1>FUN</h1>
        <p>Give your students a chance have fun through digital games</p>
    </div>
    <jdoc:include type="modules" name="Fun" /><!-- Module Position: 'Fun', insert articles here-->

    <div id = "outOfOrdinaryWrapper" class = "wrapper"><!-- Display wrapper for Out of the Ordinary-->
        <h1>OUT-OF-THE-ORDINARY</h1>
        <p>Not the usual practice of most Math teachers but you never know if it may work for your kiddos</p>
    </div>
    <jdoc:include type="modules" name="OutOfOrdinary" /><!-- Module Position: 'Out of Ordinary', insert articles here-->

    <div id = "scaffoldWrapper" class = "wrapper"><!-- Display wrapper for Scaffold-->
        <h1>SCAFFOLD FOR LOW ABILITY STUDENTS</h1>
        <p>no way the students cannot substitute and solve quadratic equations now!</p>
    </div>
    <jdoc:include type="modules" name="Scaffold" /><!-- Module Position: 'Scaffold', insert articles here-->

    <div id = "AIWrapper" class = "wrapper"><!-- Display wrapper for AI-->
        <h1>EXPERT /ARTIFICIAL INTELLIGENT TUTOR</h1>
        <p>Expert tutor to guide students how to solve quadratic equations</p>
    </div>
    <jdoc:include type="modules" name="AI" /><!-- Module Position: 'AI', insert articles here-->
</div>


<jdoc:include type="modules" name="bottom" /><!-- Module Position: 'bottom'-->
<jdoc:include type="modules" name="footer" /><!-- Module Position: 'footer'-->
</body>

<!-- End-->
</html>