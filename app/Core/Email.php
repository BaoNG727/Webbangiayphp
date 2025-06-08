<?php

require_once __DIR__ . '/Config.php';

class Email
{
    private $config;

    public function __construct()
    {
        $this->config = Config::getEmailConfig();
    }

    /**
     * Send email using PHP's mail function or SMTP
     */
    public function send($to, $subject, $body, $headers = [])
    {
        // For development/demo purposes, we'll use PHP's mail function
        // In production, you'd use PHPMailer or similar for SMTP
        
        $defaultHeaders = [
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=UTF-8',
            'From' => 'Nike Shoe Store <noreply@nikestore.com>',
            'Reply-To' => 'support@nikestore.com',
            'X-Mailer' => 'PHP/' . phpversion()
        ];

        $allHeaders = array_merge($defaultHeaders, $headers);
        
        $headerString = '';
        foreach ($allHeaders as $key => $value) {
            $headerString .= "$key: $value\r\n";
        }

        // Log email instead of sending in demo environment
        $this->logEmail($to, $subject, $body);
        
        // In a real environment, uncomment this line:
        // return mail($to, $subject, $body, $headerString);
        
        return true; // Return true for demo purposes
    }

    /**
     * Send order confirmation email
     */
    public function sendOrderConfirmation($order, $userEmail)
    {
        $subject = "Order Confirmation - Order #" . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
        
        $body = $this->renderOrderConfirmationTemplate($order);
        
        return $this->send($userEmail, $subject, $body);
    }

    /**
     * Send contact form notification
     */
    public function sendContactFormNotification($formData)
    {
        $subject = "New Contact Form Submission - " . $formData['subject'];
        
        $body = $this->renderContactFormTemplate($formData);
        
        // Send to admin
        $adminEmail = 'admin@nikestore.com';
        $this->send($adminEmail, $subject, $body);
        
        // Send auto-reply to customer
        $autoReplySubject = "Thank you for contacting Nike Shoe Store";
        $autoReplyBody = $this->renderContactAutoReplyTemplate($formData);
        
        return $this->send($formData['email'], $autoReplySubject, $autoReplyBody);
    }

    /**
     * Send order status update email
     */
    public function sendOrderStatusUpdate($order, $userEmail, $oldStatus, $newStatus)
    {
        $subject = "Order Update - Order #" . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
        
        $body = $this->renderOrderStatusUpdateTemplate($order, $oldStatus, $newStatus);
        
        return $this->send($userEmail, $subject, $body);
    }

    /**
     * Render order confirmation email template
     */
    private function renderOrderConfirmationTemplate($order)
    {
        $orderNumber = str_pad($order['id'], 6, '0', STR_PAD_LEFT);
        $orderDate = date('F j, Y \a\t g:i A', strtotime($order['created_at']));
        
        $itemsHtml = '';
        if (!empty($order['items'])) {
            foreach ($order['items'] as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $itemsHtml .= "
                    <tr>
                        <td style='padding: 10px; border-bottom: 1px solid #eee;'>
                            <strong>{$item['name']}</strong>
                        </td>
                        <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: center;'>
                            {$item['quantity']}
                        </td>
                        <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>
                            $" . number_format($item['price'], 2) . "
                        </td>
                        <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>
                            $" . number_format($itemTotal, 2) . "
                        </td>
                    </tr>
                ";
            }
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Order Confirmation</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #000; color: white; padding: 20px; text-align: center; margin-bottom: 20px;'>
                <h1 style='margin: 0; font-size: 24px;'>Nike Shoe Store</h1>
                <p style='margin: 5px 0 0 0; opacity: 0.9;'>Premium Athletic Footwear</p>
            </div>
            
            <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                <h2 style='color: #28a745; margin-top: 0;'>✓ Order Confirmed!</h2>
                <p style='margin-bottom: 0;'>Thank you for your order. We'll send you a shipping confirmation email as soon as your order ships.</p>
            </div>

            <div style='margin-bottom: 20px;'>
                <h3 style='border-bottom: 2px solid #000; padding-bottom: 10px;'>Order Details</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; font-weight: bold;'>Order Number:</td>
                        <td style='padding: 8px 0;'>#$orderNumber</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; font-weight: bold;'>Order Date:</td>
                        <td style='padding: 8px 0;'>$orderDate</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; font-weight: bold;'>Status:</td>
                        <td style='padding: 8px 0;'>" . ucfirst($order['status']) . "</td>
                    </tr>                    <tr>
                        <td style='padding: 8px 0; font-weight: bold;'>Payment Method:</td>
                        <td style='padding: 8px 0;'>" . (isset($order['payment_method']) ? ucwords(str_replace('_', ' ', $order['payment_method'])) : 'See notes') . "</td>
                    </tr>
                </table>
            </div>

            <div style='margin-bottom: 20px;'>
                <h3 style='border-bottom: 2px solid #000; padding-bottom: 10px;'>Order Items</h3>
                <table style='width: 100%; border-collapse: collapse; border: 1px solid #ddd;'>
                    <thead>
                        <tr style='background: #f8f9fa;'>
                            <th style='padding: 10px; text-align: left; border-bottom: 1px solid #ddd;'>Product</th>
                            <th style='padding: 10px; text-align: center; border-bottom: 1px solid #ddd;'>Qty</th>
                            <th style='padding: 10px; text-align: right; border-bottom: 1px solid #ddd;'>Price</th>
                            <th style='padding: 10px; text-align: right; border-bottom: 1px solid #ddd;'>Total</th>
                        </tr>
                    </thead>                    <tbody>
                        $itemsHtml";
        
        // Add discount row if applicable
        if (!empty($order['discount_code']) && $order['discount_amount'] > 0) {
            $emailBody .= "
                        <tr style='background: #e8f5e8;'>
                            <td colspan='3' style='padding: 10px; text-align: right; color: #155724;'>Discount (" . htmlspecialchars($order['discount_code']) . "):</td>
                            <td style='padding: 10px; text-align: right; color: #155724;'>-$" . number_format($order['discount_amount'], 2) . "</td>
                        </tr>";
        }
        
        $emailBody .= "
                        <tr style='background: #f8f9fa; font-weight: bold;'>
                            <td colspan='3' style='padding: 10px; text-align: right; border-top: 2px solid #000;'>Total Amount:</td>
                            <td style='padding: 10px; text-align: right; border-top: 2px solid #000;'>$" . number_format($order['total_amount'], 2) . "</td>
                        </tr>
                    </tbody>
                </table>
            </div><div style='margin-bottom: 20px;'>
                <h3 style='border-bottom: 2px solid #000; padding-bottom: 10px;'>Shipping Address</h3>
                <p style='background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 0;'>" . nl2br(htmlspecialchars(isset($order['shipping_address']) ? $order['shipping_address'] : ($order['name'] . "\n" . $order['address'] . "\n" . $order['city'] . "\n" . $order['phone']))) . "</p>
            </div>

            <div style='background: #e7f3ff; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                <h3 style='margin-top: 0; color: #0066cc;'>What's Next?</h3>
                <ul style='margin: 0; padding-left: 20px;'>
                    <li>We'll prepare your order for shipping within 1-2 business days</li>
                    <li>You'll receive a shipping confirmation email with tracking information</li>
                    <li>Your order will arrive in 5-7 business days</li>
                </ul>
            </div>

            <div style='text-align: center; margin-bottom: 20px;'>
                <a href='http://localhost/Webgiay/orders/{$order['id']}' style='background: #000; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;'>View Order Details</a>
            </div>

            <div style='border-top: 1px solid #ddd; padding-top: 20px; text-align: center; color: #666;'>
                <p>Questions about your order? Contact us at <a href='mailto:support@nikestore.com'>support@nikestore.com</a> or call 1-800-NIKE-STORE</p>
                <p style='font-size: 12px; margin-top: 20px;'>&copy; 2025 Nike Shoe Store. All rights reserved.</p>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Render contact form notification template
     */
    private function renderContactFormTemplate($formData)
    {
        $timestamp = date('F j, Y \a\t g:i A');
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>New Contact Form Submission</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #000; color: white; padding: 20px; text-align: center; margin-bottom: 20px;'>
                <h1 style='margin: 0;'>New Contact Form Submission</h1>
                <p style='margin: 5px 0 0 0; opacity: 0.9;'>Nike Shoe Store</p>
            </div>

            <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                <h2 style='margin-top: 0; color: #dc3545;'>Contact Form Details</h2>
                <p><strong>Submitted:</strong> $timestamp</p>
            </div>

            <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
                <tr>
                    <td style='padding: 10px; font-weight: bold; background: #f8f9fa; border: 1px solid #ddd;'>Name:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($formData['name']) . "</td>
                </tr>
                <tr>
                    <td style='padding: 10px; font-weight: bold; background: #f8f9fa; border: 1px solid #ddd;'>Email:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($formData['email']) . "</td>
                </tr>
                <tr>
                    <td style='padding: 10px; font-weight: bold; background: #f8f9fa; border: 1px solid #ddd;'>Subject:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($formData['subject']) . "</td>
                </tr>
            </table>

            <div style='margin-bottom: 20px;'>
                <h3 style='border-bottom: 2px solid #000; padding-bottom: 10px;'>Message</h3>
                <div style='background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #000;'>
                    " . nl2br(htmlspecialchars($formData['message'])) . "
                </div>
            </div>

            <div style='background: #fff3cd; padding: 15px; border-radius: 4px; border-left: 4px solid #ffc107;'>
                <p style='margin: 0; font-weight: bold;'>Action Required:</p>
                <p style='margin: 5px 0 0 0;'>Please respond to this customer inquiry within 24 hours.</p>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Render contact form auto-reply template
     */
    private function renderContactAutoReplyTemplate($formData)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Thank you for contacting us</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #000; color: white; padding: 20px; text-align: center; margin-bottom: 20px;'>
                <h1 style='margin: 0; font-size: 24px;'>Nike Shoe Store</h1>
                <p style='margin: 5px 0 0 0; opacity: 0.9;'>Premium Athletic Footwear</p>
            </div>

            <div style='background: #d4edda; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;'>
                <h2 style='color: #155724; margin-top: 0;'>Thank You for Contacting Us!</h2>
                <p style='margin-bottom: 0; color: #155724;'>We've received your message and will respond within 24 hours.</p>
            </div>

            <div style='margin-bottom: 20px;'>
                <p>Hello " . htmlspecialchars($formData['name']) . ",</p>
                <p>Thank you for reaching out to Nike Shoe Store. We've received your message regarding \"" . htmlspecialchars($formData['subject']) . "\" and our customer service team will review it shortly.</p>
                
                <div style='background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 20px 0;'>
                    <h3 style='margin-top: 0;'>Your Message Summary:</h3>
                    <p style='margin: 0;'><strong>Subject:</strong> " . htmlspecialchars($formData['subject']) . "</p>
                    <p style='margin: 10px 0 0 0;'><strong>Submitted:</strong> " . date('F j, Y \a\t g:i A') . "</p>
                </div>

                <p>In the meantime, here are some quick links that might be helpful:</p>
                <ul>
                    <li><a href='http://localhost/Webgiay/orders' style='color: #000;'>Track Your Order</a></li>
                    <li><a href='http://localhost/Webgiay/terms' style='color: #000;'>Return Policy</a></li>
                    <li><a href='http://localhost/Webgiay/contact' style='color: #000;'>Size Guide</a></li>
                    <li><a href='http://localhost/Webgiay/products' style='color: #000;'>Browse Products</a></li>
                </ul>
            </div>

            <div style='background: #e7f3ff; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                <h3 style='margin-top: 0; color: #0066cc;'>Contact Information</h3>
                <p style='margin: 5px 0;'><strong>Email:</strong> support@nikestore.com</p>
                <p style='margin: 5px 0;'><strong>Phone:</strong> 1-800-NIKE-STORE</p>
                <p style='margin: 5px 0;'><strong>Hours:</strong> Monday-Friday 9AM-8PM EST</p>
            </div>

            <div style='border-top: 1px solid #ddd; padding-top: 20px; text-align: center; color: #666;'>
                <p>This is an automated response. Please do not reply to this email.</p>
                <p style='font-size: 12px; margin-top: 20px;'>&copy; 2025 Nike Shoe Store. All rights reserved.</p>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Render order status update template
     */
    private function renderOrderStatusUpdateTemplate($order, $oldStatus, $newStatus)
    {
        $orderNumber = str_pad($order['id'], 6, '0', STR_PAD_LEFT);
        $statusMessage = $this->getStatusMessage($newStatus);
        $trackingInfo = '';
        
        if ($newStatus === 'shipped') {
            $trackingInfo = "
                <div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;'>
                    <h3 style='color: #155724; margin-top: 0;'>📦 Your Order is On the Way!</h3>
                    <p style='color: #155724; margin: 0;'>Your package has been shipped and should arrive within 5-7 business days.</p>
                    <p style='color: #155724; margin: 10px 0 0 0;'><strong>Tracking Number:</strong> TRK" . $order['id'] . rand(1000, 9999) . "</p>
                </div>
            ";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Order Status Update</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #000; color: white; padding: 20px; text-align: center; margin-bottom: 20px;'>
                <h1 style='margin: 0; font-size: 24px;'>Nike Shoe Store</h1>
                <p style='margin: 5px 0 0 0; opacity: 0.9;'>Order Status Update</p>
            </div>

            <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                <h2 style='margin-top: 0; color: #0066cc;'>Order #$orderNumber Status Update</h2>
                <p style='margin-bottom: 0;'>Your order status has been updated to: <strong>" . ucfirst($newStatus) . "</strong></p>
            </div>

            $trackingInfo

            <div style='margin-bottom: 20px;'>
                <p>$statusMessage</p>
            </div>

            <div style='text-align: center; margin-bottom: 20px;'>
                <a href='http://localhost/Webgiay/orders/{$order['id']}' style='background: #000; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;'>View Order Details</a>
            </div>

            <div style='border-top: 1px solid #ddd; padding-top: 20px; text-align: center; color: #666;'>
                <p>Questions about your order? Contact us at <a href='mailto:support@nikestore.com'>support@nikestore.com</a></p>
                <p style='font-size: 12px; margin-top: 20px;'>&copy; 2025 Nike Shoe Store. All rights reserved.</p>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Get status message for order updates
     */
    private function getStatusMessage($status)
    {
        switch ($status) {
            case 'processing':
                return "Great news! We've received your order and our team is preparing it for shipment. You'll receive another email once your order ships.";
            case 'shipped':
                return "Your order is on its way! You can expect delivery within 5-7 business days. We'll send you tracking information so you can monitor your package.";
            case 'delivered':
                return "Your order has been delivered! We hope you love your new shoes. If you have any issues, please don't hesitate to contact us.";
            default:
                return "Your order status has been updated. Please check your order details for more information.";
        }
    }

    /**
     * Log email for demo/development purposes
     */
    private function logEmail($to, $subject, $body)
    {
        $logDir = __DIR__ . '/../logs/';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . 'emails.log';
        $timestamp = date('Y-m-d H:i:s');
        
        $logEntry = "
========================================
Email Logged: $timestamp
To: $to
Subject: $subject
========================================
$body
========================================

";

        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
