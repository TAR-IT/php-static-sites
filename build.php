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

include 'config.php';
include 'lib\build_tools.php';
include 'lib\copy_dir.php';
include 'lib\seo_tags.php';

if (isset($_SERVER['argv'][1]) && '--prod' === $_SERVER['argv'][1]) {
    echo "Running production\n\n";
    include('./env.prod.php');
} else {
    include('./env.stage.php');
}

build();