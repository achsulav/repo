<!DOCTYPE html>
<html>
<head>
<title><?= $title ?? 'Auth | Blogify' ?></title>
</head>
<body>
<link rel="stylesheet" href='/css/Main.css'>
<main>
<?= $content ?>
<?php echo app_flasher_render(); ?>
</main>
</body>
</html>
