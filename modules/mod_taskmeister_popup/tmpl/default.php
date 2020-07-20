<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<div id="myModal" class="modal" style="display:none;">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <!--Displays Header if exists-->
        <?php if ($displayHeader) : ?>  
            <h2 class="popup-title"><?php echo $displayHeader; ?></h2>
        <?php endif; ?>
    </div>
    <div class="modal-body">
      <!--Displays Text if exists-->
      <?php if ($displayText) : ?>
            <p><?php echo $displayText; ?></p>
        <?php endif; ?>
    </div>
    <!--<div class="modal-footer">
      <h3>Modal Footer</h3>
    </div>-->
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
  setCookie("<?php echo $displayHeader; ?>","123",1);
}

// When the user clicks anywhere outside of the modal, close it
//Disabled on request
//window.onclick = function(event) {
//  if (event.target == modal) {
//    modal.style.display = "none";
//  }
//}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

document.addEventListener('DOMContentLoaded', function(){
  var isshow = getCookie("<?php echo $displayHeader; ?>");
    if (isshow == null || isshow == "") {
        modal.style.display = "block";
    }
    else modal.style.display = "none";
});
</script>