<!DOCTYPE html>
<html>

<head>
	<?php include "ui/header.php"; ?>
</head>

<body>
	<?php
	include "ui/required.php";
	include "ui/nav.php";
	?>

		<?php
		if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
		?>
			<div class="account-root">
				<h2>Settings</h2>
				<h3 class='space-top'>Account</h3>
				<p>Resetting your password regularly is a good way of keeping your account safe</p>
				<a class='btn btn-bad' href='password-reset.php'><img class='svg' src='assets/icons/password.svg'>Reset Password</a>
				<br>
				<p>Don't leave! I'm with the science team!</p>
				<a class='btn btn-bad' href='app/account/logout.php'><img class='svg' src='assets/icons/sign-out.svg'>Logout</a>
			</div>
			<?php
				if ($_SESSION["id"] == 1) {
			?>
					<div class="admin-root">
						<h2>Admin controlls</h2>
						<br>
						<h3>Invite Codes</h3>
						<?php
						$token_request = mysqli_query($conn, "SELECT * FROM tokens WHERE used = 0");
						while ($token = mysqli_fetch_array($token_request)) {
						?>
							<br>
							<button onclick='copyCode()' class='btn btn-neutral'><?php echo $token['code']; ?></button>
							<script>
								function copyCode() {
									navigator.clipboard.writeText("<?php echo $token['code']; ?>");
									sniffleAdd("Info", "Invite code has been copied!", "var(--green)", "<?php echo $root_dir; ?>assets/icons/clipboard-text.svg");
								}
							</script>
						<?php
						}
						?>
						<br>
						<h3>Database info</h3>
						<?php
							echo "<p>Address: ".$database['ip'].":".$database['port']."</p>";
							echo "<p>Username: ".$database['username']."</p>";
							echo "<p>Password: Not displayed</p>";
							echo "<p>Database: ".$database['database']."</p>";
						?>
					</div>
				<?php
				}
				?>
		<?php
		} else {
		?>
			<div class="login-root">
				<h2>Login</h2>
				<p>Passwords are important to keep safe. Don't tell anyone your password, not even Fluffy!</p>
				<br>
				<form id="loginForm" method="POST" enctype="multipart/form-data">
					<input id="loginUsername" class="btn btn-neutral" type="text" name="username" placeholder="Username">
					<input id="loginPassword" class="btn btn-neutral" type="password" name="password" placeholder="Password">
					<br>
					<button id="loginSubmit" class="btn btn-good" type="submit" name="login"><img class="svg" src="assets/icons/sign-in.svg">Login</button>
				</form>
				<button class='btn btn-neutral' onclick="signupShow()"><img class="svg" src="assets/icons/sign-in.svg">Need an account?</button>
			</div>
			<script>
				$("#loginForm").submit(function(event) {
					event.preventDefault();
					var username = $("#loginUsername").val();
					var password = $("#loginPassword").val();
					var submit = $("#loginSubmit").val();
					$("#sniffle").load("app/account/account.php", {
						username: username,
						password: password,
						submit_login: submit
					});
				});
			</script>

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
					<button id="signupSubmit" class="btn btn-good" type="submit" name="signup"><img class="svg" src="assets/icons/sign-in.svg">Sign Up</button>
				</form>
				<button class='btn btn-neutral' onclick="loginShow()"><img class="svg" src="assets/icons/sign-in.svg">I already got an account!</button>
			</div>
			<script>
				$("#signupForm").submit(function(event) {
					event.preventDefault();
					var username = $("#signupUsername").val();
					var password = $("#signupPassword").val();
					var confirm_password = $("#signupPasswordConfirm").val();
					var token = $("#signupToken").val();
					var submit = $("#signupSubmit").val();
					$("#sniffle").load("app/account/account.php", {
						username: username,
						password: password,
						confirm_password: confirm_password,
						token: token,
						submit_signup: submit
					});
				});
			</script>

			<script>
				function loginShow() {
					document.querySelector(".login-root").style.display = "block";
					document.querySelector(".signup-root").style.display = "none";
				};
				function signupShow() {
					document.querySelector(".signup-root").style.display = "block";
					document.querySelector(".login-root").style.display = "none";
				};
			</script>
		<?php
		}
		?>

	<?php include "ui/footer.php"; ?>
</body>

</html>