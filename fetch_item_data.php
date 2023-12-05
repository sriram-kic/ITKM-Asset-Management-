<br>
<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['department'])) {
        $department = $_GET['department'];
        $output = '<div class="row">';

        if ($department === 'modelwise') {
            $query = "SELECT product, model, COUNT(*) as count FROM eitems WHERE deprt != 'no' GROUP BY product, model";
        } elseif ($department === 'overall') {
            $query = "SELECT product, COUNT(*) as count FROM eitems WHERE deprt != 'no' GROUP BY product";
        } else {
            $query = "SELECT product, model, COUNT(*) as count FROM eitems WHERE deprt = '$department' AND deprt != 'no' GROUP BY product, model";
        }

        $result = mysqli_query($db, $query);
        // Define an array of predefined subtle colors
        $boxColors = array(
            '#3498db', // Blue
            '#e74c3c', // Red
            '#2ecc71', // Green
            '#f39c12', // Yellow
            '#9b59b6', // Purple
            '#16a085', // Teal
            '#d35400', // Orange
            '#34495e', // Dark Blue
            '#7f8c8d'  // Gray
        );
        $colorIndex = 0; // Initialize the color index

        while ($row = mysqli_fetch_assoc($result)) {
            $productType = strtoupper($row['product']);
            $productDetails = isset($row['model']) ? $row['model'] : $productType; // Use product name if model is empty
            $count = $row['count'];
            $iconClass = '';
            // Use the next color from the array
            $boxColor = $boxColors[$colorIndex];
            switch ($productType) {
                case 'KEYBOARD':
                    $iconClass = 'mdi mdi-keyboard mdi-24px';
                    break;
                case 'AC':
                    $iconClass = 'mdi mdi-air-conditioner mdi-24px';
                    break;
                case 'LAN':
                    $iconClass = 'mdi mdi-lan mdi-24px';
                    break;
                case 'DESKTOP':
                    $iconClass = 'mdi mdi-desktop-mac mdi-24px';
                    break;
                case 'MOUSE':
                    $iconClass = 'mdi mdi-mouse mdi-24px';
                    break;
                case 'ROUTER':
                    $iconClass = 'mdi mdi-wifi mdi-24px';
                    break;
                case 'CPU':
                    $iconClass = 'mdi mdi-office mdi-24px';
                    break;
                case 'CAMERA':
                    $iconClass = 'mdi mdi-camera mdi-24px';
                    break;
                case 'FAN':
                    $iconClass = 'mdi mdi-fan mdi-24px';
                    break;
                case 'LIGHT':
                    $iconClass = 'mdi mdi-lightbulb mdi-24px';
                    break;
                case 'SWITCHBOARD':
                    $iconClass = 'mdi mdi-flash mdi-24px';
                    break;
                case 'WIRE':
                    $iconClass = 'mdi mdi-flash mdi-24px';
                    break;
                case 'MIC':
                    $iconClass = 'mdi mdi-microphone mdi-24px';
                    break;
                case 'ETHERNET':
                    $iconClass = 'mdi mdi-ethernet mdi-24px';
                    break;
                case 'TV':
                    $iconClass = 'mdi mdi-television mdi-24px'; // TV icon
                    break;
                case 'CHARGER':
                    $iconClass = 'mdi mdi-cellphone mdi-24px'; // Charger icon
                    break;
                case 'SWITCH':
                    $iconClass = 'mdi mdi-switch mdi-24px'; // Charger icon
                    break;
                default:
                    $iconClass = 'mdi mdi-help-circle-outline mdi-24px'; // Default icon (question mark icon)
                    break;
            }

            $output .= '<div class="col-md-6 col-lg-4 col-xlg-3">';
            $output .= '<div class="card card-hover " style="background-color: ' . $boxColor . ';">';
            $output .= '<div class="box text-center ">';
            $output .= '<i class="' . $iconClass . ' text-white"></i>';
            $output .= '<h1 class="font-light text-white">' . $productType . '</h1>';
            $output .= '<p class="font-light text-white">' . $productDetails . '</p>'; // Display product details
            $output .= '<h5 class="font-light text-white">Count: ' . $count . '</h5>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            // Increment the color index and loop back to the beginning if needed
            $colorIndex = ($colorIndex + 1) % count($boxColors);
        }

        $output .= '</div>'; // Close the row

        echo $output;
    }
}
?>