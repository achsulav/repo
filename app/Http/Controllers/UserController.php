<?php 
namespace App\Http\Controllers;
use App\Foundation\Application;
class UserController extends BaseController{

  public function profile($username, $slug = null){
    // Look up the user by subdomain username
    $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
    $stmt = Application::$app->db->getConnection()->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if (!$user) {
      http_response_code(404);
      $this->render('BlogNotFound', [
          'subdomain' => $username,
          'blogOwner' => null
      ]);
      return;
    }

    // If a slug is given, show that specific post (scoped to this user)
    if ($slug !== null) {
      $postSql = "SELECT posts.*, users.name AS author FROM posts 
                  JOIN users ON posts.user_id = users.id 
                  WHERE posts.slug = :slug AND posts.user_id = :user_id LIMIT 1";
      $postStmt = Application::$app->db->getConnection()->prepare($postSql);
      $postStmt->execute([':slug' => $slug, ':user_id' => $user['id']]);
      $post = $postStmt->fetch();

      if (!$post) {
        http_response_code(404);
        $this->render('PageNotFound', ['blogOwner' => null]);
        return;
      }

      $commentsql = "SELECT comments.*, users.name AS user_name FROM comments 
                     JOIN users ON comments.user_id = users.id 
                     WHERE comments.post_id = :post_id ORDER BY comments.created_at DESC";
      $commentStmt = Application::$app->db->getConnection()->prepare($commentsql);
      $commentStmt->execute([':post_id' => $post['id']]);
      $comments = $commentStmt->fetchAll();

      $this->render('ViewPost', [
        'post'      => $post,
        'comments'  => $comments,
        'blogOwner' => $user['name'],
      ]);
      return;
    }

    // No slug → show the user's blog index
    $postSql = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC";
    $postStmt = Application::$app->db->getConnection()->prepare($postSql);
    $postStmt->execute([':user_id' => $user['id']]);
    $posts = $postStmt->fetchAll();

    $this->render('UserProfile', [
      'user'      => $user,
      'posts'     => $posts,
      'blogOwner' => null,
    ]);
  }
}
