<?php
/**
 * Database Connection Diagnostic Tool
 * This script will help diagnose issues with your MySQL connection
 */

// Define variables with default database settings
$db_host = '127.0.0.1';
$db_port = 3306;
$db_user = 'root';
$db_pass = '';
$db_name = 'techgear';

// Disable error output to browser (for security)
ini_set('display_errors', 0);

// Enable error logging
ini_set('log_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', 'php_error_log');

// Get database settings from configuration if available
$config_file = __DIR__ . '/src/includes/db_connection.php';
if (file_exists($config_file)) {
    // Extract configuration values without including the file
    $content = file_get_contents($config_file);
    
    if (preg_match("/define\s*\(\s*['\"](DB_HOST)['\"][\s]*,[\s]*['\"]([^'\"]*)['\"]/", $content, $matches)) {
        $db_host = $matches[2];
    }
    
    if (preg_match("/define\s*\(\s*['\"](DB_PORT)['\"][\s]*,[\s]*([0-9]*)\s*\)/", $content, $matches)) {
        $db_port = intval($matches[2]);
    }
    
    if (preg_match("/define\s*\(\s*['\"](DB_USERNAME)['\"][\s]*,[\s]*['\"]([^'\"]*)['\"]/", $content, $matches)) {
        $db_user = $matches[2];
    }
    
    if (preg_match("/define\s*\(\s*['\"](DB_PASSWORD)['\"][\s]*,[\s]*['\"]([^'\"]*)['\"]/", $content, $matches)) {
        $db_pass = $matches[2];
    }
    
    if (preg_match("/define\s*\(\s*['\"](DB_NAME)['\"][\s]*,[\s]*['\"]([^'\"]*)['\"]/", $content, $matches)) {
        $db_name = $matches[2];
    }
}

// Function to check if MySQL service is running
function isMySQLRunning($host, $port) {
    $connection = @fsockopen($host, $port);
    if (is_resource($connection)) {
        fclose($connection);
        return true;
    }
    return false;
}

// Function to test database connection
function testDatabaseConnection($host, $port, $user, $pass, $name = null) {
    $results = [
        'success' => false,
        'connection' => false,
        'database_exists' => false,
        'tables' => [],
        'error' => null
    ];
    
    // Test basic connection first (no database specified)
    $conn = @mysqli_connect($host, $user, $pass, '', $port);
    
    if (!$conn) {
        $results['error'] = 'Connection Error: ' . mysqli_connect_error();
        return $results;
    }
    
    $results['connection'] = true;
    
    // If no database name provided, we're just testing connection
    if ($name === null) {
        mysqli_close($conn);
        $results['success'] = true;
        return $results;
    }
    
    // Check if database exists
    $db_check = mysqli_query($conn, "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$name'");
    if ($db_check && mysqli_num_rows($db_check) > 0) {
        $results['database_exists'] = true;
        
        // Try to select the database
        if (mysqli_select_db($conn, $name)) {
            // Get list of tables
            $tables_query = mysqli_query($conn, "SHOW TABLES");
            if ($tables_query) {
                while ($table = mysqli_fetch_array($tables_query)[0]) {
                    $results['tables'][] = $table;
                }
                $results['success'] = true;
            } else {
                $results['error'] = 'Error listing tables: ' . mysqli_error($conn);
            }
        } else {
            $results['error'] = 'Error selecting database: ' . mysqli_error($conn);
        }
    } else {
        $results['error'] = "Database '$name' does not exist";
    }
    
    mysqli_close($conn);
    return $results;
}

// Check if MySQL service is running
$mysql_running = isMySQLRunning($db_host, $db_port);

// Test connection without specifying database
$connection_test = $mysql_running ? testDatabaseConnection($db_host, $db_port, $db_user, $db_pass) : ['success' => false, 'error' => 'MySQL service is not running'];

// Test full database connection if basic connection succeeded
$database_test = ($connection_test['connection']) ? testDatabaseConnection($db_host, $db_port, $db_user, $db_pass, $db_name) : ['success' => false, 'error' => 'Could not establish connection to MySQL server'];

// Check PHP version and extensions
$php_version = phpversion();
$mysqli_extension = extension_loaded('mysqli') ? 'Installed' : 'Not Installed';
$pdo_extension = extension_loaded('pdo_mysql') ? 'Installed' : 'Not Installed';

// Get server software information
$server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';

// Check for setup_database.sql
$sql_file_exists = file_exists(__DIR__ . '/setup_database.sql');

// Output results as HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechGear Database Diagnostic</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2 {
            color: #2c3e50;
        }
        .status {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background-color: #e2f3f8;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        code {
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 4px;
            font-family: Consolas, Monaco, 'Courier New', monospace;
        }
        .solution {
            margin-top: 10px;
            padding-left: 15px;
            border-left: 4px solid #ccc;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 10px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>TechGear Database Diagnostic</h1>
    
    <div class="status <?php echo $database_test['success'] ? 'success' : 'error'; ?>">
        <h2>Overall Status: <?php echo $database_test['success'] ? 'Connected Successfully' : 'Connection Failed'; ?></h2>
        <?php if (!$database_test['success']): ?>
            <p>There are issues with your database connection. See details below.</p>
        <?php else: ?>
            <p>Your database connection is working correctly.</p>
        <?php endif; ?>
    </div>
    
    <h2>Environment Information</h2>
    <table>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>PHP Version</td>
            <td><?php echo $php_version; ?></td>
        </tr>
        <tr>
            <td>MySQLi Extension</td>
            <td><?php echo $mysqli_extension; ?></td>
        </tr>
        <tr>
            <td>PDO MySQL Extension</td>
            <td><?php echo $pdo_extension; ?></td>
        </tr>
        <tr>
            <td>Web Server</td>
            <td><?php echo htmlspecialchars($server_software); ?></td>
        </tr>
        <tr>
            <td>setup_database.sql File</td>
            <td><?php echo $sql_file_exists ? 'Found' : 'Not Found'; ?></td>
        </tr>
    </table>
    
    <h2>MySQL Service</h2>
    <div class="status <?php echo $mysql_running ? 'success' : 'error'; ?>">
        <p><strong>Status:</strong> <?php echo $mysql_running ? 'Running' : 'Not Running or Not Accessible'; ?></p>
        
        <?php if (!$mysql_running): ?>
            <div class="solution">
                <h3>Solution:</h3>
                <ol>
                    <li>Open XAMPP Control Panel</li>
                    <li>Click "Start" next to MySQL</li>
                    <li>If it fails to start, check the MySQL logs in XAMPP</li>
                    <li>Restart your computer and try again</li>
                </ol>
            </div>
        <?php endif; ?>
    </div>
    
    <h2>MySQL Connection</h2>
    <div class="status <?php echo $connection_test['connection'] ? 'success' : 'error'; ?>">
        <p><strong>Status:</strong> <?php echo $connection_test['connection'] ? 'Connected Successfully' : 'Connection Failed'; ?></p>
        <p><strong>Host:</strong> <?php echo $db_host; ?></p>
        <p><strong>Port:</strong> <?php echo $db_port; ?></p>
        <p><strong>Username:</strong> <?php echo $db_user; ?></p>
        <p><strong>Password:</strong> <?php echo empty($db_pass) ? '(empty)' : '(set)'; ?></p>
        
        <?php if (!$connection_test['connection']): ?>
            <p><strong>Error:</strong> <?php echo htmlspecialchars($connection_test['error'] ?? 'Unknown error'); ?></p>
            
            <div class="solution">
                <h3>Solution:</h3>
                <ol>
                    <li>Verify MySQL is running in XAMPP Control Panel</li>
                    <li>Check if your username and password are correct</li>
                    <li>Try changing 'localhost' to '127.0.0.1' or vice versa in your configuration</li>
                    <li>Check if MySQL is running on a non-standard port</li>
                </ol>
            </div>
        <?php endif; ?>
    </div>
    
    <h2>Database Connection</h2>
    <div class="status <?php echo $database_test['success'] ? 'success' : ($database_test['connection'] ? 'warning' : 'error'); ?>">
        <p><strong>Status:</strong> 
            <?php 
            if ($database_test['success']) {
                echo 'Connected Successfully';
            } elseif ($database_test['connection']) {
                echo 'Connected to MySQL but Database Issue';
            } else {
                echo 'Connection Failed';
            }
            ?>
        </p>
        <p><strong>Database:</strong> <?php echo $db_name; ?></p>
        <p><strong>Database Exists:</strong> <?php echo $database_test['database_exists'] ? 'Yes' : 'No'; ?></p>
        
        <?php if ($database_test['success']): ?>
            <p><strong>Tables Found:</strong> <?php echo count($database_test['tables']); ?></p>
            <ul>
                <?php foreach ($database_test['tables'] as $table): ?>
                    <li><?php echo htmlspecialchars($table); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p><strong>Error:</strong> <?php echo htmlspecialchars($database_test['error'] ?? 'Unknown error'); ?></p>
            
            <div class="solution">
                <h3>Solution:</h3>
                <ol>
                    <?php if (!$database_test['database_exists']): ?>
                        <li>The database '<?php echo $db_name; ?>' does not exist. You need to create it by:</li>
                        <li>Opening phpMyAdmin: <a href="http://localhost/phpmyadmin/" target="_blank">http://localhost/phpmyadmin/</a></li>
                        <li>Clicking "Import" and selecting the setup_database.sql file</li>
                    <?php else: ?>
                        <li>Check your database user permissions</li>
                        <li>Verify that the database name is correct in your configuration</li>
                    <?php endif; ?>
                </ol>
            </div>
        <?php endif; ?>
    </div>
    
    <h2>Next Steps</h2>
    <?php if ($database_test['success']): ?>
        <div class="status success">
            <p>Your database connection is working correctly. If you're still experiencing issues with your website, check:</p>
            <ol>
                <li>PHP file permissions</li>
                <li>Apache configuration</li>
                <li>Application error logs</li>
            </ol>
        </div>
    <?php else: ?>
        <div class="status info">
            <p>Based on the diagnostics, here's what you should do:</p>
            <ol>
                <?php if (!$mysql_running): ?>
                    <li>Start the MySQL service in XAMPP Control Panel</li>
                <?php elseif (!$connection_test['connection']): ?>
                    <li>Check your MySQL username and password</li>
                    <li>Verify MySQL is running on the expected host and port</li>
                <?php elseif (!$database_test['database_exists']): ?>
                    <li>Import the setup_database.sql file using phpMyAdmin</li>
                <?php else: ?>
                    <li>Check your database user permissions</li>
                    <li>Verify all table structures are correct</li>
                <?php endif; ?>
                <li>After making changes, refresh this page to run the diagnostic again</li>
            </ol>
            
            <p>TechGear has a fallback mode that uses static data when the database is unavailable, so your website should still function with limited capabilities even without a working database connection.</p>
        </div>
    <?php endif; ?>
    
    <div class="footer">
        <p>TechGear Database Diagnostic Tool • Generated: <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
</body>
</html>
