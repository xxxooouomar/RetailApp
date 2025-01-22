
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

// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["user_id"])) {
        $query = "
            SELECT cart.id, products.name, products.price, cart.quantity, 
                   (products.price * cart.quantity) AS total
            FROM cart
            INNER JOIN products ON cart.product_id = products.id
            WHERE cart.user_id = ?";
        $stmt = sqlsrv_prepare($conn, $query, array($_GET["user_id"]));

        if ($stmt && sqlsrv_execute($stmt)) {
            $cart_items = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $cart_items[] = $row;
            }
            echo json_encode($cart_items);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to fetch cart items"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing user ID"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
