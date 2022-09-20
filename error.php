<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__."/ui/header.php"; ?>
</head>

<body>

<div></div>

<div class="error-root">
    <h2>Woops...</h2>
    <?php
        if ($_GET["e"] == "conn") {
            echo "<p>An error occured while connecting to the server. If you're an admin, check the database configuration and/or make sure the database is alive</p>";
        } else {
            echo "<p>An error occured! But no description was provided.</p>";
        }
    ?>
</div>

<?php require_once __DIR__."/ui/footer.php"; ?>
</body>

</html>