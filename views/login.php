<div class="login-wrapper">
    <div class="login-card">
        <h2 class="login-title">Secure Admin Access</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); // Clear error after showing it ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="login-form">
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required autocomplete="email">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Log In
            </button>
            
        </form>
    </div>
</div>