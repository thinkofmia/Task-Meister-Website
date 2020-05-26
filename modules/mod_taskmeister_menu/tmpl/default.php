<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<div class="customtext">
    <!--Displays Header if exists-->
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <!--Displays Text if exists-->
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>