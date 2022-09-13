<?php
/*
  User defined settings
*/
include "app/settings/settings.php";

/*if ($debug["testing"]) {
  // Used for testing, do not use this in production
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ERROR | E_PARSE | E_NOTICE);
  ?>
    <script>
				sniffleAdd('Notice', 'This website is currently in a testing state, bugs may occur', 'var(--red)', 'assets/icons/cross.svg');
		</script>
  <?php
}*/

ini_set('post_max_size', '20M');
ini_set('upload_max_filesize', '20M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE | E_NOTICE);
?>
  <script>
      sniffleAdd('Notice', 'This website is currently in a testing state, bugs may occur', 'var(--red)', 'assets/icons/cross.svg');
  </script>
<?php

if (is_file("index.php")) {
  $root_dir = "";
} else {
  $root_dir = "../";
}

/*
  Connect to the server
*/
include "app/server/conn.php";
include "app/server/secrete.php";

/*
  Classes
*/
require_once 'app/app.php';

?>
<script>
  /*
    Gets Querys from the URL the user is at
    Used by Sniffle to display notificaions
  */
  const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
  });
</script>

<!--
  Used by Sniffle to add Notifications
  Div can be displayed all time as it has no width or height initself
-->
<div id='sniffle' class='sniffle'></div>

<!--
  Div for information flyouts
  Controlled by Flyout.js
-->
<div id='flyoutDim' class='flyout-dim'></div>
<div id='flyoutRoot' class='flyout'>
  <p id='flyoutHeader' class='flyout-header'>Header</p>
  <br>
  <p id='flyoutDescription' class='flyout-description'>Description</p>
  <br>
  <div id='flyoutActionbox' class='flyout-actionbox'></div>
  <button onclick='flyoutClose()' class='btn btn-neutral'>Close</button>
</div>

<!--
  Back to top button
  Used to quickly get back up to the top of the page,
  At some point will be removed as the UI metures and
  everything can always be accessed
-->
<a id="back-to-top" href="#">
  <img src="<?php echo $root_dir; ?>assets/icons/caret-up.svg">
</a>
<script>
  button = document.getElementById("back-to-top");

  window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 20) {
      button.style.right = "1rem";
    } else {
      button.style.right = "-2.5rem";
    }
  }
</script>

<!--
  Required so main objects are centered when NAV
  is in mobile view
-->
<div class="nav-mobile"></div>