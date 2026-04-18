<?php

namespace App\Http\Controllers;

use App\Foundation\Application;
use App\Foundation\View\View;

abstract class BaseController
{
    protected function render(string $view, array $params = [], string $layout= 'root')
    {
        $subdomain = Application::$app->getSubdomain();
        if ($subdomain && !in_array($subdomain, ['blogify', 'www']) && !array_key_exists('blogOwner', $params)) {
            $db = Application::$app->db->getConnection();
            $stmt = $db->prepare("SELECT name FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $subdomain]);
            $owner = $stmt->fetch();
            if ($owner) {
                $params['blogOwner'] = $owner['name'];
            }
        }
        
        View::render($view, $params,$layout);
    }
    protected function redirect(string $url){

      header("Location: {$url}");
      exit;
    }
}
