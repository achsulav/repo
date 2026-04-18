<h1>Create Post</h1>
<form action="/store-post" method="POST">
<label for="category">Category</label>
<select name="category_id" required>
<option value="">Select Category</option>
<?php foreach ($categories as $category): ?>
<option value="<?= $category['id'] ?>" <?= (isset($old['category_id']) && $old['category_id'] == $category['id']) ? 'selected' : '' ?>>
<?= htmlspecialchars($category['name'])?>
</option>
<?php endforeach;?>
</select>
<div>
<button type="button" id="import_md_btn">Import Markdown</button>
<input type="file" id="md_file_input" accept=".md,text/markdown" style="display:none;">
<button type="button" id="undo_btn">Undo</button>
<button type="button" id="redo_btn">Redo</button>

<select id="font_select">
<option value="">Default Font</option>
<option value="Sailec Light">Sailec Light</option>
<option value="Sofia Pro">Sofia Pro</option>
<option value="Slabo 13px">Slabo 13px</option>
<option value="Roboto Slab">Roboto Slab</option>
<option value="Inconsolate">Inconsolate</option>
<option value="Ubuntu Mono">Ubuntu Mono</option>
</select>

<select id="heading_select">
<option value="">Normal</option>
<option value="1">Heading</option>
<option value="2">Subheading</option>
</select>
<button type="button" id="bold_btn">Bold</button>
<button type="button" id="italic_btn">Italic</button>
<button type="button" id="link_btn">Link</button>
<button type="button" id="underline_btn">Underline</button>
<button type="button" id="bullet_btn">Bullet List</button>
<button type="button" id="ordered_btn">Ordered List</button>
<button type="button" id="image_btn">Image</button>
<input type="file" id="image_upload" accept="image/*" style="display:none;">
</div>

<input name="title" placeholder="Title" value="<?= htmlspecialchars($old['title'] ?? '')?>" class="title-input" required>

<div id="editor"></div>
<input type="hidden" name="content_html" id="content_input" value="<?= htmlspecialchars($old['content_html'] ?? '')?>">
<button type="submit" value="Publish">Publish</button>
</form>
<div style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px;">
    <label for="commit_message_input">Commit message:</label>
    <input type="text" id="commit_message_input" placeholder="Initial version" style="width: 200px;">
    <button type="button" id="commit_btn">Commit</button>
    <button type="button" id="revert_btn">Revert</button>
</div>
<div id="history_container" style="margin-top: 10px;"></div>


<script type="module" src="<?= vite_asset('@vite/client') ?>"></script>
<script type="module" src="<?= vite_asset('resources/js/editor.js') ?>"></script>
