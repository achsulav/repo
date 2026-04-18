<?php
use App\Foundation\Migration;
class m0003_create_categories_table extends Migration
{
  public function up(){

    $sql = "CREATE TABLE categories (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NUll,
      slug VARCHAR(255) NOT NULL UNIQUE,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$this->db->exec($sql);
  }
  public function down(){
    $this->db->exec("DROP TABLE IF EXISTS categories");
  }
}
