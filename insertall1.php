<?php
include("config.php");
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "insert_all") {
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
    $nop = $_POST["nou"];
    $status = $_POST["status"];
    $product = $_POST["product"];
    $product_c = $_POST["product_c"];
    $model = $_POST["model"];
    $assetid = $_POST["assetid"];
    $cpu_s = $_POST["cpu_s"];
    $monitor_s = $_POST["monitor_s"];
    $keyb_s = $_POST["keyb_s"];
    $Mouse_s = $_POST["Mouse_s"];
    // $ = $_POST["os"];os
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
    $multi = $nop * $unit_cost;
    if ($created_date == '') {
        $created_date = date('Y-m-d');
    }
  // ...

if ($product_c == "COMPUTER") {
    $os = $_POST["os"];
    $sql = "INSERT INTO eitems (company, block, deprt, nlevel, nroom, pdate, nintent, po_n, invoice_no, waranty, unit_cost, status, product, product_c, assetid, model, cpu_s, monitor_s, keyb_s, Mouse_s, os, ram, storagetype, msoffice, ocssts, created_date) 
            VALUES ('$company', '$block', '$deprt', '$nlevel', '$nroom', '$pdate', '$nintent', '$po_n', '$invoice_no', '$waranty', '$multi', '$status', '$product', '$product_c', '$assetid', '$model', '$cpu_s', '$monitor_s', '$keyb_s', '$Mouse_s', '$os', '$ram', '$storagetype', '$msoffice', '$ocssts', '$created_date')";

    if ($db->query($sql) === TRUE) {
        $response = [
            "status" => 200,
            "message" => "Saved successfully.",
        ];
    } else {
        $response = [
            "status" => 201,
            "message" => "Error adding the record to the database: " . $db->error,
        ];
    }
} elseif ($product_c == "NETWORK") {
    $sql1 = "INSERT INTO eitems (company, block, deprt, nlevel, nroom, pdate, nintent, po_n, invoice_no, waranty, unit_cost, status, product, product_c, assetid, model, ip, mac, net_type) 
            VALUES ('$company', '$block', '$deprt', '$nlevel', '$nroom', '$pdate', '$nintent', '$po_n', '$invoice_no', '$waranty', '$unit_cost', '$status', '$product', '$product_c', '$assetid', '$model', '$ip', '$mac', '$net_type')";

    if ($db->query($sql1) === TRUE) {
        $response = [
            "status" => 200,
            "message" => "Saved successfully.",
        ];
    } else {
        $response = [
            "status" => 201,
            "message" => "Error adding the record to the database: " . $db->error,
        ];
    }
} elseif ($product_c == "CCTV") {
    $sql2 = "INSERT INTO eitems (company, block, deprt, nlevel, nroom, pdate, nintent, po_n, invoice_no, waranty, unit_cost, status, product, product_c, assetid, model, ip, mac, storage, nchannels) 
            VALUES ('$company', '$block', '$deprt', '$nlevel', '$nroom', '$pdate', '$nintent', '$po_n', '$invoice_no', '$waranty', '$unit_cost', '$status', '$product', '$product_c', '$assetid', '$model', '$ip', '$mac', '$Storage', '$nchannels')";

    if ($db->query($sql2) === TRUE) {
        $response = [
            "status" => 200,
            "message" => "Saved successfully.",
        ];
    } else {
        $response = [
            "status" => 201,
            "message" => "Error adding the record to the database: " . $db->error,
        ];
    }
} elseif ($product_c == "PRINTER") {
    $sql3 = "INSERT INTO eitems (company, block, deprt, nlevel, nroom, pdate, nintent, po_n, invoice_no, waranty, unit_cost, status, product, product_c, assetid, model, ip, mac, scann, storage) 
            VALUES ('$company', '$block', '$deprt', '$nlevel', '$nroom', '$pdate', '$nintent', '$po_n', '$invoice_no', '$waranty', '$unit_cost', '$status', '$product', '$product_c', '$assetid', '$model', '$ip', '$mac', '$scann', '$Storage')";

    if ($db->query($sql3) === TRUE) {
        $response = [
            "status" => 200,
            "message" => "Saved successfully.",
        ];
    } else {
        $response = [
            "status" => 201,
            "message" => "Error adding the record to the database: " . $db->error,
        ];
    }
} else {
    $response = [
        "status" => 201,
        "message" => "Invalid product_c value",
    ];
}

echo json_encode($response);
}
?>
