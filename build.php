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

 include 'lib\build_tools.php';
 include 'lib\copy_dir.php';
 include 'lib\seo_tags.php';

$assetsDir  = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'assets';
$pagesDir  = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'pages';
$includesDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'includes';
$templatesDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'templates';
$outputDir = __DIR__ . DIRECTORY_SEPARATOR . 'public';
$includeFile;
$templateFile;
$templateVariables = [];

function build()
{
    global $assetsDir, $pagesDir, $outputDir;
    clean($outputDir);
   
    // Copy assets directory to output directory
    $assetsOutputDir = $outputDir . DIRECTORY_SEPARATOR . 'assets';
    if (!file_exists($assetsOutputDir)) {
        mkdir($assetsOutputDir, 0777, true);
    }
    copyDir($assetsDir, $assetsOutputDir);

    $pageFiles = glob($pagesDir . DIRECTORY_SEPARATOR . '*.html');
    foreach ($pageFiles as $pageFile) {
        $outfile = str_replace($pagesDir, $outputDir, $pageFile);
        print "BUILDING $pageFile to $outfile\n";
        $out = buildFile($pageFile, false);
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