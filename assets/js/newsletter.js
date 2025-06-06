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
                return;
            }
            
            // Show loading state
            const originalIcon = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;
            
            // Simulate newsletter subscription
            setTimeout(() => {
                showFooterMessage('Thank you for subscribing! Welcome to Nike Store.', 'success');
                emailInput.value = '';
                
                // Reset button
                submitBtn.innerHTML = originalIcon;
                submitBtn.disabled = false;
            }, 1500);
        });
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
        messageDiv.textContent = message;
        
        // Insert message after the form
        const form = document.querySelector('footer #newsletter-form');
        form.parentNode.insertBefore(messageDiv, form.nextSibling);
        
        // Remove message after 5 seconds
        setTimeout(() => {
            if (messageDiv && messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }
});