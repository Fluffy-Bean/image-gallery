<?php session_start(); ?>

<nav class="nav-root flex-left between">
  <div class="nav-name flex-left">
    <h3>Fluffys Amazing Gallery!</h3>
  </div>
  <div class="nav-links flex-left">
    <a class="btn alert-default" href="https://superdupersecteteuploadtest.fluffybean.gay"><img class="svg" src="assets/icons/house.svg">Home</a>
    <hr>
    <?php
    if (isset($_SESSION["username"])) {
      echo "<a class='btn alert-default' href='https://superdupersecteteuploadtest.fluffybean.gay/upload.php'><img class='svg' src='assets/icons/upload.svg'>Upload</a>";
      echo "<hr>";
      echo "<a class='btn alert-default' href='https://superdupersecteteuploadtest.fluffybean.gay/account.php'><img class='svg' src='assets/icons/user-circle.svg'>".$_SESSION["username"]."</a>";
    } else {
      echo "<a class='btn alert-default' href='https://superdupersecteteuploadtest.fluffybean.gay/signup.php'><img class='svg' src='assets/icons/user-circle-plus.svg'>Sign Up</a>";
    }
    ?>
  </div>
</nav>
