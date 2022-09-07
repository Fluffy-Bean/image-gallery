<nav class="nav-root flex-left">
  <div class="nav-name flex-left">
    <p><?php echo $setup_json['name']; ?></p>
  </div>
  <div class="nav-links flex-left">
    <a class='btn' href='<?php echo $root_dir; ?>index.php'><img class='svg' src='<?php echo $root_dir; ?>assets/icons/house.svg'><span class='nav-hide'>Home</span></a>
    <hr>
    <a class='btn' href='<?php echo $root_dir; ?>search.php'><img class='svg' src='<?php echo $root_dir; ?>assets/icons/binoculars.svg'><span class='nav-hide'>Search</span></a>
    <hr>
    <?php
    if (loggedin()) {
      echo "<a class='btn' href='".$root_dir."upload.php'><img class='svg' src='".$root_dir."assets/icons/upload.svg'><span class='nav-hide'>Upload</span></a>";
      echo "<hr>";
      echo "<a class='btn' href='".$root_dir."/account.php'><img class='svg' src='".$root_dir."assets/icons/user-circle.svg'><span class='nav-hide'>".substr($_SESSION["username"], 0, 15)."</span></a>";
    } else {
      echo "<a class='btn' href='".$root_dir."/account.php'><img class='svg' src='".$root_dir."assets/icons/sign-in.svg'><span class='nav-hide'>Login</span></a>";
    }
    ?>
  </div>
</nav>
