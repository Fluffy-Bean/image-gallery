<?php
if (is_file("index.php")) {
	$root_dir = "";
  } else {
	$root_dir = "../";
  }
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $setup_json['website']['name']; ?></title>

<!-- Stylesheets -->
<link rel="stylesheet" href="<?php echo $root_dir; ?>css/main.css">
<link rel="stylesheet" href="<?php echo $root_dir; ?>css/normalise.css">


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
<script src="<?php echo $root_dir; ?>Sniffle/sniffle.js"></script>
<link rel='stylesheet' href='<?php echo $root_dir; ?>Sniffle/sniffle.css'>

<!-- Flyout script! -->
<script src="<?php echo $root_dir; ?>Flyout/flyout.js"></script>
<link rel='stylesheet' href='<?php echo $root_dir; ?>Flyout/flyout.css'>
