<?php

// include vendor file for auto loading
require_once "../vendor/autoload.php";

//require bootstrap file for launching base files of app
require_once "../bootstrap/app.php";

//Routing request
require_once ROOT_DIR . "/routes/Router.php";

$router = new Router();
require_once "../routes/Routes.php";