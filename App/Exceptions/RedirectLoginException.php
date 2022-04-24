<?php

namespace App\Exceptions;

use Exception;

class RedirectLoginException extends Exception
{
    private string $url;

    public function __construct(string $url)
    {
        parent::__construct();
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
