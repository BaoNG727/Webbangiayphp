<?php

/**
 * Production Configuration for Nike Shoe Store
 * This file contains settings optimized for production deployment
 */

class Config
{
    // Database Configuration
    const DB_HOST = 'localhost';
    const DB_NAME = 'nike_shoe_store';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_CHARSET = 'utf8mb4';

    // Site Configuration
    const SITE_NAME = 'Nike Shoe Store';
    const SITE_URL = 'https://your-domain.com';
    const SITE_EMAIL = 'support@nikestore.com';

    // Security Configuration
    const ENABLE_HTTPS = true;
    const CSRF_PROTECTION = true;
    const SESSION_LIFETIME = 86400; // 24 hours
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOGIN_COOLDOWN = 900; // 15 minutes

    // Email Configuration
    const SMTP_HOST = 'smtp.gmail.com';
    const SMTP_PORT = 587;
    const SMTP_USERNAME = 'your-email@gmail.com';
    const SMTP_PASSWORD = 'your-app-password';
    const SMTP_ENCRYPTION = 'tls';

    // File Upload Configuration
    const MAX_FILE_SIZE = 5242880; // 5MB
    const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    const UPLOAD_PATH = __DIR__ . '/../uploads/';

    // Caching Configuration
    const ENABLE_CACHE = true;
    const CACHE_LIFETIME = 3600; // 1 hour
    const CACHE_PATH = __DIR__ . '/../cache/';

    // Performance Configuration
    const ENABLE_COMPRESSION = true;
    const MINIFY_HTML = true;
    const MINIFY_CSS = true;
    const MINIFY_JS = true;

    // Logging Configuration
    const LOG_ERRORS = true;
    const LOG_PATH = __DIR__ . '/../logs/';
    const LOG_LEVEL = 'ERROR'; // DEBUG, INFO, WARNING, ERROR

    // Payment Configuration
    const PAYMENT_CURRENCY = 'USD';
    const TAX_RATE = 0.08; // 8%
    const SHIPPING_COST = 10.00;
    const FREE_SHIPPING_THRESHOLD = 100.00;

    // Development vs Production
    const DEBUG_MODE = false;
    const DISPLAY_ERRORS = false;
    const ERROR_REPORTING = E_ERROR | E_WARNING | E_PARSE;

    /**
     * Get database connection parameters
     */
    public static function getDatabaseConfig()
    {
        return [
            'host' => self::DB_HOST,
            'dbname' => self::DB_NAME,
            'username' => self::DB_USER,
            'password' => self::DB_PASS,
            'charset' => self::DB_CHARSET
        ];
    }

    /**
     * Initialize production settings
     */
    public static function initProduction()
    {
        // Error reporting
        error_reporting(self::ERROR_REPORTING);
        ini_set('display_errors', self::DISPLAY_ERRORS ? 1 : 0);
        ini_set('log_errors', self::LOG_ERRORS ? 1 : 0);

        // Session settings
        ini_set('session.gc_maxlifetime', self::SESSION_LIFETIME);
        ini_set('session.cookie_lifetime', self::SESSION_LIFETIME);

        // PHP settings for production
        ini_set('expose_php', 0);
        ini_set('allow_url_fopen', 0);
        ini_set('allow_url_include', 0);

        // Output compression
        if (self::ENABLE_COMPRESSION && !ob_get_level()) {
            ob_start('ob_gzhandler');
        }

        // Set timezone
        date_default_timezone_set('America/New_York');
    }

    /**
     * Get email configuration
     */
    public static function getEmailConfig()
    {
        return [
            'host' => self::SMTP_HOST,
            'port' => self::SMTP_PORT,
            'username' => self::SMTP_USERNAME,
            'password' => self::SMTP_PASSWORD,
            'encryption' => self::SMTP_ENCRYPTION
        ];
    }

    /**
     * Get cache directory path
     */
    public static function getCachePath($type = 'general')
    {
        return self::CACHE_PATH . $type . '/';
    }

    /**
     * Check if feature is enabled
     */
    public static function isFeatureEnabled($feature)
    {
        $enabledFeatures = [
            'cache' => self::ENABLE_CACHE,
            'compression' => self::ENABLE_COMPRESSION,
            'https' => self::ENABLE_HTTPS,
            'csrf' => self::CSRF_PROTECTION,
            'debug' => self::DEBUG_MODE
        ];

        return isset($enabledFeatures[$feature]) ? $enabledFeatures[$feature] : false;
    }
}
