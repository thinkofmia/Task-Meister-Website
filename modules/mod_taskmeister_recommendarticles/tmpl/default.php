<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<!-- Display out custom header/text-->
<div class="customtext">
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>

<?php 
echo "Selected - ". $params->get('filter').": ". $resultsSelected ."<br>";

echo "Selected - Article Contents: ". $recommendedContents ."<br>";
?>