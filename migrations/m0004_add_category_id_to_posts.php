<?php
use App\Foundation\Migration;
class m0004_add_category_id_to_posts extends Migration
{
  public function up(){
    $sql = "ALTER TABLE posts ADD COLUMN category_id INT NULL AFTER user_id";
    $this->db->exec($sql);
  }
  public function down(){
    $sql = "ALTER TABLE posts DROP COLUMN category_id";
    $this->db->exec($sql);
  }
}
