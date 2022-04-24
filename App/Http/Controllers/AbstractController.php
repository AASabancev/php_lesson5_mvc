<?php

namespace App\Http\Controllers;

use App\Exceptions\RedirectLoginException;
use App\Http\Requests\Request;
use Base\View;

abstract class AbstractController
{
    /** @var View $view */
    protected $view;

    /**
     * @param View $view
     */
    public function setView(View $view): void
    {
        $this->view = $view;
    }

    protected function checkAuth(Request $request, array $roles = [])
    {
        $doRedirect = false;
        if (!$request->user) {
            $doRedirect = true;
        }

        if ($request->user && !empty($roles) && !in_array($request->user->getRole(), $roles)) {
            $doRedirect = true;
        }

        if ($doRedirect) {
            return $this->redirect('/user/login');
        }
    }

    protected function redirect(string $url)
    {
        throw new RedirectLoginException($url);
    }

}
