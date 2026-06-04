<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($title ?? 'Log In | Star Web Developer') ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">

    <script type="module" src="/assets/js/admin.js"></script>

    <?php if (isset($scripts) && is_array($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script type="module" src="<?= htmlspecialchars($script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
</head>
<body>
    <?= $content ?? '' ?>
</body>
</html>