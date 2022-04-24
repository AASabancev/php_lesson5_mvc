<?php

namespace Base;

class Route
{
    private $controllerName;
    private $actionName;
    private $processed = false;
    private $routes;

    private function process()
    {
        if($this->processed){
            return;
        }

        $parts = parse_url($_SERVER['REQUEST_URI']);

        $path = $parts['path'];
        if ($route = $this->routes[$path] ?? null) {
            $this->controllerName = $route[0];
            $this->actionName = $route[1];
        } else {
            $parts = explode('/', $path);
            $this->controllerName = '\\App\\Http\\Controllers\\' . ucfirst(strtolower($parts[1])) . 'Controller';
            $this->actionName = strtolower($parts[2] ?: 'index');

            if (!class_exists($this->controllerName)) {
                throw new \Exception("Route not found [" . $this->controllerName . "]");
            }
            if (!method_exists($this->controllerName, $this->actionName)) {
                throw new \Exception("Method not found [" . $this->actionName . "]");
            }
        }

        $this->processed = true;
    }

    public function addRoute($path, $controllerName, $actionName)
    {
        $this->routes[$path] = [
            $controllerName,
            $actionName
        ];
    }

    public function getControllerName(): string
    {
        if (!$this->processed) {
            $this->process();
        }
        return $this->controllerName;
    }

    public function getActionName(): string
    {
        if (!$this->processed) {
            $this->process();
        }
        return $this->actionName;
    }
}
