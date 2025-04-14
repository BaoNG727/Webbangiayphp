<?php include_once "includes/header.php"; ?>

<div class="container">
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="display-4 mb-4">Contact Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="mb-4">Get In Touch</h2>
                    <form id="contactForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="mb-4">Contact Information</h2>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Store Address</h5>
                            <p class="mb-0">123 Nike Street, Fashion District<br>Ho Chi Minh City, Vietnam</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="fas fa-phone fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Phone Number</h5>
                            <p class="mb-0">+84 123 456 789</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Email Address</h5>
                            <p class="mb-0">info@nikeshoestore.com</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="fas fa-clock fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Opening Hours</h5>
                            <p class="mb-0">Monday - Friday: 9:00 AM - 9:00 PM<br>Saturday - Sunday: 10:00 AM - 10:00 PM</p>
                        </div>
                    </div>
                    <div class="social-links mt-4">
                        <a href="#" class="btn btn-outline-dark me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-dark me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-dark me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-dark me-2"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4241674197667!2d106.69135407483308!3d10.77762088931363!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f40a3b49e59%3A0xa1bd14e483a602db!2sBen%20Thanh%20Market!5e0!3m2!1sen!2s!4v1682316500612!5m2!1sen!2s" 
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Frequently Asked Questions</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            How can I track my order?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can track your order by logging into your account and navigating to the "My Orders" section. There you will find all your orders and their current status.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            What is your return policy?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We offer a 30-day return policy for all unworn items in their original condition with tags attached. You can initiate a return through your account or by contacting our customer service.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Do you offer international shipping?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we ship to most countries worldwide. Shipping costs and delivery times vary by location. You can see the shipping options and costs during checkout.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            How do I find the right shoe size?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We provide a size guide on each product page. You can also contact our customer service for assistance in finding the perfect fit for your feet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Thank you for your message! We will get back to you soon.');
    this.reset();
});
</script>

<?php include_once "includes/footer.php"; ?>