<?php
function startInclude(string $include, array $variables = [], bool $print = true)
{
    global $includesDir, $includeFile;
    $calledBy = debug_backtrace()[0]['file'];
    $includeFile = $includesDir . DIRECTORY_SEPARATOR . $include;
    $output = NULL;
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

function startExtend(string $template, array $variables)
{
    global $templatesDir, $templateFile, $templateVariables;
    $calledBy = debug_backtrace()[0]['file'];
    $templateFile = $templatesDir . DIRECTORY_SEPARATOR . $template;
    $templateVariables = $variables;
    ob_start();
    echo "\r\n";
}

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

function build()
{
    global $assetsDir, $pagesDir, $outDir;
    cleanDir($outDir);
   
    // Copy assets directory to output directory
    $assetsOutputDir = $outDir . DIRECTORY_SEPARATOR . 'assets';
    if (!file_exists($assetsOutputDir)) {
        mkdir($assetsOutputDir, 0777, true);
    }
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
    $languageDirs = glob($pagesDir . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
    
    foreach ($languageDirs as $languageDir) {
        // Get language code from directory name
        $langCode = basename($languageDir);

        // Create corresponding output directory
        $langOutDir = $outDir . DIRECTORY_SEPARATOR . $langCode;
        if (!file_exists($langOutDir)) {
            mkdir($langOutDir, 0777, true);
        }

        // Find all .html files in current language directory
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