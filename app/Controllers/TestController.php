<?php


namespace App\Controllers;


class TestController
{

    public function test()
    {
        return view("test_view",["name" => "Test"]);
    }
}