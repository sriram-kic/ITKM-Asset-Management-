<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rid = $_POST['rid'];
    $nroom =strtoupper($_POST['nroom']); 
    // Perform the database update here
    $sql = "UPDATE rooms SET nroom = ? WHERE rid = ?";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $nroom, $rid);
        if ($stmt->execute()) {
            $response = array('status' => 'success');
            echo json_encode($response);
        } else {
            // echo "Error: " . $stmt->error;
            $response = array('status' => 'error', 'message' => $stmt->error);
            echo json_encode($response);
        }
        $stmt->close();
    } else {
        $response = array('status' => 'error', 'message' => $db->error);
        echo json_encode($response);
    }
}

$db->close();
?>
