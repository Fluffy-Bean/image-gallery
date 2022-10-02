<?php require_once __DIR__."/app/required.php"; ?>

<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__."/assets/ui/header.php"; ?>
</head>

<body>
	<?php
		require_once __DIR__."/assets/ui/nav.php";

		use App\Account;
		$user_info = new Account();

		// Check if user is logged in
		if (!$user_info->is_loggedin()) {
		?>
			<script>
			sniffleAdd('Who are you!', 'You must be logged in to upload things, sowwy!', 'var(--alert)', 'assets/icons/cross.svg');
			</script>
		<?php
		}
	?>

	<div class="upload-root defaultDecoration defaultSpacing defaultFonts">
		<h2>Upload image</h2>
		<p>In this world you have 2 choices, to upload a really cute picture of an animal or fursuit, or something other than those 2 things.</p>
		<img id="imagePreview" src="">
		<br>
		<form id="uploadSubmit" class="flex-down between" method="POST" enctype="multipart/form-data">
			<input id="image" class="btn btn-neutral" type="file" placeholder="select image UwU">
			<br>
			<input id="alt" class="btn btn-neutral" placeholder="Description/Alt for image" type='text'>
			<input id="tags" class="btn btn-neutral" placeholder="Tags, seperated by spaces" type='text'>
			<br>
			<button id="submit" class="btn btn-good" type="submit"><img class="svg" src="assets/icons/upload.svg">Upload Image</button>
		</form>
		<script>
			image.onchange = evt => {
				const [file] = image.files
				if (file) {
					imagePreview.src = URL.createObjectURL(file);
				} else {
					imagePreview.src = "assets/no_image.png";
				}
			}
		</script>
	</div>

	<script>
		$('#uploadSubmit').submit(function(event) {
			event.preventDefault();
			// Check if image available
			var file = $("#image").val();
            if (file == "") {
                sniffleAdd('Gwha!', 'Pls provide image', 'var(--warning)', 'assets/icons/file-search.svg');
            } else {
                // Make form
                var formData = new FormData();

                // Get image
                var image_data = $("#image").prop("files")[0];
                formData.append("image", image_data);
                // Get ALT
                var alt = $("#alt").val();
                formData.append("alt", alt);
                // Get TAGS
                var tags = $("#tags").val();
                formData.append("tags", tags);
                // Submit data
                var submit = $("#submit").val();
                formData.append("submit", submit);

                // Upload the information
                $.ajax({
                    url: 'app/image/upload_image.php',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $("#newSniff").html(response);
                    }
                });

                // Empty values
                imagePreview.src = "";
                $("#image").val("");
                $("#alt").val("");
                $("#tags").val("");
                $("#submit").val("");
            }
        });
	</script>

	<?php require_once __DIR__."/assets/ui/footer.php"; ?>
</body>

</html>