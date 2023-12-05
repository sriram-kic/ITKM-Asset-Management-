<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['did'])) {
        $departement_id = $_POST['did'];
        $stmt = $db->prepare("DELETE FROM departement WHERE did = ?");
        $stmt->bind_param("i", $departement_id);
        if ($stmt->execute()) {
            $response = [
                'status' => 200,
                'message' => 'Company deleted successfully'
            ];
        }
        else {
            $response = [
                'status' => 500,
                'message' => 'Error deleting departement'
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
