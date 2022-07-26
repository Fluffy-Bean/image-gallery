<?php session_start(); ?>

<nav class="nav-root flex-left between">
  <div class="nav-name flex-left">
    <h3>Fluffys Amazing Gallery!</h3>
  </div>
  <div class="nav-links flex-left">
    <a class="btn alert-default" href="https://superdupersecteteuploadtest.fluffybean.gay"><img class="svg" src="assets/icons/house.svg"><span class="nav-hide">Home</span></a>
    <hr>
    <?php
    if (isset($_SESSION["username"])) {
      echo "<a class='btn alert-default' href='https://superdupersecteteuploadtest.fluffybean.gay/upload.php'><img class='svg' src='assets/icons/upload.svg'><span class='nav-hide'>Upload</span></a>";
      echo "<hr>";
      echo "<a class='btn alert-default' href='https://superdupersecteteuploadtest.fluffybean.gay/account.php'><img class='svg' src='assets/icons/user-circle.svg'><span class='nav-hide'>".$_SESSION["username"]."</span></a>";
    } else {
      echo "<a class='btn alert-default' href='https://superdupersecteteuploadtest.fluffybean.gay/login.php'><img class='svg' src='assets/icons/user-circle-plus.svg'><span class='nav-hide'>Login</span></a>";
    }
    ?>
  </div>
</nav>
