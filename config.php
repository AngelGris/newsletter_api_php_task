<?php
/**
 * MySQL configuration
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'vanhack');

// connect to the MySQL database
$db_link = mysqli_connect('localhost', 'root', 'root', 'vanhack');
mysqli_set_charset($db_link,'utf8');
?>