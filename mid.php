<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mid = $_POST['mid']; 
    $nmodel = strtoupper($_POST['nmodel']);
    $sql = "UPDATE model SET nmodel = ?  WHERE mid = ?";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $nmodel, $mid);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $db->error;
    }
}

$db->close();
?>
