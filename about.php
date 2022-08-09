<?php include "ui/required.php"; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lynx Gallery</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="css/master.css">
  <link rel="stylesheet" href="css/normalise.css">

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Secular+One&display=swap">

  <!-- JQuery -->
  <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous">
  </script>

  <!-- Sniffle script! -->
  <script src="Sniffle/sniffle.js"></script>
  <link rel='stylesheet' href='Sniffle/sniffle.css'>

  <!-- Flyout script! -->
  <script src="Flyout/flyout.js"></script>
  <link rel='stylesheet' href='Flyout/flyout.css'>
</head>
<body>
  <?php include "ui/nav.php"; ?>

  <div class="about-root default-window">
    <h2 id="about">What is Fluffys Amazing Gallery?</h2>
    <p>Fluffys Amazing Gallery is a smol project I originally started to control the images on my main page, but quickly turned into something much bigger...</p>
    <p>What Do I want this to become in the future? No clue, but I do want this to be usable by others, if its a file they download a docker image they setup on your own web server.</p>
    <p>Will it become that any time soon? No, but. I am going to work on this untill it becomes what I want it to be!</p>

    <h2 class="space-top-large" id="add-this">Can you add "A" or "B"?</h2>
    <p>No.</p>

    <h2 class="space-top-large" id="guide">How do I use this!</h2>
    <p>First you must obtain the invite code. If you don't have one and are interested in trying this, feel free to DM me on Telegram!</p>
    <p>But once you're done doing that, you can start making your account <a class='link' href="https://superdupersecteteuploadtest.fluffybean.gay/account/signup.php">at the signup page here</a>.</p>
    <p>From there you should be able to go and login <a class='link' href="https://superdupersecteteuploadtest.fluffybean.gay/account/login.php">at this fancy page here</a>!</p>
    <p>Now you should see "Welcome (your username)" at the homepage. From there navigate to the navbar and click on the upload button. Choose your file, enter the description and your image is up!</p>

    <h2 class="space-top-large">Credits!</h2>
    <p>To Carty for being super cool again and helping me get started with SQL and PHP!</p>
    <p>To <a class='link' href="https://phosphoricons.com/">Phosphor</a> for providing nice SVG icons.</p>
    <p>To mrHDash...</p>
  </div>

  <?php include "ui/footer.php"; ?>
</body>
</html>
