<?php
	require_once __DIR__."/app/required.php";
	
	use App\Image;

	$image_info = new Image;
	?>

<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__."/assets/ui/header.php"; ?>
</head>

<body>
	<?php
		require_once __DIR__."/assets/ui/nav.php"; 

		if ($_GET['del']) {
			?>
				<script>
					sniffleAdd("Image Deleted", "Successfully deleted image: <?php echo $_GET['id']; ?>", "var(--green)", "assets/icons/trash.svg");
				</script>
			<?php
		}
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

<div class="gallery-root defaultDecoration">
	<?php
		// Reading images from table
		$group_list = mysqli_query($conn, "SELECT * FROM groups ORDER BY id DESC");

		foreach ($group_list as $group) {
			$image_list = array_reverse(explode(" ", $group['image_list']));
			$image = $image_info->get_image_info($conn, $image_list[array_rand($image_list, 1)]);

			// Getting thumbnail
			if (file_exists("images/thumbnails/".$image['imagename'])) {
				$image_path = "images/thumbnails/".$image['imagename'];
			} else {
				$image_path = "images/".$image['imagename'];
			}

			// Check for NSFW tag
			if (str_contains($image['tags'], "nsfw")) {
				echo "<div class='gallery-item group-item'>
					<a href='group.php?id=".$group['id']."' class='nsfw-warning gallery-group'><img class='svg' src='assets/icons/warning_red.svg'><span>NSFW</span></a>
					<a href='group.php?id=".$group['id']."'><img class='gallery-image nsfw-blur' loading='lazy' src='".$image_path."' id='".$group['id']."'></a>
					<a href='group.php?id=".$group['id']."' class='group-name'>".$group['group_name']."</a>
					</div>";
			} else {
				echo "<div class='gallery-item group-item'>
					<a href='group.php?id=".$group['id']."' class='gallery-group'></a>
					<a href='group.php?id=".$group['id']."'><img class='gallery-image' loading='lazy' src='".$image_path."' id='".$group['id']."'></a>
					<a href='group.php?id=".$group['id']."' class='group-name'>".$group['group_name']."</a>
					</div>";
			}
		}
	?>
</div>

<div class="gallery-root defaultDecoration">
	<?php
		// Reading images from table
		$image_request = mysqli_query($conn, "SELECT * FROM images ORDER BY id DESC");

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
	?>
</div>

<?php require_once __DIR__."/assets/ui/footer.php"; ?>
</body>
</html>
