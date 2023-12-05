<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bid'])) {
$bid =$_POST['bid'];
        $stmt = $db->prepare("DELETE FROM blocks WHERE bid = ?");
        $stmt->bind_param("i", $bid);
        if ($stmt->execute()) {
            $response = [
                'status' => 200,
                'message' => 'Product deleted successfully'
            ];
        }
        else {
            $response = [
                'status' => 500,
                'message' => 'Error deleting product'
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
