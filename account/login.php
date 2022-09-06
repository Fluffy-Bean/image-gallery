<!DOCTYPE html>
<html>

<head>
	<?php include "../ui/header.php"; ?>
</head>


<body>
	<?php
	include "../ui/required.php";
	include "../ui/nav.php";

	// Check if the user is already logged in, if yes then redirect him to welcome page
	if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	?>
		<script>
			sniffleAdd('Whatcha doing here?', 'You are already logged in! No need to try again', 'var(--black)', '<?php echo $root_dir; ?>assets/icons/warning.svg');
		</script>
	<?php
	}
	?>

	<div class="login-root">
		<h2>Login</h2>
		<p>Passwords are important to keep safe. Don't tell anyone your password, not even Fluffy!</p>
		<br>
		<form id="loginForm" method="POST" enctype="multipart/form-data">
			<input id="loginUsername" class="btn btn-neutral" type="text" name="username" placeholder="Username">
			<input id="loginPassword" class="btn btn-neutral" type="password" name="password" placeholder="Password">
			<br>
			<button id="loginSubmit" class="btn btn-good" type="submit" name="login"><img class="svg" src="../assets/icons/sign-in.svg">Login</button>
		</form>
		<a class='btn btn-neutral' href='https://superdupersecteteuploadtest.fluffybean.gay/account/signup.php'><img class="svg" src="../assets/icons/sign-in.svg">Need an account? Sign up!</a>
	</div>

	<script>
		$("#loginForm").submit(function(event) {
			event.preventDefault();
			var username = $("#loginUsername").val();
			var password = $("#loginPassword").val();
			var submit = $("#loginSubmit").val();
			$("#sniffle").load("../app/account/login.php", {
				username: username,
				password: password,
				submit: submit
			});
		});
	</script>

	<?php include "../ui/footer.php"; ?>
</body>

</html>