<?php
/*
 * ***********************************************************************
 * *********************************************************************** 
 *      This library is filled with tools for generating the website
 * *********************************************************************** 
 * ***********************************************************************
 */

/* 
 * **************************************
 * Function for including blocks in pages
 * **************************************
 */

function startInclude(string $include, array $variables = [], bool $print = true)
{
    global $includesDir, $includeFile;
    $calledBy = debug_backtrace()[0]['file'];
    $includeFile = $includesDir . DIRECTORY_SEPARATOR . $include;
    $output = NULL; # Clear output
    if (! file_exists($includeFile)) {
        echo("\n\nERROR: Part $includeFile doesn't exist\n\n");
        die();
    }
    extract($variables);
    ob_start();
    echo "\r\n"; # Empty line at the start of the include
    include $includeFile;
    echo "\r\n"; # Empty line at the end of the include
    $output = ob_get_clean();
    if ($print) {
        print $output;
    }
    return $output;
}

/* 
 * **********************************************
 * Function for starting the extend of a template
 * **********************************************
 */

function startExtend(string $template, array $variables)
{
    global $templatesDir, $templateFile, $templateVariables;
    $calledBy = debug_backtrace()[0]['file'];
    $templateFile = $templatesDir . DIRECTORY_SEPARATOR . $template;
    $templateVariables = $variables;
    ob_start();
    echo "\r\n";
}

/* 
 * ********************************************
 * Function for ending the extend of a template
 * ********************************************
 */

function endExtend()
{
    global $templateFile, $templateVariables;
    $content = ob_get_clean();
    extract($templateVariables);
    ob_start();
    include $templateFile;
    echo "\r\n"; # Empty line at the end of the page
    $output = ob_get_clean();
    echo $output;
}

/* 
 * ***********************************************
 * Function for deleting everything in a directory
 * ***********************************************
 */

function cleanDir($path) {
    echo "CLEANING everything from $path\n";
    if (! file_exists($path)) {
        echo "CREATING output path $path\n";
        if (! mkdir($path, 0777, true)) {
            echo "ERROR: Couldn't create output directory $path\n";
            die();
        };
    }
    $files = glob($path . DIRECTORY_SEPARATOR . '*', GLOB_NOSORT);
    foreach($files as $file) {
        if (is_dir($file)) {
            echo "DELETING directory $file\n";
            rmdir($file);
        } else {
            echo "DELETING $file\n";
            unlink($file);
        }
    }
}

/* 
 * ***********************************************************
 * Function for copying a directory + subdirectories and files
 * ***********************************************************
 */

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

/* 
 * *************************************
 * Function for getting the file content
 * *************************************
 */

function buildFile($filePath)
{
    $output = NULL;
    if (file_exists($filePath)) {
        ob_start();
        include $filePath;
        $output = ob_get_clean();
    }
    return $output;
}

/* 
 * *****************************************************************
 * Function for building the pages
 * TODO: build from subdirectories using language-specific variables
 * *****************************************************************
 */

function build()
{
    global $languages, $assetsDir, $assetsOutputDir, $pagesDir, $outDir;

    // Clean output directory from all files
    cleanDir($outDir);

    // Create assets directory if it does not exist
    if (!file_exists($assetsOutputDir)) {
        mkdir($assetsOutputDir, 0777, true);
    }

    // Copy asset directories to output assets directory
    copyDir($assetsDir, $assetsOutputDir);

    // Build html files from root pages directory
    $rootPageFiles = glob($pagesDir . DIRECTORY_SEPARATOR . '*.html');
    foreach ($rootPageFiles as $pageFile) {
        // Determine the relative path from $pagesDir to $pageFile
        $relativePath = substr($pageFile, strlen($pagesDir) + 1);

        // Construct the destination file path
        $outFile = $outDir . DIRECTORY_SEPARATOR . $relativePath;

        print "BUILDING $pageFile to $outFile\n";
        $out = buildFile($pageFile, false);

        // Ensure the directory for the output file exists
        $outFileDir = dirname($outFile);
        if (!file_exists($outFileDir)) {
            mkdir($outFileDir, 0777, true);
        }

        file_put_contents($outFile, $out);
    }

    // Build html files from language directories
    foreach ($languages as $lang) {
        $langCode = $lang['code'];

        // Create corresponding output directory
        $langOutDir = $outDir . DIRECTORY_SEPARATOR . $langCode;
        if (!file_exists($langOutDir)) {
            mkdir($langOutDir, 0777, true);
        }

        // Find all .html files in current language directory
        $languageDir = $pagesDir . DIRECTORY_SEPARATOR . $langCode;
        $pageFiles = glob($languageDir . DIRECTORY_SEPARATOR . '*.html');
        foreach ($pageFiles as $pageFile) {
            // Determine the relative path from $languageDir to $pageFile
            $relativePath = substr($pageFile, strlen($languageDir) + 1);

            // Construct the destination file path
            $outFile = $langOutDir . DIRECTORY_SEPARATOR . $relativePath;

            print "BUILDING $pageFile to $outFile\n";
            $out = buildFile($pageFile, false);

            // Ensure the directory for the output file exists
            $outFileDir = dirname($outFile);
            if (!file_exists($outFileDir)) {
                mkdir($outFileDir, 0777, true);
            }

            file_put_contents($outFile, $out);
        }
    }
}

?>