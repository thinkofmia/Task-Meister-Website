<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<h3 style="text-align: center;">Class Modifier</h3>

<form class="classModifierForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
  <!--For Buttons-->
  <span>Likes Weightage: </span><span id="likesValue">100</span>%
  <input oninput="slider_change(this, 'likesValue');" type="range" min="1" max="200" value="<?php echo $likesWeightage; ?>" class="tm_slider" id="likesWeight" name="likesWeight">
  <span>Deployment Weightage: </span><span id="deployedValue">100</span>%
  <input oninput="slider_change(this, 'deployedValue');" type="range" min="1" max="200" value="<?php echo $deploymentWeightage; ?>" class="tm_slider" id="deployedWeight" name="deployedWeight">
  <span>Touched Before Weightage: </span><span id="touchedValue">100</span>%
  <input oninput="slider_change(this, 'touchedValue');" type="range" min="1" max="200" value="<?php echo $touchedWeightage; ?>" class="tm_slider" id="touchedWeight" name="touchedWeight">
  <!--For Preferences-->
  <span>Preferred Weightage: </span><span id="preferredValue">100</span>%
  <input oninput="slider_change(this, 'preferredValue');" type="range" min="1" max="200" value="<?php echo $preferredWeightage; ?>" class="tm_slider" id="preferredWeight" name="preferredWeight">

  <span>Don't Prefer Weightage: </span><span id="unpreferredValue">100</span>%
  <input oninput="slider_change(this, 'unpreferredValue');" type="range" min="1" max="200" value="<?php echo $notPreferredWeightage; ?>" class="tm_slider" id="unpreferredWeight" name="unpreferredWeight">
  <span>May Try Weightage: </span><span id="mayTryValue">100</span>%
  <input oninput="slider_change(this, 'mayTryValue');" type="range" min="1" max="200" value="<?php echo $mayTryWeightage; ?>" class="tm_slider" id="mayTryWeight" name="mayTryWeight">

  <span title="Toggling this will link your preferences to the class. ">Toggle Preference Linkage</span>
  <label class="switch">
    <?php if ($preferenceLinked): ?>
      <input class="tm_input" type="checkbox" name="togglePreferenceLinkage" checked=checked value=1>
    <?php else: ?>
      <input class="tm_input" type="checkbox" name="togglePreferenceLinkage" value=1>
    <?php endif; ?>
    <span class="slider round"></span>
  </label>
  <br><input type="submit" name="submitClassModifier" value="Save">
</form>
<script>
var loop_list = ["likes","deployed","touched","preferred","unpreferred","mayTry"];
function slider_change (slider, input) {
        var output = document.getElementById(input);
        output.innerHTML = slider.value;
    }

for (i=0;i<loop_list.length;i++){
    var item = loop_list[i];
    var slider = document.getElementById(item+"Weight");
    var output = document.getElementById(item+"Value");
    output.innerHTML = slider.value;
}
</script>