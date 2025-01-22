
<?php
require_once 'db_connection.php';
require_once 'upload_to_blob.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($_FILES["image"]) && isset($input["name"]) && isset($input["description"]) && isset($input["price"])) {
        $name = $input["name"];
        $description = $input["description"];
        $price = $input["price"];

        // Handle image upload
        $imagePath = $_FILES["image"]["tmp_name"];
        $blobName = uniqid() . "-" . $_FILES["image"]["name"];
        $imageUrl = uploadToBlob($imagePath, $blobName);

        if ($imageUrl) {
            $query = "INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)";
            $stmt = sqlsrv_prepare($conn, $query, array($name, $description, $price, $imageUrl));

            if ($stmt && sqlsrv_execute($stmt)) {
                echo json_encode(["status" => "success", "message" => "Product created successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to create product"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Image upload failed"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
