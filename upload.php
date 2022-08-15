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
					// Get ALT
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
					$("#submit").val("");
				} else {
					sniffleAdd('Gwha!', 'Pls provide image', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/file-search.svg');
				}
			});
		});
	</script>
</head>

<body>
	<?php
	include "ui/required.php";
	include "ui/nav.php";

	// Check if user is logged in
	if (!loggedin()) {
		echo "
    <script>
      sniffleAdd('Who are you!', 'You must be loggedin to upload things, sowwy!', 'var(--red)', '" . $root_dir . "assets/icons/cross.svg');
    </script>
    ";
	}
	?>

	<div class="upload-root">
		<h2>Upload image</h2>
		<p>In this world you have 2 choices, to upload a really cute picture of an animal or fursuit, or something other than those 2 things.</p>
		<br>
		<form id="uploadSubmit" class="flex-down between" method="POST" enctype="multipart/form-data">
			<input id="image" class="btn btn-neutral" type="file" placeholder="select image UwU">
			<input id="alt" class="btn btn-neutral" type="text" placeholder="Description/Alt for image">
			<br>
			<button id="submit" class="btn btn-good" type="submit"><img class="svg" src="assets/icons/upload.svg">Upload Image</button>
		</form>
	</div>

	<?php include "ui/footer.php"; ?>
</body>

</html>