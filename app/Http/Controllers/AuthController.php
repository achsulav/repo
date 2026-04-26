<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Foundation\Application;
class AuthController extends BaseController
{
  public function showLogin(){
    $this->render('Login',['blogOwner' => null],'auth');
  }
  public function showRegister(){

    $this->render('Register',['blogOwner' => null],'auth');
  }
  public function Register(){

    $userModel = new User(Application::$app->db);
    $email = $_POST['email'] ?? '';
    $username = strtolower(trim($_POST['username'] ?? ''));
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($name) || empty($password)) {
      Application::$app->session->setFlash('error', 'All fields are required');
      $this->redirect('/register');
      return;
    }

    $existingEmail = $userModel->findByEmail($email);
    if($existingEmail){
      Application::$app->session->setFlash('error', 'Email already exists');
      $this->redirect('/register');
      return;
    }

    $existingUsername = $userModel->findByUsername($username);
    if ($existingUsername) {
      Application::$app->session->setFlash('error', 'Username already exists');
      $this->redirect('/register');
      return;
    }

    $userModel->create($name, $username, $email, $password);

    Application::$app->session->setFlash('success', 'Registration successful. Please login.');
    $this->redirect('/login');

  }
  public function Login(){
    $userModel = new User(Application::$app->db);
    $user = $userModel->findByEmail($_POST['email']);
    if(!$user || !password_verify($_POST['password'],$user['password'])){
      Application::$app->session->setFlash('error', 'Invalid credentials');
      $this->redirect('/login');
      return;
    }
    Application::$app->session->set('user',$user['id']);
    Application::$app->session->set('user_name',$user['name']);
    Application::$app->session->set('username',$user['username']);
    Application::$app->session->setFlash('success', 'Login successful');
    
    // Redirect to user's subdomain
    $username = $user['username'];
    $this->redirect("http://{$username}.blogify.dev/dashboard");

  }
  public function Logout(){
    Application::$app->session->remove('user');
    Application::$app->session->remove('user_name');
    Application::$app->session->remove('username');
    Application::$app->session->setFlash('success', 'Logged out successfully');
    $this->redirect('http://blogify.dev/');
  }
}
