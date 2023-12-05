<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) && isset($_POST['add1'])) {
        $name = strtoupper($_POST['name']);
        $add1 = strtoupper($_POST['add1']); 
        $add2 = strtoupper($_POST['add2']);
        $tPhone = $_POST["t-phone"];
        $mobile = $_POST["mobile"];
        $email = $_POST["email"];
        $web = $_POST["web"];
        $supcode = $_POST["supp_code"];
        $gst_in = $_POST["gstin"];


        

        // Prepare the SQL statement using a prepared statement
        $stmt = $db->prepare("INSERT INTO master (`name`, `add1`, `add2`, `t-phone`, `mobile`, `email`, `web`, `supp_code`, `gstin`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Check if the statement preparation was successful
        if ($stmt) {
            $stmt->bind_param("sssssssss", $name, $add1, $add2, $tPhone, $mobile, $email, $web, $supcode, $gst_in);

            $res = []; 
            if ($stmt->execute()) {
                $res = [
                    'status' => 200,
                    'message' => 'Data inserted successfully!'
                ];
            } else {
                $res = [
                    'status' => 201,
                    'message' => 'Data Not inserted successfully!'
                ];
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            // Error in preparing the statement
            $res = [
                'status' => 500,
                'message' => 'Error in preparing the SQL statement: ' . $db->error
            ];
        }

        // Close the database connection
        $db->close();

        echo json_encode($res);
    } elseif (isset($_POST['ncompany'])) {
        $company = strtoupper($_POST['ncompany']);
        $stmnt = $db->prepare("INSERT INTO company (`ncompany`) VALUES (?)");
        if ($stmnt) {
            $stmnt->bind_param("s", $company);

            if ($stmnt->execute()) {
                $resp = ['status' => 200, 'message' => 'Company inserted successfully'];
            } else {
                $resp = ['status' => 201, 'message' => 'Error inserting company'];
            }

            $stmnt->close(); // Close the prepared statement
        } else {
            $resp = [
                'status' => 500,
                'message' => 'Error in preparing the SQL statement: ' . $db->error
            ];
        }

        $db->close();
        echo json_encode($resp);
    }
    
    elseif (isset($_POST['nmodel'])) {
        $model = strtoupper($_POST['nmodel']); 
        $stmnt = $db->prepare("INSERT INTO model (`nmodel`) VALUES (?)");
        if ($stmnt) {
            $stmnt->bind_param("s", $model);

            if ($stmnt->execute()) {
                $resp = ['status' => 200, 'message' => 'model inserted successfully'];
            } else {
                $resp = ['status' => 201, 'message' => 'Error inserting model'];
            }

            $stmnt->close(); // Close the prepared statement
        } else {
            $resp = [
                'status' => 500,
                'message' => 'Error in preparing the SQL statement: ' . $db->error
            ];
        }

        $db->close();
        echo json_encode($resp);
    }
    
    elseif (isset($_POST['producttype']) && isset($_POST['productname'])) {
        $prod = strtoupper($_POST['producttype']);
        $prodn = strtoupper($_POST['productname']);
        $s = $db->prepare("INSERT INTO products(`producttype`,`productname`) VALUES(?,?)");
        if ($s) {
            $s->bind_param("ss", $prod, $prodn);
            if ($s->execute()) {
                $resp = ['status' => 200, 'message' => 'product inserted successfully'];
            } else {
                $resp = ['status' => 201, 'message' => 'product NOt inserted'];
            }
            echo json_encode($resp);
        }
    }
}
?>
