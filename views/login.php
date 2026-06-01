<div>
    <h2>Secure Admin Access</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); // Clear error after showing it ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="POST">
        
        <div>
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required autocomplete="email">
        </div>
        
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
        
        <button type="submit">
            Authenticate
        </button>
    </form>
</div>