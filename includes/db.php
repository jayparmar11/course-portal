<?php
// Database Connection
$host = 'localhost';
$dbname = 'course_portal';
$username = 'root';
$password = '';

$db = new mysqli($host, $username, $password, $dbname);

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}
