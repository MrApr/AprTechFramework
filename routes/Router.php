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
    private array $routes = [];

    /**
     * Default prefix for routes that will be set through routes.php
     * @var string|null
     */
    private ?string $prefix = null;

    /**
     * Container for holding desired middleware
     * Middlewares are the classes that get executed before executing all other requests
     * @var string|null
     */
    private ?string $middleware = null;

    /**
     * Container for defined namespace
     * @var string|null
     */
    private ?string $namespace = null;

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
            "route" => ($this->prefix) ? $this->prefix."/".trim($arguments[0],'/') : trim($arguments[0],'/'),
            "action" => $this->transRouteAction($arguments[1]),
            'middleware' => ($this->middleware) ? $this->middleware : null
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
            "class" => ($this->namespace) ? $this->namespace."\\".$action[0] : $action[0],
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
        $route_handler->executeRequest($requested_route['action'],(isset($params) && count($params)) ? $params : [],($requested_route['middleware']) ? $requested_route['middleware'] : null);
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

    /**
     * Setting prefix argument
     * @param string $name
     * @return $this
     */
    public function prefix(string $name)
    {
        $this->prefix = $name;
        return $this;
    }

    /**
     * Setting Middleware property
     * @param string $name
     * @return $this
     */
    public function middleware(string $name)
    {
        $this->middleware = $name;
        return $this;
    }

    /**
     * Setting route groups namespace property
     * @param string $namespace_name
     * @return $this
     */
    public function namespace(string $namespace_name)
    {
        $this->namespace = $namespace_name;
        return $this;
    }

    /**
     * Continue executing class and reset every unnecessary arguments that has been setted
     * @param string $closure
     */
    public function group($closure = Router::class)
    {
        call_user_func($closure);
        $this->resetArguments();
    }

    /**
     * Reset unnecessary arguments that are not vital for Router.php functionality.
     */
    public function resetArguments()
    {
        $this->prefix = null;
        $this->middleware = null;
    }
}