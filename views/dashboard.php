<div class="header">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></h1>
</div>

<div>
    <p>Your secure infrastructure is running perfectly. No search engines are tracking this area.</p>
    
    <div style="display: flex; gap: 20px; margin-top: 30px;">
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); flex: 1;">
            <h3>Quick Stats</h3>
            <p>Database: SQLite (Strict Types)</p>
            <p>Environment: Docker WSL2</p>
        </div>
    </div>
</div>