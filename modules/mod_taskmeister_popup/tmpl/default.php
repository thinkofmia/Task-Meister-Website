<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
?>

<div id="myModal" class="modal">
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
}

// When the user clicks anywhere outside of the modal, close it
//Disabled on request
//window.onclick = function(event) {
//  if (event.target == modal) {
//    modal.style.display = "none";
//  }
//}
</script>