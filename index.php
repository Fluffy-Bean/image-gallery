<?php
	require_once __DIR__."/app/required.php";
	
	use App\Account;
	use App\Sanity;

	$user_info = new Account();
	$sanity = new Sanity();
?>

<!DOCTYPE html>
<html>
	<head>
		<?php require_once __DIR__."/assets/ui/header.php"; ?>
	</head>
<body>
	<?php
		require_once __DIR__."/assets/ui/nav.php"; 

		if (isset($_SESSION['del'])) {
			?>
				<script>
					sniffleAdd("Image Deleted", "Successfully deleted image: <?php echo $_SESSION['del']; ?>", "var(--success)", "assets/icons/trash.svg");
				</script>
			<?php
			unset($_SESSION['del']);
		}
		if (isset($_SESSION['welc'])) {
			?>
				<script>
					sniffleAdd('O hi <?php echo $_SESSION["username"]; ?>', 'You are now logged in, enjoy your stay!', 'var(--success)', 'assets/icons/hand-waving.svg');
				</script>
			<?php
			unset($_SESSION['welc']);

			if ($user_info->is_admin($conn, $_SESSION['id'])) {
				$check_sanity = $sanity->get_results();
				if (!empty($check_sanity)) {
					?>
						<script>
							sniffleAdd('Uh oh', 'Website has not passed some Sanity checks, please check your settings for more information', 'var(--warning)', 'assets/icons/warning.svg');
						</script>
					<?php
				}
			}
		}
	?>

	<?php
		// Reading images from table
		$image_request = mysqli_query($conn, "SELECT * FROM images ORDER BY id DESC");

		if (mysqli_num_rows($image_request) != 0) {
			?>
				<div class="info-text defaultFonts">
					<?php
						// Set time for message
						$time = date("H");
						$timezone = date("e");
						if ($time < "12") {
							$time_welc = "Good morning";
						} else if ($time >= "12" && $time < "17") {
							$time_welc = "Good afternoon";
						} else if ($time >= "17" && $time < "19") {
							$time_welc = "Good evening";
						} else if ($time >= "19") {
							$time_welc = "Good night";
						}

						// Welcome depending on if user is logged in or not
						if (isset($_SESSION["username"])) {
							echo "<h1>".$time_welc." ".$_SESSION['username']."!</h1>";
						} else {
							echo "<h1>".$time_welc."!</h1>";
						}

						// Random welcome message
						$welcome_message = $user_settings['welcome_msg'];
						echo "<p>".$welcome_message[array_rand($welcome_message, 1)]."</p>";
					?>
				</div>
			<?php

			echo "<div class='gallery-root defaultDecoration'>";

			while ($image = mysqli_fetch_array($image_request)) {
				// Getting thumbnail
				if (file_exists("images/thumbnails/".$image['imagename'])) {
					$image_path = "images/thumbnails/".$image['imagename'];
				} else {
					$image_path = "images/".$image['imagename'];
				}
	
				// Check for NSFW tag
				if (str_contains($image['tags'], "nsfw")) {
					echo "<div class='gallery-item'>
							<a href='image.php?id=".$image['id']."' class='nsfw-warning'><img class='svg' src='assets/icons/warning_red.svg'><span>NSFW</span></a>
							<a href='image.php?id=".$image['id']."'><img class='gallery-image nsfw-blur' loading='lazy' src='".$image_path."' id='".$image['id']."'></a>
						</div>";
				} else {
					echo "<div class='gallery-item'>
							<a href='image.php?id=".$image['id']."'><img class='gallery-image' loading='lazy' src='".$image_path."' id='".$image['id']."'></a>
						</div>";
				}
			}

			echo "</div>";
		} else {
			echo "<div class='info-text defaultFonts' style='text-align: center !important;'>
				<h1>Nothing here!</h1>
				<p>There are no images in the gallery, upload some!</p>
			</div>";
		}
	?>

<?php require_once __DIR__."/assets/ui/footer.php"; ?>
</body>
</html>
