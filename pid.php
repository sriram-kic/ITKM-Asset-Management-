<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = $_POST['pid'];
    $productname = strtoupper($_POST['productname']);  

    // Perform the database update here
    $sql = "UPDATE products SET productname = ? WHERE pid = ?";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $productname, $pid);
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
