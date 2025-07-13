<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'modern_web_app');

// MongoDB configuration
define('MONGO_URI', 'mongodb://localhost:27017');
define('MONGO_DB', 'modern_web_app');

// JWT Secret Key
define('JWT_SECRET', 'your_secret_key_here');

// Connect to MySQL
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Connect to MongoDB
function getMongoDB() {
    try {
        $client = new MongoDB\Client(MONGO_URI);
        return $client->selectDatabase(MONGO_DB);
    } catch (Exception $e) {
        die("MongoDB connection failed: " . $e->getMessage());
    }
}

// Generate JWT token
function generateToken($userId) {
    $payload = [
        'iat' => time(),
        'exp' => time() + (60 * 60 * 24), // 1 day expiration
        'sub' => $userId
    ];
    return \Firebase\JWT\JWT::encode($payload, JWT_SECRET, 'HS256');
}

// Verify JWT token
function verifyToken($token) {
    try {
        $decoded = \Firebase\JWT\JWT::decode($token, JWT_SECRET, ['HS256']);
        return $decoded->sub;
    } catch (Exception $e) {
        return false;
    }
}
?>