<?php

namespace App\Http\Controllers;

use App\Foundation\View\View;

abstract class BaseController
{
    protected function render(string $view, array $params = [], string $layout= 'root')
    {
        View::render($view, $params,$layout);
    }
    protected function redirect(string $url){

      header("Location: {$url}");
      exit;
    }
}
