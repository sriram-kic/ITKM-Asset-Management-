<?php
require_once 'config.php';

if (isset($_GET['min']) && $_GET['min'] === 'true') {
    $sql = "SELECT id, pdate, status, product FROM eitems";

    $result = mysqli_query($db, $sql);
    $res = array();
    $res = mysqli_fetch_all($result);
    $arrays = array();
    $arrays['recordsTotal'] = count($res);
    $arrays['data'] = $res;

    // Add sno to each row
    foreach ($arrays['data'] as $key => $row) {
        array_unshift($arrays['data'][$key], strval($key + 1));
        $arrays['data'][$key][] = "
        <button onclick=\"delete_order('" . $arrays['data'][$key][1] . "')\" type=\"button\" class=\"btn btn-danger mr-4\">X</button><a href='editasset.php?id=" . $arrays['data'][$key][1] . "' class=\"btn btn-primary\"><i class=' mdi mdi-lead-pencil'></i></a>
        ";
    }

    header('Content-Type: application/json'); // Set the response content type to JSON
    echo json_encode($arrays);
} else if (isset($_GET['max']) && $_GET['max'] === 'true') {
    $sql = "SELECT * FROM eitems";

    $result = mysqli_query($db, $sql);
    $res = array();
    $res = mysqli_fetch_all($result);
    $arrays = array();
    $arrays['recordsTotal'] = count($res);
    $arrays['data'] = $res;

    // Add sno to each row
    foreach ($arrays['data'] as $key => $row) {
        array_unshift($arrays['data'][$key], strval($key + 1));
        $arrays['data'][$key][] = "
        <button onclick=\"delete_order('" . $arrays['data'][$key][1] . "')\" type=\"button\" class=\"btn btn-danger mr-4\">X</button><a href='editasset.php?id=" . $arrays['data'][$key][1] . "' class=\"btn btn-primary\"><i class=' mdi mdi-lead-pencil'></i></a>
        ";
    }

    header('Content-Type: application/json'); // Set the response content type to JSON
    echo json_encode($arrays);
} else if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $sql = "SELECT * FROM eitems WHERE assetid LIKE '%$searchQuery%'";

    $result = mysqli_query($db, $sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    header('Content-Type: application/json'); // Set the response content type to JSON
    echo json_encode($data);
} else {
    // Handle the case when min=true is not present in the URL
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => 'parameter missing'));
}
