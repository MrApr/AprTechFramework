<?php


namespace App\Middlewares;


class TestMiddleware implements \MiddlewareInterface
{
    public function handle()
    {
        die("I am inside middleware");
    }
}