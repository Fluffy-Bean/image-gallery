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
include "../server/conn.php";
include "../app.php";

use App\Make;

$make_stuff = new Make();

if (isset($_POST['submit'])) {
	if (isset($_SESSION['id'])) {
		// Root paths
		$dir = "../../images/";
		$thumb_dir = $dir."thumbnails/";
		$preview_dir = $dir."previews/";

		// File name updating
		$file_type = pathinfo($dir.$_FILES['image']['name'],PATHINFO_EXTENSION);
		$image_newname = "IMG_".$_SESSION["username"]."_".round(microtime(true)).".".$file_type;
		$image_path = $dir.$image_newname;

		// Clean tags
		$tags = $make_stuff->tags(trim($_POST['tags']));

		// Allowed file types
		$allowed_types = array('jpg', 'jpeg', 'png', 'webp');
		if (in_array($file_type, $allowed_types)) {
			// Move file to server
			if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
				// Attempt making a thumbnail
				list($width, $height) = getimagesize($image_path);
				if ($width > 300) {
					if ($make_stuff->thumbnail($image_path, $thumb_dir.$image_newname, 300) != "success") {
						?>
							<script>
								sniffleAdd('Gwha!', 'We hit a small roadbump during making of the thumbail. We will continue anyway! \n Full Error: <?php echo $make_thumbnail; ?>', 'var(--black)', 'assets/icons/bug.svg');
							</script>
						<?php
					}
				}
				if ($width > 1100) {
					if ($make_stuff->thumbnail($image_path, $preview_dir.$image_newname, 900) != "success") {
						?>
							<script>
								sniffleAdd('Gwha!', 'We hit a small roadbump during making of the preview. We will continue anyway! \n Full Error: <?php echo $make_preview; ?>', 'var(--black)', 'assets/icons/bug.svg');
							</script>
						<?php
					}
				}

				// Prepare sql for destruction and filtering the sus
				$sql = "INSERT INTO swag_table (imagename, alt, tags, author) VALUES (?, ?, ?, ?)";

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
								sniffleAdd(':3', 'Your Image uploaded successfully!', 'var(--green)', 'assets/icons/check.svg');
							</script>
						<?php
					} else {
						?>
							<script>
								sniffleAdd(':c', 'Something went fuckywucky, please try later', 'var(--red)', 'assets/icons/cross.svg');
							</script>
						<?php
					}
				}
			} else {
				?>
					<script>
						sniffleAdd('Hmmff', 'Something happened when moving the file to the server. This may just been a 1-off so try again', 'var(--red)', 'assets/icons/bug.svg');
					</script>
				<?php
			}
		} else {
			?>
				<script>
					sniffleAdd('Woopsie', 'The file type you are trying to upload is not supported. Supported files include: JPEG, JPG, PNG and WEBP', 'var(--red)', 'assets/icons/cross.svg');
				</script>
			<?php
		}
	} else {
		?>
			<script>
				sniffleAdd('Denied!!!', 'As you are not loggedin, your upload has been stopped, L', 'var(--red)', 'assets/icons/cross.svg');
			</script>
		<?php
	}
}
