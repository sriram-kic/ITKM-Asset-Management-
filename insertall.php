<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "insert_all") {
    // Define your values for insertion
    $company = $_POST["company"];
    $block = $_POST["block"];
    $deprt = $_POST["deprt"];
    $nlevel = $_POST["nlevel"];
    $nroom = $_POST["nroom"];
    $pdate = $_POST["pdate"];
    $nintent = $_POST["nintent"];
    $po_n = $_POST["po_n"];
    $invoice_no = $_POST["invoice_no"];
    $waranty = $_POST["waranty"];
    $unit_cost = $_POST["unit_cost"];
    $nop=$_POST["nou"];
    $status = $_POST["status"];
    $product = $_POST["product"];
    $product_c = $_POST["product_c"];
    $model = $_POST["model"];
    $assetid = $_POST["assetid"];
    $cpu_s = $_POST["cpu_s"];
    $monitor_s = $_POST["monitor_s"];
    $keyb_s = $_POST["keyb_s"];
    $Mouse_s = $_POST["Mouse_s"];
    $os = $_POST["os"];
    $ram = $_POST["ram"];
    $storagetype = $_POST["storagetype"];
    $msoffice = $_POST["msoffice"];
    $ocssts = $_POST["ocssts"];
    $created_date = $_POST["created_date"];
    $ip = $_POST["ip"];
    $mac = $_POST["mac"];
    $nchannels = $_POST["nchannels"];
    $scann = $_POST["scann"];
    $Storage = $_POST["Storage"];
    $net_type = $_POST["net_type"];
     $multi=$nop*$unit_cost;
    if ($created_date == '') {
        $created_date = date('Y-m-d');
    }
    // Check if nintent already exists in the eitems table
    $checkQuery = "SELECT 1 FROM eitems WHERE nintent = '$nintent'";
    $result = $db->query($checkQuery);

    if ($result === false) {
        // Handle database error
        $response = [
            "status" => 500,
            "message" => "Database error: " . $conn->error,
        ];
    } elseif ($result->num_rows > 0) {
        // If nintent already exists, generate a separate response
        $response = [
            "status" => 201,
            "message" => "nintent already exists.",
        ];
    } else {
        // Define the SQL INSERT query
        $sql = "INSERT INTO eitems (company, block, deprt, nlevel, nroom, pdate, nintent, po_n, invoice_no, waranty, unit_cost, status, product, product_c, model, assetid, cpu_s, monitor_s, keyb_s, Mouse_s, os, ram, storagetype, msoffice, ocssts, created_date, ip, mac, nchannels, scann, Storage, net_type)
        VALUES ('$company', '$block', '$deprt', '$nlevel', '$nroom', '$pdate', '$nintent', '$po_n', '$invoice_no', '$waranty', '$multi', '$status', '$product', '$product_c', '$model', '$assetid', '$cpu_s', '$monitor_s', '$keyb_s', '$Mouse_s', '$os', '$ram', '$storagetype', '$msoffice', '$ocssts', '$created_date', '$ip', '$mac', '$nchannels', '$scann', '$Storage', '$net_type')";

        // Execute the SQL INSERT query
        if ($db->query($sql) === TRUE) {
            $response = [
                "status" => 200,
                "message" => "Saved successfully.",
            ];
        } else {
            $response = [
                "status" => 201,
                "message" => "Error adding the  to the database: " . $conn->error,
            ];
        }
    }

    // Send the response as JSON
    echo json_encode($response);
}
