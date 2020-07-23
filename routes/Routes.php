<?php
require_once "Router.php";
$router = new Router();

$router->get("/user/forgot/password",'aasdsadadas@ababio');
$router->post("/home",'bbbbbbbcccc@mamadio');
$router->get("/add/user/{user_id}/profile/{profile_id}",'aasdsadadas@aadasd');
$router->findMatchingRoute();
