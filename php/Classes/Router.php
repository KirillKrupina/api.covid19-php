<?php


class Router
{
    // Хранит конфигурацию маршрутов.
    private $routes;

    function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    // Метод получает URI. Несколько вариантов представлены для надёжности.
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        if (!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }

        if (!empty($_SERVER['QUERY_STRING'])) {
            return trim($_SERVER['QUERY_STRING'], '/');
        }
    }

    function run()
    {
        // Получаем URI.
        $uri = $this->getURI();
        $foundRoute = null;
        // Пытаемся применить к нему правила из конфигуации.
        foreach ($this->routes as $pattern => $route) {
            // Если правило совпало.
            // preg_match — Выполняет проверку на соответствие регулярному выражению
            if (preg_match("~$pattern~", $uri)) {
                $foundRoute = $route;
                break;
            }

        }
        if (is_null($foundRoute)) {
            // Ничего не применилось. 404.
            header("HTTP/1.0 404 Not Found");
            return;
        }
        // Получаем внутренний путь из внешнего согласно правилу.
        // preg_replace — Выполняет поиск и замену по регулярному выражению
        // Выполняет поиск совпадений в строке uri с шаблоном pattern и заменяет их на route.
        //$internalRoute = preg_replace("~$pattern~", $route, $uri);
        $parts = explode('|', $foundRoute);
        $controllerName = $parts[0];
        $action = $parts[1];
        $controllerFile = __DIR__ . '/../Controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            throw new Exception("File not found");
        }

        // Если файл найдет - импортируем его и создаём объект
        require_once $controllerFile;
        $controllerObj = new $controllerName;

        // Если метод найдет - вызываем его. Иначе обрабатываем ошибку
        if (method_exists($controllerObj, $action)) {
            $controllerObj->$action();
        } else throw new Exception('Action not found');
    }
}