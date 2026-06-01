<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($title ?? 'Admin Dashboard') ?></title>
    
    <link rel="stylesheet" href="/assets/css/admin.css">
    
    <script type="module" src="/assets/js/admin.js"></script>
</head>
<body>
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <nav>
            <a href="/admin/dashboard">Dashboard</a>
            <a href="/admin/posts">Manage Posts</a>
            <a href="/logout" style="color: #e74c3c; margin-top: 20px;">Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <?= $content ?? '' ?>
    </main>
</body>
</html>