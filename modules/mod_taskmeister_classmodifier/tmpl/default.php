<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<!--Header-->
<h3 style="text-align: center;">Class Modifier</h3>

<!--Form to be redirected to self-->
<form class="classModifierForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
  <!--Slider for Likes Weightage-->
  <span>Likes Weightage: </span><span id="likesValue">100</span>%
  <input oninput="slider_change(this, 'likesValue');" type="range" min="1" max="200" value="<?php echo $likesWeightage; ?>" class="tm_slider" id="likesWeight" name="likesWeight">
  <!--Slider for Deployment Weightage--><br>
  <span>Deployment Weightage: </span><span id="deployedValue">100</span>%
  <input oninput="slider_change(this, 'deployedValue');" type="range" min="1" max="200" value="<?php echo $deploymentWeightage; ?>" class="tm_slider" id="deployedWeight" name="deployedWeight">
  <!--Slider for Touched Before Weightage--><br>
  <span>Touched Before Weightage: </span><span id="touchedValue">100</span>%
  <input oninput="slider_change(this, 'touchedValue');" type="range" min="1" max="200" value="<?php echo $touchedWeightage; ?>" class="tm_slider" id="touchedWeight" name="touchedWeight">
  <!--For Preferences-->
  <!--Slider for Preferred Weightage--><br>
  <span>Preferred Weightage: </span><span id="preferredValue">100</span>%
  <input oninput="slider_change(this, 'preferredValue');" type="range" min="1" max="200" value="<?php echo $preferredWeightage; ?>" class="tm_slider" id="preferredWeight" name="preferredWeight">
  <!--Slider for Against Weightage--><br>
  <span>Against Weightage: </span><span id="unpreferredValue">100</span>%
  <input oninput="slider_change(this, 'unpreferredValue');" type="range" min="1" max="200" value="<?php echo $notPreferredWeightage; ?>" class="tm_slider" id="unpreferredWeight" name="unpreferredWeight">
  <!--Slider for May Try Weightage--><br>
  <span>May Try Weightage: </span><span id="mayTryValue">100</span>%
  <input oninput="slider_change(this, 'mayTryValue');" type="range" min="1" max="200" value="<?php echo $mayTryWeightage; ?>" class="tm_slider" id="mayTryWeight" name="mayTryWeight">
  <!--Bonus Tag Input Field--><br>
  <?php if ($bonusTags && $bonusTags!="[]"):?>
    <span>Bonus Tags: </span><input type="text" name="bonusTags" id="bonusTags" value=<?php echo $bonusTags;?> placeholder = "['Mask','Input Tag Name Here']"><br> 
  <?php else: ?>
    <span>Bonus Tags: </span><input type="text" name="bonusTags" id="bonusTags" placeholder = "['Mask','Input Tag Name Here']"><br> 
  <?php endif; ?>
  <!--Save Button--><br>
  <br><input type="submit" class="btn btn-primary login-button" name="submitClassModifier" value="Save">
</form>

<script>
//Set up list to loop
var loop_list = ["likes","deployed","touched","preferred","unpreferred","mayTry"];

/***
* JavaScript function to change the input value while sliding
 */
function slider_change (slider, input) {
        var output = document.getElementById(input);
        output.innerHTML = slider.value;
    }

//Loop for each item in the list
for (i=0;i<loop_list.length;i++){
    var item = loop_list[i];
    var slider = document.getElementById(item+"Weight");//Get the particular slider
    var output = document.getElementById(item+"Value");//Get the value
    output.innerHTML = slider.value;
}
</script>