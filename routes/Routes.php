<?php
require_once "Router.php";
$router = new Router();

$router->get("/user/forgot/password",'TestController@test');
$router->post("/home",'bbbbbbbcccc@mamadio');
$router->get("/add/user/{user_id}/profile/{profile_id}",'TestController@test');
$router->findMatchingRoute();
