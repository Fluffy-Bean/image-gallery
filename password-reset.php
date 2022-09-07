<!DOCTYPE html>
<html>

<head>
	<?php include "ui/header.php"; ?>
</head>


<body>
	<?php
	include "ui/required.php";
	include "ui/nav.php";

	// Check if the user is logged in, otherwise redirect to login page
	if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
		header("location: account.php");
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
			<button id="passwordSubmit" class="btn btn-bad" type="submit" name="reset"><img class="svg" src="assets/icons/sign-in.svg">Reset</button>
		</form>
	</div>

	<script>
		$("#passwordForm").submit(function(event) {
			event.preventDefault();
			var new_passowrd = $("#newPassword").val();
			var confirm_password = $("#confirmSassword").val();
			var submit = $("#passwordSubmit").val();
			$("#sniffle").load("app/account/password_reset.php", {
				new_passowrd: new_passowrd,
				confirm_password: confirm_password,
				submit: submit
			});
		});
	</script>

	<?php include "ui/footer.php"; ?>
</body>

</html>