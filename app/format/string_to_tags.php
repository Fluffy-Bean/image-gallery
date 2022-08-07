<?php
/*
  Clean up long text input and turn into an array for tags

  Returns clean string of words with equal white space between it
*/
function tag_clean($string) {
  // Replace hyphens
  $string = str_replace('-', '_', $string);
  // Regex
  $string = preg_replace('/[^A-Za-z0-9\_ ]/', '', $string);
  // Change to lowercase
  $string = strtolower($string);
  // Removing extra spaces
  $string = preg_replace('/ +/', ' ', $string);

  return $string;
}
