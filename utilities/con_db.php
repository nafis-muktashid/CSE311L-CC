<?php
/**
 * Database Connection Configuration
 * 
 * Establishes connection to MySQL database using mysqli.
 * Used across the application for database operations.
 * 
 * Configuration Variables:
 * - db_host: Database server hostname
 * - db_user: Database username
 * - db_password: Database password
 * - db_name: Database name
 * 
 * @return mysqli Connection object available as $db_connection
 * @throws mysqli_sql_exception on connection failure
 */

// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "connectcore";

// Error reporting for development
// TODO: Disable in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Establish database connection
    $db_connection = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($db_connection->connect_error) {
        throw new Exception("Database Connection Failed: " . $db_connection->connect_error);
    }

    // Set charset to ensure proper encoding
    $db_connection->set_charset("utf8mb4");

} catch (Exception $e) {
    // Log error and terminate
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database Connection Error. Please contact administrator.");
}

/**
 * Usage Example:
 * 
 * require_once './utilities/con_db.php';
 * 
 * $query = "SELECT * FROM users WHERE id = ?";
 * $stmt = $db_connection->prepare($query);
 * $stmt->bind_param("i", $userId);
 * $stmt->execute();
 * $result = $stmt->get_result();
 */
