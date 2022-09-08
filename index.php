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

<script>
	if (params.del == "true") {
		sniffleAdd("Image Deleted", "Successfully deleted image: <?php echo $_GET['id']; ?>", "var(--green)", "assets/icons/trash.svg");
	}
</script>

<div class="info-text">
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
		$welcome_message = $user_settings['website']['welcome_msg'];
		echo "<p>".$welcome_message[array_rand($welcome_message, 1)]."</p>";
	?>
</div>

<!--
<div class="gallery-order">
	<button class="btn btn-neutral">Grid</button>
	<button class="btn btn-neutral">List</button>
</div>
-->

<div class="gallery-root">
	<?php
	// Reading images from table
	$image_request = mysqli_query($conn, "SELECT * FROM swag_table ORDER BY id DESC");

	while ($image = mysqli_fetch_array($image_request)) {
		// Getting thumbnail
		if (file_exists("images/thumbnails/".$image['imagename'])) {
			$image_path = "images/thumbnails/".$image['imagename'];
		} else {
			$image_path = "images/".$image['imagename'];
		}

		// Check for NSFW tag
		if (str_contains($image['tags'], "nsfw")) {
			$image_nsfw = "nsfw-blur";
			$nsfw_warning = "<a href='image.php?id=".$image['id']."' class='nsfw-warning'><img class='svg' src='assets/icons/warning_red.svg'><span>NSFW</span></a>";
		} else {
			$image_nsfw = "";
			$nsfw_warning = "";
		}

		// Image loading
		echo "<div class='gallery-item'>";
		echo $nsfw_warning;
		echo "<a href='image.php?id=".$image['id']."'><img class='gallery-image ".$image_nsfw."' loading='lazy' src='".$image_path."' id='".$image['id']."'></a>";
		echo "</div>";
	}
	?>
</div>


<?php include "ui/footer.php"; ?>
</body>
</html>
