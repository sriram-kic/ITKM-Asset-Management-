
<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cid'])) {
        $company_id = $_POST['cid'];

        // Prepare and execute a DELETE query to delete the company
        $stmt = $db->prepare("DELETE FROM company WHERE cid = ?");
        $stmt->bind_param("i", $company_id);

        if ($stmt->execute()) {
            $response = [
                'status' => 200,
                'message' => 'Company deleted successfully'
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Error deleting company'
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
