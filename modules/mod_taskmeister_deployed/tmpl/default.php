<?php 
// No direct access
defined('_JEXEC') or die; 
?>

<!--HTML display for the custom text-->
<div class="customtext">
    <!--Show custom header if exists-->
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <!--Show custom text if exists-->
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>

<?php if ($articleID) : ?>
    <!--Display for the deployment box/icon-->
    <div id="deployedBox">
        <!--If the user is a guest, set the onclick button to login first -->
        <?php if ($userID==0) : ?>
            <button name= "fakeButton" id= "deployedButton" onclick="alert('Login First!!')" title="Requires login to click">👨‍💻 <?php echo $deployedSize; ?></button> 
        <!--Else if already logined, set post button for the deployment -->
        <?php else : ?>
            <form method="post">
                <button name= "dButton" id= "deployedButton" title="Total # of Deployment: <?php echo $deployedSize; ?>">👨‍💻 <?php echo $deployedSize; ?></button> 
            </form>
        <?php endif; ?>
    </div>
<?php endif; ?>
