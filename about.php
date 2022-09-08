<!DOCTYPE html>
<html>

<head>
	<?php include "ui/header.php"; ?>
</head>


<body>
	<?php
	include "ui/required.php";
	include "ui/nav.php";
	?>

	<div class="about-root">
		<h2>What is this?</h2>
		<p>This Gallery is a smol project I originally started to control the images on my main page, but quickly turned into something much bigger...</p>
		<p>What Do I want this to become in the future? No clue, but I do want this to be usable by others, if its a file they download a docker image they setup on your own web server.</p>
		<p>Will it become that any time soon? No, but. I am going to work on this untill it becomes what I want it to be!</p>

		<br>

		<h2>Can you add "A" or "B"?</h2>
		<p>No.</p>

		<br>

		<h2>How do I use this!</h2>
		<p>First you must obtain the invite code. If you don't have one and are interested in trying this, feel free to DM me on Telegram!</p>
		<p>But once you're done doing that, you can start making your account <a class='link' href="https://superdupersecteteuploadtest.fluffybean.gay/account/signup.php">at the signup page here</a>.</p>
		<p>From there you should be able to go and login <a class='link' href="https://superdupersecteteuploadtest.fluffybean.gay/account/login.php">at this fancy page here</a>!</p>
		<p>Now you should see "Welcome (your username)" at the homepage. From there navigate to the navbar and click on the upload button. Choose your file, enter the description and your image is up!</p>

		<br>

		<h2>Where to find me</h2>
		<a class='link' href="https://gay.fluffybean.gay">
			<img class='svg' src='<?php echo $root_dir; ?>assets/icons/link.svg'>
			My website!
		</a>
		<a class='link' href="https://t.me/Fluffy_Bean">
			<img class='svg' src='<?php echo $root_dir; ?>assets/icons/telegram-logo.svg'>
			Telegram
		</a>
		<a class='link' href="https://twitter.com/fluffybeanUwU">
			<img class='svg' src='<?php echo $root_dir; ?>assets/icons/twitter-logo.svg'>
			Twitter
		</a>

		<h2>Credits!</h2>
		<p>To Carty for being super cool again and helping me get started with SQL and PHP!</p>
		<p>To <a class='link' href="https://phosphoricons.com/">Phosphor</a> for providing nice SVG icons.</p>
		<p>To mrHDash...</p>
	</div>

	<?php include "ui/footer.php"; ?>
</body>

</html>