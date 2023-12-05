<?php
include("config.php");
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "addCompanyForm") {



    $newCompany = strtoupper($_POST['ncompany']); 

    $sql = "INSERT INTO company (ncompany) VALUES (?)";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $newCompany);
        if ($stmt->execute()) {
            // Successfully inserted the new company
            $response = [
                "status" => 200,
                "message" => "Company added successfully.",
            ];
        } else {
            // Error inserting the new company
            $response = [
                "status" => 201,
                "message" => "Error adding the company to the database.",
            ];
        }

        $stmt->close();
    }


    $db->close();
    echo json_encode($response);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "addblockForm") {
    $newblock = $_POST['nblock'];
    $sql1 = "INSERT INTO blocks(nblock) values(?)";
    $stmts = $db->prepare($sql1);
    if ($stmts) {
        $stmts->bind_param("s", $newblock);
        if ($stmts->execute()) {

            $response = [
                "status" => 200,
                "message" => "Block added successfully.",
            ];
        } else {
            // Error inserting the new company
            $response = [
                "status" => 201,
                "message" => "Error adding the Block to the database.",
            ];
        }
    }
    $stmts->close();
    $db->close();
    echo json_encode($response);
}
///add block
elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "adddprtform") {
    $newdeprt = $_POST['ndept'];
    $sql1 = "INSERT INTO departement(ndept) values(?)";
    $stmtss = $db->prepare($sql1);
    if ($stmtss) {
        $stmtss->bind_param("s", $newdeprt);
        if ($stmtss->execute()) {

            $response = [
                "status" => 200,
                "message" => "Block added successfully.",
            ];
        } else {
            // Error inserting the new company
            $response = [
                "status" => 201,
                "message" => "Error adding the Block to the database.",
            ];
        }
    }
    $stmtss->close();
    $db->close();
    echo json_encode($response);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "addroom") {
    $newroom = $_POST['nroom'];
    $sql2 = "INSERT INTO rooms(nroom) values(?)";
    $stmtsss = $db->prepare($sql2);
    if ($stmtsss) {
        $stmtsss->bind_param("s", $newroom);
        if ($stmtsss->execute()) {

            $response = [
                "status" => 200,
                "message" => "room added successfully.",
            ];
        } else {
            // Error inserting the new company
            $response = [
                "status" => 201,
                "message" => "Error adding the room to the database.",
            ];
        }
    }
    $stmtsss->close();
    $db->close();
    echo json_encode($response);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "addmodel") {
    $model = $_POST['nmodel'];
    $sql2 = "INSERT INTO model(nmodel) values(?)";
    $stmtsss = $db->prepare($sql2);
    if ($stmtsss) {
        $stmtsss->bind_param("s", $model);
        if ($stmtsss->execute()) {

            $response = [
                "status" => 200,
                "message" => "model added successfully.",
            ];
        } else {
            // Error inserting the new company
            $response = [
                "status" => 201,
                "message" => "Error adding the model to the database.",
            ];
        }
    }
    $stmtsss->close();
    $db->close();
    echo json_encode($response);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "addmodel1") {
    $model = $_POST['nmodel'];
    $sql2 = "INSERT INTO model(nmodel) values(?)";
    $stmtsss = $db->prepare($sql2);
    if ($stmtsss) {
        $stmtsss->bind_param("s", $model);
        if ($stmtsss->execute()) {

            $response = [
                "status" => 200,
                "message" => "model1 added successfully.",
            ];
        } else {
            // Error inserting the new company
            $response = [
                "status" => 201,
                "message" => "Error adding the model1 to the database.",
            ];
        }
    }
    $stmtsss->close();
    $db->close();
    echo json_encode($response);
}
 elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "addmodel2") {
    $model = $_POST['nmodel'];
    $sql2 = "INSERT INTO model(nmodel) values(?)";
    $stmtsss = $db->prepare($sql2);
    if ($stmtsss) {
        $stmtsss->bind_param("s", $model);
        if ($stmtsss->execute()) {

            $response = [
                "status" => 200,
                "message" => "room model2 successfully.",
            ];
        } else {
            // Error inserting the new company
            $response = [
                "status" => 201,
                "message" => "Error adding the model2 to the database.",
            ];
        }
    }
    $stmtsss->close();
    $db->close();
    echo json_encode($response);
}

elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "addmodel3") {
    $model = $_POST['nmodel'];
    $sql2 = "INSERT INTO model(nmodel) values(?)";
    $stmtsss = $db->prepare($sql2);
    if ($stmtsss) {
        $stmtsss->bind_param("s", $model);
        if ($stmtsss->execute()) {

            $response = [
                "status" => 200,
                "message" => "room model3 successfully.",
            ];
        } else {
            // Error inserting the new company
            $response = [
                "status" => 201,
                "message" => "Error adding the model3 to the database.",
            ];
        }
    }
    $stmtsss->close();
    $db->close();
    echo json_encode($response);
}
elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "productform") {
    $newproduct = $_POST['productname'];
    $productype = $_POST['producttype'];
    $sql3 = "INSERT INTO products(producttype,productname) values(?,?)";
    $stmtssss = $db->prepare($sql3);
    if ($stmtssss) {
        $stmtssss->bind_param("ss", $productype, $newproduct);
        if ($stmtssss->execute()) {

            $response = [
                "status" => 200,
                "message" => "product added successfully.",
            ];
        } else {
            // Error inserting the new company
            $response = [
                "status" => 201,
                "message" => "Error adding the product to the database.",
            ];
        }
    }
    $stmtssss->close();
    $db->close();
    echo json_encode($response);
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(["status" => 400, "message" => "Bad Request"]);
}
