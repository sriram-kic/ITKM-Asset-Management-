<?php
include("config.php");

$selectedCompany = isset($_POST['company']) ? $_POST['company'] : "";
$selectedDepartment = isset($_POST['depart']) ? $_POST['depart'] : "";
$selectedProductc = isset($_POST['product_c']) ? $_POST['product_c'] : "";
$selectedProduct = isset($_POST['product']) ? $_POST['product'] : "";
$selectedBlock = isset($_POST['block']) ? $_POST['block'] : "";

$conditions = [];
$parameters = [];

if (!empty($selectedCompany)) {
    $conditions[] = "company = ?";
    $parameters[] = $selectedCompany;
}

if (!empty($selectedDepartment)) {
    $conditions[] = "deprt = ?";
    $parameters[] = $selectedDepartment;
}

if (!empty($selectedProductc)) {
    $conditions[] = "product_c = ?";
    $parameters[] = $selectedProductc;
}

if (!empty($selectedProduct)) {
    $conditions[] = "product = ?";
    $parameters[] = $selectedProduct;
}

if (!empty($selectedBlock)) {
    $conditions[] = "block = ?";
    $parameters[] = $selectedBlock;
}

$whereClause = !empty($conditions) ? "WHERE " . implode(" OR ", $conditions) : "";

$sql = "SELECT * FROM eitems $whereClause";
$stmt = mysqli_prepare($db, $sql);

if ($stmt) {
    if (!empty($parameters)) {
        $types = str_repeat('s', count($parameters));
        mysqli_stmt_bind_param($stmt, $types, ...$parameters);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);    if ($result) {
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($rows);
    } else {
        echo "Error fetching results: " . mysqli_error($db);
    }
 mysqli_stmt_close($stmt);
} else {
    echo "Error in the prepared statement: " . mysqli_error($db);
}

mysqli_close($db);65
?>
