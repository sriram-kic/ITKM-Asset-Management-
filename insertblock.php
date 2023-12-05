<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nblock'])) {
        $nblock = strtoupper($_POST['nblock']); 
        $stmt = $db->prepare("INSERT INTO blocks (`nblock`) VALUES (UPPER(?))");
        if ($stmt) {
            $stmt->bind_param("s", $nblock);
            $respo = [];
            if ($stmt->execute()) {
                $respo = [
                    'status' => 200,
                    'message' => 'Data inserted successfully!'
                ];
            } else {
                $respo = [
                    'status' => 201,
                    'message' => 'Data Not inserted successfully!'
                ];
            }
        }
        $stmt->close();
    }
    echo json_encode($respo);
}
