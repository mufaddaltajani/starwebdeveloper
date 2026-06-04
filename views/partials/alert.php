<?php declare(strict_types=1); ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['success']); ?>
        <?php unset($_SESSION['success']); // Clear immediately after rendering ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['error']); ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>