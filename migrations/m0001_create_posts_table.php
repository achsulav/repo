<?php
use App\Foundation\Migration;
class m0001_create_posts_table extends Migration{
  public function up()  
  {
    $sql = "CREATE TABLE posts (
      id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      content TEXT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
$this->db->exec($sql);
  }
  public function down()
  {
    $this->db->exec("DROP TABLE IF EXISTS posts");
  }

}
