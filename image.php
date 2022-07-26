<?php
	require_once __DIR__."/app/required.php";

	use App\Make;
	use App\Account;
	use App\Image;
	use App\Diff;

	$make_stuff = new Make();
	$image_info = new Image;
	$user_info = new Account;
	$diff = new Diff();
?>

<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__."/assets/ui/header.php"; ?>
</head>

<body>
	<?php
		require_once __DIR__."/assets/ui/nav.php";

		/*
		|-------------------------------------------------------------
		| Get image ID
		|-------------------------------------------------------------
		| Image ID should be written in the URL of the page as ?id=69
		| If ID cannot be obtained, give error.      ID going here ^^
		|-------------------------------------------------------------
		*/
		if (isset($_GET['id'])) {
			// Get all image info
			$image = $image_info->get_image_info($conn, $_GET['id']);

			// Check if image is avalible
			if (!empty($image['imagename']) && isset($image['imagename'])) {
				$image_present = True;
			} else {
		?>
				<script>
					sniffleAdd('Woops', 'Something happened, either image with the ID <?php echo $_GET['id']; ?> was deleted or never existed, either way it could not be found!', 'var(--warning)', 'assets/icons/cross.svg');
				</script>
				<?php
				$image_present = False;
			}
		} else {
			?>
				<script>
					sniffleAdd('Where is da image?', 'The link you followed seems to be broken, or there was some other error, who knows!', 'var(--warning)', 'assets/icons/cross.svg');
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
				$user = $user_info->get_user_info($conn, $image['author']);

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
				$image_path = "images/".$image['imagename'];
				$image_alt = $image['alt'];

				$image_colour = $make_stuff->get_image_colour($image_path);
				if (!empty($image_colour)) {
					$image_colour = $image_colour;
				} else {
					$image_colour = "var(--bg)";
				}
				?>
					<style>
						.image-container, .fullscreen-image {
							background-color: <?php echo $image_colour; ?>33 !important;
						}
					</style>
				<?php
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
		if ($image_info->image_privilage($image['author']) || $user_info->is_admin($conn, $_SESSION['id'])) {
			$privilaged = True;
		} else {
			$privilaged = False;
		}

		echo "<div class='fullscreen-image'>
			<button onclick='closeFullScreen()'><img src='assets/icons/cross.svg'></button>
			<img>
			</div>";
		
		if (is_file("images/previews/".$image['imagename'])) {
			echo "<div class='image-container'>
				<img class='image' id='".$image['id']."' src='images/previews/".$image['imagename']."' alt='".$image_alt."'>
				<button class='preview-button' onclick='fullScreen()'><img src='assets/icons/scan.svg'></button>
				</div>";
		} else {
			echo "<div class='image-container'>
				<img class='image' id='".$image['id']."' src='".$image_path."' alt='".$image_alt."'>
				<button class='preview-button' onclick='fullScreen()'><img src='assets/icons/scan.svg'></button>
				</div>";
		}
		

		?>
		<script>
			function fullScreen() {
				document.querySelector(".preview-button").style.display = "none";
				document.querySelector("html").style.overflow = "hidden";

				document.querySelector(".fullscreen-image").style.display = "block";
				document.querySelector(".fullscreen-image > img").src = "<?php echo $image_path;?>";
				setTimeout(function(){
					document.querySelector(".fullscreen-image").style.opacity = 1;
				}, 1);
			}

			function closeFullScreen() {
				document.querySelector(".preview-button").style.display = "block";
				document.querySelector("html").style.overflow = "auto";
				
				document.querySelector(".fullscreen-image").style.opacity = 0;
				setTimeout(function(){
					document.querySelector(".fullscreen-image").style.display = "none";
				}, 500);
			}
		</script>
		<?php

		/*
		|-------------------------------------------------------------
		| Start of displaying all info on image
		|-------------------------------------------------------------
		*/
		if ($image_present) {
	?>

		<div class="defaultDecoration defaultSpacing defaultFonts">
			<h2>Description</h2>
			<p><?php echo htmlentities($image_alt, ENT_QUOTES); ?></p>
		</div>


		<div class="image-detail defaultDecoration defaultSpacing defaultFonts">
			<h2>Details</h2>
			<div>
				<div>
					<?php
						// User
						if (empty($image['author'])) {
							echo "<p>Author: ".$image_author."</p>";
						} else {
							echo "<p>Author: <a href='profile.php?user=".$image['author']."' class='link'>".$image_author."</a></p>";
						}

						// Image ID
						if ($image['id'] == 69) {
							echo "<p>ID: ".$image['id'].", nice</p>";
						} else {
							echo "<p>ID: ".$image['id']."</p>";
						}

						$upload_time = new DateTime($image['upload_date']);
						echo "<p id='updateTime'>Uploaded at: ".$upload_time->format('d/m/Y H:i:s T')."</p>";
					?>
					<script>
						// Updating time to Viewers local
						var updateDate = new Date('<?php echo $upload_time->format('m/d/Y H:i:s T'); ?>');
						var format = {year: 'numeric',
									  month: 'short',
									  day: 'numeric',
									  hour: '2-digit',
									  minute: '2-digit'
									 };
									 
						updateDate = updateDate.toLocaleDateString('en-GB', format);

						$("#updateTime").html("Uploaded at: "+updateDate);
					</script>

					<p>Last Modified: <?php echo $diff->time($image['last_modified']); ?></p>
				</div>
				<div>
					<?php
						// File name
						$image_pathinfo = pathinfo($image['imagename']);
						
						echo "<p>File Name: ".$image_pathinfo['filename']."</p>";

						// File extention
						echo "<p>File Type: ".pathinfo($image['imagename'], PATHINFO_EXTENSION)."</p>";

						// Image resolution
						list($width, $height) = getimagesize($image_path);
						echo "<p>Image resolution: ".$width."x".$height."</p>";

						function human_filesize($bytes, $decimals = 2) {
							$sz = 'BKMGTP';
							$factor = floor((strlen($bytes) - 1) / 3);
							return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
						}
						echo "<p>File size: ".human_filesize(filesize($image_path), 2)."</p>";
					?>
				</div>
			</div>
			<!-- Download Image -->
			<a id='download' class='btn btn-good' href='<?php echo "images/".$image['imagename']; ?>' download='<?php echo $image['imagename']; ?>'><img class='svg' src='assets/icons/download.svg'>Download image</a>
			<script>
				$("#download").click(function() {
					sniffleAdd("Info", "Image download started!", "var(--success)", "assets/icons/download.svg");
				});
			</script>

			<!-- Copy link -->
			<button class='btn btn-good' onclick='copyLink()'><img class='svg' src='assets/icons/clipboard-text.svg'>Copy image link</button>
			<script>
				function copyLink() {
					navigator.clipboard.writeText(window.location.href);

					sniffleAdd("Info", "Link has been copied!", "var(--success)", "assets/icons/clipboard-text.svg");
				}
			</script>
		</div>

		<div class="defaultDecoration defaultSpacing defaultFonts">
			<h2>Tags</h2>
			<div class="tags">
				<?php
				// Get image tags
				if (isset($image['tags']) && !empty($image['tags'])) {
					$image_tags_array = explode(" ", $image['tags']);
					foreach ($image_tags_array as $tag) {
						if ($tag == "nsfw") {
							echo "<p id='tag' class='tag btn-bad'>".$tag."</p>";
						} else {
							echo "<p id='tag' class='tag btn-good'>".$tag."</p>";
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
			<div class='warningDecoration defaultSpacing defaultFonts'>
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
							$("#newSniff").load("app/image/image.php", {
								id: <?php echo $_GET['id']; ?>,
								submit_delete: deleteSubmit
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

						$('#descriptionInput').val("<?php if ($image_alt != "No description avalible") echo str_replace('"', '\"', $image_alt); ?>");
						
						$("#descriptionConfirm").submit(function(event) {
							event.preventDefault();
							var descriptionInput = $("#descriptionInput").val();
							var descriptionSubmit = $("#descriptionSubmit").val();
							$("#newSniff").load("app/image/image.php", {
								id: <?php echo $_GET['id']; ?>,
								input: descriptionInput,
								submit_description: descriptionSubmit
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
						var description = "Tags are seperated by spaces, only alowed characters are a-z and underscores, all hyphens are converted to underscores. There are also special tags such as nsfw that'll blur images in the overview";
						var actionBox = "<form id='tagsConfirm' action='app/image/edit_tags.php' method='POST'>\
						<input id='tagsInput' class='btn btn-neutral space-bottom' type='text' placeholder='Tags are seperated by spaces'>\
						<button id='tagsSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/edit.svg'>Edit tags</button>\
						</form>";
						flyoutShow(header, description, actionBox);
						
						$('#tagsInput').val("<?php echo $image['tags']; ?>");

						$("#tagsConfirm").submit(function(event) {
							event.preventDefault();
							var tagsInput = $("#tagsInput").val();
							var tagsSubmit = $("#tagsSubmit").val();
							$("#newSniff").load("app/image/image.php", {
								id: <?php echo $_GET['id']; ?>,
								input: tagsInput,
								submit_tags: tagsSubmit
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
				if ($user_info->is_admin($conn, $_SESSION['id'])) {
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
								$("#newSniff").load("app/image/image.php", {
									id: <?php echo $_GET['id']; ?>,
									input: authorInput,
									submit_author: authorSubmit
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

		<?php require_once __DIR__."/assets/ui/footer.php"; ?>
</body>

</html>