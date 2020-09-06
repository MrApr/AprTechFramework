<?php


class RouteHandler
{

    /**
     * Controller source string container
     * @var string
     */
    private $ctlrStrSrc = "App\\Controllers\\";

    /**
     * Middleware source string container
     * @var string
     */
    private string $middlewareSrc = "App\\Middlewares\\";
    
    /**
     * Loads controller with desired method and executes it
     * @param array $action
     * @param array $parameters
     * @param string $middleware
     */
    public function executeRequest(array $action, array $parameters = [], string $middleware = null)
    {
        if($middleware)
        {
            $this->executeMiddleware($middleware);
        }
        $controller_obj = $this->createControllerObject($action['class']);
        $this->callObjectMethod($controller_obj,$action['method'], $parameters);
    }

    /**
     * Executes requested middleware
     * @param string $middleware_name
     */
    public function executeMiddleware(string $middleware_name)
    {
        $this->checkMiddlewareExistence($middleware_name);
        $middleware = $this->middlewareSrc.$middleware_name;
        $middleware = new $middleware();
        $this->checkClassImplementedMiddlewareInstance($middleware);
        $middleware->handle();
    }

    /**
     * Checks if middleware exists
     * @param string $middleware
     */
    public function checkMiddlewareExistence(string $middleware)
    {
        if(!class_exists($this->middlewareSrc.$middleware))
        {
            die("Middleware Doesnt exist");
        }
    }

    /**
     * Checks if middleware class implemented Middleware interface or not in order to execute
     * @param object $middleware
     */
    public function checkClassImplementedMiddlewareInstance(object $middleware)
    {
        if(!$middleware instanceof MiddlewareInterface)
        {
            die("Included Middleware Doesnt implemented middleware instance");
        }
    }

    /**
     * Checking that if defined and desired controller exists or not
     * @param string $controller
     */
    public function checkControllerExists(string $controller)
    {
        if(!class_exists($this->ctlrStrSrc.$controller))
        {
            die("Defined Controller doesn't exists");
        }
    }

    /**
     * Creates object from desired controller
     * @param string $controller
     * @return string
     */
    public function createControllerObject(string $controller)
    {
        $this->checkControllerExists($controller);
        $controller = $this->ctlrStrSrc.$controller;
        $controller = new $controller();
        return $controller;
    }

    /**
     * Checks if Method exists in instantiated controller or not !
     * @param object $controller
     * @param string $method
     */
    public function checkControllerMethodExists(object $controller, string $method)
    {
        if(!method_exists($controller,$method))
        {
            die("Unknown method for instantiated controller object");
        }
    }


    /**
     * Re order passed params for calling function passing arguments
     * @param array $params
     * @param object $controller
     * @param string $method
     * @return array
     * @throws ReflectionException
     */
    public function reOrderParamsForMethod(array $params,object $controller,string $method)
    {
        if(count($_GET) || count($_POST))
        {
            $object = convertToObject((count($_POST)) ? $_POST : $_GET);
            $index = 0;

            $method = new ReflectionMethod($controller,$method);
            foreach ($method->getParameters() as $param)
            {
                if($param->getType() == gettype($object))
                {
                    break;
                }
                $index++;
            }
            array_splice($params,$index,0,[$object]);
        }
        return $params;
    }

    /**
     * First checks if the requested method exists, if it exist, call & executes it.
     * @param object $controller
     * @param string $method
     * @param array $params
     * @throws ReflectionException
     */
    public function callObjectMethod(object $controller,string $method, array $params)
    {
        $params = array_values(array_filter($params));
        $this->checkControllerMethodExists($controller,$method);
        $params = $this->reOrderParamsForMethod($params,$controller,$method);
        call_user_func_array([$controller,$method],$params);
    }
}