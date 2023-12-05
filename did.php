<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $did = $_POST['did']; 
    $ndept = strtoupper($_POST['ndept']); 
    $sql = "UPDATE departement SET ndept = ?  WHERE did = ?";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $ndept, $did);
        if ($stmt->execute()) {
            $response = array('status' => 'success');
            echo json_encode($response);
        } else {
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
