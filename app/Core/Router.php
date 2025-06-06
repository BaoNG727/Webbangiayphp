<?php

class Router
{
    private $routes = [];
    private $params = [];

    public function add($route, $controller, $action, $method = 'GET')
    {
        $this->routes[] = [
            'route' => $route,
            'controller' => $controller,
            'action' => $action,
            'method' => $method
        ];
    }

    public function get($route, $controller, $action)
    {
        $this->add($route, $controller, $action, 'GET');
    }

    public function post($route, $controller, $action)
    {
        $this->add($route, $controller, $action, 'POST');
    }    public function dispatch($url)
    {
        $url = $this->removeQueryString($url);
        $method = $_SERVER['REQUEST_METHOD'];

        error_log("Router dispatch - URL: $url, Method: $method");
        error_log("Available routes: " . print_r($this->routes, true));

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if ($this->match($route['route'], $url)) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];

                error_log("Matched route - Controller: $controllerName, Action: $actionName");

                $controllerFile = __DIR__ . "/../Controllers/{$controllerName}.php";
                
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    
                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        
                        if (method_exists($controller, $actionName)) {
                            error_log("Executing $controllerName::$actionName");
                            $controller->$actionName(...array_values($this->params));
                            return;
                        } else {
                            error_log("Method $actionName not found in $controllerName");
                        }
                    } else {
                        error_log("Class $controllerName not found");
                    }
                } else {
                    error_log("Controller file not found: $controllerFile");
                }
            }
        }

        error_log("No route matched - showing 404");
        // Default route - show 404
        $this->show404();
    }

    private function match($route, $url)
    {
        // Convert route pattern to regex
        $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route);
        $route = '#^' . $route . '$#';

        if (preg_match($route, $url, $matches)) {
            // Remove the full match, keep only the captured groups
            array_shift($matches);
            $this->params = $matches;
            return true;
        }

        return false;
    }    private function removeQueryString($url)
    {
        $parts = explode('?', $url);
        return $parts[0];
    }

    private function show404()
    {
        http_response_code(404);
        
        // Include 404 error page
        $title = 'Page Not Found - Nike Shoe Store';
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/errors/404.php';
        include __DIR__ . '/../Views/layouts/footer.php';
        exit;
    }
}
