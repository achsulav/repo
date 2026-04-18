<?php
use App\Foundation\Migration;
class m0000_create_users_table extends Migration{
  public function up()
  {
    $sql = "CREATE TABLE users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      username VARCHAR(255) NOT NULL UNIQUE,
      email VARCHAR(255) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $this->db->exec($sql);
  }
  public function down()
  {
    $this->db->exec("DROP TABLE IF EXISTS users");
  }
}
