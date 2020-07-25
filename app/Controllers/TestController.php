<?php


namespace App\Controllers;


class TestController
{

    public function test($name,$family)
    {

        echo $name." -> ".$family;
    }
}