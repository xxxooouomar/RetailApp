
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

    if (isset($input["user_id"]) && isset($input["address"]) && isset($input["payment_method"])) {
        $user_id = $input["user_id"];
        $address = $input["address"];
        $payment_method = $input["payment_method"];

        // Start transaction
        sqlsrv_begin_transaction($conn);

        // Create order
        $query_order = "INSERT INTO orders (user_id, address, payment_method) OUTPUT INSERTED.id VALUES (?, ?, ?)";
        $stmt_order = sqlsrv_prepare($conn, $query_order, array($user_id, $address, $payment_method));

        if ($stmt_order && sqlsrv_execute($stmt_order)) {
            $order_id = sqlsrv_fetch_array($stmt_order, SQLSRV_FETCH_ASSOC)["id"];

            // Move cart items to order_items
            $query_items = "
                INSERT INTO order_items (order_id, product_id, quantity, price)
                SELECT ?, product_id, quantity, (SELECT price FROM products WHERE id = product_id)
                FROM cart WHERE user_id = ?";
            $stmt_items = sqlsrv_prepare($conn, $query_items, array($order_id, $user_id));

            if ($stmt_items && sqlsrv_execute($stmt_items)) {
                // Clear the user's cart
                $query_clear_cart = "DELETE FROM cart WHERE user_id = ?";
                $stmt_clear_cart = sqlsrv_prepare($conn, $query_clear_cart, array($user_id));

                if ($stmt_clear_cart && sqlsrv_execute($stmt_clear_cart)) {
                    sqlsrv_commit($conn);
                    echo json_encode(["status" => "success", "message" => "Checkout successful"]);
                } else {
                    sqlsrv_rollback($conn);
                    echo json_encode(["status" => "error", "message" => "Failed to clear cart"]);
                }
            } else {
                sqlsrv_rollback($conn);
                echo json_encode(["status" => "error", "message" => "Failed to move items to order"]);
            }
        } else {
            sqlsrv_rollback($conn);
            echo json_encode(["status" => "error", "message" => "Failed to create order"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
