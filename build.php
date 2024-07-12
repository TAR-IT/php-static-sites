<?php
/*
 * ***********************************************************************
 * *********************************************************************** 
 *      This file is used for bulding the pages.
 *          use "php .\build.php" to start the process
 *              and the argument "--prod" for production variabless
 * *********************************************************************** 
 * ***********************************************************************
 */

include 'config.php';
include 'lib/build_tools.php';

if (isset($_SERVER['argv'][1]) && '--prod' === $_SERVER['argv'][1]) {
    echo "Running production\n\n";
    include('./env.prod.php');
} else {
    include('./env.stage.php');
}

build();
