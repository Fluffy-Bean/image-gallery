<?php
/*
  Start session

  This is important as most pages use the PHP session and will complain if its not possible to access.
*/
session_start();


/*
  Check which directory user is in

  I don't know if theres a better way of doing this? If there is please let me know
*/
if (is_file("index.php")) {
  $root_dir = "";
} else {
  $root_dir = "../";
}


/*
  Connect to the server
*/
include $root_dir."app/server/conn.php";

/*
  Add functions
*/
include $root_dir."app/account/get_info.php";
include $root_dir."app/account/is_admin.php";
include $root_dir."app/account/login_status.php";

include $root_dir."app/format/string_to_tags.php";

include $root_dir."app/image/get_image_info.php";
include $root_dir."app/image/image_privilage.php";

include $root_dir."app/server/secrete.php";
?>
<script>
  /*
    This is a little secrete for the ones who care, nothing important
  */
  console.log(" . . /|/| . . . . . . . \n .. /0 0 \\ . . . . . .. \n (III% . \\________, . . \n .. .\\_, .%###%/ \\'\\,.. \n . . . .||#####| |'\\ \\. \n .. . . ||. . .|/. .\\V. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . cc/ . .cc/ . . .");

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
<div id='flyoutRoot' class='flyout flex-down'>
  <p id='flyoutHeader' class='flyout-header space-bottom'>Header</p>
  <p id='flyoutDescription' class='flyout-description space-bottom'>Description</p>
  <div id='flyoutActionbox' class='flyout-actionbox space-bottom-small'></div>
  <button onclick='flyoutClose()' class='btn alert-default'>Close</button>
</div>

<!--
  Back to top button
  Used to quickly get back up to the top of the page,
  At some point will be removed as the UI metures and
  everything can always be accessed
-->
<a id="back-to-top" href="#">
  <img src="assets/icons/caret-up.svg">
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