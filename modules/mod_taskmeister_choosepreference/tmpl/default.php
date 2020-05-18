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

<div class="preferenceList">
    <?php foreach ($tagList as $key => $value) : ?>
        <div class="preferenceBox">
            <img src="/taskmeisterx/templates/taskmeistertemplate-userpage/images/accountIcon.jpg" width="100%" height="100%" />
            <p><?php echo json_encode($key); ?></p>
        </div>
    <?php endforeach; ?>
</div>