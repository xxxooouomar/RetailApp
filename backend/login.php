
<?php

require_once 'error_logger.php';

try {
    // Original API logic goes here
} catch (Exception $e) {
    log_error($e->getMessage());
    echo json_encode(["status" => "error", "message" => "An unexpected error occurred"]);
}

// Include the database connection
require_once 'db_connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    // Validate input
    if (isset($input["username"]) && isset($input["password"])) {
        $username = $input["username"];
        $password = $input["password"];

        // Query to check if the user exists
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = sqlsrv_prepare($conn, $query, array($username));

        if ($stmt && sqlsrv_execute($stmt)) {
            $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            if ($user && password_verify($password, $user["password"])) {
                echo json_encode(["status" => "success", "message" => "Login successful", "user" => $user]);
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Database query failed"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing username or password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
