<?php

// Include the database connection
include 'db_connect.php';

// Start session
session_start();

// Check if username and password are set
if(isset($_POST['username']) && isset($_POST['password'])) {
    // Sanitize user inputs to prevent SQL injection
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    
    // Check if the provided credentials are for admin
    if($username === 'admin' && $password === 'admin') {
        // Set session variables for admin
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        $_SESSION['full_name'] = 'admin';
        echo '<script>console.log('.$username.' '.$password.');</script>';

        // Redirect to admin dashboard
        header("Location: ../admin.html");
        exit();
    } else {
        // Query to check if the username and password match in user_list table
        $user_query = "SELECT * FROM customers WHERE username='$username' AND `password`='$password'";
        $user_result = $conn->query($user_query);

        if($user_result && $user_result->num_rows > 0) {
            // Fetch user data
            $user_row = $user_result->fetch_assoc();
            $full_name = $user_row['full_name'];
            $user_id = $user_row['user_id'];
            

            // Set session variables for user
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'customer';
            $_SESSION['full_name'] = $full_name;

            // Redirect to user dashboard
            header("Location: ../menu.html");
            exit();
        }

        // Query to check if the username and password match in in_charge table
        $chef_query = "SELECT * FROM in_charge WHERE username='$username' AND `password`='$password'";
        $chef_result = $conn->query($chef_query);

        if ($chef_result && $chef_result->num_rows > 0) {
            // Fetch rider data
            $chef_row = $chef_result->fetch_assoc();
            $full_name = $chef_row['full_name'];
            $user_id = $chef_row['user_id'];

            // Set session variables for rider
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = 'in-charge';
            $_SESSION['full_name'] = $full_name;

            // Redirect to rider dashboardsssa
            header("Location: ../dashboard.html");
            exit();
        }
    }
}

// Invalid username or password, redirect to index page
echo '<script>alert("Invalid username or password");';
echo 'window.location.href= "../index.html"</script>';
exit();

?>
