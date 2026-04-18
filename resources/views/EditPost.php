<h2>Edit Post</h2>
<form method="POST" action="/post/update/<?= $post['id'] ?>">
<label for="category">Category</label>
<select name="category_id" required>
<option value="">Select Category</option>
<?php foreach ($categories as $category): ?>
<option value="<?= $category['id'] ?>" <?= $post['category_id'] == $category['id'] ? 'selected' : '' ?>>
<?= htmlspecialchars($category['name'])?>
</option>
<?php endforeach;?>
</select>
<div>
<button type="button" id="undo_btn">Undo</button>
<button type="button" id="redo_btn">Redo</button>

<select id="heading_select">
<option value="">Paragraph</option>
<option value="h1">Heading 1</option>
<option value="h2">Heading 2</option>
<option value="h3">Heading 3</option>
</select>
<button type="button" id="bold_btn">Bold</button>
<button type="button" id="italic_btn">Italic</button>
<button type="button" id="link_btn">Link</button>
<button type="button" id="heading_btn">H1</button>
<button type="button" id="bullet_btn">Bullet List</button>
<button type="button" id="ordered_btn">Ordered List</button>
<button type="button" id="image_btn">Image</button>
<input type="file" id="image_upload" accept="image/*" style="display:none;">
</div>

<input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" class="title-input" required>
<div id="editor"></div>
<input type="hidden" name="content_html" id="content_input" value="<?= htmlspecialchars($post['content_html']) ?>">
<button type="submit">Update Post</button>
</form>

<div style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px;">
    <label for="commit_message_input">Commit message:</label>
    <input type="text" id="commit_message_input" placeholder="Update version" style="width: 200px;">
    <button type="button" id="commit_btn">Commit</button>
    <button type="button" id="revert_btn">Revert</button>
</div>
<div id="history_container" style="margin-top: 10px;"></div>

<script type="module" src="<?= vite_asset('@vite/client') ?>"></script>
<script type="module" src="<?= vite_asset('resources/js/editor.js') ?>"></script>

