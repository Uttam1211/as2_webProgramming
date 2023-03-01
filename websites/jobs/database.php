<?php

define('DB_TYPE', 'mysql');

define('DB_HOSTNAME', 'mysql');

define('DB_NAME', 'josjobs');
define('DB_CHARSET', 'utf8');
define('DB_USERNAME', 'student');
define('DB_PASSWORD', 'student');
define('DB_DEV_MODE', true);

// Attempt to instantiate the PDO class and store it under the variable $pdo
try {
    $pdo = new PDO(
        DB_TYPE . ':host=' .
            DB_HOSTNAME . ';dbname=' .
            DB_NAME . ';charset=' .
            DB_CHARSET,
        DB_USERNAME,
        DB_PASSWORD
    );
    if (DB_DEV_MODE) {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    exit(
        'An error occurred attempting to create a database connection.' .
        'Exiting...\n' . $e->getMessage()
    );
}
