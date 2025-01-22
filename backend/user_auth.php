
<?php
// Include the database connection file
require_once 'db_connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get input data from the request body
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input["username"]) && isset($input["password"])) {
        $username = $input["username"];
        $password = $input["password"];

        // Prepare and execute SQL query to fetch user
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = sqlsrv_prepare($conn, $query, array(&$username));

        if ($stmt) {
            if (sqlsrv_execute($stmt)) {
                $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                if ($user && password_verify($password, $user["password"])) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "Login successful",
                        "user" => [
                            "id" => $user["id"],
                            "username" => $user["username"]
                        ]
                    ]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to execute query"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to prepare query"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing username or password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
