<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($title ?? 'Secure Access') ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?= $content ?? '' ?>
</body>
</html>