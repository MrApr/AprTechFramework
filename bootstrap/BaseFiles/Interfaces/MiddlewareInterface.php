<?php

/**
 * Middleware are the classes that get executed before requests
 * Interface MiddlewareInterface
 */
interface MiddlewareInterface
{
    /**
     * Middleware interfaces
     * @return mixed
     */
    public function handle();
}