<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nid = $_POST['nid'];
    $name = strtoupper($_POST['name']);
    $add1 = strtoupper($_POST['add1']);
    $add2 = strtoupper($_POST['add2']);
    $tphone = strtoupper($_POST['tphone']); 
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $web = $_POST['web'];

    $sql = "UPDATE master SET name = ?, add1 = ?, add2 = ?, `t-phone` = ?, mobile = ?, email = ?, web = ? WHERE id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssssi", $name, $add1, $add2, $tphone, $mobile, $email, $web, $nid);
        if ($stmt->execute()) {
            echo json_encode(array("status" => 200, "message" => "Data updated successfully."));
        } else {
            echo json_encode(array("status" => 500, "message" => "Error updating data: " . $stmt->error));
        }
        $stmt->close();
    } else {
        echo json_encode(array("status" => 500, "message" => "Error updating data: " . $db->error));
    }
}

$db->close();
?>
