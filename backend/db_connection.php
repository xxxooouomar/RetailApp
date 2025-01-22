
<?php
// Azure SQL Database configuration
define('DB_SERVER', 'tcp:retailsr.database.windows.net,1433');
define('DB_USERNAME', 'adminuser');
define('DB_PASSWORD', 'Ly7002om'); // Replace with your actual password
define('DB_NAME', 'RetailDB');

// Attempt to connect to the database
try {
    $conn = new PDO("sqlsrv:server = " . DB_SERVER . "; Database = " . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
