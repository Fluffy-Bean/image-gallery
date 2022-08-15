<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Lynx Gallery</title>

	<!-- Stylesheets -->
	<link rel="stylesheet" href="../css/main.css">
	<link rel="stylesheet" href="../css/normalise.css">

	<!-- Google Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Secular+One&display=swap">

	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
	</script>

	<!-- Sniffle script! -->
	<script src="../Sniffle/sniffle.js"></script>
	<link rel='stylesheet' href='../Sniffle/sniffle.css'>

	<!-- Flyout script! -->
	<script src="../Flyout/flyout.js"></script>
	<link rel='stylesheet' href='../Flyout/flyout.css'>
</head>

<body>
	<?php
	include "../ui/required.php";
	include "../ui/nav.php";

	// Check if the user is logged in, otherwise redirect to login page
	if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
		header("location: https://superdupersecteteuploadtest.fluffybean.gay/account/login.php");
		exit;
	}
	?>

	<div class="password-reset-root">
		<h2>Reset Password</h2>
		<p>After reset, you will be kicked out to login again</p>
		<br>
		<form id="passwordForm" method="POST" enctype="multipart/form-data">
			<input id="newPassword" class="btn btn-neutral" type="password" name="new_password" placeholder="New Password">
			<input id="confirmSassword" class="btn btn-neutral" type="password" name="confirm_password" placeholder="Confirm Password">
			<br>
			<button id="passwordSubmit" class="btn btn-bad" type="submit" name="reset"><img class="svg" src="../assets/icons/sign-in.svg">Reset</button>
		</form>
	</div>

	<script>
		$("#passwordForm").submit(function(event) {
			event.preventDefault();
			var new_passowrd = $("#newPassword").val();
			var confirm_password = $("#confirmSassword").val();
			var submit = $("#passwordSubmit").val();
			$("#sniffle").load("../app/account/password_reset.php", {
				new_passowrd: new_passowrd,
				confirm_password: confirm_password,
				submit: submit
			});
		});
	</script>

	<?php include "../ui/footer.php"; ?>
</body>

</html>