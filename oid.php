<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oid = $_POST['oid']; 
    $os = strtoupper($_POST['ostype']);
    $sql = "UPDATE os SET ostype = ?  WHERE oid = ?";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $os, $oid);
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
