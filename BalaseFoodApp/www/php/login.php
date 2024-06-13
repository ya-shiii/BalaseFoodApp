<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Include the database connection
include 'db_connect.php';

// Get the JSON input data
$data = json_decode(file_get_contents('php://input'), true);

// Check if username and password are set
if (isset($data['username']) && isset($data['password'])) {
    // Sanitize user inputs to prevent SQL injection
    $username = $conn->real_escape_string($data['username']);
    $password = $conn->real_escape_string($data['password']);
    
    // Check if the provided credentials are for admin
    if ($username === 'admin' && $password === 'admin') {
        // Return success response for admin
        echo json_encode([
            'success' => true,
            'user_id' => 1,
            'username' => $username,
            'role' => 'admin',
            'full_name' => 'admin',
            'redirect' => 'admin.html'
        ]);
        exit();
    } else {
        // Query to check if the username and password match in customers table
        $user_query = "SELECT * FROM customers WHERE username='$username' AND `password`='$password'";
        $user_result = $conn->query($user_query);

        if ($user_result && $user_result->num_rows > 0) {
            // Fetch user data
            $user_row = $user_result->fetch_assoc();
            $full_name = $user_row['full_name'];
            $user_id = $user_row['user_id'];

            // Return success response for user
            echo json_encode([
                'success' => true,
                'user_id' => $user_id,
                'username' => $username,
                'role' => 'customer',
                'full_name' => $full_name,
                'redirect' => 'menu.html'
            ]);
            exit();
        }

        // Query to check if the username and password match in in_charge table
        $chef_query = "SELECT * FROM in_charge WHERE username='$username' AND `password`='$password'";
        $chef_result = $conn->query($chef_query);

        if ($chef_result && $chef_result->num_rows > 0) {
            // Fetch chef data
            $chef_row = $chef_result->fetch_assoc();
            $full_name = $chef_row['full_name'];
            $user_id = $chef_row['user_id'];

            // Return success response for chef
            echo json_encode([
                'success' => true,
                'user_id' => $user_id,
                'username' => $username,
                'role' => 'in-charge',
                'full_name' => $full_name,
                'redirect' => 'dashboard.html'
            ]);
            exit();
        }
    }
}

// Invalid username or password
echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
exit();
