// Enhanced Newsletter and Footer Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all footer enhancements
    initNewsletterForm();
    initBackToTop();
    initSocialLinks();
    initFooterAnimations();
    
    // Newsletter form functionality
    function initNewsletterForm() {
        const footerNewsletterForm = document.querySelector('footer #newsletter-form');
        
        if (footerNewsletterForm) {
            footerNewsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const emailInput = this.querySelector('.newsletter-input-enhanced');
                const submitBtn = this.querySelector('.newsletter-btn-enhanced');
                const email = emailInput.value.trim();
                
                // Basic email validation
                if (!email || !isValidEmail(email)) {
                    showFooterMessage('⚠️ Please enter a valid email address.', 'error');
                    emailInput.classList.add('error');
                    setTimeout(() => emailInput.classList.remove('error'), 3000);
                    return;
                }
                
                // Show loading state
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span class="btn-text">Subscribing...</span>';
                submitBtn.disabled = true;
                submitBtn.classList.add('loading');
                
                // Simulate newsletter subscription
                setTimeout(() => {
                    showFooterMessage('🎉 Thank you for subscribing! Check your email for a 10% discount code.', 'success');
                    emailInput.value = '';
                    emailInput.classList.add('success');
                    
                    // Reset button with success state
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> <span class="btn-text">Subscribed!</span>';
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
            const emailInput = footerNewsletterForm.querySelector('.newsletter-input-enhanced');
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
    
    // Social links with ripple effects
    function initSocialLinks() {
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
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255,255,255,0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.pointerEvents = 'none';
                
                // Remove ripple after animation
                setTimeout(() => {
                    ripple.remove();
                }, 600);
                
                // Simulate social media link opening
                const platform = this.getAttribute('aria-label');
                console.log(`Opening ${platform} - Add your social media URLs here`);
                
                // Show a toast message for demo
                showToast(`Opening ${platform} page...`);
            });
        });
    }
    
    // Footer animations and interactions
    function initFooterAnimations() {
        // Add hover effects to footer links
        const footerLinks = document.querySelectorAll('.footer-link');
        footerLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px)';
                this.style.paddingLeft = '8px';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
                this.style.paddingLeft = '0';
            });
        });
        
        // Add floating animation to payment icons
        const paymentIcons = document.querySelectorAll('.payment-icon');
        paymentIcons.forEach((icon, index) => {
            icon.style.animationDelay = `${index * 0.1}s`;
            
            icon.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.2)';
                this.style.filter = 'brightness(1.2)';
            });
            
            icon.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.filter = 'brightness(1)';
            });
        });
        
        // Add intersection observer for footer animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);
        
        // Observe footer sections
        const footerSections = document.querySelectorAll('.enhanced-footer .col-lg-4, .enhanced-footer .col-lg-2');
        footerSections.forEach(section => {
            observer.observe(section);
        });
    }
    
    // Utility functions
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
        messageDiv.style.cssText = `
            font-size: 0.85rem;
            border-radius: 20px;
            padding: 8px 16px;
            animation: slideInUp 0.3s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
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
    
    function showToast(message) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            z-index: 9999;
            animation: slideInRight 0.3s ease;
            font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        `;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});

// CSS animations for the enhanced footer
const style = document.createElement('style');
style.textContent = `
    /* Animation keyframes */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideOutDown {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    /* Newsletter input states */
    .newsletter-input-enhanced.error {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 3px rgba(220,53,69,0.2) !important;
        animation: shake 0.5s ease-in-out;
    }
    
    .newsletter-input-enhanced.success {
        border-color: #28a745 !important;
        box-shadow: 0 0 0 3px rgba(40,167,69,0.2) !important;
    }
    
    .newsletter-btn-enhanced.loading {
        background: linear-gradient(135deg, #6c757d, #5a6268) !important;
        cursor: wait;
    }
    
    .newsletter-btn-enhanced.success {
        background: linear-gradient(135deg, #28a745, #20c997) !important;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    /* Footer section animations */
    .enhanced-footer .col-lg-4,
    .enhanced-footer .col-lg-2 {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }
    
    .enhanced-footer .col-lg-4.animate-in,
    .enhanced-footer .col-lg-2.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Staggered animation delays */
    .enhanced-footer .col-lg-4:nth-child(1).animate-in { transition-delay: 0.1s; }
    .enhanced-footer .col-lg-2:nth-child(2).animate-in { transition-delay: 0.2s; }
    .enhanced-footer .col-lg-2:nth-child(3).animate-in { transition-delay: 0.3s; }
    .enhanced-footer .col-lg-2:nth-child(4).animate-in { transition-delay: 0.4s; }
    .enhanced-footer .col-lg-4:nth-child(5).animate-in { transition-delay: 0.5s; }
`;
document.head.appendChild(style);
