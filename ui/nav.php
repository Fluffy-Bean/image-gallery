<?php
use App\Account;

$loggedin = new Account();
?>

<nav class="nav-root flex-left">
  <div class="nav-name flex-left">
    <p><?php echo $user_settings['website']['name']; ?></p>
  </div>
  <div class="nav-links flex-left">
    <a class='btn' href='index.php'><img class='svg' src='assets/icons/house.svg'><span class='nav-hide'>Home</span></a>
    <hr>
    <?php
    if ($loggedin->is_loggedin()) {
      ?>
      <a class='btn' href='upload.php'><img class='svg' src='assets/icons/upload.svg'><span class='nav-hide'>Upload</span></a>
      <hr>
      <a class='btn' href='account.php'><img class='svg' src='assets/icons/gear.svg'><span class='nav-hide'><?php echo substr($_SESSION["username"], 0, 15); ?></span></a>
      <?php
    } else {
      ?>
      <a class='btn' href='account.php'><img class='svg' src='assets/icons/sign-in.svg'><span class='nav-hide'>Login</span></a>
      <?php
    }
    ?>
  </div>
</nav>
