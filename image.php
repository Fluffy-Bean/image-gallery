<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Lynx Gallery</title>

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/normalise.css">

	<!-- Google Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Secular+One&display=swap">

	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
	</script>

	<!-- Sniffle script! -->
	<script src="Sniffle/sniffle.js"></script>
	<link rel='stylesheet' href='Sniffle/sniffle.css'>

	<!-- Flyout script! -->
	<script src="Flyout/flyout.js"></script>
	<link rel='stylesheet' href='Flyout/flyout.css'>
</head>

<body>
	<?php
	/*
	 |-------------------------------------------------------------
	 | Import Required
	 |-------------------------------------------------------------
	 | These are common scripts used across all pages of the
	 | website. At some point I want the whole website to be only
	 | one index page. But that's going to require many many many
	 | many rewrites and hours of learning....
	 |-------------------------------------------------------------
	*/
	include "ui/required.php";
	include "ui/nav.php";


	/*
	 |-------------------------------------------------------------
	 | Get image ID
	 |-------------------------------------------------------------
	 | Image ID should be written in the URL of the page as ?id=69
	 | If ID cannot be obtained, give error.      ID going here ^^
	 |-------------------------------------------------------------
	*/
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		// Get all image info
		$image = get_image_info($conn, $_GET['id']);

		// Check if image is avalible
		if (isset($image['imagename'])) {
			$image_present = True;
		} else {
	?>
			<script>
				sniffleAdd('Woops', 'Something happened, either image with the ID <?php echo $_GET['id']; ?> was deleted or never existed, either way it could not be found!', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/cross.svg');
			</script>
		<?php
			$image_present = False;
		}
	} else {
		?>
		<script>
			sniffleAdd('Where is da image?', 'The link you followed seems to be broken, or there was some other error, who knows!', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/cross.svg');
		</script>
	<?php
		$image_present = False;
	}

	/*
	 |-------------------------------------------------------------
	 | Image verification
	 |-------------------------------------------------------------
	 | Doublechecking if all information on images exists, if yes
	 | display it, otherwise don't display at all or replace with
	 | blank or filler info
	 |-------------------------------------------------------------
	*/
	if ($image_present) {
		/*
		 |-------------------------------------------------------------
		 | Check user details
		 |-------------------------------------------------------------
		*/
		if (isset($image['author'])) {
			// Get all information on the user
			$user = get_user_info($conn, $image['author']);

			if (isset($user['username'])) {
				$image_author = $user['username'];
			} else {
				$image_author = "Deleted User";
			}
		} else {
			$image_author = "No author";
		}

		/*
		 |-------------------------------------------------------------
		 | Check if image path is good
		 |-------------------------------------------------------------
		*/
		if (isset($image['imagename'])) {
			$image_path = "images/" . $image['imagename'];
			$image_alt = $image['alt'];
		} else {
			$image_path = "assets/no_image.png";
			$image_alt = "No image could be found, sowwy";
		}

		/*
		 |-------------------------------------------------------------
		 | If description not set or empty, replace with filler
		 |-------------------------------------------------------------
		*/
		if (!isset($image_alt) || empty($image_alt)) {
			$image_alt = "No description avalible";
		}
	} else {
		/*
		 |-------------------------------------------------------------
		 | Image was not present
		 |-------------------------------------------------------------
		*/
		$image_path = "assets/no_image.png";
		$image_alt = "No image could be found, sowwy";
	}

	/*
	 |-------------------------------------------------------------
	 | Check user privilge
	 |-------------------------------------------------------------
	*/
	if (image_privilage($image['author']) || is_admin($_SESSION['id'])) {
		$privilaged = True;
	} else {
		$privilaged = False;
	}
	?>

	<div class="image-container">
		<img class='image' id='<?php echo $image['id']; ?>' src='<?php echo $image_path; ?>' alt='<?php echo $image_alt; ?>'>
	</div>

	<?php
	/*
	 |-------------------------------------------------------------
	 | Start of displaying all info on image
	 |-------------------------------------------------------------
	*/
	if ($image_present) {
	?>

		<div class="image-description default-window">
			<h2>Description</h2>
			<p><?php echo $image_alt; ?></p>
		</div>


		<div class="image-detail flex-down default-window">
			<h2>Details</h2>
			<?php
			// User
			echo "<p>Author: " . $image_author . "</p>";

			// Image ID
			echo "<p>ID: " . $image['id'] . "</p>";

			// File name
			if (strlen($image['imagename']) > 30) {
				echo "<p>File Name: " . substr($image['imagename'], 0, 30) . "...</p>";
			} else {
				echo "<p>File Name: " . $image['imagename'] . "</p>";
			}

			// File extention
			echo "<p>File Type: " . pathinfo($image['imagename'], PATHINFO_EXTENSION) . "</p>";

			// Image resolution
			list($width, $height) = getimagesize($image_path);
			echo "<p>Image resolution: " . $width . "x" . $height . "</p>";

			// Image Upload date
			echo "<p>Last updated: +0 " . $image['upload'] . "</p>";

			echo "<br>";

			// Image download
			echo "<a class='btn btn-good' href='images/" . $image['imagename'] . "' download='" . $image['imagename'] . "'><img class='svg' src='assets/icons/download.svg'>Download image</a>";

			// Copy image
			?>
			<script>
				function copyLink() {
					navigator.clipboard.writeText(window.location.href);

					sniffleAdd("Info", "Link has been copied!", "var(--green)", "assets/icons/clipboard-text.svg");
				}
			</script>
			<?php
			echo "<button class='btn btn-good' onclick='copyLink()'><img class='svg' src='assets/icons/clipboard-text.svg'>Copy image link</button>";
			?>
		</div>

		<div class="tags-root default-window">
			<h2>Tags</h2>
			<div class="tags">
				<?php
				// Get image tags
				if (isset($image['tags']) && !empty($image['tags'])) {
					$image_tags_array = explode(" ", $image['tags']);
					foreach ($image_tags_array as $tag) {
						if ($tag == "nsfw") {
							echo "<p id='tag' class='tag btn-bad'>" . $tag . "</p>";
						} else {
							echo "<p id='tag' class='tag btn-good'>" . $tag . "</p>";
						}
					}
				} else {
					echo "<p>No tags present</p>";
				}
				?>
			</div>
		</div>

		<?php
		/*
		|-------------------------------------------------------------
		| Check if user is privilaged,
		|-------------------------------------------------------------
		| If yes, grant them access to the Danger zone, or "the place
		| that can fuck things up"
		|-------------------------------------------------------------
		*/
		if ($privilaged) {
		?>
			<!-- Danger zone -->
			<div class='danger-zone flex-down default-window'>
				<h2>Danger zone</h2>

				<!--
				|-------------------------------------------------------------
				| Delete
				|-------------------------------------------------------------
				| sus
				| I cannot describe the anxiety this gives me. pls help
				|-------------------------------------------------------------
				-->
				<button id='deleteButton' class='btn btn-bad'><img class='svg' src='assets/icons/trash.svg'>Delete image</button>
				<script>
					$('#deleteButton').click(function() {
						var header = "Are you sure?";
						var description = "Deleting this image is pernament, there is no going back after this!!!!!";
						var actionBox = "<form id='deleteConfirm' method='POST'>\
				<button id='deleteSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/trash.svg'>Delete image</button>\
				</form>";
						flyoutShow(header, description, actionBox);

						$("#deleteConfirm").submit(function(event) {
							event.preventDefault();
							var deleteSubmit = $("#deleteSubmit").val();
							$("#sniffle").load("app/image/delete_image.php", {
								id: <?php echo $_GET['id']; ?>,
								submit: deleteSubmit
							});
						});
					});
				</script>

				<!--
				|-------------------------------------------------------------
				| Edit Description
				|-------------------------------------------------------------
				| Most people reading through the code will probably say how
				| shit it is. YOU HAVE NO FUCKING CLUE HOW LONG THIS TOOK ME
				| TO FIGURE OUT. i hate js.
				|-------------------------------------------------------------
				-->
				<button id='descriptionButton' class='btn btn-bad'><img class='svg' src='assets/icons/edit.svg'>Edit description</button>
				<script>
					$('#descriptionButton').click(function() {
						var header = "Enter new Description/Alt";
						var description = "Whatcha gonna put in there 👀";
						var actionBox = "<form id='descriptionConfirm' action='app/image/edit_description.php' method='POST'>\
				<input id='descriptionInput' class='btn btn-neutral space-bottom' type='text' placeholder='Description/Alt for image'>\
				<button id='descriptionSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/edit.svg'>Update information</button>\
				</form>";
						flyoutShow(header, description, actionBox);

						$("#descriptionConfirm").submit(function(event) {
							event.preventDefault();
							var descriptionInput = $("#descriptionInput").val();
							var descriptionSubmit = $("#descriptionSubmit").val();
							$("#sniffle").load("app/image/edit_description.php", {
								id: <?php echo $_GET['id']; ?>,
								input: descriptionInput,
								submit: descriptionSubmit
							});
						});
					});
				</script>

				<!--
				|-------------------------------------------------------------
				| Edit Tags
				|-------------------------------------------------------------
				| Literally no amount of work will get tags/lists to play well
				| with SQL so I gave up and made my own shitty system. It
				| works but once I re-add the search function this will make
				| anyone cry. I am so sorry.
				|-------------------------------------------------------------
				-->
				<button id='tagsButton' class='btn btn-bad'><img class='svg' src='assets/icons/tag.svg'>Add image tags</button>
				<script>
					$('#tagsButton').click(function() {
						var header = "Tags";
						var description = "Add image tags here! This is still being tested so your tags may be removed later on. Tags ONLY accept, letters, numbers and underscores. Hyphens will be stitched to underscores and spaces will seperate the different tags from eachother";
						var actionBox = "<form id='tagsConfirm' action='app/image/edit_tags.php' method='POST'>\
				<input id='tagsInput' class='btn btn-neutral space-bottom' type='text' placeholder='Tags are seperated by spaces'>\
				<button id='tagsSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/edit.svg'>Edit tags</button>\
				</form>";
						flyoutShow(header, description, actionBox);

						$("#tagsConfirm").submit(function(event) {
							event.preventDefault();
							var tagsInput = $("#tagsInput").val();
							var tagsSubmit = $("#tagsSubmit").val();
							$("#sniffle").load("app/image/edit_tags.php", {
								id: <?php echo $_GET['id']; ?>,
								input: tagsInput,
								submit: tagsSubmit
							});
						});
					});
				</script>

				<!--
				|-------------------------------------------------------------
				| Edit Author
				|-------------------------------------------------------------
				| You must be a super cool person to see this section ;3
				|-------------------------------------------------------------
				-->
				<?php
				if (is_admin($_SESSION['id'])) {
				?>
					<button id='authorButton' class='btn btn-bad'><img class='svg' src='assets/icons/edit.svg'>Edit author</button>
					<script>
						$('#authorButton').click(function() {
							var header = "Who owns the image?????";
							var description = "Enter ID of image owner";
							var actionBox = "<form id='authorConfirm' action='app/image/edit_author.php' method='POST'>\
					<input id='authorInput' class='btn btn-neutral space-bottom' type='text' placeholder='le author'>\
					<button id='authorSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/edit.svg'>Edit author</button>\
					</form>";
							flyoutShow(header, description, actionBox);

							$("#authorConfirm").submit(function(event) {
								event.preventDefault();
								var authorInput = $("#authorInput").val();
								var authorSubmit = $("#authorSubmit").val();
								$("#sniffle").load("app/image/edit_author.php", {
									id: <?php echo $_GET['id']; ?>,
									input: authorInput,
									submit: authorSubmit
								});
							});
						});
					</script>
		<?php
				}
				echo "</div>";
			}

			/*
	 |-------------------------------------------------------------
	 | End of displaying all user info
	 |-------------------------------------------------------------
	*/
		}
		?>

		<?php include "ui/footer.php"; ?>
</body>

</html>