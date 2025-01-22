
<?php
// Include the database connection file
require_once 'db_connection.php';

// Check the HTTP request method
$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case "GET":
        // Fetch all products or a single product by ID
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
        break;

    case "POST":
        // Add a new product
        $input = json_decode(file_get_contents("php://input"), true);
        $query = "INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)";
        $stmt = sqlsrv_prepare($conn, $query, array($input["name"], $input["description"], $input["price"], $input["image_url"]));

        if ($stmt && sqlsrv_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Product added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add product"]);
        }
        break;

    case "PUT":
        // Update an existing product
        $input = json_decode(file_get_contents("php://input"), true);
        $query = "UPDATE products SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?";
        $stmt = sqlsrv_prepare($conn, $query, array($input["name"], $input["description"], $input["price"], $input["image_url"], $input["id"]));

        if ($stmt && sqlsrv_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Product updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update product"]);
        }
        break;

    case "DELETE":
        // Delete a product by ID
        parse_str(file_get_contents("php://input"), $input);
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = sqlsrv_prepare($conn, $query, array($input["id"]));

        if ($stmt && sqlsrv_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Product deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete product"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid request method"]);
        break;
}
?>
