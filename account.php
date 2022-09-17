<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__."/ui/header.php"; ?>
</head>

<body>
	<?php
	require_once __DIR__."/ui/required.php";
	require_once __DIR__."/ui/nav.php";

	use App\Account;
	use App\Diff;

	$user_info = new Account();
	$diff = new Diff();
	?>

		<?php
		if ($user_info->is_loggedin()) {
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
				if ($user_info->is_admin($conn, $_SESSION['id'])) {
				?>
					<div class="admin-root">
						<h2>Admin controlls</h2>
						<h3>Invite Codes</h3>
						<?php
						$token_request = mysqli_query($conn, "SELECT * FROM tokens WHERE used = 0");
						while ($token = mysqli_fetch_array($token_request)) {
							?>
								<button onclick='copyCode()' class='btn btn-neutral'><?php echo $token['code']; ?></button>
								<script>
									function copyCode() {
										navigator.clipboard.writeText("<?php echo $token['code']; ?>");
										sniffleAdd("Info", "Invite code has been copied!", "var(--green)", "assets/icons/clipboard-text.svg");
									}
								</script>
							<?php
						}
						?>

						<br>

						<div class="tabs">
							<button class="btn btn-neutral tablinks" onclick="openTab(event, 'logs')">Logs</button>
							<button class="btn btn-neutral tablinks" onclick="openTab(event, 'bans')">Bans</button>
							<button class="btn btn-neutral tablinks" onclick="openTab(event, 'users')">User settings</button>
						</div>

						<div id="logs" class="logs tabcontent">
							<div class="log">
								<p>ID</p>
								<p>User IP</p>
								<p>Action</p>
								<p>Time</p>
							</div>
							<?php
								// Reading images from table
								$logs_request = mysqli_query($conn, "SELECT * FROM logs ORDER BY id DESC");

								while ($log = mysqli_fetch_array($logs_request)) {
									?>
										<div class="log">
											<p><?php echo $log['id']; ?></p>
											<p><?php echo $log['ipaddress']; ?></p>
											<p><?php echo $log['action']; ?></p>
											<?php
												$log_time = new DateTime($log['time']);
												echo "<p>" . $log_time->format('Y-m-d H:i:s T') . " | " . $diff->time($log['time']) . "</p>";
											?>
										</div>
									<?php
								}
							?>
						</div>
						
						<div id="bans" class="bans tabcontent">
							<div class="ban">
								<p>ID</p>
								<p>User IP</p>
								<p>Reason</p>
								<p>Lenght</p>
								<p>Time</p>
							</div>
							<?php
								// Reading images from table
								$bans_request = mysqli_query($conn, "SELECT * FROM bans ORDER BY id DESC");

								while ($ban = mysqli_fetch_array($bans_request)) {
									if ($ban['permanent']) {
										echo "<div class='ban perm'>";
									} else {
										echo "<div class='ban'>";
									}
									?>
											<p><?php echo $ban['id']; ?></p>
											<p><?php echo $ban['ipaddress']; ?></p>
											<p><?php echo $ban['reason']; ?></p>
											<p><?php echo $ban['length']; ?> mins</p>
											<?php
												$log_time = new DateTime($ban['time']);
												echo "<p>" . $log_time->format('Y-m-d H:i:s T') . " | " . $diff->time($ban['time']) . "</p>";
											?>
										</div>
									<?php
								}
							?>
						</div>
						
						<div id="users" class="user-settings tabcontent">

							<div class="user">
								<p>ID</p>
								<p>Username</p>
								<p>Last Modified</p>
								<p>User Options</p>
								<p></p>
								<p></p>
							</div>
							<?php
								// Reading images from table
								$user_request = mysqli_query($conn, "SELECT * FROM users");

								while ($user = mysqli_fetch_array($user_request)) {
									if ($user['admin'] || $user['id'] == 1) {
										echo "<div class='user is-admin'>";
									} else {
										echo "<div class='user'>";
									}
									?>
											<p><?php echo $user['id']; ?></p>
											<p><?php echo $user['username']; ?></p>
											<?php
												$user_time = new DateTime($user['created_at']);
												echo "<p>" . $user_time->format('Y-m-d H:i:s T') . " | " . $diff->time($user['created_at']) . "</p>";
											
												if ($user['id'] == 1) {
													?>
														<button class="btn btn-neutral" style="outline: none;">Reset Password</button>
														<button class="btn btn-neutral" style="outline: none;">Delete user</button>
														<button class="btn btn-neutral" style="outline: none;">Toggle admin</button>
													<?php
												} else {
													?>
														<button id="userResetPassword" class="btn btn-bad" onclick="userResetPassword('<?php echo $user['id']; ?>', '<?php echo $user['username']; ?>')">Reset Password</button>
														<button id="userDeleteButton" class="btn btn-bad" onclick="userDelete('<?php echo $user['id']; ?>', '<?php echo $user['username']; ?>')">Delete user</button>
														<button id="userToggleAdmin" class="btn btn-bad" onclick="userToggleAdmin('<?php echo $user['id']; ?>', '<?php echo $user['username']; ?>')">Toggle admin</button>
													<?php
												}
											?>
										</div>
									<?php
								}
							?>
						</div>
						<script src="scripts/account.js"></script>
					</div>
					<?php // UwU
				}
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

	<?php require_once __DIR__."/ui/footer.php"; ?>
</body>

</html>