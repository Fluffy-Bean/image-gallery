<?php
	require_once __DIR__."/app/required.php";
	
	use App\Account;
	use App\Diff;
	use App\Sanity;

	$user_info = new Account();
	$diff = new Diff();
	$sanity = new Sanity();

	$profile_info = $user_info->get_user_info($conn, $_SESSION['id']);
?>

<!DOCTYPE html>
<html>
	<head>
		<?php require_once __DIR__."/assets/ui/header.php"; ?>
	</head>
<body>
	<?php require_once __DIR__."/assets/ui/nav.php"; ?>

		<?php
		if ($user_info->is_loggedin()) {
		?>
			<div class="defaultDecoration defaultSpacing defaultFonts">
				<h2>Profile</h2>
				<div class="pfp-upload">
					<form id="pfpForm" method="POST" enctype="multipart/form-data">
						<h3>Profile Picture</h3>
						<input id="image" class="btn btn-neutral" type="file" placeholder="select image UwU">
						<button id="pfpSubmit" class="btn btn-good" type="submit"><img class="svg" src="assets/icons/upload.svg">Upload Image</button>
					</form>
					<?php
						if (is_file("images/pfp/".$profile_info['pfp_path'])) {
							echo "<img src='images/pfp/".$profile_info['pfp_path']."'>";
						} else {
							echo "<img src='assets/no_image.png'>";
						}
					?>
					<script>
						$("#pfpForm").submit(function(event) {
							event.preventDefault();
							// Check if image avalible
							var file = $("#image").val();
							if (file != "") {
								// Make form
								var formData = new FormData();

								// Get image
								var image_data = $("#image").prop("files")[0];
								formData.append("image", image_data);
								// Submit data
								var submit = $("#pfpSubmit").val();
								formData.append("pfp_submit", submit);

								// Upload the information
								$.ajax({
									url: 'app/account/account.php',
									type: 'post',
									data: formData,
									contentType: false,
									processData: false,
									success: function(response) {
										$("#newSniff").html(response);
									}
								});

								// Empty values
								$("#image").val("");
								$("#submit").val("");
							} else {
								sniffleAdd('Gwha!', 'Pls provide image', 'var(--warning)', 'assets/icons/file-search.svg');
							}
						});
					</script>
				</div>
				<br>
				<a href="profile.php?user=<?php echo $_SESSION['id']; ?>" class="btn btn-neutral">Go to profile</a>
			</div>

			<div class="warningDecoration defaultSpacing defaultFonts">
				<h2>Account</h2>
				<p>Don't leave! I'm with the science team!</p>
				<a class='btn btn-bad' href='app/account/logout.php'><img class='svg' src='assets/icons/sign-out.svg'>Forget Me</a>
				<br>
				<a class='btn btn-bad' href='password-reset.php'><img class='svg' src='assets/icons/password.svg'>Reset Password</a>
				<button class="btn btn-bad" onclick="deleteAccount()"><img class='svg' src='assets/icons/trash.svg'>Forget me forever</button>
			</div>
			<script>
				function deleteAccount() {
					var header = "Are you very very sure?";
					var description = "This CANNOT be undone, be very carefull with your decition!!!";
					var actionBox = "<button class='btn btn-bad' onclick='deleteAccountConfirm()'><img class='svg' src='assets/icons/trash.svg'>Delete account (keep posts)</button>\
					<button class='btn btn-bad' onclick='deleteAccountConfirmFull()'><img class='svg' src='assets/icons/trash.svg'>Delete account (delete posts)</button>";

					flyoutShow(header, description, actionBox);
				}

				function deleteAccountConfirm () {
					var header = "Deleting just your account!";
					var description = "This is your last warning, so enter your password now.";
					var actionBox = "<form id='accountDelete' method='POST'>\
					<input id='accountDeletePassword' class='btn btn-neutral' type='password' name='password' placeholder='Password'>\
					<button id='accountDeleteSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/trash.svg'>Delete account (keep posts)</button>\
					</form>";
					
					flyoutShow(header, description, actionBox);

					$("#accountDelete").submit(function(event) {
						event.preventDefault();
						var accountDeletePassword = $("#accountDeletePassword").val();
						var accountDeleteSubmit = $("#accountDeleteSubmit").val();
						$("#newSniff").load("app/account/account.php", {
							delete_id: <?php echo $_SESSION['id']; ?>,
							full: 'false',
							account_password: accountDeletePassword,
							account_delete_submit: accountDeleteSubmit
						});
					});
				}

				function deleteAccountConfirmFull () {
					var header = "Deleting EVERYTHINGGGGG";
					var description = "This is your last warning, so enter your password now.";
					var actionBox = "<form id='accountDeleteFull' method='POST'>\
					<input id='accountDeletePassword' class='btn btn-neutral' type='password' name='password' placeholder='Password'>\
					<button id='accountDeleteSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/trash.svg'>Delete account (delete posts)</button>\
					</form>";

					flyoutShow(header, description, actionBox);

					$("#accountDeleteFull").submit(function(event) {
						event.preventDefault();
						var accountDeletePassword = $("#accountDeletePassword").val();
						var accountDeleteSubmit = $("#accountDeleteSubmit").val();
						$("#newSniff").load("app/account/account.php", {
							delete_id: <?php echo $_SESSION['id']; ?>,
							full: 'true',
							account_password: accountDeletePassword,
							account_delete_submit: accountDeleteSubmit
						});
					});
				}
			</script>

			<?php
				if ($user_info->is_admin($conn, $_SESSION['id'])) {
				?>
					<div class="defaultDecoration defaultSpacing defaultFonts">
						<h2>Admin</h2>
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
							<button class="btn btn-neutral tablinks" onclick="openTab(event, 'users')">Users</button>
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
												echo "<p>".$log_time->format('Y-m-d H:i:s T')." (".$diff->time($log['time']).")</p>";
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
												echo "<p>".$log_time->format('Y-m-d H:i:s T')." (".$diff->time($ban['time']).")</p>";
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
												echo "<p>".$user_time->format('Y-m-d H:i:s T')." (".$diff->time($user['last_modified']).")</p>";
											
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
							<script>
								function userResetPassword(id, username) {
									var header = "UwU whats the new passywassy code?";
									var description = "Do this only if "+username+" has forgotten their password, DO NOT abuse this power";
									var actionBox = "<form id='userResetPasswordForm' method='POST' enctype='multipart/form-data'>\
									<input id='userNewPassword' class='btn btn-neutral' type='password' name='new_password' placeholder='New Password'>\
									<input id='userConfirmPassword' class='btn btn-neutral' type='password' name='confirm_password' placeholder='Confirm Password'>\
									<br>\
									<button id='userPasswordSubmit' class='btn btn-bad' type='submit' name='reset' value='"+id+"'><img class='svg' src='assets/icons/password.svg'>Reset</button>\
									</form>";

									flyoutShow(header, description, actionBox);
									
									$("#userResetPasswordForm").submit(function(event) {
										event.preventDefault();
										var new_password = $("#userNewPassword").val();
										var confirm_password = $("#userConfirmPassword").val();
										var submit = $("#userPasswordSubmit").val();
										var userId = $("#userPasswordSubmit").val();
										$("#newSniff").load("app/account/account.php", {
											new_password: new_password,
											confirm_password: confirm_password,
											id: userId,
											password_reset_submit: submit
										});
									});
								}

								function userDelete(id, username) {
									var header = "Are you very very sure?";
									var description = "This CANNOT be undone, be very carefull with your decition... There is no second warning!";
									var actionBox = "<form id='userDelete' method='POST'>\
									<button id='userDeleteSubmit' class='btn btn-bad' type='submit' value='"+id+"'><img class='svg' src='assets/icons/trash.svg'>Delete user "+username+" (keep posts)</button>\
									</form>\
									<form id='userDeleteFull' method='POST'>\
									<button id='userDeleteSubmit' class='btn btn-bad' type='submit' value='"+id+"'><img class='svg' src='assets/icons/trash.svg'>Delete user "+username+" (delete posts)</button>\
									</form>";

									flyoutShow(header, description, actionBox);
									
									$("#userDelete").submit(function(event) {
										event.preventDefault();
										var id = $("#userDeleteSubmit").val();
										var userDeleteSubmit = $("#userDeleteSubmit").val();
										$("#newSniff").load("app/account/account.php", {
											delete_id: id,
											full: false,
											account_delete_submit: userDeleteSubmit
										});
									});
									$("#userDeleteFull").submit(function(event) {
										event.preventDefault();
										var id = $("#userDeleteSubmit").val();
										var userDeleteSubmit = $("#userDeleteSubmit").val();
										$("#newSniff").load("app/account/account.php", {
											delete_id: id,
											full: true,
											account_delete_submit: userDeleteSubmit
										});
									});
								}

								function userToggleAdmin(id, username) {
									var header = "With great power comes great responsibility...";
									var description = "Do you trust this user? With admin permitions they can cause a whole lot of damage to this place, so make sure you're very very sure";
									var actionBox = "<form id='toggleAdminConfirm' method='POST'>\
									<button id='toggleAdminSubmit' class='btn btn-bad' type='submit' value='"+id+"'>Toggle "+username+"'s admin status</button>\
									</form>";

									flyoutShow(header, description, actionBox);
									
									$("#toggleAdminConfirm").submit(function(event) {
										event.preventDefault();
										var toggleAdminSubmit = $("#toggleAdminSubmit").val();
										$("#newSniff").load("app/account/account.php", {
											id: toggleAdminSubmit,
											toggle_admin: toggleAdminSubmit
										});
									});
								}
							</script>
						</div>
						<script>
							function openTab(evt, tabName) {
								var i, tabcontent, tablinks;

								tabcontent = document.getElementsByClassName("tabcontent");
								for (i = 0; i < tabcontent.length; i++) {
									tabcontent[i].style.display = "none";
								}

								tablinks = document.getElementsByClassName("tablinks");
								for (i = 0; i < tablinks.length; i++) {
									tablinks[i].className = tablinks[i].className.replace(" active-tab", "");
								}

								document.getElementById(tabName).style.display = "flex";
								evt.currentTarget.className += " active-tab";
							}
						</script>
					</div>

					<div class="sanity-check defaultDecoration defaultSpacing defaultFonts">
							<h2>Sanity check</h2>
							<?php
								$check_sanity = $sanity->get_results();

								if (empty($check_sanity) || !isset($check_sanity)) {
									echo "<p class='btn btn-good' style='outline: none;'>No errors! Lookin' good</p>";
								} else {
									?>
										<style>
											.sanity-check {
												border-color: var(--warning);
											}
										</style>
									<?php
									foreach ($check_sanity as $result) {
										if (str_contains($result, "Critical")) {
											echo "<p class='btn btn-bad' style='outline: none; cursor: default;'>".$result."</p>";
										} elseif (str_contains($result, "Warning")) {
											echo "<p class='btn btn-warning' style='outline: none; cursor: default;'>".$result."</p>";
										}
									}
								}
							?>
					</div>
					<?php
				}
		} else {
		?>
			<div class="login-root defaultDecoration defaultSpacing defaultFonts">
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
					$("#newSniff").load("app/account/account.php", {
						username: username,
						password: password,
						submit_login: submit
					});
				});
			</script>

			<div class="signup-root defaultDecoration defaultSpacing defaultFonts" style="display: none;">
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
					$("#newSniff").load("app/account/account.php", {
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

	<?php require_once __DIR__."/assets/ui/footer.php"; ?>
</body>

</html>