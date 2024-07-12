<?php
/*
 * ************************************************************************************** 
 * **************************************************************************************  
 *      This is the configuration file. You can change the values to fit your needs.
 * **************************************************************************************  
 * ************************************************************************************** 
*/

/*
 * Configuration variables for directory paths
*/

 $assetsDir  = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'assets';
 $assetsOutputDir = $outDir . DIRECTORY_SEPARATOR . 'assets';
 $pagesDir  = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'pages';
 $includesDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'includes';
 $templatesDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'templates';
 $outDir = __DIR__ . DIRECTORY_SEPARATOR . 'public';

/*
 * Configuration array for languages
 * (leave array empty if not building multilanguage)
*/
$languages = [
    [
        'code' => 'en',
        'name' => 'English',
    ],
    [
        'code' => 'de',
        'name' => 'German',
    ],
    // Add more languages as needed
];

/*
 * Arrays for language specific variables
 * (leave array empty if not building multilanguage)
*/

$enVariables = [
    'site_title' => 'My Website',
    'welcome_message' => 'Welcome to our website!',
];

$deVariables = [
    'site_title' => 'Meine Webseite',
    'welcome_message' => 'Willkommen auf unserer Webseite!',
];