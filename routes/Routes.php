<?php

$router->post("/home",'bbbbbbbcccc@mamadio');
$router->prefix("apr")->middleware("TestMiddleware")->group(function () use($router){
    $router->get("/user/forgot/password",'TestController@test');
});
$router->get("/add/user/{user_id}/profile/{profile_id}",'TestController@test');
/*$router->get("/user/forgot/password",'TestController@test');
$router->post("/home",'bbbbbbbcccc@mamadio');
$router->get("/add/user/{user_id}/profile/{profile_id}",'TestController@test');*/