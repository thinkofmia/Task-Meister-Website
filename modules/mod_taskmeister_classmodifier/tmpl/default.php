<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<h3 style="text-align: center;">Class Modifier</h3>
<!--For Buttons-->
<span>Likes Weightage: </span><span id="likesValue">100</span>%
<input oninput="slider_change(this, 'likesValue');" type="range" min="1" max="200" value="100" class="tm_slider" id="likesWeight">
<span>Deployment Weightage: </span><span id="deployedValue">100</span>%
<input oninput="slider_change(this, 'deployedValue');" type="range" min="1" max="200" value="100" class="tm_slider" id="deployedWeight">
<span>Touched Before Weightage: </span><span id="touchedValue">100</span>%
<input oninput="slider_change(this, 'touchedValue');" type="range" min="1" max="200" value="100" class="tm_slider" id="touchedWeight">
<!--For Preferences-->
<span>Preferred Weightage: </span><span id="preferredValue">100</span>%
<input oninput="slider_change(this, 'preferredValue');" type="range" min="1" max="200" value="100" class="tm_slider" id="preferredWeight">

<span>Don't Prefer Weightage: </span><span id="unpreferredValue">100</span>%
<input oninput="slider_change(this, 'unpreferredValue');" type="range" min="1" max="200" value="100" class="tm_slider" id="unpreferredWeight">
<span>May Try Weightage: </span><span id="mayTryValue">100</span>%
<input oninput="slider_change(this, 'mayTryValue');" type="range" min="1" max="200" value="100" class="tm_slider" id="mayTryWeight">

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