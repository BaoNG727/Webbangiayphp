<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Email.php';
require_once __DIR__ . '/../Core/Security.php';

class PageController extends Controller
{
    public function about()
    {
        $data = [
            'title' => 'About Us - Nike Shoe Store',
            'description' => 'Learn more about Nike Shoe Store, our mission, and commitment to providing the best athletic footwear.'
        ];        $this->view('layouts/header', $data);
        $this->view('pages/about', $data);
        $this->view('layouts/footer');
    }
    
    public function contact()
    {
        $data = [
            'title' => 'Contact Us - Nike Shoe Store',
            'description' => 'Get in touch with Nike Shoe Store customer service for support, questions, or feedback.'
        ];
        
        // Handle contact form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = Security::sanitizeInput($_POST['name'] ?? '');
            $email = Security::sanitizeInput($_POST['email'] ?? '');
            $subject = Security::sanitizeInput($_POST['subject'] ?? '');
            $message = Security::sanitizeInput($_POST['message'] ?? '');

            $errors = [];

            if (empty($name)) {
                $errors[] = 'Name is required.';
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Valid email is required.';
            }

            if (empty($subject)) {
                $errors[] = 'Subject is required.';
            }

            if (empty($message)) {
                $errors[] = 'Message is required.';
            }

            if (empty($errors)) {
                // Send contact form emails
                try {
                    $emailService = new Email();
                    $contactData = [
                        'name' => $name,
                        'email' => $email,
                        'subject' => $subject,
                        'message' => $message,
                        'date' => date('Y-m-d H:i:s')
                    ];
                    
                    $emailService->sendContactFormNotification($contactData);
                    $data['success'] = 'Thank you for your message! We\'ll get back to you soon.';
                } catch (Exception $emailError) {
                    error_log("Failed to send contact form email: " . $emailError->getMessage());
                    $data['success'] = 'Thank you for your message! We\'ll get back to you soon.';
                }
            } else {
                $data['errors'] = $errors;
                $data['form_data'] = compact('name', 'email', 'subject', 'message');
            }
        }

        $this->view('layouts/header', $data);
        $this->view('pages/contact', $data);
        $this->view('layouts/footer');
    }

    public function terms()
    {
        $data = [
            'title' => 'Terms of Service - Nike Shoe Store',
            'description' => 'Read our terms of service and conditions for using Nike Shoe Store.'
        ];

        $this->view('layouts/header', $data);
        $this->view('pages/terms', $data);
        $this->view('layouts/footer');
    }

    public function privacy()
    {
        $data = [
            'title' => 'Privacy Policy - Nike Shoe Store',
            'description' => 'Learn about how Nike Shoe Store collects, uses, and protects your personal information.'
        ];

        $this->view('layouts/header', $data);
        $this->view('pages/privacy', $data);
        $this->view('layouts/footer');
    }
}
