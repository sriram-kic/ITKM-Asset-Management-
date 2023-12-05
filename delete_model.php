<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mid'])) {
$mid =$_POST['mid'];
        $stmt = $db->prepare("DELETE FROM model WHERE mid = ?");
        $stmt->bind_param("i", $mid);
        if ($stmt->execute()) {
            $response = [
                'status' => 200,
                'message' => 'Model deleted successfully'
            ];
        }
        else {
            $response = [
                'status' => 500,
                'message' => 'Error deleting model'
            ];
        }
        echo json_encode($response);
        $stmt->close();
    }
    else {
        $response = [
            'status' => 405,
            'message' => 'Method Not Allowed'
        ];
        echo json_encode($response);
    }
}
