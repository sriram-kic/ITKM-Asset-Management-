<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['oid'])) {
        $oid = $_POST['oid'];

        // Prepare and execute a DELETE query to delete the product
        $stmt = $db->prepare("DELETE FROM os WHERE oid = ?");
        $stmt->bind_param("i", $oid);

        if ($stmt->execute()) {
            $response = [
                'status' => 200,
                'message' => 'Product deleted successfully'
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Error deleting product'
            ];
        }

        $stmt->close();
    } else {
        $response = [
            'status' => 400,
            'message' => 'Invalid POST data'
        ];
    }

    echo json_encode($response);
} else {
    $response = [
        'status' => 405,
        'message' => 'Method Not Allowed'
    ];
    echo json_encode($response);
}
