<?php
use App\Foundation\Migration;
class m0004_theme_column extends Migration
{
  public function up(){
    $this->db->exec("ALTER TABLE users ADD COLUMN theme VARCHAR(100) DEFAULT 'default'");
  }
  public function down(){
    $this->db->exec("ALTER TABLE users DROP COLUMN theme");
  }
}
