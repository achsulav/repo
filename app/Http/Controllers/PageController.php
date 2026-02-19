<?php

namespace App\Http\Controllers;

class PageController extends BaseController
{
    public function home()
    {
        $this->render('Home',[],'root');
    }
    public function login(){
      $this->render('Login',[],'auth');
    }
    public function testRedirect(){
    $this->redirect('/login');
      #echo "Testy Route hit";
    }
}
