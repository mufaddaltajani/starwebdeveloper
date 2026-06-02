<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($meta['title'] ?? 'AI & Web Development Solutions') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta['description'] ?? 'Expert web development and AI business integrations.') ?>">
    
    <meta property="og:title" content="<?= htmlspecialchars($meta['title'] ?? 'StarWebDev') ?>">
    <meta property="og:type" content="website">
    
    <?php
        // We build the schema as a native PHP array first
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService', 
            'name' => 'Star Web Developer',
            'url' => 'https://starwebdeveloper.com',
            'description' => $meta['description'] ?? 'Expert web development and AI business integrations.',
            'areaServed' => ['IN', 'US', 'GB', 'CA', 'AU', 'EU', 'NZ'],
            'knowsAbout' => ['Web Development', 'PHP 8', 'Artificial Intelligence', 'Automations', 'Laravel']
        ];
    ?>
    <script type="application/ld+json">
        <?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
    </script>

    <link rel="stylesheet" href="/assets/css/main.css">
    <script type="module" src="/assets/js/app.js"></script>
</head>
<body>
    <header class="site-header">
        <div class="container nav-container">
            <a href="/" class="brand">StarWeb<span>Dev</span></a>
            
            <button class="mobile-menu-toggle" aria-label="Toggle navigation" aria-expanded="false">
                <span class="hamburger"></span>
            </button>
            
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="/" class="nav-link">Home</a></li>
                    <li><a href="/about" class="nav-link">About</a></li>
                    <li><a href="/blog" class="nav-link">Blog</a></li>
                    <li><a href="/contact" class="nav-link btn-contact">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="site-main">
        <?= $content ?? '' ?>
    </main>

    <footer class="site-footer">
        <div class="container footer-content">
            <div class="footer-brand">
                <h3>StarWebDev</h3>
                <p>Engineering extremely fast, secure, and AI-integrated web applications for modern businesses.</p>
            </div>
            <div class="footer-links">
                <h4>Legal</h4>
                <a href="/privacy">Privacy Policy</a>
                <a href="/terms">Terms of Service</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> StarWebDev. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>