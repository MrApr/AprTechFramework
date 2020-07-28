<?php

// Launch and read configs
$config_files = glob(dirname(dirname(__FILE__))."/config/*.php");

foreach ($config_files as $config_file)
{
    require_once $config_file;
}

// Launch and load functions

$functions_files = glob(ROOT_DIR."/bootstrap/Helpers/Functions/*.php");
foreach ($functions_files as $functions_file)
{
    require_once $functions_file;
}
