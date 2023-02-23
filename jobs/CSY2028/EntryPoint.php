<?php
namespace CSY2028;
class EntryPoint {
	private $routes;
	private $pdo;
    private $route;
    private $method;



	public function __construct(\PDO $pdo, string $route, string $method,  $routes)
    {
        $this->pdo = $pdo;
        $this->route = $route;
        $this->method = $method;
        $this->routes = $routes;
        $this->checkUrl();
    }

	private function checkUrl()
    {
        if ($this->route !== strtolower($this->route)) {
            http_response_code(301);
            header('location: ' . strtolower($this->route));
        }
    }

	public function run()
    {
        $routes = $this->routes->getRoutes();
        $authentication = $this->routes->getAuthentication();

        if (
            isset($routes[$this->route]['login']) &&
            !$authentication->isLoggedIn()
        ) {
            http_response_code(403);
            header('location: /403');
        } elseif (
            isset($routes[$this->route]['permissions']) &&
            !$this->routes->checkPermission($routes[$this->route]['permissions'])
        ) {
            http_response_code(403);
            header('location: /403');
        } else {
            $controller = $routes[$this->route][$this->method]['controller'];
            $action = $routes[$this->route][$this->method]['action'];
            $page = $controller->$action();

            $title = $page['title'];

            if (isset($page['variables'])) {
                $output = $this->loadTemplate($page['template'], $page['variables']);
            } else {
                $output = $this->loadTemplate($page['template']);
            }

            echo $this->loadTemplate(
                'layout.html.php',
                $this->routes->getLayoutVars($title, $output)
            );
        }
    }

	private function loadTemplate($filename, $templateVars = [])
    {
        // Extract variables from the $templateVars associative
        // array if size isn't empty
        if (!empty($templateVars)) {
            extract($templateVars);
        }
        ob_start();
        include __DIR__ . '/../templates/' . $filename;
        return ob_get_clean();
    }
}