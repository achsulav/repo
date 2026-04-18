<?php
namespace App\Http\Middleware; 

use App\Foundation\Application;

class GuestMiddleware implements Middleware{
  public function handle():void
  {
    if(Application::$app->session->get('user')){
      header('Location: /');
      exit;
    }
  }
}

