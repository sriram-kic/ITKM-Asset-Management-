<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $pro = isset($_POST['product']) ? $_POST['product'] : null;
        $dep = isset($_POST['department']) ? $_POST['department'] : null;

        if (!$pro) {
            // throw new Exception("Please select the Product!");
            echo '<div class="alert alert-danger" role="alert">Please select the Product!</div>';
            exit();
        }

        if (!$dep) {
            // throw new Exception("Please select the Department!");
            echo '<div class="alert alert-danger" role="alert">Please select the Company!</div>';
            exit();
        }

        $sql = "SELECT * FROM eitems WHERE company='$dep' AND product_c='$pro'";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<table id="resultTable" class="table dataTable">';
            echo '<thead class="thead-dark">';
            echo '<tr>';
            echo '<th>Product_cateogary</th>';
             echo '<th>Departement</th>';
        echo '<th>Company</th>';
        echo '<th>Block</th>';
        echo '<th>Level</th>';
        echo '<th>Room</th>';
        echo '<th>Purchase_date</th>';
        echo '<th>Intent</th>';
        echo '<th>PO_No</th>';
        echo '<th>Invoice_No</th>';
        echo '<th>Waranty</th>';
        echo '<th>Unit_cost</th>';
        echo '<th>Status</th>';
        echo '<th>Product</th>';
        echo '<th>Model</th>';
        echo '<th>Asset_id</th>';
        echo '<th>CPU_Serial</th>';
        echo '<th>Monitor_Serial</th>';
        echo '<th>Keyboard_Serial</th>';
        echo '<th>Mouse_Serial</th>';
        echo '<th>OS</th>';
        echo '<th>RAM</th>';
        echo '<th>Storage_type</th>';
        echo '<th>MS_Office</th>';
        echo '<th>OCS_Status</th>';
        echo '<th>Created_date</th>';
        echo '<th>IP</th>';
        echo '<th>MAC</th>';
        echo '<th>No_of_Channels</th>';
        echo '<th>Scanner</th>';
        echo '<th>Storage</th>';
        echo '<th>Network_type</th>'; 
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                            echo '<td>' . $row['product_c'] . '</td>';
            echo '<td>' . $row['deprt'] . '</td>';
            echo '<td>' . $row['company'] . '</td>';
            echo '<td>' . $row['block'] . '</td>';
            echo '<td>' . $row['nlevel'] . '</td>';
            echo '<td>' . $row['nroom'] . '</td>';
            echo '<td>' . $row['pdate'] . '</td>';
            echo '<td>' . $row['nintent'] . '</td>';
            echo '<td>' . $row['po_n'] . '</td>';
            echo '<td>' . $row['invoice_no'] . '</td>';
            echo '<td>' . $row['waranty'] . '</td>';
            echo '<td>' . $row['unit_cost'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            echo '<td>' . $row['product'] . '</td>';
            echo '<td>' . $row['model'] . '</td>';
            echo '<td>' . $row['assetid'] . '</td>';
            echo '<td>' . $row['cpu_s'] . '</td>';
            echo '<td>' . $row['monitor_s'] . '</td>';
            echo '<td>' . $row['keyb_s'] . '</td>';
            echo '<td>' . $row['Mouse_s'] . '</td>'; 
            echo '<td>' . $row['os'] . '</td>';
            echo '<td>' . $row['ram'] . '</td>';
            echo '<td>' . $row['storagetype'] . '</td>';
            echo '<td>' . $row['msoffice'] . '</td>';
            echo '<td>' . $row['ocssts'] . '</td>';
            echo '<td>' . $row['created_date'] . '</td>';
            echo '<td>' . $row['ip'] . '</td>';
            echo '<td>' . $row['mac'] . '</td>';
            echo '<td>' . $row['nchannels'] . '</td>';
            echo '<td>' . $row['scann'] . '</td>';
            echo '<td>' . $row['Storage'] . '</td>';
            echo '<td>' . $row['net_type'] . '</td>'; 
      echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo "No results found.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
