<!DOCTYPE html>
<html>
<head>
<title><?= $title ?? 'Auth | Blogify' ?></title>
</head>
<body>
<link rel="stylesheet" href='/css/Main.css'>
<?php if ($css = vite_css('resources/js/main.js')): ?>
    <link rel="stylesheet" href="<?= $css ?>">
<?php endif; ?>
<main>
<?= $content ?>
</main>
<?php require BASE_PATH . '/resources/views/components/toast.php'; ?>
<script type="module" src="<?= vite_asset('resources/js/main.js') ?>"></script>
</body>
</html>
