<?php

use App\Account;

$loggedin = new Account();
?>

<nav class="nav-root">
	<div class="nav-name">
		<?php
			if (isset($user_settings['logo']) && $user_settings['logo'] != "") {
				echo "<img src='usr/".$user_settings['logo']."' alt='Logo'>";
			}
		?>
		<p><?php echo $user_settings['website_name']; ?></p>
	</div>
	<div class="nav-links">
		<a class='btn' href='index.php'><img class='svg' src='assets/icons/house.svg'><span class='nav-hide'>Home</span></a>
		<hr>
		<a class='btn' href='group.php'><img class='svg' src='assets/icons/package.svg'><span class='nav-hide'>Groups</span></a>
		<hr>
		<?php
		if ($_SESSION["loggedin"]) {
			$profile_info = $user_info->get_user_info($conn, $_SESSION['id']);

			?>
				<a class='btn' href='upload.php'><img class='svg' src='assets/icons/upload.svg'><span class='nav-hide'>Upload</span></a>
				<hr>
			<?php

			if (is_file("usr/images/pfp/".$profile_info['pfp_path'])) {
				echo "<a class='btn' href='account.php'><img class='svg' src='usr/images/pfp/".$profile_info['pfp_path']."'><span class='nav-hide'>".substr($_SESSION["username"], 0, 15)."</span></a>";
			} else {
				echo "<a class='btn' href='account.php'><img class='svg' src='assets/icons/gear.svg'><span class='nav-hide'>".substr($_SESSION["username"], 0, 15)."</span></a>";
			}
		} else {
			?>
				<a class='btn' href='account.php'><img class='svg' src='assets/icons/sign-in.svg'><span class='nav-hide'>Login</span></a>
			<?php
		}
		?>
	</div>
</nav>

<script>
	console.log(". . /|/| . . . . . . .\n\
.. /0 0 \\ . . . . . ..\n\
(III% . \\________, . .\n\
.. .\\_, .%###%/ \\'\\,..\n\
. . . .||#####| |'\\ \\.\n\
.. . . ||. . .|/. .\\V.\n\
. . . .|| . . || . . .\n\
.. . . ||. . .||. . ..\n\
. . . .|| . . || . . .\n\
.. . . ||. . .||. . ..\n\
. . . .|| . . || . . .\n\
.. . . ||. . .||. . ..\n\
. . . .|| . . || . . .\n\
.. . . ||. . .||. . ..\n\
. . . .|| . . || . . .\n\
.. . . ||. . .||. . ..\n\
. . . .|| . . || . . .\n\
.. . . ||. . .||. . ..\n\
. . . .|| . . || . . .\n\
.. . . ||. . .||. . ..\n\
. . . .|| . . || . . .\n\
.. . . ||. . .||. . ..\n\
. . . cc/ . .cc/ . . .");
</script>
<!--
	Used by Sniffle to add Notifications
	Div can be displayed all time as it has no width or height initself
-->
<div id='sniffle' class='sniffle'></div>
<span id="newSniff" style="display: none;"></span>

<!--
	Div for information flyouts
	Controlled by Flyout.js
-->
<div id='flyoutDim' class='flyout-dim'></div>
<div id='flyoutRoot' class='flyout'>
	<p id='flyoutHeader' class='flyout-header'>Header</p>
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
	<img src="assets/icons/caret-up.svg">
</a>
<script>
	button = document.getElementById("back-to-top");

	window.onscroll = function() {
		scrollFunction()
	};

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