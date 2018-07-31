<?php
namespace Controller;

class Router {

    public $controller = 'question';
    public $action ='show';

    public function __construct($connection, $twig) 
	{
	$this->connection = $connection;
	$this->twig = $twig;
    }

    public function route() 
    {
    	if (isset($_GET['c']) && isset($_GET['a'])) {
    		$this->controller = $_GET['c'];
    	  $this->action = $_GET['a'];
    	}
        $controllerText = $this->controller . 'Controller';
        $controllerFile = 'controller/' . ucfirst($controllerText) . '.php';

        if (is_file($controllerFile)) 
        {
          include $controllerFile;
          $controllerText = 'Controller\\' . $controllerText;

          if (class_exists($controllerText)) 
          {
            $controller = new $controllerText($this->connection, $this->twig);
            if (isset($_GET['id'])) 
              {
              $id = $_GET['id'];
              if (method_exists($controller, $this->action)) 
              {
              	$a = $this->action;
                $controller->$a($id);
              }
              } 
              else 
              {
                if (method_exists($controller, $this->action)) 
              {
                $a = $this->action;
                $controller->$a();
              }
              }
          }
        } 
    }

}
