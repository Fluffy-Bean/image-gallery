<?php
/*
  User defined settings
*/
require_once dirname(__DIR__)."/app/settings/settings.php";

ini_set('post_max_size', $user_settings['upload_max']);
ini_set('upload_max_filesize', ($user_settings['upload_max'] + 1));

if ($user_settings['is_testing']) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ERROR | E_PARSE | E_NOTICE);
  ?>
    <script>
				sniffleAdd('Notice', 'This website is currently in a testing state, bugs may occur', 'var(--red)', 'assets/icons/cross.svg');
		</script>
  <?php
}

/*
  Connect to the server
*/
require_once dirname(__DIR__)."/app/server/conn.php";
require_once dirname(__DIR__)."/app/server/secrete.php";

/*
  Classes
*/
require_once dirname(__DIR__)."/app/app.php";

?>
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