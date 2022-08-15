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
	?>

	<div class="signup-root">
		<h2>Make account</h2>
		<p>And amazing things happened here...</p>
		<br>
		<form id="signupForm" method="POST" action="signup.php" enctype="multipart/form-data">
			<input id="signupUsername" class="btn btn-neutral" type="text" name="username" placeholder="Username">
			<br>
			<input id="signupPassword" class="btn btn-neutral" type="password" name="password" placeholder="Password">
			<input id="signupPasswordConfirm" class="btn btn-neutral" type="password" name="confirm_password" placeholder="Re-enter Password">
			<br>
			<input id="signupToken" class="btn btn-neutral" type="text" name="token" placeholder="Invite Code">
			<br>
			<button id="signupSubmit" class="btn btn-good" type="submit" name="signup"><img class="svg" src="../assets/icons/sign-in.svg">Sign Up</button>
		</form>
	</div>

	<script>
		$("#signupForm").submit(function(event) {
			event.preventDefault();
			var username = $("#signupUsername").val();
			var password = $("#signupPassword").val();
			var confirm_password = $("#signupPasswordConfirm").val();
			var token = $("#signupToken").val();
			var submit = $("#signupSubmit").val();
			$("#sniffle").load("../app/account/signup.php", {
				username: username,
				password: password,
				confirm_password: confirm_password,
				token: token,
				submit: submit
			});
		});
	</script>

	<?php include "../ui/footer.php"; ?>
</body>

</html>