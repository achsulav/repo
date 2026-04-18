<?php
use App\Foundation\Migration;
class m0002_add_slug_to_posts extends Migration{
  public function up(){
    $sql = "ALTER TABLE posts ADD COLUMN slug VARCHAR(255) NOT NULL AFTER title";
    $this->db->exec($sql);
  }
  public function down(){
    $sql = "ALTER TABLE posts DROP COLUMN slug";
    $this->db->exec($sql);
  }

}
