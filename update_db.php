<?php
@include 'config.php';

$alter_query = "ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL";
$conn->exec($alter_query);

echo "Password column updated successfully!";
?>
