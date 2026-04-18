<?php
namespace App\Http\Middleware;

use App\Foundation\Application;

class AuthMiddleware implements Middleware{
  public function handle():void
  {
    if(!Application::$app->session->get('user')){
      header('Location: /login');
      exit;
    }

    $subdomain = Application::$app->getSubdomain();
    $loggedInUsername = Application::$app->session->get('username');

    if ($subdomain && $subdomain !== 'blogify' && $subdomain !== 'www') {
        if ($subdomain !== $loggedInUsername) {
            flash()->addError('Unauthorized access to this subdomain.');
            header("Location: http://{$loggedInUsername}.blogify.dev/dashboard");
            exit;
        }
    }

  }
}
