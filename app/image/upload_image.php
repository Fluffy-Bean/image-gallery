<?php
/*
|-------------------------------------------------------------
| Uploading Images
|-------------------------------------------------------------
| gwa gwa
|-------------------------------------------------------------
*/
session_start();
// Include server connection
include dirname(__DIR__)."/server/conn.php";
include dirname(__DIR__)."/app.php";
include dirname(__DIR__)."/settings/settings.php";

use App\Make;

$make_stuff = new Make();

if (isset($_POST['submit'])) {
	if (isset($_SESSION['id'])) {
		$error = 0;

		// Root paths
		$dir			= "../../images/";
		$thumb_dir		= $dir."thumbnails/";
		$preview_dir	= $dir."previews/";

		$file_type		= pathinfo($dir.$_FILES['image']['name'],PATHINFO_EXTENSION);

		$tags			= $make_stuff->tags(trim($_POST['tags']));

		// Check filetype
		$allowed_types	= array('jpg', 'jpeg', 'png', 'webp');
		if (!in_array($file_type, $allowed_types)) {
			?>
				<script>
					sniffleAdd('Woopsie', 'The file type you are trying to upload is not supported. Supported files include: JPEG, JPG, PNG and WEBP', 'var(--warning)', 'assets/icons/cross.svg');
				</script>
			<?php
			$error += 1;
		}

		if ($upload_conf['rename_on_upload'] == true && $error <= 0) {
			/* Accepted name templates includes

				{{username}}	->	Uploaders username
				{{userid}}		->	Uploaders ID

				{{time}}		->	microtime of upload
				{{date}}		->	date of upload

				{{filename}}	->	takes original filename
				{{autoinc}}		->	checks if file with name already exists
									if so it adds a number on the end of it

				"foo"			-> Text is accepted between templates
			*/

			$name_template	= $upload_conf['rename_to'];

			$name_template	= str_replace('{{username}}', $_SESSION["username"], $name_template);
			$name_template	= str_replace('{{userid}}', $_SESSION["id"], $name_template);

			$name_template	= str_replace('{{time}}', round(microtime(true)), $name_template);
			$name_template	= str_replace('{{date}}', date("Y-m-d"), $name_template);

			$name_template	= str_replace('{{filename}}', pathinfo($dir.$_FILES['image']['name'],PATHINFO_FILENAME), $name_template);

			if (str_contains($name_template, "{{autoinc}}")) {
				$autoinc 			= 0;
				$autoinc_tmp_name	= str_replace('{{autoinc}}', $autoinc, $name_template).".".$file_type;

				while (is_file($dir.$autoinc_tmp_name)) {
					$autoinc += 1;
					$autoinc_tmp_name = str_replace('{{autoinc}}', $autoinc, $name_template).".".$file_type;
				}

				$name_template	= str_replace('{{autoinc}}', $autoinc, $name_template);
			}

			$image_newname	= $name_template.".".$file_type;
			$image_path		= $dir.$image_newname;

			// Check for conflicting names, as the config could be setup wrong
			if (is_file($image_path)) {
				?>
					<script>
						sniffleAdd('Woopsie', 'There was an error in your manifest.json and cause filename errors, please setup a name with a unique template', 'var(--warning)', 'assets/icons/cross.svg');
					</script>
				<?php
				$error += 1;
			}
		} else {
			$image_newname	= $_FILES['image']['name'];
			$image_path		= $dir.$image_newname;

			// Check for file already existing under that name
			if (is_file($image_path)) {
				?>
					<script>
						sniffleAdd('Woopsie', 'A file under that name already exists!', 'var(--warning)', 'assets/icons/cross.svg');
					</script>
				<?php
				$error += 1;
			}
		}

		// Move file to server
		if ($error <= 0) {
			if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
				// Attempt making a thumbnail
				list($width, $height) = getimagesize($image_path);
				if ($width > 300) {
					if ($make_stuff->thumbnail($image_path, $thumb_dir.$image_newname, 300) != "success") {
						?>
							<script>
								sniffleAdd('Gwha!', 'We hit a small roadbump during making of the thumbail. We will continue anyway! \n Full Error: <?php echo $make_thumbnail; ?>', 'var(--allert)', 'assets/icons/bug.svg');
							</script>
						<?php
					}
				}
				if ($width > 1100) {
					if ($make_stuff->thumbnail($image_path, $preview_dir.$image_newname, 900) != "success") {
						?>
							<script>
								sniffleAdd('Gwha!', 'We hit a small roadbump during making of the preview. We will continue anyway! \n Full Error: <?php echo $make_preview; ?>', 'var(--allert)', 'assets/icons/bug.svg');
							</script>
						<?php
					}
				}
	
				// Prepare sql for destruction and filtering the sus
				$sql = "INSERT INTO images (imagename, alt, tags, author) VALUES (?, ?, ?, ?)";
	
				if ($stmt = mysqli_prepare($conn, $sql)) {
					// Bind the smelly smelly
					mysqli_stmt_bind_param($stmt, "ssss", $param_image_name, $param_alt_text, $param_tags, $param_user_id);
	
					// Setting up parameters
					$param_image_name = $image_newname;
					$param_alt_text = $_POST['alt'];
					$param_user_id = $_SESSION['id'];
					$param_tags = $tags;
	
					// Attempt to execute the prepared statement
					if (mysqli_stmt_execute($stmt)) {
						?>
							<script>
								sniffleAdd(':3', 'Your Image uploaded successfully!', 'var(--success)', 'assets/icons/check.svg');
							</script>
						<?php
					} else {
						?>
							<script>
								sniffleAdd(':c', 'Something went fuckywucky, please try later', 'var(--warning)', 'assets/icons/cross.svg');
							</script>
						<?php
					}
				}
			} else {
				?>
					<script>
						sniffleAdd('Hmmff', 'Something happened when moving the file to the server. This may just been a 1-off so try again', 'var(--warning)', 'assets/icons/bug.svg');
					</script>
				<?php
			}
		}
	} else {
		?>
			<script>
				sniffleAdd('Denied!!!', 'As you are not loggedin, your upload has been stopped, L', 'var(--warning)', 'assets/icons/cross.svg');
			</script>
		<?php
	}
}
