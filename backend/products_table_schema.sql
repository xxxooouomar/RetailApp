
-- SQL schema for `products` table
CREATE TABLE products (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(255) NOT NULL,
    description NVARCHAR(MAX) NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url NVARCHAR(2083) NULL,
    created_at DATETIME DEFAULT GETDATE()
);
