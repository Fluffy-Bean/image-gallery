<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
	// Get image info for image meta
	if ( basename($_SERVER['PHP_SELF']) == "image.php") {
		$image = $image_info->get_image_info($conn, $_GET['id']);
	}
?>
<!-- Auto generated header tags -->
<title><?php echo $user_settings['website_name']; ?></title>
<meta name="description" content="<?php echo $user_settings['website_description']; ?>"/>
<meta name="keywords" content="Image, Gallery"/>
<meta name="author" content="<?php echo $user_settings['user_name']; ?>"/>
<!-- OG -->
<meta property="og:title" content="<?php echo $user_settings['website_name']; ?>"/>
<meta property="og:description" content="<?php echo $user_settings['website_description']; ?>"/>
<meta property="og:image" content="<?php echo "images/".$image['imagename']; ?>"/>
<meta property="og:type" content="website"/>
<meta name="theme-color" content="#8C977D">
<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:description" content="<?php echo $user_settings['website_description']; ?>">/
<meta name="twitter:title" content="<?php echo $user_settings['website_name']; ?>"/>


<!-- Stylesheets -->
<link rel="stylesheet" href="css/main.css">

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
