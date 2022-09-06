<!DOCTYPE html>
<html>

<head>
	<?php include "../ui/header.php"; ?>
</head>

<body>
	<?php
	include "../ui/required.php";
	include "../ui/nav.php";
	?>

	<div class="account-root">
		<h2>Account settings</h2>
		<?php
		if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
			echo "<br>";
			if ($_SESSION["id"] == 1) {
				echo "<h3>Invite Codes</h3>";
				$token_request = mysqli_query($conn, "SELECT * FROM tokens WHERE used = 0");
				while ($token = mysqli_fetch_array($token_request)) {
		?>
					<!-- Button that's displayed with the invite code -->
					<button onclick='copyCode()' class='btn btn-neutral'><?php echo $token['code']; ?></button>
					<!-- Copy code on click -->
					<script>
						function copyCode() {
							navigator.clipboard.writeText("<?php echo $token['code']; ?>");
							sniffleAdd("Info", "Invite code has been copied!", "var(--green)", "<?php echo $root_dir; ?>assets/icons/clipboard-text.svg");
						}
					</script>
			<?php
				}
			}
			?>
			<br>
			<h3 class='space-top'>Danger ahead</h3>
			<p>Resetting your password regularly is a good way of keeping your account safe</p>
			<a class='btn btn-bad' href='https://superdupersecteteuploadtest.fluffybean.gay/account/password-reset.php'><img class='svg' src='../assets/icons/password.svg'>Reset Password</a>
			<br>
			<p>Don't leave! I'm with the science team!</p>
			<a class='btn btn-bad' href='https://superdupersecteteuploadtest.fluffybean.gay/account/logout.php'><img class='svg' src='../assets/icons/sign-out.svg'>Logout</a>
		<?php
		} else {
		?>
			<p>You must be logged in to change your account settings!</p>
			<a class='btn btn-good' href='https://superdupersecteteuploadtest.fluffybean.gay/account/login.php'>Login!</a>
		<?php
		}
		?>
	</div>

	<?php include "../ui/footer.php"; ?>
</body>

</html>