<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Foundation\Application;
class PageController extends BaseController
{
    public function home()
    {
        if (Application::$app->session->get('user')) {
            $this->redirect('/dashboard');
            return;
        }
        $this->render('Home',['blogOwner' => null],'root');
    }

    public function testRedirect(){
    $this->redirect('/login');
      #echo "Testy Route hit";
    }


}
