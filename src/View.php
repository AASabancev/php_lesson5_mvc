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
        ob_start();
        include $this->templatePath . DIRECTORY_SEPARATOR . $template;
        return ob_get_clean();
    }

    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }
}
