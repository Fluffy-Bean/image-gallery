<?php
session_start();

if (is_dir("assets/icons/")) {
  $dir = "assets/icons/";
} else {
  $dir = "../assets/icons/";
}
?>

<nav class="nav-root flex-left between">
  <div class="nav-name flex-left">
    <h3>OnlyLegs</h3>
  </div>
  <div class="nav-links flex-left">
    <?php
    echo "<a class='btn alert-default' href='https://superdupersecteteuploadtest.fluffybean.gay'><img class='svg' src='".$dir."house.svg'><span class='nav-hide'>Home</span></a>";
    echo "<hr>";
    echo "<a class='btn alert-default flyout-display' href='https://superdupersecteteuploadtest.fluffybean.gay?srch=show'><img class='svg' src='".$dir."binoculars.svg'><span class='nav-hide'>Search</span></a>";
    echo "<hr>";

    if (isset($_SESSION["username"])) {
      echo "<a class='btn alert-default' href='https://superdupersecteteuploadtest.fluffybean.gay/upload.php'><img class='svg' src='".$dir."upload.svg'><span class='nav-hide'>Upload</span></a>";
      echo "<hr>";
      echo "<a class='btn alert-default' href='https://superdupersecteteuploadtest.fluffybean.gay/account/account.php'><img class='svg' src='".$dir."user-circle.svg'><span class='nav-hide'>".substr($_SESSION["username"], 0, 15)."</span></a>";
    } else {
      echo "<a class='btn alert-high' href='https://superdupersecteteuploadtest.fluffybean.gay/account/login.php'><img class='svg' src='".$dir."user-circle-plus.svg'><span class='nav-hide'>Login</span></a>";
    }
    ?>
  </div>
</nav>
