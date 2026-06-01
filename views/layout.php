<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= htmlspecialchars($meta['title'] ?? 'Star Web Developer') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta['description'] ?? 'Expert Web Development Services') ?>">

    <link rel="stylesheet" href="/assets/css/main.css">
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Star Web Developer",
      "description": "<?= htmlspecialchars($meta['description'] ?? '') ?>",
      "url": "https://starwebdeveloper.com",
      "areaServed": ["IN", "US", "GB", "CA", "AU", "EU"]
    }
    </script>
    <script type="module" src="/assets/js/app.js"></script>
</head>
<body>
    <main>
        <?= $content ?? '' ?>
    </main>
</body>
</html>