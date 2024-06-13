<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}


session_start();
include 'db_connect.php';

// Check if the user ID is set in the POST request
if (isset($_POST['u_id'])) {
    // Sanitize the input data
    $u_id = mysqli_real_escape_string($conn, $_POST['u_id']);

    // Begin a transaction
    mysqli_begin_transaction($conn);

    try {
        // Fetch the user's details from the in_charge table
        $select_query = "SELECT * FROM in_charge WHERE user_id = '$u_id'";
        $result = mysqli_query($conn, $select_query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch the row data
            $row = mysqli_fetch_assoc($result);

            // Insert the fetched details into the customer table
            $insert_query = "INSERT INTO customers (username, password, full_name, email, phone, address)
                             VALUES ('{$row['username']}', '{$row['password']}', '{$row['full_name']}', '{$row['email']}', '{$row['phone']}', '{$row['address']}')";
            if (mysqli_query($conn, $insert_query)) {
                // Delete the user from the in_charge table
                $delete_query = "DELETE FROM in_charge WHERE user_id = '$u_id'";
                if (mysqli_query($conn, $delete_query)) {
                    // Commit the transaction
                    mysqli_commit($conn);
                    $response = array(
                        'success' => true,
                        'message' => 'User type changed successfully.'
                    );
                } else {
                    // Rollback the transaction if delete fails
                    mysqli_rollback($conn);
                    $response = array(
                        'success' => false,
                        'message' => 'Failed to delete user from in_charge table.'
                    );
                }
            } else {
                // Rollback the transaction if insert fails
                mysqli_rollback($conn);
                $response = array(
                    'success' => false,
                    'message' => 'Failed to insert user into customer table.'
                );
            }
        } else {
            $response = array(
                'success' => false,
                'message' => 'User not found in in_charge table.'
            );
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of an exception
        mysqli_rollback($conn);
        $response = array(
            'success' => false,
            'message' => 'Transaction failed: ' . $e->getMessage()
        );
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    $response = array(
        'success' => false,
        'message' => 'User ID is missing.'
    );
}

// Send the JSON response
echo json_encode($response);
?>
