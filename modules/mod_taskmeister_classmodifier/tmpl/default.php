<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<h3>Class Modifier</h3>
<span>Likes Weightage: </span><span id="likesValue">100</span>%
<input oninput="slider_change(this, 'likesValue');" type="range" min="1" max="200" value="100" class="tm_slider" id="likesWeight">
<span>Deployment Weightage: </span><span id="deployedValue">100</span>%
<input oninput="slider_change(this, 'deployedValue');" type="range" min="1" max="200" value="100" class="tm_slider" id="deployedWeight">
<span>Touched Before Weightage: </span><span id="touchedValue">100</span>%
<input oninput="slider_change(this, 'touchedValue');" type="range" min="1" max="200" value="100" class="tm_slider" id="touchedWeight">


<script>
var loop_list = ["likes","deployed","touched"];
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