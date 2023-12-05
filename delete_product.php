<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pid'])) {
        $pid = $_POST['pid'];

        // Prepare and execute a DELETE query to delete the product
        $stmt = $db->prepare("DELETE FROM products WHERE pid = ?");
        $stmt->bind_param("i", $pid);

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
