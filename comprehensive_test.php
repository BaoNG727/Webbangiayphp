<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer & Logo Test - Nike Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/Webgiay/assets/css/style.css" rel="stylesheet">
    <style>
        .test-section {
            padding: 2rem 0;
            border-bottom: 1px solid #eee;
        }
        .device-preview {
            border: 2px solid #ddd;
            border-radius: 8px;
            margin: 1rem 0;
            overflow: hidden;
        }
        .device-header {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .device-content {
            padding: 1rem;
        }
        .logo-test {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-5">Nike Store - Footer & Logo Display Test</h1>
        
        <!-- Logo Test Section -->
        <div class="test-section">
            <h2>Logo Display Test</h2>
            <div class="row">
                <div class="col-md-6">
                    <h4>Header Logo (32px)</h4>
                    <div class="logo-test">
                        <img src="/Webgiay/assets/images/nike-logo.png" alt="Nike Logo" style="height: 32px; width: auto;">
                        <span>Nike Store</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4>Footer Logo (28px)</h4>
                    <div class="logo-test" style="background: #1a1a1a; padding: 1rem; border-radius: 8px;">
                        <img src="/Webgiay/assets/images/nike-logo.png" alt="Nike Logo" class="footer-logo">
                        <span style="color: white;">Nike Store</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device Preview Section -->
        <div class="test-section">
            <h2>Responsive Preview</h2>
            
            <div class="device-preview">
                <div class="device-header">Desktop View (1200px+)</div>
                <div class="device-content">
                    <p>Footer should display with 4 columns, horizontal newsletter form, and full social icons.</p>
                </div>
            </div>
            
            <div class="device-preview">
                <div class="device-header">Tablet View (768px - 991px)</div>
                <div class="device-content">
                    <p>Footer should maintain structure with slight spacing adjustments.</p>
                </div>
            </div>
            
            <div class="device-preview">
                <div class="device-header">Mobile View (< 768px)</div>
                <div class="device-content">
                    <p>Footer should stack vertically, newsletter form should stack, social icons centered.</p>
                </div>
            </div>
        </div>

        <!-- Test Instructions -->
        <div class="test-section">
            <h2>Test Instructions</h2>
            <div class="alert alert-info">
                <h5>To verify the fixes:</h5>
                <ol>
                    <li><strong>Desktop Test:</strong> Footer should have dark gradient background with white text</li>
                    <li><strong>Mobile Test:</strong> Resize browser or use developer tools to test responsive layout</li>
                    <li><strong>Logo Test:</strong> Logos should be properly sized and not oversized</li>
                    <li><strong>Footer Visibility:</strong> All text should be clearly visible (no black box issue)</li>
                    <li><strong>Interactive Elements:</strong> Social links and newsletter form should work</li>
                </ol>
            </div>
        </div>

        <!-- Spacer for scrolling -->
        <div style="height: 100vh; background: linear-gradient(45deg, #f8f9fa, #e9ecef); display: flex; align-items: center; justify-content: center;">
            <div class="text-center">
                <h3>Scroll down to see the footer</h3>
                <p class="text-muted">The footer should be fully visible with proper styling</p>
                <i class="fas fa-arrow-down fa-2x text-muted"></i>
            </div>
        </div>
    </div>

    <?php include 'app/Views/layouts/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add some interactive feedback
        document.addEventListener('DOMContentLoaded', function() {
            const socialLinks = document.querySelectorAll('.social-link');
            socialLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('Social link clicked! Footer is working correctly.');
                });
            });
            
            const newsletterForm = document.getElementById('newsletter-form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Newsletter form submitted! Footer functionality is working.');
                });
            }
        });
    </script>
</body>
</html>
