<?php
// fetch items from the database and then display them
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itkm";

// Create a connection to the database
$db = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
