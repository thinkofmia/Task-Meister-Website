<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<!--Display for custom text and header-->
<div class="customtext">
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>
<?php if ($articleID) : ?>
    <div id="thumbsBox">
        <!--If user is a guest, create fake buttons with alert messages-->
        <?php if ($userID==0) : ?>
            <button name= "fakeButton1" id= "thumbsUp" class="action-button-unset" onclick="alert('Login First!!')" title ="Requires login to be enabled" >👍 <?php echo $noOfLikes; ?></button>
            <button name= "fakeButton2" id = "thumbsDown" class="action-button-unset" onclick="alert('Login First!!')" title ="Requires login to be enabled" >👎 <?php echo $noOfDislikes; ?></button>  
        <!--If user has logined, create buttons with actual functions-->
        <?php else : ?>
            <form method="post">
                <?php if ($hasLiked) : ?>
                    <button name= "tUp" class="action-button-set" id= "thumbsUp" title ="Total # of likes: <?php echo $noOfLikes; ?>">👍 <?php echo $noOfLikes; ?></button>
                <?php else : ?>
                    <button name= "tUp" class="action-button-unset" id= "thumbsUp" title ="Total # of likes: <?php echo $noOfLikes; ?>">👍 <?php echo $noOfLikes; ?></button>
                <?php endif; ?>
                <?php if ($hasDisliked) : ?>
                    <button name= "tDown" class="action-button-set" id = "thumbsDown" title ="Total # of dislikes: <?php echo $noOfDislikes; ?>">👎 <?php echo $noOfDislikes; ?></button>  
                <?php else : ?>
                    <button name= "tDown" class="action-button-unset" id = "thumbsDown" title ="Total # of dislikes: <?php echo $noOfDislikes; ?>">👎 <?php echo $noOfDislikes; ?></button>   
                <?php endif; ?>                   
            </form>
        <?php endif; ?>
    </div>
<?php endif; ?>
