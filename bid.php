<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bid = $_POST['bid'];
    $nblock = strtoupper($_POST['nblock']);
    $sql = "UPDATE blocks SET nblock = ?  WHERE bid = ?";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $nblock, $bid);
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
