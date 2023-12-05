<?php
include("config.php");

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Assuming you have a unique identifier for the record, e.g., assetid
$id = $_POST['id']; // Make sure to sanitize user input

// Initialize an array to hold the SQL update values
$updateValues = array();

// Loop through the fields and check if they exist in $_POST
$allowedFields = [
    'company', 'block', 'deprt', 'nlevel', 'nroom', 'pdate', 'nintent', 'po_n', 'invoice_no', 'waranty',
    'unit_cost', 'status', 'product', 'product_c', 'model', 'cpu_s', 'monitor_s', 'keyb_s', 'Mouse_s',
    'os', 'ram', 'storagetype', 'msoffice', 'ocssts', 'created_date', 'ip', 'mac', 'nchannels', 'scann', 'Storage', 'net_type',
    'assetid'
];

foreach ($allowedFields as $field) {
    if (isset($_POST[$field])) {
        $value = $db->real_escape_string($_POST[$field]); // Sanitize input
        $updateValues[] = "$field = '$value'";
    }
}

if (!empty($updateValues)) {
    // Construct the SQL update query with the values that exist in $_POST
    $sql = "UPDATE eitems SET " . implode(', ', $updateValues) . " WHERE id = $id";

    // Execute the SQL query
    if ($db->query($sql) === TRUE) {
        // Update was successful
        echo json_encode(array("status" => 200, "message" => "Record updated successfully"));
    } else {
        // Update failed
        echo json_encode(array("status" => 201, "message" => "Failed to update record: " . $db->error));
    }
} else {
    // No values to update
    echo json_encode(array("status" => 201, "message" => "No values to update."));
}

// Close the database connection
$db->close();
