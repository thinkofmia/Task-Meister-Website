<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<div class="customtext">
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>
<div id="thumbsBox">
    <button id= "thumbsUp" type="button" onclick="alert('You gave a thumbs up!')">👍</button>
    <button id = "thumbsDown" type="button" onclick="alert('You gave a thumbs down!')">👎</button>
</div>