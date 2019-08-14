<?php

$info = file_get_contents("package.json");
$info = (array) json_decode($info);
$files = getFiles(__DIR__);

foreach ($files as $file) {
  $content = file_get_contents($file);
  foreach ($info as $property => $value) {
    $content = str_replace("<*!{$property}!*>", $value, $content);
  }
  file_put_contents($file, $content);
}

function getFiles($dir){
    $all = scandir($dir);
    $files = [];

    foreach ($all as $something) {
      $full_path = "{$dir}/{$something}";

      if (is_file($full_path)) {
        $files[] = $full_path;
      }
      if (is_dir($full_path) && notHiddenExceptForCircle($full_path)) {
        $files = array_merge($files, getFiles($full_path));
      }
    }

    return $files;
}

function notHiddenExceptForCircle($dir) {
  if (substr_count($dir, ".circleci") > 0 && substr_count($dir, ".") == 1) {
    return TRUE;
  }

  if (substr_count($dir, ".") > 0) {
    return FALSE;
  }

  return TRUE;
}
