<?php
// Establish database connection
$conn = mysqli_connect('db', 'coffee_app', 'frequent-recopy-stinking-valley-campus-idealism-elbow-bucked', 'coffee_shop');


// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Application configuration
define('UPLOAD_DIR', '/var/www/html/uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']); // Not enforced for CTF purposes
?>