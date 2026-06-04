<div class="login-wrapper">
    <div class="login-card">
        <h2 class="login-title">Log In</h2>
        
        <?php require __DIR__ . '/partials/alert.php'; ?>

        <form action="/login" method="POST" class="login-form">
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?= htmlspecialchars($_SESSION['old_email'] ?? '') ?>" 
                       required autocomplete="email">
                <?php unset($_SESSION['old_email']); // Clear it immediately after rendering ?>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
            </div>

            <div class="form-group">
                <div class="cf-turnstile" 
                     data-sitekey="<?= htmlspecialchars($turnstile_site_key ?? '') ?>" 
                     data-theme="light">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Log In
            </button>
            
        </form>
    </div>
</div>