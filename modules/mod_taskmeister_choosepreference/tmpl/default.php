<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<script type="text/javascript">
    userPreferenceList_JS = [];
    <?php foreach ($tagList as $key => $value) : ?>
        userPreferenceList_JS.push("<?php echo $key; ?>");
    <?php endforeach; ?>
</script>

<div class="customtext preferenceOptions">
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
</div>

<div class="preferenceList">
    <?php foreach ($tagList as $key => $value) : ?>
        <div class="preferenceBox" id="<?php echo $key;?>">
            <img src="/taskmeisterx/templates/taskmeistertemplate-userpage/images/accountIcon.jpg" width="100%" height="100%" />
            <p><?php echo $key; ?>: <?php echo $value; ?> uses</p>
        </div>
    <?php endforeach; ?>
</div>