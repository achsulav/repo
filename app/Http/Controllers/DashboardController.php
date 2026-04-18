<?php
namespace App\Http\Controllers;
use App\Foundation\Application;

class DashboardController extends BaseController
{
    public function index()
    {

      $userId = Application::$app->session->get('user');
      $fullName = Application::$app->session->get('user_name');
      $sql = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC";
      $stmt = Application::$app->db->getConnection()->prepare($sql);
      $stmt->execute([
        ':user_id'=> $userId
      ]);
      $posts = $stmt->fetchAll();
      $this->render('Dashboard',[
        'fullName' => $fullName,
        'posts' => $posts
      ]);
    }
}

