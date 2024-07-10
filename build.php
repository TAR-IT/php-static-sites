<?php
/*
 * Input: files matching src/pages/*.html
 * Output: public/*.html
 *
 * Pages can extend a template in src/templates by calling:
 *
 *   startExtend(string $template, array $variables)
 *
 * at the start and:
 *
 *   endExtend()
 *
 * at the end. Inside the template you can:
 *
 *   echo $content;
 *
 * Pages and parts can use the following method to use includes:
 *
 *   startInclude(string $include, array $variables, bool $print)
 * 
 * At the moment, this function does not need a endInclude, since it does not exist.
 * This is a placeholder for coming features.
 *
 * If environment variables are needed you can define these as PHP constants in:
 *
 *  - env.stage.php
 *  - env.prod.php
 *
 * These enviromental variables are compiled into the built HTML. Be careful with what you put in here.
 *
 * To run in prod mode, call `build.php --prod`
 */

$outputDir = __DIR__ . DIRECTORY_SEPARATOR . 'public';
$pagesDir  = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'pages';
$includesDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'includes';
$templatesDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'templates';
$includeFile;
$templateFile;
$templateVariables = [];

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
    include $includeFile;
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
}

function endExtend()
{
    global $templateFile, $templateVariables;
    $content = ob_get_clean();
    extract($templateVariables);
    ob_start();
    include $templateFile;
    $output = ob_get_clean();
    echo $output;
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

/*
 * This checks for, creates, and deletes the HTML file contents of the path
 */
function clean($path) {
    echo "CLEANING HTML from $path\n";
    if (! file_exists($path)) {
        echo "CREATING output path $path\n";
        if (! mkdir($path, 0777, true)) {
            echo "ERROR: Couldn't create output directory $path\n";
            die();
        };
    }
    $htmlFiles = glob($path . DIRECTORY_SEPARATOR . '*.html');
    foreach($htmlFiles as $htmlFile) {
        echo "DELETING $htmlFile\n";
        unlink($htmlFile);
    }
}

function build()
{
    global $pagesDir, $outputDir;
    clean($outputDir);
    $files = glob($pagesDir . DIRECTORY_SEPARATOR . '*.html');
    foreach ($files as $file) {
        $outfile = str_replace($pagesDir, $outputDir, $file);
        print "BUILDING $file to $outfile\n";
        $out = buildFile($file, false);
        file_put_contents($outfile, $out);
    }
}

if (isset($_SERVER['argv'][1]) && '--prod' === $_SERVER['argv'][1]) {
    echo "Running production\n\n";
    include('./env.prod.php');
} else {
    include('./env.stage.php');
}

build();
