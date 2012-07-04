<?php

$files = getDirectoryList("./");
echo "<pre>";
rsort($files);
$pattern = "/^install.[0-9]+\.[0-9]+\.[0-9]+\.zip$/";
var_dump($files);echo "<br>";
foreach ($files as $file) {
  $zipFileVersion = preg_match($pattern, $file);
  if ($zipFileVersion !== 0) {
    var_dump($file); echo "<br>";
    $zipFileFound = $file;
    $fileExp = explode(".", $file);
    $zipVersion = $fileExp[1] . "." . $fileExp[2] . "." . $fileExp[3];
  }
}
var_dump($zipVersion);echo "<br>";
var_dump($zipFileFound);echo "<br>";
//uzimanje fajlova iz direktorijuma za zip
function getDirectoryList($directory) {

    // create an array to hold directory list
    $results = array();

    // create a handler for the directory
    $handler = opendir($directory);

    // open directory and walk through the filenames
    while ($file = readdir($handler)) {

        // if file isn't this directory or its parent, add it to the results
        if ($file != "." && $file != "..") {
            $results[] = $file;
        }
    }

    // tidy up: close the handler
    closedir($handler);

    // done!
    return $results;
}
?>