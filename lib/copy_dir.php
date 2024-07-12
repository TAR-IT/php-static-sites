<?php
function copyDir($source, $destination) {
    // Check if the source is a directory
    if (is_dir($source)) {
        // If the destination directory doesn't exist, create it
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        // Get all the files and subdirectories in the source directory
        $directory = dir($source);
        while (false !== ($file = $directory->read())) {
            if ($file != '.' && $file != '..') {
                // Recursively copy the content
                copyDir("$source/$file", "$destination/$file");
            }
        }
        
        // Close the directory handle
        $directory->close();
    } else {
        // If the source is a file, copy it to the destination
        copy($source, $destination);
    }
}
?>