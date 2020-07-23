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
        $params = $this->extractParams($this->requestedRoute,$requested_route['route']);
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
        return $matches;
    }
}