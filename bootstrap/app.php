<?php

// Launch and read configs
$config_files = glob(dirname(dirname(__FILE__))."/config/*.php");

foreach ($config_files as $config_file)
{
    require_once $config_file;
}