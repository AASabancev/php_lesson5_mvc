<?php

namespace Base;

class View
{
    private string $templatePath;
    private array $data = [];

    public function __construct()
    {
        $this->templatePath = DOC_ROOT . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Views';
    }

    public function render($template, $data = []): string
    {
        $this->data += $data;
        $ext = pathinfo($template, PATHINFO_EXTENSION);
        ob_start();
        switch($ext){
            case "twig":
                $loader = new \Twig_Loader_Filesystem('App/Views');
                $twig = new \Twig_Environment($loader);
                echo $twig->render($template, $data);
                break;
            default:
                include $this->templatePath . DIRECTORY_SEPARATOR . $template;
                break;
        }
        return ob_get_clean();
    }

    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }
}
