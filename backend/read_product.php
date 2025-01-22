
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
    if (isset($_GET["id"])) {
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = sqlsrv_prepare($conn, $query, array($_GET["id"]));
    } else {
        $query = "SELECT * FROM products";
        $stmt = sqlsrv_query($conn, $query);
    }

    if ($stmt && sqlsrv_execute($stmt)) {
        $products = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $products[] = $row;
        }
        echo json_encode($products);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to fetch products"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
