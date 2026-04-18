<?php
use App\Foundation\Migration;
class m0005_create_comments_table extends Migration{
  public function up(){
    $sql = "CREATE TABLE comments(
      id INT AUTO_INCREMENT PRIMARY KEY,
      post_id INT NOT NULL,
      user_id INT NOT NULL,
      content TEXT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$this->db->exec($sql);
  }
  public function down(){
    $this->db->exec("DROP TABLE IF EXISTS comments");
  }
}
