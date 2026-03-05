<?php

class App
{
    protected string $controller = 'Homes';
    protected object $controllerInstance;
    protected string $method = 'index';
    protected array $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        if (isset($url[0]) && file_exists('app/controller/' . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }

        require_once 'app/controller/' . $this->controller . '.php';
        $this->controllerInstance = new $this->controller();

        if (isset($url[1]) && method_exists($this->controllerInstance, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controllerInstance, $this->method], $this->params);
    }

    private function getUrl(): array
    {
        $url = $_GET['url'] ?? 'Homes';
        $url = filter_var(trim($url, '/'), FILTER_SANITIZE_URL);
        return explode('/', $url);
    }
}
