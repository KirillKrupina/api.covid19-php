<?php


class Router
{
    // Хранит конфигурацию маршрутов.
    private $routes;

    function __construct($routesPath)
    {
        // Получаем конфигурацию из файла.
        $this->routes = include($routesPath);
    }

    // Метод получает URI. Несколько вариантов представлены для надёжности.
    function getURI()
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

        // Пытаемся применить к нему правила из конфигуации.
        foreach ($this->routes as $pattern => $route) {

            // Если правило совпало.
            // preg_match — Выполняет проверку на соответствие регулярному выражению
            if (preg_match("~$pattern~", $uri)) {

                // Получаем внутренний путь из внешнего согласно правилу.
                // preg_replace — Выполняет поиск и замену по регулярному выражению
                $internalRoute = preg_replace("~$pattern~", $route, $uri);

                // Разбиваем внутренний путь на сегменты.
                // explode — Разбивает строку с помощью разделителя
                $segments = explode('/', $internalRoute);

                // Первый сегмент — контроллер.
                // ucfirst — Преобразует первый символ строки в верхний регистр
                // array_shift — Извлекает первый элемент массива
                $controller = ucfirst(array_shift($segments)) . 'Controller';

                // Второй — действие.
                $action = 'action' . ucfirst(array_shift($segments));

                // Остальные сегменты — параметры.
                $parameters = $segments;

                // Подключаем файл контроллера, если он имеется
                // file_exists — Проверяет существование указанного файла или каталога
                $controllerFile = ROOT . '../app/Сontrollers/' . $controller . '.php';
                if (file_exists($controllerFile)) {
                    include($controllerFile);
                }

                // Если не загружен нужный класс контроллера или в нём нет
                // нужного метода — 404
                if (!is_callable(array($controller, $action))) {
                    header("HTTP/1.0 404 Not Found");
                    return;
                }

                // Вызываем действие контроллера с параметрами
                call_user_func_array(array($controller, $action), array());
            }
        }

        // Ничего не применилось. 404.
        header("HTTP/1.0 404 Not Found");
        return;
    }

}