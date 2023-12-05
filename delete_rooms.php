<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['rid'])) {
        $room_id = $_POST['rid'];
        $stmt = $db->prepare("DELETE FROM rooms WHERE rid = ?");
        $stmt->bind_param("i", $room_id); // Use $room_id here, not $departement_id
        if ($stmt->execute()) {
            $response = [
                'status' => 200,
                'message' => 'Room deleted successfully'
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Error deleting room'
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
