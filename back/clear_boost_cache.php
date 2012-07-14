<?php

define("CACHE_DIR",'/home/rumali/EatTwitter/web/cache');
define("CACHE_MINUTES", 5);

clear_old_files_from_cache(CACHE_DIR);

function clear_old_files_from_cache($dir) {
  $time_limit_in_cache = CACHE_MINUTES * 60;
  $objects = scandir($dir);
  foreach ($objects as $object) {
    if ($object == "." || $object == "..") {
      continue;
    }
    if ($object == ".htaccess") {
      continue;
    }
  
    $file = $dir . "/" . $object;
    if (is_dir($file)) {
      clear_old_files_from_cache($file);
    } else {
      $age = file_age($file);
      echo $file . ' is ' . $age . "\n";
      if ($age > $time_limit_in_cache) {
        echo 'Unlinking ' . $file . ' is ' . $age . "\n";
        unlink($file);
      }
    }
  }
}

function file_age($file) {
  return time() - filemtime($file);
}
