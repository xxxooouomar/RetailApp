
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

// Check if the request method is DELETE
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    parse_str(file_get_contents("php://input"), $input);

    if (isset($input["id"])) {
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = sqlsrv_prepare($conn, $query, array($input["id"]));

        if ($stmt && sqlsrv_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Product deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete product"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing product ID"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
