<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ndept'])) {
        $ndept = strtoupper($_POST['ndept']); 
        $stmt = $db->prepare("INSERT INTO departement (`ndept`) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $ndept); // Use $ndept here
            $respo = [];
            if ($stmt->execute()) {
                $respo = [
                    'status' => 200,
                    'message' => 'Data inserted successfully!'
                ];
            } 
            else {
                $respo = [
                    'status' => 201,
                    'message' => 'Data Not inserted successfully!'
                ];
            }
            $stmt->close();
        }
        echo json_encode($respo);
    }
}
?>
