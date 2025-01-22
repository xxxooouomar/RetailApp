
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

    if (isset($input["user_id"]) && isset($input["product_id"]) && isset($input["quantity"])) {
        $user_id = $input["user_id"];
        $product_id = $input["product_id"];
        $quantity = $input["quantity"];

        // Query to add the product to the cart
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = sqlsrv_prepare($conn, $query, array($user_id, $product_id, $quantity));

        if ($stmt && sqlsrv_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Product added to cart"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add product to cart"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
