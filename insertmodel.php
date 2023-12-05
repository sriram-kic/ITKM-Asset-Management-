<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nmodel'])) {
        $nmodel = strtoupper($_POST['nmodel']); 
        $stmt = $db->prepare("INSERT INTO model (`nmodel`) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $nmodel);
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
