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
<div class="search">
    <form action="<?php echo JUri::base(); ?>index.php/search">
        <input type="text" placeholder="Search.." name="keyword" value = "<?php echo $_REQUEST["keyword"]; ?>">
        <button type="submit" name="queryKeyword" value="keyword" >🔍</button>
    </form>
</div><br>