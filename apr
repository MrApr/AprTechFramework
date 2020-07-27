<?php
// Check whether script is running from cli or not ! if not exit
if (PHP_SAPI != "cli") {
    exit;
}

// include vendor file for auto loading
require_once "vendor/autoload.php";

//require bootstrap file for launching base files of app
require_once "bootstrap/app.php";

if(!($argc > 1))
{
    die("Invalid parameters count !\n");
}

unset($argv[0]);

switch ($argv[1])
{
    case "migrate":
        $migrator = new Migrator();
        $migrator->launch();
        break;
    default:
        echo "Please pass one of the valid parameters\n";
}