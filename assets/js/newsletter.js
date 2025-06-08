// Newsletter Popup functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if user has closed the popup before
    if (!localStorage.getItem('newsletterPopupClosed')) {
        // Show popup after 5 seconds
        setTimeout(function() {
            showNewsletterPopup();
        }, 5000);
    }

    // Function to show the popup
    function showNewsletterPopup() {
        // Create the popup
        const popup = document.createElement('div');
        popup.className = 'newsletter-popup';
        popup.innerHTML = `
            <div class="newsletter-content">
                <button class="newsletter-close">&times;</button>
                <div class="newsletter-header">
                    <img src="/Webgiay/assets/images/nike-logo.png" alt="Nike Logo" height="30">
                    <h3>Join Our Newsletter</h3>
                </div>
                <p>Subscribe to our newsletter and get 10% off your first purchase!</p>
                <form id="newsletter-form" class="newsletter-form">
                    <input type="email" class="form-control" placeholder="Your email address" required>
                    <button type="submit" class="btn btn-dark w-100 mt-2">Subscribe</button>
                </form>
                <div class="newsletter-footer">
                    <label class="form-check">
                        <input type="checkbox" class="dont-show-again"> Don't show this popup again
                    </label>
                </div>
            </div>
        `;

        // Add to DOM
        document.body.appendChild(popup);

        // Add event listeners
        const closeBtn = popup.querySelector('.newsletter-close');
        const checkbox = popup.querySelector('.dont-show-again');
        const form = popup.querySelector('#newsletter-form');

        closeBtn.addEventListener('click', function() {
            closeNewsletterPopup(popup, checkbox.checked);
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            
            if (email) {
                // Here you would typically send the email to your server
                // For now, we'll just simulate a successful subscription
                
                // Update the popup content
                const newsletterContent = popup.querySelector('.newsletter-content');
                newsletterContent.innerHTML = `
                    <button class="newsletter-close">&times;</button>
                    <div class="newsletter-header">
                        <img src="/Webgiay/assets/images/nike-logo.png" alt="Nike Logo" height="30">
                        <h3>Thank You!</h3>
                    </div>
                    <p>You've been successfully subscribed to our newsletter.</p>
                    <p>Your 10% discount code: <strong>WELCOME10</strong></p>
                    <button class="btn btn-dark w-100 mt-3 newsletter-close">Close</button>
                `;
                
                // Re-add event listener to the new close button
                const newCloseButtons = popup.querySelectorAll('.newsletter-close');
                newCloseButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        closeNewsletterPopup(popup, true);
                    });
                });
            }
        });

        // Prevent closing when clicking inside the content
        popup.querySelector('.newsletter-content').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Close when clicking outside the content
        popup.addEventListener('click', function() {
            closeNewsletterPopup(popup, checkbox.checked);
        });
    }

    // Function to close the popup
    function closeNewsletterPopup(popup, dontShowAgain) {
        // Remove the popup
        document.body.removeChild(popup);
        
        // Set localStorage if checkbox is checked
        if (dontShowAgain) {
            localStorage.setItem('newsletterPopupClosed', 'true');
        }
    }
});

// Enhanced newsletter form in footer
document.addEventListener('DOMContentLoaded', function() {
    const footerNewsletterForm = document.querySelector('footer #newsletter-form');
    
    if (footerNewsletterForm) {
        footerNewsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button[type="submit"]');
            const email = emailInput.value.trim();
            
            // Basic email validation
            if (!email || !isValidEmail(email)) {
                showFooterMessage('Please enter a valid email address.', 'error');
                emailInput.classList.add('error');
                setTimeout(() => emailInput.classList.remove('error'), 3000);
                return;
            }
            
            // Show loading state
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Subscribing...</span>';
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            
            // Simulate newsletter subscription with more realistic delay
            setTimeout(() => {
                showFooterMessage('🎉 Thank you for subscribing! Check your email for a 10% discount code.', 'success');
                emailInput.value = '';
                emailInput.classList.add('success');
                
                // Reset button with success state
                submitBtn.innerHTML = '<i class="fas fa-check"></i> <span>Subscribed!</span>';
                submitBtn.classList.remove('loading');
                submitBtn.classList.add('success');
                
                // Reset to original state after 3 seconds
                setTimeout(() => {
                    submitBtn.innerHTML = originalContent;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('success');
                    emailInput.classList.remove('success');
                }, 3000);
            }, 2000);
        });
        
        // Add input animation on focus
        const emailInput = footerNewsletterForm.querySelector('input[type="email"]');
        if (emailInput) {
            emailInput.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            emailInput.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        }
    }
    
    // Initialize back to top button
    initBackToTop();
}
    
    // Back to top button functionality
    function initBackToTop() {
        const backToTopBtn = document.getElementById('backToTop');
        if (backToTopBtn) {
            // Show/hide button based on scroll position
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
            });
            
            // Smooth scroll to top
            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showFooterMessage(message, type) {
        // Remove existing messages
        const existingMessage = document.querySelector('.footer-newsletter-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create new message
        const messageDiv = document.createElement('div');
        messageDiv.className = `footer-newsletter-message alert alert-${type === 'success' ? 'success' : 'danger'} mt-2`;
        messageDiv.style.fontSize = '0.85rem';
        messageDiv.style.borderRadius = '20px';
        messageDiv.style.padding = '8px 16px';
        messageDiv.style.animation = 'slideInUp 0.3s ease';
        messageDiv.innerHTML = message;
        
        // Insert message after the form
        const form = document.querySelector('footer #newsletter-form');
        if (form && form.parentNode) {
            form.parentNode.insertBefore(messageDiv, form.nextSibling);
        }
        
        // Remove message after 5 seconds
        setTimeout(() => {
            if (messageDiv && messageDiv.parentNode) {
                messageDiv.style.animation = 'slideOutDown 0.3s ease';
                setTimeout(() => messageDiv.remove(), 300);
            }
        }, 5000);
    }
});

// Additional footer enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add ripple effect to social links
    const socialLinks = document.querySelectorAll('.social-link');
    socialLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Create ripple effect
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            // Remove ripple after animation
            setTimeout(() => {
                ripple.remove();
            }, 600);
            
            // Open social media link (you can customize URLs)
            const platform = this.getAttribute('aria-label');
            console.log(`Opening ${platform} - Add your social media URLs here`);
        });
    });
    
    // Add hover effects to footer links
    const footerLinks = document.querySelectorAll('.footer-link');
    footerLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(8px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // Add floating animation to payment icons
    const paymentIcons = document.querySelectorAll('.payment-icon');
    paymentIcons.forEach((icon, index) => {
        icon.style.animationDelay = `${index * 0.1}s`;
        
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.1)';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});