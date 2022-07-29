<div class="flyout-dim">
</div>
<div class="flyout flex-down default-window between">
  <?php
  // Header for the flyout, must be included
  if (isset($flyout_header) && !empty($flyout_header)) {
    echo "<h2 class='space-bottom'>".$flyout_header."</h2>";
  } else {
    echo "<h2 class='space-bottom'>Missing Header</h2>";
  }

  // Flyout content, must be included!!!!
  if (isset($flyout_content) && !empty($flyout_content)) {
    echo "<p class='space-bottom'>".$flyout_content."</p>";
  } else {
    echo "<p class='space-bottom'>This is just being tested as a better alternative to some things, sowwy!</p>";
  }

  // Flyout button, not required so must need more information when added
  if (isset($flyout_interaction) && !empty($flyout_interaction)) {
    echo $flyout_interaction;
  }
  ?>
  <a class="btn alert-default space-top flyout-close">Cancel</a>
</div>
<script src="scripts/flyout.js"></script>
