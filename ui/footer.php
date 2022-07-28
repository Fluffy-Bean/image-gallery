<?php
if (is_dir("assets/icons/")) {
  $dir = "assets/icons/";
} else {
  $dir = "../assets/icons/";
}
?>

<footer class="footer-root flex-left around">
  <div class="footer-child center flex-down">
    <h3>Contact me</h3>
    <a class='link' href="https://t.me/Fluffy_Bean">
      <img class='svg' src='<?php echo $dir; ?>telegram-logo.svg'>
      Telegram</a>
    <a class='link' href="https://twitter.com/fluffybeanUwU">
      <img class='svg' src='<?php echo $dir; ?>twitter-logo.svg'>
      Twitter</a>
    <a class='link' href="https://github.com/Fluffy-Bean">
      <img class='svg' src='<?php echo $dir; ?>github-logo.svg'>
      GitHub</a>
  </div>
  <div class="footer-child center flex-down">
    <h3>Information</h3>
    <a class='link' href="https://superdupersecteteuploadtest.fluffybean.gay/info/about.php">
      <img class='svg' src='<?php echo $dir; ?>scroll.svg'>
      About</a>
    <a href="https://github.com/Fluffy-Bean/image-gallery">
      <img class='svg' src='<?php echo $dir; ?>github-logo.svg'>
      Project Code</a>
    <a href="https://github.com/Fluffy-Bean/image-gallery/blob/main/LICENSE.md">
      <img class='svg' src='<?php echo $dir; ?>link.svg'>
      Legal</a>
  </div>
  <div class="footer-child center flex-down">
    <h3>Other Work</h3>
    <a class='link' href="https://testing.fluffybean.gay">
      <img class='svg' src='<?php echo $dir; ?>link.svg'>
      Testing Page</a>
    <a class='link' href="https://gay.fluffybean.gay">
      <img class='svg' src='<?php echo $dir; ?>link.svg'>
      My website!</a>
  </div>
</footer>
