<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<h3>Class Modifier</h3>
<span>Likes Weightage: </span><span id="likesValue">100</span>%
<input oninput="slider_input(this,document.getElementById('likesValue'));" type="range" min="1" max="200" value="100" class="tm_slider" id="likesWeight">

<script>
var likes_slider = document.getElementById("likesWeight");
var likes_output = document.getElementById("likesValue");
likes_output.innerHTML = likes_slider.value;

slider_input = function(slider, output) {
  output.innerHTML = slider.value;
}
</script>