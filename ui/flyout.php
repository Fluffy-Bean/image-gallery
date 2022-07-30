<?php
function flyout($flyout_header, $flyout_description, $flyout_action) {
  // Used for background dimming
  echo "<div class='flyout-dim'></div>";
  // Div Start
  echo "<div class='flyout flex-down default-window between'>";

  // Header for the flyout, must be included
  if (isset($flyout_header) && !empty($flyout_header)) {
    echo "<h2 class='space-bottom'>".$flyout_header."</h2>";
  }
  // Flyout content, must be included!!!!
  if (isset($flyout_content) && !empty($flyout_content)) {
    echo "<p class='space-bottom'>".$flyout_content."</p>";
  }
  // Flyout button, not required so must need more information when added
  if (isset($flyout_action) && !empty($flyout_action)) {
    echo $flyout_action;
  }

  // Exit button + Div End
  echo "<button class='btn alert-default space-top flyout-close'>Cancel</button>
  </div>";
}
?>
