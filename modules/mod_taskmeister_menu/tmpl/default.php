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

<div id="customMenu">
    <!--Displays Label 1 if exists-->
    <?php if ($alias1) : ?>
        <a href='/<?php echo $baseSite; ?>/index.php/<?php echo $alias1; ?>'><?php echo $label1; ?></a>
    <?php endif; ?>
    <!--Displays Label 2 if exists-->
    <?php if ($alias2) : ?>
        <a href='/<?php echo $baseSite; ?>/index.php/<?php echo $alias2; ?>'><?php echo $label2; ?></a>
    <?php endif; ?>   
    <!--Displays Label 3 if exists-->
    <?php if ($alias3) : ?>
        <a href='/<?php echo $baseSite; ?>/index.php/<?php echo $alias3; ?>'><?php echo $label3; ?></a>
    <?php endif; ?>   
    <!--Displays Label 4 if exists-->
    <?php if ($alias4) : ?>
        <a href='/<?php echo $baseSite; ?>/index.php/<?php echo $alias4; ?>'><?php echo $label4; ?></a>
    <?php endif; ?>   
    <!--Displays Label 3 if exists-->
    <?php if ($alias5) : ?>
        <a href='/<?php echo $baseSite; ?>/index.php/<?php echo $alias5; ?>'><?php echo $label5; ?></a>
    <?php endif; ?>  
</div>