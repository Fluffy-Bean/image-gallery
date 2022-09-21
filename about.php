<?php require_once __DIR__."/app/required.php"; ?>

<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__."/assets/ui/header.php"; ?>
</head>

<body>
	<?php require_once __DIR__."/assets/ui/nav.php"; ?>

	<div class="about-root">
		<h1><?php echo $user_settings['website_name']; ?></h1>
		<p><?php echo $user_settings['website_description']; ?></p>
		<p>Version <?php echo $user_settings['version']; ?></p>
		
		<br>

		<h2>TOS</h2>
		<p><?php echo $user_settings['tos']; ?></p>
		<p>This project is protected under the <?php echo $user_settings['license']; ?> license by <?php echo $user_settings['user_name']; ?></p>
		
		<br>

		<h2>Credits to development</h2>
		<p>Carty: Kickstarting development and SQL/PHP development</p>
		<p>Jeetix: Helping patch holes in some features</p>
		<p>mrHDash, Verg, Fennec, Carty, Jeetix and everyone else for helping with early bug testing</p>
		<p><a class='link' href="https://phosphoricons.com/">Phosphor</a> for providing nice SVG icons</p>

		<br>

		<h2>Development</h2>
		<a href="https://github.com/Fluffy-Bean/image-gallery" class="link">Project Github</a>
		<a href="https://twitter.com/fluffybeanUwU" class="link">Creators Twitter</a>
	</div>

	<?php require_once __DIR__."/assets/ui/footer.php"; ?>
</body>

</html>