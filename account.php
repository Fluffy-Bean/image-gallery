<?php
	require __DIR__."/app/required.php";
	
	use App\Account;
	use App\Diff;
	use App\Make;

	$user_info	= new Account();
	$diff		= new Diff();
	$make_stuff	= new Make();

	$profile_info = $user_info->get_user_info($conn, $_SESSION['id']);
	$join_date = new DateTime($profile_info['created_at']);
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include __DIR__."/assets/ui/header.php"; ?>
	</head>
<body>
	<?php 
		include __DIR__."/assets/ui/nav.php";
		
		if (isset($_SESSION['err'])) {
			?>
				<script>
					sniffleAdd('Woopsie', '<?php echo $_SESSION["err"]; ?>', 'var(--warning)', 'assets/icons/cross.svg');
				</script>
			<?php
			unset($_SESSION['err']);
		}

		if ($user_info->is_loggedin($conn)) {
			?>
				<div class="profile-root defaultDecoration defaultSpacing defaultFonts">
					<?php
						if (is_file("usr/images/pfp/".$profile_info['pfp_path'])) {
							echo "<img src='usr/images/pfp/".$profile_info['pfp_path']."'>";

							$pfp_colour = $make_stuff->get_image_colour("usr/images/pfp/".$profile_info['pfp_path']);
							if (empty($pfp_colour)) $pfp_colour = "var(--bg-3)";
							?>
								<style>
									.profile-root {
										background-image: linear-gradient(120deg, <?php echo $pfp_colour; ?>, var(--bg-3) 80%) !important;
									}
									@media (max-width: 669px) {
										.profile-root {
											background-image: linear-gradient(200deg, <?php echo $pfp_colour; ?>, var(--bg-3) 80%) !important;
										}
									}
								</style>
							<?php
						} else {
							echo "<img src='assets/no_image.png'>";
						}
					?>
				<h2>
					<?php
						echo $_SESSION['username'];
						if ($user_info->is_admin($conn, $_SESSION['id'])) echo "<span style='color: var(--accent); font-size: 16px; margin-left: 0.5rem;'>Admin</span>";
					?>
				</h2>
				<div class="profile-info">
					<p id="joinDate">Member since: <?php echo $join_date->format('d/m/Y T'); ?></p>
					<script>
						var updateDate = new Date('<?php echo $join_date->format('m/d/Y T'); ?>');
						var format = {year: 'numeric', month: 'short', day: 'numeric'};
										
						updateDate = updateDate.toLocaleDateString('en-GB', format);

						$("#joinDate").html("Member since: "+updateDate);
					</script>
				</div>
			</div>

			<div class="defaultDecoration defaultSpacing defaultFonts">
				<h2>Profile</h2>
				<h3>Profile Picture</h3>
				<form id="pfpForm" method="POST" enctype="multipart/form-data">
					<input id="image" class="btn btn-neutral" type="file" placeholder="select image UwU">
					<button id="pfpSubmit" class="btn btn-good btn-icon" type="submit"><img class="svg" src="assets/icons/upload.svg"></button>
				</form>
				<script>
					$("#pfpForm").submit(function(event) {
						event.preventDefault();
						// Check if image avalible
						var file = $("#image").val();

						if (file == "") {
							sniffleAdd('Gwha!', 'Pls provide image', 'var(--warning)', 'assets/icons/file-search.svg');
							return;
						}

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
					});
				</script>
			</div>

			<div class="warningDecoration defaultSpacing defaultFonts">
				<h2>Account</h2>
				<p>Don't leave! I'm with the science team!</p>
				<a class='btn btn-bad' href='app/account/logout.php'><img class='svg' src='assets/icons/sign-out.svg'>Forget Me</a>
				<br>
				<p>Scawwy</p>
				<a class='btn btn-bad' href='password-reset.php'><img class='svg' src='assets/icons/password.svg'>Reset Password</a>
				<button class="btn btn-bad" onclick="deleteAccount()"><img class='svg' src='assets/icons/trash.svg'>Forget me forever</button>
			</div>
			<script>
				function deleteAccount() {
					var header		= "Are you very very sure?";
					var description	= "This CANNOT be undone, be very carefull with your decition!!!";
					var actionBox	= "<button class='btn btn-bad' onclick='deleteAccountConfirm()'><img class='svg' src='assets/icons/trash.svg'>Delete account (keep posts)</button>\
					<button class='btn btn-bad' onclick='deleteAccountConfirmFull()'><img class='svg' src='assets/icons/trash.svg'>Delete account (delete posts)</button>";

					flyoutShow(header, description, actionBox);
				}

				function deleteAccountConfirm () {
					var header		= "Deleting just your account!";
					var description	= "This is your last warning, so enter your password now.";
					var actionBox	= "<form id='accountDelete' method='POST'>\
					<input id='accountDeletePassword' class='btn btn-neutral' type='password' name='password' placeholder='Password'>\
					<button id='accountDeleteSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/trash.svg'>Delete account (keep posts)</button>\
					</form>";
					
					flyoutShow(header, description, actionBox);

					$("#accountDelete").submit(function(event) {
						event.preventDefault();
						var accountDeletePassword	= $("#accountDeletePassword").val();
						var accountDeleteSubmit		= $("#accountDeleteSubmit").val();
						$("#newSniff").load("app/account/account.php", {
							delete_id: <?php echo $_SESSION['id']; ?>,
							full: 'false',
							account_password: accountDeletePassword,
							account_delete_submit: accountDeleteSubmit
						});
					});
				}

				function deleteAccountConfirmFull () {
					var header		= "Deleting EVERYTHINGGGGG";
					var description	= "This is your last warning, so enter your password now.";
					var actionBox	= "<form id='accountDeleteFull' method='POST'>\
					<input id='accountDeletePassword' class='btn btn-neutral' type='password' name='password' placeholder='Password'>\
					<button id='accountDeleteSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/trash.svg'>Delete account (delete posts)</button>\
					</form>";

					flyoutShow(header, description, actionBox);

					$("#accountDeleteFull").submit(function(event) {
						event.preventDefault();
						var accountDeletePassword	= $("#accountDeletePassword").val();
						var accountDeleteSubmit		= $("#accountDeleteSubmit").val();
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
					$sql_start = microtime(true);
				?>
					<div class="sanity-check defaultDecoration defaultSpacing defaultFonts">
						<h2>Website</h2>

						<h3>Invite Codes</h3>
						<div style="display: flex; flex-direction: column; gap: 0.5rem;" id="tokenList">
							<?php
							$token_request = mysqli_query($conn, "SELECT * FROM tokens WHERE used = 0");
							if (mysqli_num_rows($token_request) > 0) {
								while ($token = mysqli_fetch_array($token_request)) {
									?>
										<div style="display: flex; flex-direction: row; gap: 0.5rem;">
											<button onclick='copyCode(this)' class='btn btn-neutral btn-code'><?php echo $token['code']; ?></button>
											<button onclick='regenerateCode("<?php echo $token["code"]; ?>", this)' class='btn btn-good btn-icon'><img src="assets/icons/arrow-clockwise.svg"></button>
											<button onclick='deleteCode(<?php echo $token["id"]; ?>)' class='btn btn-bad btn-icon'><img src="assets/icons/cross.svg"></button>
										</div>
									<?php
								}
							} else {
								echo "<div class='info-text defaultFonts' style='text-align: center !important; margin-left: 0 !important;'>
									<p>No invite codes/tokens :c</p>
								</div>";
							}						
							?>
						</div>
						<button onclick='generateCode()' class='btn btn-good'>Generate code</button>
						<script>
							function refreshList() {
								$("#tokenList").load("app/account/token.php", {
									refresh: 'true'
								});
							}
							function copyCode(thisButton) {
								var text = thisButton.innerHTML;
								navigator.clipboard.writeText(text);
								sniffleAdd("Info", "Invite code has been copied!", "var(--green)", "assets/icons/clipboard-text.svg");
							}
							function regenerateCode(code, thisButton) {
								$.ajax ({
									url: "app/account/token.php",
									type: "POST",
									data: { regenerate: code },

									success: function(response) {
										if (response == true) {
											sniffleAdd("Woop!", "Invite code has been regenerated!", "var(--green)", "assets/icons/check.svg");
											refreshList();
										} else {
											sniffleAdd("Oops!", "Something went wrong!", "var(--red)", "assets/icons/cross.svg");
										}
									},
									error: function() {
										sniffleAdd("AAAAAAAAAAAAAAAAA", "Something went wrong!", "var(--red)", "assets/icons/cross.svg");
									}
								});
							}
							function generateCode() {
								$.ajax ({
									url: "app/account/token.php",
									type: "POST",
									data: { generate: true },

									success: function(response) {
										if (response == true) {
											sniffleAdd("Woop!", "Invite code has been generated!", "var(--green)", "assets/icons/check.svg");
											refreshList();
										} else {
											sniffleAdd("Oops!", "Something went wrong!", "var(--red)", "assets/icons/cross.svg");
										}
									},
									error: function() {
										sniffleAdd("AAAAAAAAAAAAAAAAA", "Something went wrong!", "var(--red)", "assets/icons/cross.svg");
									}
								});
							}
							function deleteCode(codeID, thisButton) {
								$.ajax ({
									url: "app/account/token.php",
									type: "POST",
									data: { delete: codeID },

									success: function(response) {
										if (response == true) {
											sniffleAdd("Woop!", "Invite code has been deleted!", "var(--green)", "assets/icons/check.svg");
											refreshList();
										} else {
											sniffleAdd("AAAAAAAAAAAAAAAAA", "Something went wrong!", "var(--red)", "assets/icons/cross.svg");
										}
									},
									error: function() {
										sniffleAdd("AAAAAAAAAAAAAAAAA", "Something went wrong!", "var(--red)", "assets/icons/cross.svg");
									}
								});
							}
							function gwa() {
								$.ajax ({
									url: "app/account/token.php",
									type: "POST",
									data: { gwagwa: true },

									success: function(response) {
										if (response == true) {
											sniffleAdd("Woop!", "Invite code has been deleted!", "var(--green)", "assets/icons/check.svg");
											refreshList();
										} else {
											sniffleAdd("AAAAAAAAAAAAAAAAA", "Something went wrong!", "var(--red)", "assets/icons/cross.svg");
										}
									},
									error: function() {
										sniffleAdd("AAAAAAAAAAAAAAAAA", "Something went wrong!", "var(--red)", "assets/icons/cross.svg");
									}
								});
							}
						</script>

						<br><h3>Admin</h3>
						<div class="tabs">
							<button class="btn btn-neutral tablinks" onclick="openTab(event, 'logs')">Logs</button>
							<button class="btn btn-neutral tablinks" onclick="openTab(event, 'bans')">Bans</button>
							<button class="btn btn-neutral tablinks" onclick="openTab(event, 'users')">Users</button>
						</div>

						<div id="logs" class="logs tabcontent">
							<div class="log">
								<p>ID</p> <p>User IP</p> <p>Action</p> <p>Time</p>
							</div>
							<?php
								// Reading images from table
								$logs_request = mysqli_query($conn, "SELECT * FROM logs ORDER BY id DESC LIMIT 100");

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
							<div>
								<script>
									function loadMore() {
										$.ajax ({
											url: "app/account/load.php",
											type: "POST",
											data: { log: true},

											success: function(response) {
												$("#logs").html(response);
											}
										});
									}

									if ($("#logs").children().length > 100) {
										var trueHeight = $("#logs").children().length * document.getElementById('logs').children.item(1).clientHeight;
	
										$('#logs').scroll (function() {
											if ($(this).scrollTop() + $(this).innerHeight() >= trueHeight - 100) {
												loadMore();
											}
										});
									}
								</script>
							</div>
						</div>
						<div id="bans" class="bans tabcontent">
							<div class="ban">
								<p>ID</p> <p>User IP</p> <p>Reason</p> <p>Lenght</p> <p>Time</p>
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
								<p>ID</p> <p>Username</p> <p>Last Modified</p> <p>User Options</p> <p></p> <p></p>
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
													echo "<p></p><p></p><p></p>";
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
									var header		= "UwU whats the new passywassy code?";
									var description	= "Do this only if "+username+" has forgotten their password, DO NOT abuse this power";
									var actionBox	= "<form id='userResetPasswordForm' method='POST' enctype='multipart/form-data'>\
									<input id='userNewPassword' class='btn btn-neutral' type='password' name='new_password' placeholder='New Password'>\
									<input id='userConfirmPassword' class='btn btn-neutral' type='password' name='confirm_password' placeholder='Confirm Password'>\
									<br>\
									<button id='userPasswordSubmit' class='btn btn-bad' type='submit' name='reset' value='"+id+"'><img class='svg' src='assets/icons/password.svg'>Reset</button>\
									</form>";

									flyoutShow(header, description, actionBox);
									
									$("#userResetPasswordForm").submit(function(event) {
										event.preventDefault();
										var new_password		= $("#userNewPassword").val();
										var confirm_password	= $("#userConfirmPassword").val();
										var submit				= $("#userPasswordSubmit").val();
										var userId				= $("#userPasswordSubmit").val();
										$("#newSniff").load("app/account/account.php", {
											new_password: new_password,
											confirm_password: confirm_password,
											id: userId,
											password_reset_submit: submit
										});
									});
								}

								function userDelete(id, username) {
									var header		= "Are you very very sure?";
									var description	= "This CANNOT be undone, be very carefull with your decition... There is no second warning!";
									var actionBox	= "<form id='userDelete' method='POST'>\
									<button id='userDeleteSubmit' class='btn btn-bad' type='submit' value='"+id+"'><img class='svg' src='assets/icons/trash.svg'>Delete user "+username+" (keep posts)</button>\
									</form>\
									<form id='userDeleteFull' method='POST'>\
									<button id='userDeleteSubmit' class='btn btn-bad' type='submit' value='"+id+"'><img class='svg' src='assets/icons/trash.svg'>Delete user "+username+" (delete posts)</button>\
									</form>";

									flyoutShow(header, description, actionBox);
									
									$("#userDelete").submit(function(event) {
										event.preventDefault();
										var id					= $("#userDeleteSubmit").val();
										var userDeleteSubmit	= $("#userDeleteSubmit").val();
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
									var header		= "With great power comes great responsibility...";
									var description	= "Do you trust this user? With admin permitions they can cause a whole lot of damage to this place, so make sure you're very very sure";
									var actionBox	= "<form id='toggleAdminConfirm' method='POST'>\
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
									tabcontent[i].style.height = "0";
									tabcontent[i].style.overflow = "hidden";
									tabcontent[i].style.transform = "scale(0.9)";
								}

								tablinks = document.getElementsByClassName("tablinks");
								for (i = 0; i < tablinks.length; i++) {
									tablinks[i].className = tablinks[i].className.replace(" active-tab", "");
								}

								document.getElementById(tabName).style.height = "21rem";
								document.getElementById(tabName).style.overflow = "scroll";
								document.getElementById(tabName).style.transform = "scale(1)";
								evt.currentTarget.className += " active-tab";
							}
						</script>

						<br><h3>Sanity check</h3>
						<div id='sanityCheck'></div>
						
						<button class='btn btn-good' onclick='sanityCheck(this)'>Run check</button>
						<script>
							function sanityCheck(thisButton) {
								thisButton.innerHTML = "<img src='assets/icons/circle-notch.svg' class='svg loading'> Running...";
								document.getElementById('sanityCheck').style.cssText = "transform: scale(0.8);opacity: 0;";
								$("#sanityCheck").html("");

								setTimeout(function() {
									$.ajax ({
										url: "app/sanity/sanity.php",
										type: "POST",
										data: { check: true},

										success: function(response) {
											$("#sanityCheck").html(response);
											thisButton.innerHTML = "Run check";
											document.getElementById('sanityCheck').style.cssText = "transform: scale(1);opacity: 1;";
										},
										error: function(error) {
											$("#sanityCheck").html(`<p class='alert alert-bad'>\
											<span class='badge badge-critical'>Critical</span> \
											An error occured when proccessing your request, sowwy :c\
											<br>\
											Response: ${error.status} - ${error.statusText}\
											</p>`);
											thisButton.innerHTML = "Run check";
											document.getElementById('sanityCheck').style.cssText = "transform: scale(1);opacity: 1;";
										}
									});
								}, 621);
							}
						</script>
					</div>
					<?php
					$sql_end = microtime(true);
				}
		} else {
		?>
			<div class="login-root defaultDecoration defaultSpacing defaultFonts">
				<h2>Login</h2>
				<p>Passwords are important to keep safe. Don't tell anyone your password, not even Fluffy!</p>
				<div id="alertsLogin" class="alert-box"></div>
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
					var username	= $("#loginUsername").val();
					var password	= $("#loginPassword").val();
					var submit		= $("#loginSubmit").val();
					$("#alertsLogin").load("app/account/account.php", {
						username: username,
						password: password,
						submit_login: submit
					});
				});
			</script>

			<div class="signup-root defaultDecoration defaultSpacing defaultFonts" style="display: none;">
				<h2>Make account</h2>
				<p>And amazing things happened here...</p>
				<div id="alertsSignup" class="alert-box"></div>
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
					var username			= $("#signupUsername").val();
					var password			= $("#signupPassword").val();
					var confirm_password	= $("#signupPasswordConfirm").val();
					var token				= $("#signupToken").val();
					var submit				= $("#signupSubmit").val();
					$("#alertsSignup").load("app/account/account.php", {
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

	<?php
		include __DIR__."/assets/ui/footer.php";
	?>
</body>

</html>