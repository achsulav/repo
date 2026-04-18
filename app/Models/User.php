<?php
namespace App\Models;
use App\Foundation\Database;
use PDO;
class User{
  protected PDO $db;
  public function __construct(Database $database)
  {
    $this->db = $database->getConnection();
  }
  public function create(string $name, string $username, string $email, string $password) {
    $stmt = $this->db->prepare("INSERT INTO users(name, username, email, password) VALUES(:name, :username, :email, :password)");
    return $stmt->execute([
      'name'     => $name,
      'username' => $username,
      'email'    => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
  }

  public function findByEmail(string $email) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findByUsername(string $username) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

}
