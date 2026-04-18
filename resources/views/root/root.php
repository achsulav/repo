<!DOCTYPE html> 
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Blogify - Post and See Others">
<link rel="stylesheet" href='/css/Main.css'>
</head>
<body>
<div>
<?php require BASE_PATH . '/resources/views/components/navbar.php';?>
</div>
<?= $content ?>
<?php echo app_flasher_render(); ?>
<?php require BASE_PATH . '/resources/views/components/footer.php';?>
</body>
</html>


