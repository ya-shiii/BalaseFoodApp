<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Unset all of the cookies
setcookie('user_id', '', time() - 3600, '/');
setcookie('username', '', time() - 3600, '/');
setcookie('role', '', time() - 3600, '/');
setcookie('full_name', '', time() - 3600, '/');

// Redirect to the login page or home page
header('Location: ../'); // Adjust the path based on your file structure
exit();
?>
