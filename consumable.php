<?php
include("config.php");

// Check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs to prevent SQL injection
    $product_category = mysqli_real_escape_string($db, $_POST['product_category']);
    $product = mysqli_real_escape_string($db, $_POST['product']);
    $supp = mysqli_real_escape_string($db, $_POST['suppliername']);
    $date_of_purchase = mysqli_real_escape_string($db, $_POST['date_of_purchase']);
    $intent_no = mysqli_real_escape_string($db, $_POST['intent_no']);
    $po_no = mysqli_real_escape_string($db, $_POST['po_no']);
    $inv_no = mysqli_real_escape_string($db, $_POST['inv_no']);
    $inv_date = mysqli_real_escape_string($db, $_POST['invdate']);
    $nou =  $_POST['no_of_products'];
    $warranty = mysqli_real_escape_string($db, $_POST['warranty']);
    $unit_cost = $_POST['unit_cost'];
    // Handle file upload separately; don't store in the database
    $up_image = $_FILES['image']['name'];
    $status = mysqli_real_escape_string($db, $_POST['status']);
    $multi = $nou * $unit_cost;
    // SQL query to insert data into the table
    $sql = "INSERT INTO cons (product_category, product, suppliername, date_of_purchase, intent_no, po_no, inv_no, invdate, warranty, unit_cost, image, status) 
            VALUES ('$product_category', '$product', '$supp', '$date_of_purchase', '$intent_no', '$po_no', '$inv_no', '$inv_date', '$warranty', '$multi', '$up_image','$status')";

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    if ($db->query($sql) === TRUE) {
        // Handle file upload
        $target_dir = "uploads/"; // Specify the target directory for file uploads
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        echo "Data inserted successfully";
    } else {
        echo "Error inserting data: " . $db->error;
    }

    $db->close();
}


// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Decode the JSON data received from the request
    $data = json_decode(file_get_contents('php://input'), true);

    // Assuming $db is your database connection

    $sql = "DELETE FROM cons WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $data["id"]);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Product deleted successfully.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to delete product.'];
    }

    $stmt->close();

    echo json_encode($response);
    exit();
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png" />
    <title>ITKM</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="assets/extra-libs/multicheck/multicheck.css" />
    <!-- DataTables CSS -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

    <link href="dist/css/style.min.css" rel="stylesheet" />
    <!-- Alertify-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon -->
                        <b class="logo-icon p-l-10">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="assets/images/download.jpeg" alt="homepage" height="40px" width="40px" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text">
                            <!-- dark Logo text -->
                            <img src="assets/images/mkce.png" alt="homepage" height="50px" width="100%" class="light-logo" />
                        </span>
                        <!-- Logo icon -->
                        <!-- <b class="logo-icon"> -->
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->
                        <!-- <img src="assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->

                        <!-- </b> -->
                        <!--End Logo icon -->
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a>
                        </li>
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item search-box">
                            <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search position-absolute">
                                <input type="text" class="form-control" placeholder="Search &amp; enter" />
                                <a class="srh-btn"><i class="ti-close"></i></a>
                            </form>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-bell font-24"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="font-24 mdi mdi-comment-processing"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">
                                <ul class="list-style-none">
                                    <li>
                                        <div class="">
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-success btn-circle"><i class="ti-calendar"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Event today</h5>
                                                        <span class="mail-desc">Just a reminder that event</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-info btn-circle"><i class="ti-settings"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Settings</h5>
                                                        <span class="mail-desc">You can customize this template</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-primary btn-circle"><i class="ti-user"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Pavan kumar</h5>
                                                        <span class="mail-desc">Just see the my admin!</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-danger btn-circle"><i class="fa fa-link"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Luanch Admin</h5>
                                                        <span class="mail-desc">Just see the my new admin!</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31" /></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i>
                                    My Profile</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i>
                                    My Balance</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-email m-r-5 m-l-5"></i>
                                    Inbox</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-settings m-r-5 m-l-5"></i> Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                                <div class="dropdown-divider"></div>
                                <div class="p-l-30 p-10">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-success ">View
                                        Profile</a>
                                </div>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <!-- <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="p-t-30">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="Dashboard.php" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="views.php" aria-expanded="false"><i class="fas fa-laptop"></i><span class="hide-menu">View &
                                    Edit</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link d-flex justify-content-between" href="requestslab.php" aria-expanded="false">
                                <span>

                                    <i class="mdi mdi-comment-question-outline"></i><span class="hide-menu">Requests</span>
                                </span>

                                <?php


                                /*$badgecountquery = "SELECT  * from requests where lab = $lab_id";
                                $sqli = mysqli_query($db, $badgecountquery);


                                $badgecount = 0; // Initialize the badgecount variable

                                // Loop through the result set
                                while ($row = mysqli_fetch_assoc($sqli)) {
                                    // Assuming 'status' is one of the columns in your result set
                                    $status = $row['status'];

                                    // Check if the status is not "COMPLETED"
                                    if ($status != "COMPLETED") {
                                        ++$badgecount;
                                    }
                                }
                                // $badgecount = mysqli_num_rows($sqli);


                                if ($badgecount > 0) {

                                    echo "<span class='badge badge-pill badge-danger'>" . $badgecount . "</span>";
                                }*/

                                ?>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="consumable.php" aria-expanded="false"><i class="fas fa-tint"></i><span class="hide-menu">Consumable</span></a>
                        </li>
                    </ul>
                </nav> -->
                <nav class="sidebar-nav">
                    <?php require("navlinks.php") ?>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h2 class="page-title">Consumables</h2>
                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Consumable
                                    </li>
                                </ol>
                            </nav>

                        </div>
                    </div>
                </div>
            </div>


            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- Container fluid -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body ">
                                <p class="d-flex justify-content-between">
                                    <a class="btn  btn-primary" data-toggle="collapse" href="#collapseExample" role="button" onclick="openCollapse()" aria-expanded="false" aria-controls="collapseExample">
                                        Create new
                                    </a>
                                </p>

                                <div class="collapse" id="collapseExample">
                                    <div class="card card-body">
                                        <form id="insert_master">


                                            <div class="form-group row">
                                                <label for="product_category" class="col-sm-2 col-form-label">Product
                                                    Category</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control " id="product_category" name="product_category">
                                                        <option value="" disabled selected>Select product category
                                                        </option>
                                                        <?php
                                                        include("config.php");
                                                        $sql = "SELECT producttype FROM products";
                                                        $result = $db->query($sql);

                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo '<option value="' . $row['producttype'] . '">' . $row['producttype'] . '</option>';
                                                            }
                                                        } else {
                                                            echo "0 results";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product" class="col-sm-2 col-form-label">Product</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control " id="product" name="product">
                                                        <option value="" disabled selected>Select product</option>
                                                        <?php
                                                        include("config.php");
                                                        $sql = "SELECT productname FROM products";
                                                        $result = $db->query($sql);

                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo '<option value="' . $row['productname'] . '">' . $row['productname'] . '</option>';
                                                            }
                                                        } else {
                                                            echo "0 results";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="supplier" class="col-sm-2 col-form-label">Supplier</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control " id="supplier" name="suppliername">
                                                        <option value="" disabled selected>Select product</option>
                                                        <?php
                                                        include("config.php");
                                                        $sql = "SELECT name FROM master";
                                                        $result = $db->query($sql);

                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                                            }
                                                        } else {
                                                            echo "0 results";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="date_of_purchase" class="col-sm-2 col-form-label">Date Of
                                                    Purchase</label>
                                                <div class="col-sm-4">
                                                    <input type="date" class="form-control " id="date_of_purchase" name="date_of_purchase">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="intent_no" class="col-sm-2 col-form-label">Intent No</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control " id="intent_no" name="intent_no">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="po_no" class="col-sm-2 col-form-label">PO Number</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control " id="po_no" name="po_no">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inv_no" class="col-sm-2 col-form-label">Invoice No</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control " id="inv_no" name="inv_no">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inv_date" class="col-sm-2 col-form-label">Date Of
                                                    Purchase</label>
                                                <div class="col-sm-4">
                                                    <input type="date" class="form-control " id="inv_date" name="invdate">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="warranty" class="col-sm-2 col-form-label">Warranty</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control " id="warranty" name="warranty">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="unit_cost" class="col-sm-2 col-form-label">Unit Cost (With
                                                    GST)</label>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control " id="unit_cost" name="unit_cost">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="unit_cost" class="col-sm-2 col-form-label">No.of.units</label>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control " id="no_of_products" name="no_of_products">
                                                </div>
                                            </div>



                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Upload Image</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Upload</span>
                                                        </div>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="inputGroupFile01" name="image">
                                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control " id="status" name="status">
                                                        <option value="" disabled selected>Select status</option>
                                                        <option value="In">In</option>
                                                        <option value="Out">Out</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <button type="submit" class="btn  btn-success">Submit</button>
                                                    <button class="btn  btn-danger" type="button" onclick="closeCollapse()" aria-label="Close">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body ">
                                <div class="d-flex justify-content-end align-items-center mb-3">
                                    <button class="btn" id="full-screen-btn"><i class="fas fa-expand" title="Fullscreen view"></i></button>
                                    <div class="btn-group">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-download" title="Download"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="download-button">
                                            <a class="dropdown-item" href="#" id="downloadPdfButton">PDF</a>
                                            <a class="dropdown-item" href="#" id="downloadCsvButton">CSV</a>
                                        </div>
                                    </div>

                                    <div class="btn-group">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter" data-toggle="tooltip" data-placement="top" title="Filter Columns"></i>
                                        </button>

                                        <div class="dropdown-menu" aria-labelledby="filter-button">
                                            <form id="column-filter-form">
                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column2" class="column-filter-checkbox" checked>
                                                    <label for="Category">Category</label>
                                                </div>
                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column3" class="column-filter-checkbox" checked>
                                                    <label for="Product">Product</label>
                                                </div>
                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column4" class="column-filter-checkbox" checked>
                                                    <label for="Purchase Date">Purchase Date</label>
                                                </div>
                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column5" class="column-filter-checkbox" checked>
                                                    <label for="Indent No.">Indent No.</label>
                                                </div>
                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column6" class="column-filter-checkbox" checked>
                                                    <label for="Order No">Order No</label>
                                                </div>
                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column7" class="column-filter-checkbox" checked>
                                                    <label for="Invoice No">Invoice No</label>
                                                </div>

                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column8" class="column-filter-checkbox" checked>
                                                    <label for="Invoice Date">Invoice Date</label>
                                                </div>

                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column9" class="column-filter-checkbox" checked>
                                                    <label for="Warranty">Warranty</label>
                                                </div>
                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column10" class="column-filter-checkbox" checked>
                                                    <label for="Unit cost with GST">Unit cost with GST</label>
                                                </div>

                                                <div class="dropdown-item">
                                                    <input type="checkbox" id="column11" class="column-filter-checkbox" checked>
                                                    <label for="Image">Image</label>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>



                                <!-- Table and Pagination Controls -->
                                <div class="table-responsive" id="table-container">
                                    <table id="zero_config" class="table table-striped dataTable" role="grid" aria-describedby="zero_config_info">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">S.No</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Purchase Date</th>
                                                <th scope="col">Indent No.</th>
                                                <th scope="col">Order No</th>
                                                <th scope="col">Invoice No</th>
                                                <th scope="col">Invoice Date</th>
                                                <th scope="col">Warranty</th>
                                                <th scope="col">Unit cost</th>
                                                <th scope="col">Image</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            <?php
                                            // Fetch data and populate the table rows
                                            if ($db->connect_error) {
                                                die("Connection failed: " . $db->connect_error);
                                            }

                                            $sql = "SELECT * FROM cons";
                                            $result = $db->query($sql);
                                            $sn = 1;

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                        <td>" . $sn . "</td>
                                                        <td>" . $row["product_category"] . "</td>
                                                        <td>" . $row["product"] . "</td>
                                                        <td>" . $row["date_of_purchase"] . "</td>
                                                        <td>" . $row["intent_no"] . "</td>
                                                        <td>" . $row["po_no"] . "</td>
                                                        <td>" . $row["inv_no"] . "</td>
                                                        <td>" . $row["invdate"] . "</td>
                                                        <td>" . $row["warranty"] . "</td>
                                                        <td>" . $row["unit_cost"] . "</td>
                                                        <td><a class='btn btn-info btn-sm' href='uploads/" . $row['image'] . "' download>Download</a></td>
                                                        <td><button onclick=\"Checkout('" . $row["status"] . "')\" type='button' class='btn btn-info btn-sm'><i class='fas fa-sign-out-alt'></i> Checkout</button></td>
                                                        <td><button onclick=\"delete_order('" . $row["id"] . "')\" type='button' class='btn btn-outline-danger btn-sm delete-product'><i class='fas fa-trash'></i></button></td>
                                                    </tr>";
                                                    $sn++;
                                                }
                                            }

                                            $db->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Page Content -->
                    <!-- Right sidebar and other content -->
                </div>
                <!-- End Container fluid -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
            </div>
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                <?php include 'footer.php'; ?>
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Wrapper -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- All Jquery -->
        <!-- ============================================================== -->
        <script src="assets/libs/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
        <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
        <script src="assets/extra-libs/sparkline/sparkline.js"></script>
        <!--Wave Effects -->
        <script src="dist/js/waves.js"></script>
        <!--Menu sidebar -->
        <script src="dist/js/sidebarmenu.js"></script>

        <script src="dist/js/custom.min.js"></script>
        <!-- this page js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>



        <!-- DataTables JavaScript -->
        <script src="assets/extra-libs/DataTables/datatables.min.js"></script>
        <script>
            $(document).ready(function() {
                window.zeroconfig = $('#zero_config').DataTable();
            });
        </script>

        <script>
            document.getElementById('full-screen-btn').addEventListener('click', function() {
                const table = document.getElementById('table-container');

                if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.msFullscreenElement) {
                    // Enter fullscreen mode
                    if (table.requestFullscreen) {
                        table.requestFullscreen();
                    } else if (table.webkitRequestFullscreen) {
                        table.webkitRequestFullscreen();
                    } else if (table.msRequestFullscreen) {
                        table.msRequestFullscreen();
                    }

                    // Set background color to white
                    table.style.backgroundColor = 'white';
                } else {
                    // Exit fullscreen mode
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }

                    // Revert background color to its original color
                    // You may need to set it back to your desired color
                    table.style.backgroundColor = ''; // Revert to original color or set to a specific color
                }
            });
        </script>

        <script>
            $(document).on('submit', '#insert_master', function(event) {
                event.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({
                    type: 'POST',
                    url: '#',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#insert_master')[0].reset();
                        window.zeroconfig.destroy();
                        $("#table-body").load(" #table-body > *", () => {
                            window.zeroconfig = $('#zero_config').DataTable();
                        });
                        console.log('Data inserted successfully:', response);
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success('Submitted successfully');
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error('Error inserting data:', errorThrown);
                    }
                });
            });


            function delete_order(id) {
                const res = confirm("Are you sure you want to delete this product?");
                if (!res) {
                    return;
                }
                $.ajax({
                    url: "#",
                    type: "DELETE",
                    data: JSON.stringify({
                        id: id
                    }),
                    success: function(result) {
                        result = JSON.parse(result)
                        swal({
                            title: "Deleted!",
                            text: result.message,
                            icon: "success",
                            button: "OK",
                        }).then(() => {
                            // $("#table-body").load(" #table-body > *");
                            // location.reload();

                            window.zeroconfig.destroy();
                            $("#table-body").load(" #table-body > *", () => {
                                window.zeroconfig = $('#zero_config').DataTable();
                            });
                        });
                    },
                    error: function(xhr, resp, text) {
                        console.log(xhr, resp, text);
                        swal({
                            title: "Error!",
                            text: "An error occured while deleting the item.",
                            icon: "error",
                            button: "OK",
                        });
                    }
                });
            }


            function closeCollapse() {
                $('#collapseExample').collapse('hide');
            }
        </script>

        <!---script for downloading csv-->
        <script>
            document.getElementById('downloadCsvButton').addEventListener('click', function() {
                const table = document.getElementById('zero_config');
                const rows = table.getElementsByTagName('tr');
                const filteredData = [];

                // Get the header row data
                const headerRow = Array.from(table.querySelectorAll('th')).map(cell => cell.textContent);
                filteredData.push(headerRow.join(',')); // Push header row to the data array

                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    const rowData = Array.from(cells).map(cell => cell.textContent);
                    filteredData.push(rowData.join(',')); // Join with commas
                }

                const csvContent = filteredData.join('\n');

                const blob = new Blob([csvContent], {
                    type: "text/csv;charset=utf-8"
                });

                const a = document.createElement("a");
                a.href = window.URL.createObjectURL(blob);
                a.download = "consumable_report.csv";
                a.style.display = "none";
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        </script>


        <!---script for pdf--->
        <script>
            function convertImageToDataURL(url, callback) {
                const img = new Image();
                img.crossOrigin = 'Anonymous';
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.height = this.height;
                    canvas.width = this.width;
                    ctx.drawImage(this, 0, 0);
                    const dataURL = canvas.toDataURL('image/png');
                    callback(dataURL);
                };
                img.src = url;
            }

            function convertSecondImageToDataURL(url, callback) {
                const img = new Image();
                img.crossOrigin = 'Anonymous';
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.height = this.height;
                    canvas.width = this.width;
                    ctx.drawImage(this, 0, 0);
                    const dataURL = canvas.toDataURL('image/png');
                    callback(dataURL);
                };
                img.src = url;
            }

            document.getElementById('downloadPdfButton').addEventListener('click', function() {
                const currentDate = new Date().toLocaleDateString();

                const hostedLogoImgUrl = '\\task-tih\\new\\leftlogo.png';
                const additionalImageUrl = '\\task-tih\\new\\rightlogo.png'; // Replace with your additional image URL

                convertImageToDataURL(hostedLogoImgUrl, function(dataURL) {
                    convertSecondImageToDataURL(additionalImageUrl, function(additionalImageDataUrl) {
                        const tableData = [];
                        const table = document.getElementById('zero_config');
                        const tableHeaderCells = table.getElementsByTagName('thead')[0]
                            .getElementsByTagName('th');
                        const tableBodyRows = table.getElementsByTagName('tbody')[0]
                            .getElementsByTagName('tr');

                        const tableHeaders = Array.from(tableHeaderCells).map(cell => cell
                            .textContent.trim());

                        for (let i = 0; i < tableBodyRows.length; i++) {
                            const cells = tableBodyRows[i].getElementsByTagName('td');
                            const rowData = Array.from(cells).map(cell => cell.textContent.trim());
                            tableData.push(rowData);
                        }

                        const docDefinition = {
                            pageOrientation: 'landscape',
                            pageSize: 'A4',
                            pageMargins: [40, 60, 40, 60],
                            header: {
                                columns: [{
                                        image: dataURL,
                                        width: 100,
                                        alignment: 'left',
                                        margin: [0, 10],
                                    },
                                    {
                                        text: 'Consumable Report',
                                        alignment: 'center',
                                        margin: [0, 10],
                                        fontSize: 18
                                    },
                                    {
                                        image: additionalImageDataUrl,
                                        width: 100,
                                        alignment: 'right',
                                        margin: [0, 10]
                                    }
                                ]
                            },
                            footer: {
                                columns: [{
                                    text: 'Manager',
                                    alignment: 'right',
                                    margin: [0, 20, 40,
                                        0
                                    ]
                                }]
                            },
                            content: [{
                                    text: 'Date: ' + currentDate,
                                    alignment: 'right',
                                    margin: [0, 10]
                                },
                                {
                                    table: {
                                        headerRows: 1,
                                        body: [tableHeaders, ...tableData]
                                    }
                                },
                            ]
                        };

                        pdfMake.createPdf(docDefinition).download('consumable_report.pdf');
                    });
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $(".column-filter-checkbox").change(function() {
                    const columnId = $(this).attr("id");
                    const columnIndex = parseInt(columnId.replace("column", ""));
                    const isVisible = $(this).prop("checked");
                    if (columnId !== "column1") {
                        if (isVisible) {
                            $(`#zero_config th:nth-child(${columnIndex}), #zero_config td:nth-child(${columnIndex})`)
                                .show();
                        } else {
                            $(`#zero_config th:nth-child(${columnIndex}), #zero_config td:nth-child(${columnIndex})`)
                                .hide();
                        }
                    }
                    $(this).prop("checked", isVisible);
                });
            });
        </script>


        <script>
            function Checkout(status) {
                const modalStatus = document.getElementById('modalStatus');
                $('#myModal').modal('show');
            }
        </script>


        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Checkout Consumable to User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body" id="modalStatus">
                        <div class="form-group row">
                            <label for="company" class="col-sm-3 col-form-label"><strong>Company:</strong></label>
                            <div class="col-sm-9">
                                <select class="form-control " id="company" name="company">
                                    <option value="" disabled selected>Select company</option>
                                    <?php
                                    include("config.php");
                                    $sql = "SELECT ncompany FROM company";
                                    $result = $db->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['ncompany'] . '">' . $row['ncompany'] . '</option>';
                                        }
                                    } else {
                                        echo "0 results";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dept" class="col-sm-3 col-form-label"><strong>Department:</strong></label>
                            <div class="col-sm-9">
                                <select class="form-control " name="dept" id="dept" required>
                                    <option value="" disabled selected>Select Department</option>
                                    <?php
                                    include("config.php");
                                    $sql = "SELECT ndept FROM departement";
                                    $result = $db->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['ndept'] . '">' . $row['ndept'] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No blocks found</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="userDropdown" class="col-sm-3 col-form-label"><strong>Select
                                    User:</strong></label>
                            <div class="col-sm-9">
                                <select class="form-control" id="userDropdown">
                                    <option value="user1">User 1</option>
                                    <option value="user2">User 2</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="notes" class="col-sm-3 col-form-label"><strong>Notes:</strong></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn  btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn  btn-success" onclick="submitForm()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        </script>

</body>

</html>