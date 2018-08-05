<?php

namespace Controller;

class Router
{
    /**
     * @var string
     */
    private $controller = 'question';
    /**
     * @var string
     */
    private $action = 'show';
    /**
     * @var \PDO
     */
    private $connection;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    
    public function __construct(\PDO $connection, \Twig_Environment $twig)
    {
        $this->connection = $connection;
        $this->twig = $twig;
    }
    
    public function route()
    {
        if (isset($_GET['c'], $_GET['a'])) {
            $this->controller = $_GET['c'];
            $this->action = $_GET['a'];
        }
        $controllerText = $this->controller . 'Controller';
        $controllerText = 'Controller\\' . $controllerText;
        if (class_exists($controllerText)) {
            $controller = new $controllerText($this->connection, $this->twig);
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                if (method_exists($controller, $this->action)) {
                    $a = $this->action;
                    $controller->$a($id);
                }
            } else if (method_exists($controller, $this->action)) {
                $a = $this->action;
                $controller->$a();
            }
        }
    }
}
