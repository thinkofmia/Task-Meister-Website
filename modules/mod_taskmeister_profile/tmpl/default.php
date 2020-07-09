<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<br>
<div class="customtext">
    <!--Displays Header if exists-->
    <?php if ($displayHeader) : ?>
        <h3 class="center"><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <!--Displays Text if exists-->
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>

<div>
    Prefer only videos?
    <label class="switch">
        <input class="tm_input" type="checkbox" name="videoPreferred" value=1>
        <span class="slider round"></span>
    </label>
    <input type="submit" class="btn btn-primary login-button" name="saveProfile" value="Save">
</div>
