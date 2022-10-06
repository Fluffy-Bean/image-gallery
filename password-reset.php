<?php
	require __DIR__."/app/required.php";
	
	use App\Account;

	$user_info = new Account();

	// Check if the user is logged in, otherwise redirect to login page
	if ($user_info->is_loggedin($conn) == false) {
		header("location: account.php");
	}
?>

<!DOCTYPE html>
<html>

<head>
	<?php include __DIR__."/assets/ui/header.php"; ?>
</head>

<body>
	<?php include __DIR__."/assets/ui/nav.php"; ?>

	<div class="warningDecoration defaultSpacing defaultFonts">
		<h2>Reset Password</h2>
		<p>After reset, you will be kicked out to login again</p>
		<br>
		<form id="passwordForm" method="POST" enctype="multipart/form-data">
			<input id="currentPassword" class="btn btn-neutral" placeholder="Current password!!!!" type='password' disabled>
			<br>
			<input id="newPassword" class="btn btn-neutral" type="password" name="new_password" placeholder="New Password">
			<input id="confirmPassword" class="btn btn-neutral" type="password" name="confirm_password" placeholder="Confirm Password">
			<br>
			<button id="passwordSubmit" class="btn btn-bad" type="submit" name="reset"><img class="svg" src="assets/icons/sign-in.svg">Reset</button>
		</form>
		<br>
		<a href="account.php" class="btn btn-neutral" ><img class="svg" src="assets/icons/sign-in.svg">Cancel</a>
	</div>

	<script>
		$("#passwordForm").submit(function(event) {
			event.preventDefault();
			var current_password	= $("#currentPassword").val();
			var new_password		= $("#newPassword").val();
			var confirm_password	= $("#confirmPassword").val();
			var submit				= $("#passwordSubmit").val();
			
			$("#newSniff").load("app/account/account.php", {
				current_password: current_password,
				new_password: new_password,
				confirm_password: confirm_password,
				password_reset_submit: submit
			});
		});
	</script>

	<?php include __DIR__."/assets/ui/footer.php"; ?>
</body>

</html>