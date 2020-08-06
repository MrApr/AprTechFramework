<?php

/**
 * Loads view and pass params into it
 * @param string $path
 * @param array $params
 */
function view(string $path,array $params = [])
{
    if(!file_exists(ROOT_DIR."/views/".$path.".php"))
    {
        die("view not found");
    }

    extract($params,EXTR_PREFIX_SAME, "wddx");
    include_once ROOT_DIR."/views/".$path.".php";
}