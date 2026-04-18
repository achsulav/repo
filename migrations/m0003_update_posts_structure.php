<?php
use App\Foundation\Migration;
class m0003_update_posts_structure extends Migration
{
  public function up(){
    $this->db->exec("ALTER TABLE posts ADD COLUMN user_id INT NOT NULL AFTER id");
    $this->db->exec("ALTER TABLE posts MODIFY content LONGTEXT NOT NULL");
    $this->db->exec("CREATE INDEX idx_user_id ON posts(user_id)");
  }
  public function down(){
    $this->db->exec("ALTER TABLE posts DROP COLUMN user_id");
    $this->db->exec("DROP INDEX idx_user_id ON posts");
  }
}
