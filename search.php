<?php
require_once('../config.php');

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$token = str_replace('Bearer ', '', $authHeader);

$userId = verifyToken($token);
if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$query = $_GET['query'] ?? '';
if (empty($query)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Search query is required']);
    exit;
}

// Search in both SQL and NoSQL databases
$results = [];

// Search in MySQL
$conn = getDBConnection();
$searchTerm = "%$query%";

// Search users
$stmt = $conn->prepare("SELECT name, phone FROM users WHERE name LIKE ? OR email LIKE ?");
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['type'] = 'user';
    $results[] = $row;
}

// Search orders
$stmt = $conn->prepare("SELECT product, date FROM orders WHERE product LIKE ?");
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['type'] = 'order';
    $results[] = $row;
}

// Search in MongoDB
try {
    $mongoDB = getMongoDB();
    
    // Search users
    $mongoUsers = $mongoDB->users->find([
        '$or' => [
            ['name' => new MongoDB\BSON\Regex($query, 'i')],
            ['email' => new MongoDB\BSON\Regex($query, 'i')]
        ]
    ]);
    
    foreach ($mongoUsers as $user) {
        $results[] = [
            'name' => $user->name,
            'phone' => $user->phone ?? '',
            'type' => 'mongo_user'
        ];
    }
    
    // Search orders (if stored in MongoDB)
    $mongoOrders = $mongoDB->orders->find([
        'product' => new MongoDB\BSON\Regex($query, 'i')
    ]);
    
    foreach ($mongoOrders as $order) {
        $results[] = [
            'product' => $order->product,
            'date' => $order->date,
            'type' => 'mongo_order'
        ];
    }
} catch (Exception $e) {
    // Log error but don't fail the whole search
    error_log("MongoDB search error: " . $e->getMessage());
}

echo json_encode(['success' => true, 'results' => $results]);
?>