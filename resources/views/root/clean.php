<!DOCTYPE html> 
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Blogify - Search Engine">
<link rel="stylesheet" href='/css/Main.css'>
<style>
    /* Add any specific styles for the clean layout here */
    body { padding-top: 20px; }
</style>
</head>
<body>
<div class="container">
    <?= $content ?>
</div>
<?php echo app_flasher_render(); ?>
</body>
</html>
