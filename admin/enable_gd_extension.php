<?php
/**
 * Enable GD Extension for XAMPP
 * This script will enable the GD extension in php.ini
 */

echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;'>";
echo "<h1>🛠️ Enable GD Extension</h1>";

$phpIniPath = 'C:\xampp\php\php.ini';

if (!file_exists($phpIniPath)) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24; margin: 15px 0;'>";
    echo "<strong>❌ Error:</strong> Could not find php.ini file at: {$phpIniPath}";
    echo "</div>";
    exit;
}

echo "<div style='background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>What this script does:</h3>";
echo "<ul>";
echo "<li>🔍 Checks if GD extension is currently enabled</li>";
echo "<li>✏️ Uncomments the GD extension line in php.ini</li>";
echo "<li>🔄 Instructions to restart Apache</li>";
echo "<li>✅ Verifies GD extension is working</li>";
echo "</ul>";
echo "</div>";

// Check current status
$isGdLoaded = extension_loaded('gd');
echo "<p><strong>Current GD Status:</strong> " . ($isGdLoaded ? "<span style='color: green;'>✅ Enabled</span>" : "<span style='color: red;'>❌ Disabled</span>") . "</p>";

if (isset($_GET['action']) && $_GET['action'] === 'enable') {
    echo "<h2>🔄 Enabling GD Extension...</h2>";
    
    // Read php.ini file
    $phpIniContent = file_get_contents($phpIniPath);
    
    if ($phpIniContent === false) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
        echo "<strong>❌ Error:</strong> Could not read php.ini file.";
        echo "</div>";
        exit;
    }
    
    // Check if GD is already enabled
    if (strpos($phpIniContent, 'extension=gd') !== false && strpos($phpIniContent, ';extension=gd') === false) {
        echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px; color: #0c5460;'>";
        echo "<strong>ℹ️ Info:</strong> GD extension is already enabled in php.ini.";
        echo "</div>";
    } else {
        // Create backup first
        $backupPath = $phpIniPath . '.backup.' . date('Y-m-d-H-i-s');
        if (copy($phpIniPath, $backupPath)) {
            echo "<p>✅ Created backup: " . basename($backupPath) . "</p>";
        }
        
        // Enable GD extension
        $phpIniContent = str_replace(';extension=gd', 'extension=gd', $phpIniContent);
        
        if (file_put_contents($phpIniPath, $phpIniContent)) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724; margin: 15px 0;'>";
            echo "<strong>✅ Success!</strong> GD extension has been enabled in php.ini.";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
            echo "<strong>❌ Error:</strong> Could not write to php.ini file. Please check file permissions.";
            echo "</div>";
        }
    }
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; color: #856404; margin: 15px 0;'>";
    echo "<h4>⚠️ Important: Restart Required</h4>";
    echo "<p>You need to restart Apache for the changes to take effect:</p>";
    echo "<ol>";
    echo "<li>Open XAMPP Control Panel</li>";
    echo "<li>Stop Apache</li>";
    echo "<li>Start Apache again</li>";
    echo "<li>Refresh this page to verify GD is working</li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<p style='text-align: center; margin: 30px 0;'>";
    echo "<a href='?' style='background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;'>🔄 Check Status Again</a>";
    echo "</p>";
    
} else {
    // Show current status and options
    if (!$isGdLoaded) {
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; color: #856404; margin: 15px 0;'>";
        echo "<strong>⚠️ GD Extension is Disabled</strong><br>";
        echo "The GD extension is required for image manipulation functions like <code>imagecreate()</code>.";
        echo "</div>";
        
        echo "<p style='text-align: center;'>";
        echo "<a href='?action=enable' style='background: #dc3545; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-size: 18px; font-weight: bold; display: inline-block;'>🛠️ Enable GD Extension</a>";
        echo "</p>";
    } else {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724; margin: 15px 0;'>";
        echo "<strong>✅ GD Extension is Enabled</strong><br>";
        echo "Your PHP installation supports image manipulation functions.";
        echo "</div>";
        
        echo "<p style='text-align: center;'>";
        echo "<a href='update_product_images.php' style='background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;'>🖼️ Run Image Updater</a>";
        echo "</p>";
    }
}

echo "<div style='margin-top: 30px; text-align: center;'>";
echo "<a href='bulk_image_downloader.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🚀 Bulk Image Downloader</a>";
echo "<a href='view_image_gallery.php' style='background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🖼️ Image Gallery</a>";
echo "</div>";

echo "</div>";
?>

<style>
body {
    background: #f8f9fa;
    margin: 0;
    padding: 0;
}
code {
    background: #f1f1f1;
    padding: 2px 4px;
    border-radius: 3px;
    font-family: monospace;
}
</style>
