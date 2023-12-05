<?php
include("config.php");

if (isset($_POST["itkm"])) {
    $filename = $_FILES["file"]["tmp_name"];

    if ($_FILES["file"]["size"] > 0) {
        $f = fopen($filename, "r");
        $skipHeader = true;
        $duplicateSerials = array();

        while (($column = fgetcsv($f, 1000, ",")) !== FALSE) {
            if ($skipHeader) {
                $skipHeader = false;
                continue;
            }

            $cabinno = strtoupper(mysqli_real_escape_string($db, $column[0]));
            $dept = mysqli_real_escape_string($db, $column[1]);
            $lab = mysqli_real_escape_string($db, $column[2]);
            $block = mysqli_real_escape_string($db, $column[3]);
            $serial = mysqli_real_escape_string($db, $column[4]);
            $specs = mysqli_real_escape_string($db, $column[5]);
            $brand = mysqli_real_escape_string($db, $column[6]);
            $date = mysqli_real_escape_string($db, $column[7]);
            $system = mysqli_real_escape_string($db, $column[8]);

            // Check if the serial number already exists
            $checkQuery = "SELECT serial FROM eitems WHERE serial = '$serial'";
            $checkResult = mysqli_query($db, $checkQuery);
            //$lab_id=123;///untill the session get catched///////////////////////////////////////////////
            if (mysqli_num_rows($checkResult) > 0) {

                $duplicateSerials[] = $serial;
            } else {
                //$myString = explode('-', $date);
                //$date1=(array_reverse($mystring));
                $sqlinsert = "INSERT INTO eitems (cabinno,dept, lab, block,serial,specs,brand,date,system) VALUES ('$cabinno', '$dept', '$lab', '$block', '$serial','$specs','$brand','$date','$system')";
                $mysq = mysqli_query($db, $sqlinsert);
                if ($mysq) {
                    $response = [
                        'status' => 200,
                        'message' => 'inserted successfully'
                    ];
                }
                if (!$mysq) {
                    error_log("Error: " . mysqli_error($db));
                    echo '<script>alert("Error: ' . mysqli_error($db) . '");</script>';

                    $response = [
                        'status' => 201,
                        'message' => 'not inserted'
                    ];
                }
            }
        }

        fclose($f);

        if (!empty($duplicateSerials)) {
            // Duplicate serial numbers were found, show a pop-up alert
            // echo '<script>alert("Duplicate Serial Numbers: ' . implode(', ', $duplicateSerials) . '");</script>';
            //////////check the ajax file///////////////////////////////////////////////////////////
            $response = ['status' => 202, 'message' => 'duplicates', 'duplicates' => $duplicateSerials];;
        }
    }
    echo json_encode($response);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $data = json_decode(file_get_contents('php://input'), true);


    $sql = "DELETE FROM eitems WHERE serial = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $data["id"]);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'product deleted successfully.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to delete items.'];
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="dist/css/style.min.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
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
                        <!-- create new -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-none d-md-block">Create New <i class="fa fa-angle-down"></i></span>
                                <span class="d-block d-md-none"><i class="fa fa-plus"></i></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </li>
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
                                    <a href="javascript:void(0)" class="btn btn-sm btn-success btn-rounded">View
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
                <nav class="sidebar-nav">
                    <?php include('navlinks.php'); ?>
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
                        <h4 class="page-title">Products</h4>

                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Library
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

            <div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="companyModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="addCompanyForm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="companyModalLabel">Add Company</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body"><!--------modal------------------------>
                                <!-- Form for adding a new company -->
                                <form id="addCompanyForm">
                                    <div class="form-group">
                                        <label for="newCompany">New Company Name</label>
                                        <input type="text" class="form-control" id="newCompany" placeholder="Enter new company">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
            <!---------------block-modal----------------------->
            <div class="modal fade" id="blockmodal" tabindex="-1" role="dialog" aria-labelledby="blockmodal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="addBlockForm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="blockmodal">Add Block</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body"><!--------modal------------------------>
                                <!-- Form for adding a new company -->
                                <form id="addCompanyForm">
                                    <div class="form-group">
                                        <label for="newCompany">New Block Name</label>
                                        <input type="text" class="form-control" id="newCompany" placeholder="Enter new Block">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div><!-------------end of the block modal-------------->
            <!-- ================adding new departement============================================== -->

            <div class="modal fade" id="departementmodal" tabindex="-1" role="dialog" aria-labelledby="departementmodal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="addDepartmentForm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="departementmodal">Add Department</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body"><!--------modal------------------------>
                                <!-- Form for adding a new company -->
                                <form id="departementmodal">
                                    <div class="form-group">
                                        <label for="newCompany">New Departement Name</label>
                                        <input type="text" class="form-control" id="departementmodal" placeholder="Enter new Departement">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Product Modal -->
            <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form id="addProductForm">
                            <div class="modal-header">
                                <h5 class="modal-title" id="departementmodal">Create new product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body"><!--------modal------------------------>
                                <!-- Form for adding a new company -->
                                <form id="productModal">
                                    <div class="form-group">
                                        <label for="newCompany">Enter new product</label>
                                        <input type="text" class="form-control" id="productModal" placeholder="Enter new product">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body shadow">
                                <p class="d-flex justify-content-between">
                                    <a class="btn btn-rounded btn-primary" data-toggle="collapse" href="#collapseExample" role="button" onclick="openCollapse()" aria-expanded="false" aria-controls="collapseExample">
                                        Create new
                                    </a>
                                    <button class="btn btn-rounded btn-danger" type="button" onclick="closeCollapse()" aria-label="Close">
                                        Close
                                    </button>
                                </p>

                                <div class="collapse" id="collapseExample">
                                    <div class="card card-body">
                                        <form id="insert_master">
                                            <div class="form-group row">
                                                <label for="company" class="col-sm-2 col-form-label">Company</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control" id="company" name="company">
                                                        <option value="" disabled selected>Select company</option>
                                                        <option value="MKCE">MKCE</option>
                                                        <option value="KRTF 1">KRTF 1</option>
                                                        <option value="KRTF 2">KRTF 2</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn  btn-success" data-toggle="modal" data-target="#companyModal">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="block" class="col-sm-2 col-form-label">Block</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control  " id="block" name="block">
                                                        <option value="" disabled selected>Select block</option>
                                                        <option value="APJ">APJ</option>
                                                        <option value="Radhakrishna">Radhakrishna</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn  btn-success" data-toggle="modal" data-target="#blockmodal">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="dept" class="col-sm-2 col-form-label">Department</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control  " id="dept" name="dept">
                                                        <option value="" disabled selected>Select department</option>
                                                        <option value="MCA">MCA</option>
                                                        <option value="MBA">MBA</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-success  " data-toggle="modal" data-target="#departementmodal">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="level" class="col-sm-2 col-form-label">Level ID</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control " id="level" name="level">
                                                        <option value="" disabled selected>Select level id</option>
                                                        <option value="L1">L1</option>
                                                        <option value="L2">L2</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="room" class="col-sm-2 col-form-label btn-rounded ">Room</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control  " id="room" name="room">
                                                        <option value="" disabled selected>Select room</option>
                                                        <option value="APJ 001">APJ 001</option>
                                                        <option value="RK 001">RK 001</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="pd" class="col-sm-2 col-form-label">Product</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control" id="pd" name="pd">
                                                        <option value="" disabled selected>Select product</option>
                                                        <option value="APJ 001">APJ 001</option>
                                                        <option value="RK 001">RK 001</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#productModal">+</button>
                                                </div>
                                            </div>

                                            <!--Order details -->
                                            <div class="col-sm-3 mb-3">
                                                <!-- Adjust column size based on your layout -->
                                                <button class="btn" type="button" data-toggle="collapse" data-target="#orderinfo" aria-expanded="false" aria-controls="order">
                                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                                </button>
                                                <label for="orderdetails" class="col-form-label">Order details</label>
                                            </div>

                                            <!-- Optional Company Information -->
                                            <div class="collapse" id="orderinfo">
                                                <!-- Additional Input Fields -->
                                                <div class="form-group row ">
                                                    <label for="dateOfPurchase" class="col-sm-2  col-form-label  ">Date
                                                        Of Purchase</label>
                                                    <div class="col-sm-4">
                                                        <input type="date" class="form-control" id="dateOfPurchase" placeholder="Date Of Purchase">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="intentNo" class="col-sm-2 col-form-label">Intent
                                                        No</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="intentNo" placeholder="Intent No">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="poNumber" class="col-sm-2 col-form-label">PO
                                                        Number</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="poNumber" placeholder="PO Number">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="invoiceNo" class="col-sm-2 col-form-label">Invoice
                                                        No</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="invoiceNo" placeholder="Invoice No">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="warranty" class="col-sm-2 col-form-label">Warranty</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="warranty" placeholder="Warranty">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="unitCost" class="col-sm-2 col-form-label">Unit
                                                        Cost(With GST)</label>
                                                    <div class="col-sm-4">
                                                        <input type="number" class="form-control" id="unitCost" placeholder="Unit Cost(With GST)">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="status" name="status">
                                                            <option value="Active">Active</option>
                                                            <option value="Inactive">Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Computer Optional details -->
                                            <div class="col-sm-3 mb-3">
                                                <button class="btn" type="button" data-toggle="collapse" data-target="#Details" aria-expanded="false" aria-controls="order">
                                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                                </button>
                                                <label for="optionaldetails" class="col-form-label">Optional
                                                    details</label>
                                            </div>
                                            <!-- Computer Optional Details -->
                                            <div class="collapse" id="Details">


                                                <div class="form-group row">
                                                    <label for="pd" class="col-sm-2 col-form-label">Product</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="pd" name="pd">
                                                            <option value="" disabled selected>Select product</option>
                                                            <option value="APJ 001">APJ 001</option>
                                                            <option value="RK 001">RK 001</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#productModal">+</button>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="pcat" class="col-sm-2 col-form-label">Product
                                                        category</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" id="pcat" name="pcat" onchange="toggleOptionalDetails()">
                                                            <option value="" disabled selected>Select product category
                                                            </option>
                                                            <option value="Computer">Computer</option>
                                                            <option value="Network">Network</option>
                                                            <option value="CCTV">CCTV</option>
                                                            <option value="Printer">Printer</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#companyModal">+</button>
                                                    </div>
                                                </div>
                                                <div class="collapse" id="computerDetails">
                                                    <!-- Additional Input Fields -->
                                                    <div class="form-group row">
                                                        <label for="model" class="col-sm-2 col-form-label">Model</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="model" placeholder="Model">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="assetID" class="col-sm-2 col-form-label">Asset
                                                            ID</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="assetID" placeholder="Asset ID">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="cpu" class="col-sm-2 col-form-label">CPU Serial
                                                            No</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="cpuSerialNo" placeholder="CPU Serial No">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="monitor" class="col-sm-2 col-form-label">Monitor
                                                            Serial No</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="monitorSerialNo" placeholder="Monitor Serial No">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="keyboard" class="col-sm-2 col-form-label">Keyboard
                                                            Serial No</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="keyboardSerialNo" placeholder="Keyboard Serial No">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="mouse" class="col-sm-2 col-form-label">Mouse Serial
                                                            No</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="mouseSerialNo" placeholder="Mouse Serial No">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="os" class="col-sm-2 col-form-label">OS</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="os" placeholder="OS">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="ram" class="col-sm-2 col-form-label">RAM</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="ram" placeholder="RAM">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="storageType" class="col-sm-2 col-form-label">Storage
                                                            Type (HDD/SSD)</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="storageType" placeholder="Storage Type">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="storageCapacity" class="col-sm-2 col-form-label">Storage Capacity</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="storageCapacity" placeholder="Storage Capacity">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="msOffice" class="col-sm-2 col-form-label">MS
                                                            Office</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="msOffice" placeholder="MS Office">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="ocsStatus" class="col-sm-2 col-form-label">OCS
                                                            Status (Yes/No)</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="ocsStatus" placeholder="OCS Status">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="createdDate" class="col-sm-2 col-form-label">Created
                                                            Date</label>
                                                        <div class="col-sm-4">
                                                            <input type="date" class="form-control" id="createdDate" placeholder="Created Date">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Network Optional Details -->
                                                <div class="collapse" id="networkDetails">
                                                    <div class="form-group row">
                                                        <label for="type" class="col-sm-2 col-form-label">Type</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" id="type" name="type">
                                                                <option value="Normal">Normal</option>
                                                                <option value="SFP">SFP</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="model" class="col-sm-2 col-form-label">Model</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="model" placeholder="Model">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="assetID" class="col-sm-2 col-form-label">Asset
                                                            ID</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="assetID" placeholder="Asset ID">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="ipAddress" class="col-sm-2 col-form-label">IP
                                                            Address</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="ipAddress" placeholder="IP Address">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="macAddress" class="col-sm-2 col-form-label">MAC
                                                            Address</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="macAddress" placeholder="MAC Address">
                                                        </div>
                                                    </div>
                                                    <!-- Other input fields here -->
                                                </div>


                                                <!-- CCTV Optional Details -->
                                                <div class="collapse" id="cctvDetails">
                                                    <!-- Additional Input Fields -->
                                                    <div class="form-group row">
                                                        <label for="model" class="col-sm-2 col-form-label">Model</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="model" placeholder="Model">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="assetID" class="col-sm-2 col-form-label">Asset
                                                            ID</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="assetID" placeholder="Asset ID">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="ipAddress" class="col-sm-2 col-form-label">IP
                                                            Address</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="ipAddress" placeholder="IP Address">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="macAddress" class="col-sm-2 col-form-label">MAC
                                                            Address</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="macAddress" placeholder="MAC Address">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="noOfChannel" class="col-sm-2 col-form-label">No Of
                                                            Channel</label>
                                                        <div class="col-sm-4">
                                                            <input type="number" class="form-control" id="noOfChannel" placeholder="No Of Channel">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="storageCapacity" class="col-sm-2 col-form-label">Storage Capacity</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="storageCapacity" placeholder="Storage Capacity">
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Printer Optional Details -->
                                                <div class="collapse" id="printerDetails">
                                                    <div class="form-group row">
                                                        <label for="model" class="col-sm-2 col-form-label">Model</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="model" placeholder="Model">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="assetID" class="col-sm-2 col-form-label">Asset
                                                            ID</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="assetID" placeholder="Asset ID">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="ipAddress" class="col-sm-2 col-form-label">IP
                                                            Address</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="ipAddress" placeholder="IP Address">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="macAddress" class="col-sm-2 col-form-label">MAC
                                                            Address</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="macAddress" placeholder="MAC Address">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="scann" class="col-sm-2 col-form-label">Scann
                                                            (Yes/No)</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" id="scann" name="scann">
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="storageCapacity" class="col-sm-2 col-form-label">Storage Capacity</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="storageCapacity" placeholder="Storage Capacity">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col">
                        <div class="card shadow ">
                            <div class="card-body ">
                                <!--this is on 13-09--->
                                <b>
                                    <p>Download the csv file*</p>
                                </b>

                                <button type="button" class="btn  btn-rounded btn-outline-success shadow" id="downloadButton">Download the CSV file</button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card ">

                            <div class="card-body shadow">
                                <!--this is on 13-09--->
                                <b>
                                    <p>Upload the csv file here*</p>
                                </b>
                                <div class="importForm">
                                    <form id="upload" enctype="multipart/form-data">
                                        <input type="file" name="file" id="file" class="btn btn-rounded btn-light shadow">
                                        <input type="submit" name="upload" value="Import CSV" class="btn btn-rounded btn-outline-primary shadow">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-12">
                        <div class="card">
                            <div class="card-body shadow">
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="btn-group float-right" id="table-buttons">
                                            <input type="text" id="search-input" class="form-control ml-2" placeholder="Search">
                                            <button class="btn btn-primary mr-2" id="refresh-btn"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                                            <button class="btn btn-primary mr-2" id="full-screen-btn"><i class="bi bi-arrows-fullscreen"></i> Full Screen</button>
                                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                <i class="bi bi-cloud-download"></i> Download
                                            </button>
                                            <ul class="dropdown-menu" id="download-options">
                                                <li><a class="dropdown-item" href="#" data-export-type="pdf">PDF</a></li>
                                                <li><a class="dropdown-item" href="#" data-export-type="csv">CSV</a></li>
                                                <li><a class="dropdown-item" href="#" data-export-type="excel">Excel</a></li>
                                                <li><a class="dropdown-item" href="#" data-export-type="json">JSON</a></li>
                                            </ul>

                                        </div>
                                    </div>
                                </div>


                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered dataTable" role="grid" aria-describedby="zero_config_info">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col">S.No</th>
                                                <th scope="col">Brand</th>
                                                <th scope="col">Specification</th>
                                                <th scope="col">Cabin No</th>
                                                <th scope="col">Serial No</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Type of the System</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            <?php
                                            // Fetch data and populate the table rows
                                            if ($db->connect_error) {
                                                die("Connection failed: " . $db->connect_error);
                                            }

                                            $sql = "SELECT * FROM eitems";
                                            $result = $db->query($sql);
                                            $sn = 1;

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                    <td>" . $sn . "</td>
                                    <td>" . $row["brand"] . "</td>
                                    <td>" . $row["specs"] . "</td>
                                    <td>" . $row["cabinno"] . "</td>
                                    <td>" . $row["serial"] . "</td>
                                    <td>" . $row["date"] . "</td>
                                    <td>" . $row["system"] . "</td>
                                    <td><button onclick=\"delete_order('" . $row["serial"] . "')\" type='button' class='btn btn-rounded btn-outline-danger btn-sm delete-product shadow'><i class='bi bi-trash'></i></button></td>
                                </tr>";
                                                    $sn++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No results found.</td></tr>";
                                            }
                                            $db->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="dataTables_info" id="zero_config_info" role="status" aria-live="polite">Showing 1 to 10 of 100 entries</div>
                        </div>
                    </div>
                </div>


                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right sidebar -->
            <!-- ============================================================== -->
            <!-- .right-sidebar -->
            <!-- ============================================================== -->
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
            All Rights Reserved by Matrix-admin. Designed and Developed by
            <a href="https://wrappixel.com">WrapPixel</a>.
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
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
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!-- this page js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>





    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#zero_config').DataTable({
                "paging": true,
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            // Refresh button
            $('#refresh-btn').html('<i class="bi bi-arrow-clockwise"></i>'); // Bootstrap refresh icon
            $('#refresh-btn').click(function() {
                table.ajax.reload();
            });

            // Full Screen button
            $('#full-screen-btn').html('<i class="bi bi-arrows-fullscreen"></i>'); // Bootstrap full screen icon
            $('#full-screen-btn').click(function() {
                var elem = document.getElementById('zero_config_wrapper');
                elem.requestFullscreen();
            });

            // Export table to various formats
            $('#download-options a').click(function() {
                var exportType = $(this).data('export-type');
                exportTableTo(exportType);
            });

            function exportTableTo(type) {
                switch (type) {
                    case 'pdf':
                        table.button('.buttons-pdf').trigger();
                        break;
                    case 'csv':
                        table.button('.buttons-csv').trigger();
                        break;
                    case 'excel':
                        table.button('.buttons-excel').trigger();
                        break;
                    case 'json':
                        table.button('.buttons-json').trigger();
                        break;
                    default:
                        break;
                }
            }
        });
    </script>




    <script>
        document.getElementById("downloadButton").addEventListener("click", function() {
            const csvData = "Cabin,Departement,Lab,Block,Serial,Specs,Brand,Date";
            const blob = new Blob([csvData], {
                type: "text/csv"
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "add_the_data.csv";
            a.click();
            window.URL.revokeObjectURL(url);
        });

        $(document).ready(function() {
            $("#upload").submit(function(event) {
                event.preventDefault();

                var formData = new FormData(this);
                formData.append("itkm", true);
                console.log(formData);
                $.ajax({
                    type: "POST",
                    url: "#",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        response = JSON.parse(response)
                        console.log(12);
                        console.log(response)
                        if (response.status == 200) {
                            console.log("Data inserted successfully!");
                            $('#upload')[0].reset();

                            $("#table-body").load(" #table-body > *");
                            console.log(1);
                            swal({
                                title: "inserted!",
                                text: response.message,
                                icon: "success",
                                button: "OK",
                            })



                        } else if (response.status == 201) {
                            console.log("Not inserted ");
                            swal({
                                title: "Not inserted!",
                                text: response.message,
                                icon: "error",
                                button: "OK",
                            })
                            $('#upload')[0].reset()
                            $("#table-body").load(" #table-body > *");
                        } else if (response.status == 202) {
                            console.log(response);
                            swal({
                                title: "Duplicate entries found!",
                                text: "No of " + response.message + ": " + response
                                    .duplicates.length,
                                icon: "warning",
                                button: "OK",
                            })

                            $('#upload')[0].reset()
                            $("#table-body").load(" #table-body > *");
                        }

                    },
                    error: function(error) {
                        console.error("Error inserting data:", error);
                    }
                });
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
                    // reload the page
                    swal({
                        title: "Deleted!",
                        text: result.message,
                        icon: "success",
                        button: "OK",
                    }).then(() => {
                        $("#table-body").load(" #table-body > *");

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


        // addCompanyForm

        $(document).ready(function() {
            $('#addCompanyForm').submit(function(event) {
                event.preventDefault();
                // AJAX call for adding a company
                $.ajax({
                    type: 'POST',
                    url: '#', // Replace with your backend endpoint
                    data: $(this).serialize(),
                    success: function(response) {
                        // Handle success
                    },
                    error: function(error) {
                        // Handle error
                    }
                });
            });
        });


        // add Block Form

        $(document).ready(function() {
            $('#addBlockForm').submit(function(event) {
                event.preventDefault();
                // AJAX call for adding a block
                $.ajax({
                    type: 'POST',
                    url: '#', // Replace with your backend endpoint
                    data: $(this).serialize(),
                    success: function(response) {
                        // Handle success
                    },
                    error: function(error) {
                        // Handle error
                    }
                });
            });
        });

        // add Department Form modal

        $(document).ready(function() {
            $('#addDepartmentForm').submit(function(event) {
                event.preventDefault();
                // AJAX call for adding a department
                $.ajax({
                    type: 'POST',
                    url: '#', // Replace with your backend endpoint
                    data: $(this).serialize(),
                    success: function(response) {
                        // Handle success
                    },
                    error: function(error) {
                        // Handle error
                    }
                });
            });
        });


        // add product form modal 
        $(document).ready(function() {
            $('#addProductForm').submit(function(event) {
                event.preventDefault();
                // AJAX call for adding a product
                $.ajax({
                    type: 'POST',
                    url: '#', // Replace with your backend endpoint
                    data: $(this).serialize(),
                    success: function(response) {
                        // Handle success
                    },
                    error: function(error) {
                        // Handle error
                    }
                });
            });
        });



        function toggleOptionalDetails() {
            const selectedCategory = document.getElementById("pcat").value;

            // Hide all optional detail sections
            document.getElementById("computerDetails").classList.remove("show");
            document.getElementById("networkDetails").classList.remove("show");
            document.getElementById("cctvDetails").classList.remove("show");
            document.getElementById("printerDetails").classList.remove("show");

            // Show the relevant optional detail section based on the selected category
            if (selectedCategory === "Computer") {
                document.getElementById("computerDetails").classList.add("show");
            } else if (selectedCategory === "Network") {
                document.getElementById("networkDetails").classList.add("show");
            } else if (selectedCategory === "CCTV") {
                document.getElementById("cctvDetails").classList.add("show");
            } else if (selectedCategory === "Printer") {
                document.getElementById("printerDetails").classList.add("show");
            }
        }


        function closeCollapse() {
            $('#collapseExample').collapse('hide');
        }
    </script>


</body>

</html>