<div class="flyout-dim">
</div>
<div class="flyout flex-down default-window between">
  <?php
  if (isset($flyout_content) && !empty($flyout_content)) {
    echo $flyout_content;
  } else {
    $flyout_content = "<h2 class='space-bottom-large'>Dialog</h2> <p>This is a very cool flyout that does nothing but be annoying right now! Sowwy for taking up your screenspace with this box!</p class='space-bottom-large'> <p>This is just being tested as a better alternative to some things, sowwy!</p> <a class='btn alert-low'>Accept the world is cruel</a>";
    echo $flyout_content;
  }
  ?>
  <a class="btn alert-default space-top-small flyout-close">Cancel</a>
</div>
<script src="scripts/flyout.js"></script>
