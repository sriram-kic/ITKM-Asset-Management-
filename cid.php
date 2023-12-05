<?php
include_once('config.php');

$response = array(); // Create a response array

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cid = $_POST['cid'];
    $ncompany = strtoupper($_POST['ncompany']);

    // Perform the database update here
    $sql = "UPDATE company SET ncompany = ? WHERE cid = ?";
    $stmt = $db->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("si", $ncompany, $cid);
        
        if ($stmt->execute()) {
            $response['status'] = "success"; // Update was successful
        } else {
            $response['status'] = "error";
            $response['message'] = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $response['status'] = "error";
        $response['message'] = "Error: " . $db->error;
    }
}

$db->close();

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
