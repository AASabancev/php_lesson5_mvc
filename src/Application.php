<?php

namespace Base;

use App\Exceptions\RedirectLoginException;
use App\Http\Requests\Request;


class Application
{

    private $router;
    private $controller;
    private $view;
    private $request;

    public function __construct()
    {
        $dt = new Database();

        $this->router = new Route();
        $this->defaultRoutes();
        $this->view = new View();
        $this->request = new Request();
    }

    function run()
    {
        try {
            $controllerName = $this->router->getControllerName();
            $this->controller = new $controllerName();

            $this->controller->setView($this->view);

            $actionName = $this->router->getActionName();
            $content = $this->controller->$actionName($this->request);

            echo $this->prepareContent($content);

        } catch (RedirectLoginException $e) {
            header('Location: ' . $e->getUrl());
            exit();
        } catch (\Exception $e) {
            /**
             * TODO: пока не догадался как красиво это вернуть в рендер. вышел ВТОРОЙ ЭХО :)
             */
            header('HTTP/1.0 404 Not Found');
            echo $this->view->render('Errors/404.phtml', ['error' => $e->getMessage()]);
        }

    }

    /**
     * Что-то типа единого обработчика вывода данных, что можем, вернуть в JSON, что не можем, отдаем текстом
     * @param Object|array|string $content
     */
    function prepareContent($content)
    {
        if (is_array($content) || is_object($content)) {
            if (method_exists($content, "toJson")) {
                return $content->toJson();
            } else {
                return json_encode($content);
            }
        }

        return $content;
    }

    function defaultRoutes()
    {
//        /** @uses \App\Controllers\UserController::login() */
//        $this->router->addRoute('/user/login', UserController::class, 'login');
//
//        /** @uses \App\Controllers\UserController::register() */
//        $this->router->addRoute('/user/register', UserController::class, 'register');
//
//        /** @uses \App\Controllers\BlogController::index() */
//        $this->router->addRoute('/blog/test', BlogController::class, 'index');
//
//        /** @uses \App\Controllers\BlogController::index() */
//        $this->router->addRoute('/blog/index', BlogController::class, 'index');
    }
}
