<?php


class RouteHandler
{

    /**
     * Controller source string container
     * @var string
     */
    private $ctlrStrSrc = "App\\Controllers\\";
    
    /**
     * Loads controller with desired method and executes it
     * @param array $action
     * @param array $parameters
     */
    public function executeRequest(array $action, array $parameters = [])
    {
        $controller_obj = $this->createControllerObject($action['class']);
        $this->callObjectMethod($controller_obj,$action['method'], $parameters);
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

/*        $method = new ReflectionMethod($controller,$method);
        foreach ($method->getParameters() as $param)
        {

        }*/
    }

    /**
     * First checks if the requested method exists, if it exist, call & executes it.
     * @param object $controller
     * @param string $method
     * @param array $params
     */
    public function callObjectMethod(object $controller,string $method, array $params)
    {
        $params = array_values(array_filter($params));
        $this->checkControllerMethodExists($controller,$method);
        call_user_func_array([$controller,$method],$params);
    }
}