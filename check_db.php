<?php
require_once 'app/Core/Database.php';

$db = new Database();

echo "=== Order Items Table Structure ===\n";
$result = $db->fetchAll('SHOW CREATE TABLE order_items');
print_r($result);

echo "\n=== Foreign Key Constraints ===\n";
$constraints = $db->fetchAll("
    SELECT 
        CONSTRAINT_NAME,
        COLUMN_NAME,
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM 
        information_schema.KEY_COLUMN_USAGE 
    WHERE 
        TABLE_SCHEMA = 'nike_store' 
        AND TABLE_NAME = 'order_items' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
");
print_r($constraints);
?>
