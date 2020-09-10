<?php
use eftec\bladeone\BladeOne;
/**
 * Loads view and pass params into it
 * @param string $path
 * @param array $params
 */
function view(string $path,array $params = [])
{
    $views_dir = ROOT_DIR."/resources/views";
    $cache_dir = ROOT_DIR.'/resources/cache';

    $blade = new BladeOne($views_dir,$cache_dir,BladeOne::MODE_DEBUG);
    echo $blade->run($path,$params);
/*    extract($params,EXTR_PREFIX_SAME, "wddx");
    include_once ROOT_DIR."/views/".$path.".php";*/
}