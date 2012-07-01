<?php

$usableZip = $_GET["version"];

$directory = "./";


$files = scan_directory_recursively("./");

foreach ($files as $key => $file) {
    if (strpos($file, ".zip") !== false) {
        $file = explode("/", $file);
        if ($file[count($file) - 1] != $usableZip) {
            unset($files[$key]);
        }
    } else if (strpos($file, "zipinstall") !== false) {
        unset($files[$key]);
    }
}

$zipPath = explode("/",$_SERVER["PHP_SELF"]);
unset($zipPath[count($zipPath) - 1]);
$zipPath = implode("/", $zipPath);

$zipPath = $_SERVER["HTTP_HOST"] . $zipPath . "/installer.zip";

$destc = "./installer.zip";
$isCreated = create_zip($files, $destc, "./", "./");

if ($isCreated) {
    echo "Installer.zip is successfully created with version $usableZip!<br><a href='http://$zipPath' >Install.zip</a>";
} else {
    echo "Installer.zip is not successfully created!";
}

/**
 * This function is ziping bacuped files form deploy
 *
 * @param array $files
 * @param string $destination
 * @param bool $overwrite
 * @return bool
 */
function create_zip($files = array(), $destination = '', $sourceFolder, $overwrite = false) {
    //if the zip file already exists and overwrite is false, return false    
    if (file_exists($destination) && !$overwrite) {
        return false;
    }
    $valid_files = array();
    //if files were passed in...
    if (is_array($files)) {
        //cycle through each file
        foreach ($files as $file) {
            //make sure the file exists
            if (file_exists($file)) {
                $valid_files[] = $file;
            }
        }
    }
    //if we have good files...
    if (count($valid_files)) {
        //create the archive
        $zip = new ZipArchive();
        if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            return false;
        }
        //add the files
        foreach ($valid_files as $file) {
            $filePath = str_replace($sourceFolder, "", $file);
            $zip->addFile($file, $filePath);
        }

        //close the zip -- done!
        $zip->close();
        
        //check to make sure the file exists
        return file_exists($destination);
    } else {
        return false;
    }
}

/**
 * This function is scaning provided directory and returns array of recursevly read files inside provided directory with full path
 *
 * @param $directory
 * @param bool $filter
 * @return array|bool
 */
function scan_directory_recursively($directory, $filter = FALSE) {
    // if the path has a slash at the end we remove it here
    if (substr($directory, -1) == '/') {
        $directory = substr($directory, 0, -1);
    }

    // if the path is not valid or is not a directory ...
    if (!file_exists($directory) || !is_dir($directory)) {
        // ... we return false and exit the function
        return FALSE;
        // ... else if the path is readable
    } else if (is_readable($directory)) {
        // initialize directory tree variable
        $directory_tree = array();

        // we open the directory
        $directory_list = opendir($directory);

        // and scan through the items inside
        while (FALSE !== ($file = readdir($directory_list))) {
            // if the filepointer is not the current directory
            // or the parent directory
            if ($file != '.' && $file != '..') {
                // we build the new path to scan
                $path = $directory . '/' . $file;

                // if the path is readable
                if (is_readable($path)) {
                    // we split the new path by directories
                    $subdirectories = explode('/', $path);

                    // if the new path is a directory
                    if (is_dir($path)) {
                        // add the directory details to the file list
                        // we scan the new path by calling this function
                        $array = scan_directory_recursively($path, $filter);

                        $directory_tree = array_merge($directory_tree, $array);
                        // if the new path is a file
                    } else if (is_file($path)) {
                        // get the file extension by taking everything after the last dot
                        $extension = end(explode('.', end($subdirectories)));

                        // if there is no filter set or the filter is set and matches
                        if ($filter === FALSE || $filter == $extension) {
                            // add the file details to the file list
                            $directory_tree[] = $path;
                        }
                    }
                }
            }
        }
        // close the directory
        closedir($directory_list);

        // return file list
        return $directory_tree;

        // if the path is not readable ...
    } else {
        // ... we return false
        return FALSE;
    }
}

?>
