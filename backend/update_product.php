
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

// Check if the request method is PUT
if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input["id"]) && isset($input["name"]) && isset($input["description"]) && isset($input["price"])) {
        $id = $input["id"];
        $name = $input["name"];
        $description = $input["description"];
        $price = $input["price"];
        $image_url = isset($input["image_url"]) ? $input["image_url"] : null;

        // Query to update the product
        $query = "UPDATE products SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?";
        $stmt = sqlsrv_prepare($conn, $query, array($name, $description, $price, $image_url, $id));

        if ($stmt && sqlsrv_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Product updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update product"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
