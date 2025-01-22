
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
    if (isset($input["username"]) && isset($input["password"]) && isset($input["email"])) {
        $username = $input["username"];
        $email = $input["email"];
        $password = password_hash($input["password"], PASSWORD_BCRYPT);

        // Query to insert a new user
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = sqlsrv_prepare($conn, $query, array($username, $email, $password));

        if ($stmt && sqlsrv_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Registration successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to register user"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
