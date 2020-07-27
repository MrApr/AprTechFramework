<?php

class Router
{

    /**
     * property that contains requested url
     * @var
     */
    private $requestedRoute;

    /**
     * array that contains application routes
     * @var array
     */
    private $routes = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->requestedRoute = trim(strtok($_SERVER['REQUEST_URI'],'?'),'/');
    }

    /**
     * adds get and post anonymous functions calls and adds them to route array list
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        if($name != "get" && $name!= "post")
        {
            die("undefined method name {$name} in adding routes ! please correct it");
        }

        if(count($arguments) > 2 || !is_countable($arguments))
        {
            die("Not enough arguments for add route using method {$name}");
        }

        $this->routes[] = [
            "method" => $name,
            "route" => trim($arguments[0],'/'),
            "action" => $this->transRouteAction($arguments[1])
        ];
    }

    /**
     * converts route action into understandable format in order to call related controllers with desired methods
     * @param string $action
     * @return array
     */
    public function transRouteAction(string $action)
    {
        $action = explode("@",$action);

        if(count($action) != 2)
        {
            die("Route action {$action} format is wrong !!!");
        }

        return [
            "class" => $action[0],
            "method" => $action[1]
        ];
    }

    /**
     * Find Matching route with sent params then
     */
    public function findMatchingRoute()
    {
        $requested_route = $this->checkRoutesAreEqual($this->requestedRoute);

        if(!is_countable($requested_route))
        {
            die("404 not found");
        }

        $this->checkRequestMethodIsValid($requested_route['method']);
        $has_params = $this->checkRouteHasParams($requested_route['route']);

        if($has_params)
        {
            $params = $this->extractParams($this->requestedRoute,$requested_route['route']);
        }

        $route_handler = new RouteHandler();
        $route_handler->executeRequest($requested_route['action'],(isset($params) && count($params)) ? $params : []);
    }


    /**
     * Check if user requested route exists in registered routes
     * @param string $requested_url
     * @return mixed
     */
    public function checkRoutesAreEqual(string $requested_url)
    {
        $routes = $this->routes;

        foreach ($routes as $route)
        {
            $url = $route['route'];

            $pattern = preg_replace('/{(.*?)}/','*',$url);

            if(fnmatch($pattern,$requested_url))
            {
                return $route;
            }
        }
    }


    /**
     * Extract passed params from route
     * @param string $url
     * @param string $pattern
     * @return mixed
     */
    public function extractParams(string $url, string $pattern)
    {
        $pattern .= "/";
        $url .= "/";
        $pattern = preg_replace('/{(.*?)}/','*',$pattern);
        $pattern = str_replace('/','\/',$pattern);
        $pattern = str_replace('*','(.*?)',$pattern);
        preg_match_all("/".$pattern."/",$url,$matches);
        unset($matches[0]);
        $matches = array_map('current', $matches);
        return $matches;
    }

    /**
     * This method checks if request method is valid or not!
     * @param string $requested_route_type
     */
    public function checkRequestMethodIsValid(string $requested_route_type)
    {
        if($requested_route_type != strtolower($_SERVER['REQUEST_METHOD']))
        {
            die("Invalid method for this Route");
        }
    }


    /**
     * Checks if Router has parameters or not and returns true if router has params
     * @param string $pattern
     * @return bool
     */
    public function checkRouteHasParams(string $pattern)
    {
        $pattern .= "/";
        $pattern = "/".$pattern;
        preg_match_all('/{(.*?)}/',$pattern,$matches);

        if(count($matches) == 2 && isset($matches[1]) && count($matches[1]))
        {
            return true;
        }

        return false;
    }
}