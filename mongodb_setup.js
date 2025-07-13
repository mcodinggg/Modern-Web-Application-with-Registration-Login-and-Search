// MongoDB setup script
// Run this in MongoDB shell: mongo modern_web_app mongodb_setup.js

// Create collections and indexes
db.createCollection("users");
db.createCollection("orders");

// Create indexes for faster search
db.users.createIndex({ "name": "text", "email": "text" });
db.orders.createIndex({ "product": "text" });

// Sample data
db.users.insertMany([
    {
        "mysql_id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "3634",
        "created_at": new Date(),
        "orders": [
            { "date": "2002", "product": "Gizmo" },
            { "date": "2004", "product": "Gadget" }
        ]
    },
    {
        "mysql_id": 2,
        "name": "Jane Smith",
        "email": "jane@example.com",
        "phone": "6343",
        "created_at": new Date(),
        "orders": [
            { "date": "2002", "product": "Gadget" }
        ]
    }
]);

db.orders.insertMany([
    {
        "user_id": 1,
        "product": "Gizmo",
        "date": "2002"
    },
    {
        "user_id": 1,
        "product": "Gadget",
        "date": "2004"
    },
    {
        "user_id": 2,
        "product": "Gadget",
        "date": "2002"
    }
]);