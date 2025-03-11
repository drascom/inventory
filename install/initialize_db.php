<?php
require_once('../initialize.php');

function initializeDatabase()
{
    try {
        // Create connection without database selection
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Create database if not exists
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
        if (!$conn->query($sql)) {
            throw new Exception("Error creating database: " . $conn->error);
        }

        // Select the database
        $conn->select_db(DB_NAME);

        // Check if SQL file exists
        $sql_file_path = __DIR__ . '/sms_db.sql';
        if (!file_exists($sql_file_path)) {
            throw new Exception("SQL file not found at: " . $sql_file_path);
        }

        // Read SQL file
        $sql_file = file_get_contents($sql_file_path);
        if ($sql_file === false) {
            throw new Exception("Unable to read SQL file");
        }

        // Split SQL file into individual queries
        $queries = array_filter(array_map('trim', explode(';', $sql_file)));

        if (empty($queries)) {
            throw new Exception("No SQL queries found in file");
        }

        // Execute each query
        foreach ($queries as $query) {
            if (!empty($query)) {
                if (!$conn->query($query)) {
                    throw new Exception("Error executing query: " . $conn->error . "\nQuery: " . $query);
                }
            }
        }

        // Insert default admin user if not exists
        $default_admin = "INSERT INTO users (firstname, lastname, username, password, type) 
                         SELECT 'Administrator', 'Admin', 'admin', '" . md5('admin123') . "', 1 
                         WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin')";
        if (!$conn->query($default_admin)) {
            throw new Exception("Error creating admin user: " . $conn->error);
        }

        $conn->close();
        return ["success" => true, "message" => "Database initialized successfully"];

    } catch (Exception $e) {
        return ["success" => false, "message" => $e->getMessage()];
    }
}

// Create installation check file
function createInstallationFile()
{
    $install_file = __DIR__ . '/../.installed';
    if (!file_put_contents($install_file, date('Y-m-d H:i:s'))) {
        throw new Exception("Unable to create installation file");
    }
}

// Check if already installed
if (file_exists(__DIR__ . '/../.installed')) {
    die(json_encode(["success" => false, "message" => "System is already installed"]));
}

// Run installation
$result = initializeDatabase();
if ($result["success"]) {
    try {
        createInstallationFile();
    } catch (Exception $e) {
        $result = ["success" => false, "message" => $e->getMessage()];
    }
}

// Return result as JSON
header('Content-Type: application/json');
echo json_encode($result);
?>