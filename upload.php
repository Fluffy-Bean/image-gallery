<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__."/ui/header.php"; ?>

	<!-- Upload Script -->
	<script>
		$(document).ready(function() {
			$("#uploadSubmit").submit(function(event) {
				event.preventDefault();
				// Check if image avalible
				var file = $("#image").val();
				if (file != "") {
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
						success: function(response) {
							$("#sniffle").html(response);
						}
					});

					// Empty values
					$("#image").val("");
					$("#alt").val("");
					$("#tags").val("");
					$("#submit").val("");
				} else {
					sniffleAdd('Gwha!', 'Pls provide image', 'var(--red)', 'assets/icons/file-search.svg');
				}
			});
		});
	</script>
</head>

<body>
	<?php
	require_once __DIR__."/ui/required.php";
	require_once __DIR__."/ui/nav.php";

	use App\Account;
	$user_info = new Account();

	// Check if user is logged in
	if (!$user_info->is_loggedin()) {
	?>
		<script>
		sniffleAdd('Who are you!', 'You must be loggedin to upload things, sowwy!', 'var(--red)', 'assets/icons/cross.svg');
		</script>
	<?php
	}
	?>

	<div class="upload-root">
		<h2>Upload image</h2>
		<p>In this world you have 2 choices, to upload a really cute picture of an animal or fursuit, or something other than those 2 things.</p>
		<br>
		<form id="uploadSubmit" class="flex-down between" method="POST" enctype="multipart/form-data">
			<input id="image" class="btn btn-neutral" type="file" placeholder="select image UwU">
			<textarea id="alt" class="btn btn-neutral" placeholder="Description/Alt for image" rows="3"></textarea>
			<textarea id="tags" class="btn btn-neutral" placeholder="Tags, seperated by white-space" rows="3"></textarea>
			<br>
			<button id="submit" class="btn btn-good" type="submit"><img class="svg" src="assets/icons/upload.svg">Upload Image</button>
		</form>
	</div>

	<?php require_once __DIR__."/ui/footer.php"; ?>
</body>

</html>