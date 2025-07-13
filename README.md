# Modern Web Application

A full-stack web application with registration, login, and search functionality.

## Features

- User registration and login
- JWT-based authentication
- Search across both SQL and NoSQL databases
- Responsive design
- MySQL and MongoDB integration

## Technologies Used

- Frontend: HTML5, CSS3, JavaScript
- Backend: PHP
- Databases: MySQL, MongoDB
- Authentication: JWT

## Setup Instructions

1. Clone the repository
2. Set up a web server (Apache, Nginx) with PHP support
3. Import the MySQL database schema from `database/mysql_setup.sql`
4. Run the MongoDB setup script `database/mongodb_setup.js`
5. Update database credentials in `backend/config.php`
6. Deploy the files to your web server

## API Endpoints

- POST `/backend/api/auth.php?action=register` - User registration
- POST `/backend/api/auth.php?action=login` - User login
- GET `/backend/api/auth.php?action=get_orders` - Get user orders
- GET `/backend/api/search.php?query=...` - Search functionality

## Screenshots

- Registration page
- Login page
- Dashboard with search functionality