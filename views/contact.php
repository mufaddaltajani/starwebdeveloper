<?php 
    // Safely extract old input and clear it from the session
    $old = $_SESSION['old_input'] ?? []; 
    unset($_SESSION['old_input']); 
?>

<section class="contact-section">
    <div class="container">
        
        <div class="section-header">
            <h1>Get In Touch</h1>
            <p>Let's build something extraordinary together.</p>
        </div>

        <?php require __DIR__ . '/partials/alert.php'; ?>

        <div class="contact-grid">
            
            <div class="contact-form-container">
                <form action="/contact" method="POST">
                    
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="message">Project Details *</label>
                        <textarea id="message" name="message" class="form-control" rows="5" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                </form>
            </div>

            <div class="contact-details">
                <h3>Office Information</h3>
                
                <div class="contact-info-block">
                    <h4>Location</h4>
                    <p>123 Innovation Drive<br>Suite 400<br>Tech District, 90210</p>
                </div>

                <div class="contact-info-block">
                    <h4>Business Hours</h4>
                    <p>Monday - Friday<br>9:00 AM - 6:00 PM (EST)</p>
                </div>

                <div class="contact-info-block">
                    <h4>Direct Contact</h4>
                    <p>
                        <button class="secure-link" data-action="mailto:" data-b64="aW5mb0BzdGFyd2ViZGV2LmNvbQ==">
                            Click to Email Us
                        </button>
                    </p>
                    <p>
                        <button class="secure-link" data-action="tel:" data-b64="KzEgMjM0IDU2NyA4OTAw">
                            Click to Call Us
                        </button>
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>