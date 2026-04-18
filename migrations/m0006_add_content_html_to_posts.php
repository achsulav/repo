<?php
use App\Foundation\Migration;

class m0006_add_content_html_to_posts extends Migration
{
    public function up()
    {
        $this->db->exec("ALTER TABLE posts ADD COLUMN content_html LONGTEXT AFTER content");
        // Make existing 'content' column nullable since we are moving to 'content_html'
        $this->db->exec("ALTER TABLE posts MODIFY content LONGTEXT NULL");
    }

    public function down()
    {
        $this->db->exec("ALTER TABLE posts DROP COLUMN content_html");
    }
}
