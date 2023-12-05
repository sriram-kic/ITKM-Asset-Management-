<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nroom'])) {
        $nroom = strtoupper($_POST['nroom']);
        $stmt = $db->prepare("INSERT INTO rooms (`nroom`) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $nroom); // Use $nroom here
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
            $stmt->close();
        }
        echo json_encode($respo);
    }
}
?>
