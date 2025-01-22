
<?php
require_once 'vendor/autoload.php'; // Ensure the Azure SDK is loaded
require_once 'db_connection.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

function uploadToBlob($filePath, $blobName) {
    $connectionString = "DefaultEndpointsProtocol=https;AccountName=" . BLOB_ACCOUNT_NAME . ";AccountKey=" . BLOB_ACCOUNT_KEY;
    $blobClient = BlobRestProxy::createBlobService($connectionString);

    try {
        $content = fopen($filePath, "r");
        $blobClient->createBlockBlob("product-images", $blobName, $content);
        return "https://" . BLOB_ACCOUNT_NAME . ".blob.core.windows.net/product-images/" . $blobName;
    } catch (ServiceException $e) {
        log_error($e->getMessage());
        return false;
    }
}
?>
