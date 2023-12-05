<?php
include("config.php");
$response = [];

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

            $comp = strtoupper(mysqli_real_escape_string($db, $column[0]));
            $block = strtoupper(mysqli_real_escape_string($db, $column[1]));
            $depart = strtoupper(mysqli_real_escape_string($db, $column[2]));
            $Level = strtoupper(mysqli_real_escape_string($db, $column[3]));
            $roomno = strtoupper(mysqli_real_escape_string($db, $column[4]));
            $dop = mysqli_real_escape_string($db, $column[5]);
            //  DD-MM-YYYY 
            $dop = date('Y-m-d', strtotime($dop));
            $intent = strtoupper(mysqli_real_escape_string($db, $column[6]));
            $pon = strtoupper(mysqli_real_escape_string($db, $column[7]));
            $invoice = strtoupper(mysqli_real_escape_string($db, $column[8]));
            $Waranty = mysqli_real_escape_string($db, $column[9]);
            $unitcost = mysqli_real_escape_string($db, $column[10]);
            $status = strtoupper(mysqli_real_escape_string($db, $column[11]));
            $product = strtoupper(mysqli_real_escape_string($db, $column[12]));
            $product_cat = strtoupper(mysqli_real_escape_string($db, $column[13]));
            $model = strtoupper(mysqli_real_escape_string($db, $column[14]));
            $asset = strtoupper(mysqli_real_escape_string($db, $column[15]));
            $cpus = strtoupper(mysqli_real_escape_string($db, $column[16]));
            $monitors = strtoupper(mysqli_real_escape_string($db, $column[17]));
            $keyboards = strtoupper(mysqli_real_escape_string($db, $column[18]));
            $mouses = strtoupper(mysqli_real_escape_string($db, $column[19]));
            $os = strtoupper(mysqli_real_escape_string($db, $column[20]));
            $ram = strtoupper(mysqli_real_escape_string($db, $column[21]));
            $storage_type = strtoupper(mysqli_real_escape_string($db, $column[22]));
            $ms = strtoupper(mysqli_real_escape_string($db, $column[23]));
            $ocs = mysqli_real_escape_string($db, $column[24]);
            $created_date = mysqli_real_escape_string($db, $column[25]);
            $created_date = date('Y-m-d', strtotime($created_date));
            $ip = mysqli_real_escape_string($db, $column[26]);
            $mac = mysqli_real_escape_string($db, $column[27]);
            $noc = mysqli_real_escape_string($db, $column[28]);
            $scan = strtoupper(mysqli_real_escape_string($db, $column[29]));
            $storage = strtoupper(mysqli_real_escape_string($db, $column[30]));
            $net_type = strtoupper(mysqli_real_escape_string($db, $column[31]));

            // Check if the serial number already exists
            $checkQuery = "SELECT * FROM eitems WHERE assetid = '$asset'";
            $checkResult = mysqli_query($db, $checkQuery);
            //$lab_id=123;///untill the session get catched///////////////////////////////////////////////
            if (mysqli_num_rows($checkResult) > 0) {

                $duplicateSerials[] = $asset;
            } else {
                $sqlinsert = "INSERT INTO eitems (company, block, deprt, nlevel, nroom, pdate, nintent, po_n, invoice_no, waranty, unit_cost, status, product, product_c, model, assetid, cpu_s, monitor_s, keyb_s, Mouse_s, os, ram, storagetype, msoffice, ocssts, created_date, ip, mac, nchannels, scann, Storage, net_type)
                VALUES ('$comp', '$block', '$depart', '$Level', '$roomno', '$dop', '$intent', '$pon', '$invoice', '$Waranty', '$unitcost', '$status', '$product', '$product_cat', '$model', '$asset', '$cpus', '$monitors', '$keyboards', '$mouses', '$os', '$ram', '$storage_type', '$ms', '$ocs', '$created_date', '$ip', '$mac', '$noc', '$scan', '$storage', '$net_type')";

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

    $sql = "DELETE FROM eitems WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $data["id"]);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Deleted successfully.'];
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>ITKM</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <!-- <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" /> -->
    <!-- <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/> -->

    <link rel="stylesheet" type="text/css" href="assets/extra-libs/multicheck/multicheck.css">
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <style type="text/css">
    .jqstooltip {
        position: absolute;
        left: 0px;
        top: 0px;
        visibility: hidden;
        background: rgb(0, 0, 0) transparent;
        background-color: rgba(0, 0, 0, 0.6);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
        -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
        color: white;
        font: 10px arial, san serif;
        text-align: left;
        white-space: nowrap;
        padding: 5px;
        border: 1px solid white;
        z-index: 10000;
    }


    .jqsfield {
        color: white;
        font: 10px arial, san serif;
        text-align: left;
    }
    </style>

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
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon -->
                        <b class="logo-icon p-l-10">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="assets/images/download.jpeg" alt="homepage" height="40px" width="40px"
                                class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text">
                            <!-- dark Logo text -->
                            <img src="assets/images/mkce.png" alt="homepage" height="50px" width="100%"
                                class="light-logo" />
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
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
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
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a>
                        </li>
                        <!-- ============================================================== -->
                        <!-- create new -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item dropdown">
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
                        </li> -->
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item search-box">
                            <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i
                                    class="ti-search"></i></a>
                            <form class="app-search position-absolute" id="search-form">
                                <input id="search-input" type="text" class="form-control"
                                    placeholder="Search &amp; enter" />
                                <span id="closeSearch"> <a class="srh-btn"><i class="ti-close"></i></a></span>
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
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
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
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="font-24 mdi mdi-comment-processing"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown"
                                aria-labelledby="2">
                                <ul class="list-style-none">
                                    <li>
                                        <div class="">
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-success btn-circle"><i
                                                            class="ti-calendar"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Event today</h5>
                                                        <span class="mail-desc">Just a reminder that event</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-info btn-circle"><i
                                                            class="ti-settings"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Settings</h5>
                                                        <span class="mail-desc">You can customize this template</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-primary btn-circle"><i
                                                            class="ti-user"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Pavan kumar</h5>
                                                        <span class="mail-desc">Just see the my admin!</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-danger btn-circle"><i
                                                            class="fa fa-link"></i></span>
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
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                    src="assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31" /></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i>
                                    My Profile</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i>
                                    My Balance</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-email m-r-5 m-l-5"></i>
                                    Inbox</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i
                                        class="ti-settings m-r-5 m-l-5"></i> Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i
                                        class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                                <div class="dropdown-divider"></div>
                                <div class="p-l-30 p-10">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-success">View
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
        <aside class="left-sidebar vh-100" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
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
        <div class="page-wrapper ">
            <div class="container py-5 d-none" id="searchErrorAlert">

                <div class="alert alert-danger " role="alert">
                    No Results Found!
                </div>
            </div>
            <div class="d-none py-5 row mx-5" id="searchResult">

            </div>
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- <div class="page-breadcrumb" id="main-content-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">Products</h4>

                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Library
                                    </li>
                                </ol>
                            </nav>

                        </div>
                    </div>
                </div>
            </div> -->

            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="companyModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="companyModalLabel">Add Company</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new company -->
                            <form id="addCompanyForm">
                                <div class="form-group">
                                    <label for="newCompany">New Company Name</label>
                                    <input type="text" class="form-control" name="ncompany" id="ncompany"
                                        placeholder="Enter new company">
                                </div>
                                <button type="submit" class="btn btn-primary">Add company</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <!---------------block-modal----------------------->
            <div class="modal fade" id="blockmodal" tabindex="-1" role="dialog" aria-labelledby="blockmodal"
                aria-hidden="true">
                <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="blockmodal">Add Block</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new block -->
                            <form id="addblockForm">
                                <div class="form-group">
                                    <label for="newCompany">New Block Name</label>
                                    <input type="text" class="form-control" name="nblock" id="nCompany"
                                        placeholder="Enter new Block">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-------------end of the block modal-------------->
            <!-- ================adding new departement============================================== -->

            <div class="modal fade" id="departementmodal" tabindex="-1" role="dialog" aria-labelledby="departementmodal"
                aria-hidden="true">
                <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="departementmodal">Add Department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new company -->
                            <form id="adddprtform">
                                <div class="form-group">
                                    <label for="newCompany">New Departement Name</label>
                                    <input type="text" class="form-control" id="ndept" name="ndept"
                                        placeholder="Enter new Departement">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Modal -->
            <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="departementmodal">Create new product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new company -->
                            <form id="productform">
                                <div class="form-group">
                                    <label for="productcategory">Select product category</label>
                                    <select class="form-control" id="producttype" name="producttype">
                                        <option value="COMPUTER">COMPUTER</option>
                                        <option value="NETWORK">NETWORK</option>
                                        <option value="CCTV">CCTV</option>
                                        <option value="PRINTER">PRINTER</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="newCompany">Enter new product</label>
                                    <input type="text" class="form-control" id="productname" name="productname"
                                        placeholder="Enter new product">
                                </div>

                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- room modal -->
            <div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="departementmodal">Create new Room</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new company -->
                            <form id="addroom">
                                <div class="form-group">
                                    <label for="newCompany">Enter new Room</label>
                                    <input type="text" class="form-control" id="nroom" name="nroom"
                                        placeholder="Enter new product">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- model modal -->
            <div class="modal fade" id="model" tabindex="-1" role="dialog" aria-labelledby="blockmodal"
                aria-hidden="true">
                <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="blockmodal">Add Model</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new model -->
                            <form id="addmodel">
                                <div class="form-group">
                                    <label for="newCompany">New Model Name</label>
                                    <input type="text" class="form-control" name="nmodel" id="nmodel"
                                        placeholder="Enter new model">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- model1 modal -->
            <div class="modal fade" id="model1" tabindex="-1" role="dialog" aria-labelledby="blockmodal"
                aria-hidden="true">
                <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="blockmodal">Add Model</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new model -->
                            <form id="addmodel1">
                                <div class="form-group">
                                    <label for="newCompany">New Model Name</label>
                                    <input type="text" class="form-control" name="nmodel" id="nmodel"
                                        placeholder="Enter new model">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- model2 modal -->
            <div class="modal fade" id="model2" tabindex="-1" role="dialog" aria-labelledby="blockmodal"
                aria-hidden="true">
                <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="blockmodal">Add Model</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new model -->
                            <form id="addmodel2">
                                <div class="form-group">
                                    <label for="newCompany">New Model Name</label>
                                    <input type="text" class="form-control" name="nmodel" id="nmodel"
                                        placeholder="Enter new model">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- model3 modal -->
            <div class="modal fade" id="model3" tabindex="-1" role="dialog" aria-labelledby="blockmodal"
                aria-hidden="true">
                <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="blockmodal">Add Model</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new model -->
                            <form id="addmodel3">
                                <div class="form-group">
                                    <label for="newCompany">New Model Name</label>
                                    <input type="text" class="form-control" name="nmodel" id="nmodel"
                                        placeholder="Enter new model">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- modal for the os -->
            <div class="modal fade" id="osmodel" tabindex="-1" role="dialog" aria-labelledby="osmodel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="blockmodal">Add OS</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--------modal------------------------>
                            <!-- Form for adding a new model -->
                            <form id="osmodel1">
                                <div class="form-group">
                                    <label for="newCompany">New OS Name</label>
                                    <input type="text" class="form-control" name="ostype" id="ostype"
                                        placeholder="Enter new OS type">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end of the os modal -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid" id="main-content">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body ">
                                <p class="d-flex justify-content-between">
                                    <a class="btn btn-light btn-outline-primary" data-toggle="collapse"
                                        href="#collapseExample" role="button" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        <i class=" mdi mdi-react">Create new</i>
                                    </a>
                                </p>

                                <div class="collapse" id="collapseExample">
                                    <div class="card card-body" id="addcomps">
                                        <!-- insert the whole form here  -->
                                        <form id="insert_all">
                                            <div class="form-group row">
                                                <label for="company" class="col-sm-2 col-form-label">Company</label>
                                                <div class="col-sm-4" id="addcomp">
                                                    <select name="company" id="supplier-name" class="form-control w-100"
                                                        required>
                                                        <option value="" disabled selected>Select companyname</option>
                                                        <?php
                                                        include("config.php");

                                                        $sql = "SELECT DISTINCT ncompany FROM company";
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

                                                <div class="col-sm-2">
                                                    <button type="button" class="btn  btn-success" data-toggle="modal"
                                                        data-target="#companyModal">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="block" class="col-sm-2 col-form-label">Block</label>
                                                <div class="col-sm-4">
                                                    <select name="block" id="block-name" class="form-control w-100"
                                                        required>
                                                        <option value="" disabled selected>Select a block</option>
                                                        <?php
                                                        include("config.php");

                                                        $sql = "SELECT DISTINCT nblock FROM blocks";
                                                        $result = $db->query($sql);

                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo '<option value="' . $row['nblock'] . '">' . $row['nblock'] . '</option>';
                                                            }
                                                        } else {
                                                            echo "0 results";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn  btn-success" data-toggle="modal"
                                                        data-target="#blockmodal">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="dept" class="col-sm-2 col-form-label">Department</label>
                                                <div class="col-sm-4">
                                                    <select name="deprt" id="departement-name"
                                                        class="form-control w-100">
                                                        <option value="" disabled selected>Select a departement</option>
                                                        <?php
                                                        include("config.php");

                                                        $sql = "SELECT DISTINCT ndept FROM departement";
                                                        $result = $db->query($sql);

                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo '<option value="' . $row['ndept'] . '">' . $row['ndept'] . '</option>';
                                                            }
                                                        } else {
                                                            echo "0 results";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-success  " data-toggle="modal"
                                                        data-target="#departementmodal">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="nlevel" class="col-sm-2 col-form-label">Level ID</label>
                                                <div class="col-sm-4">
                                                    <select name="nlevel" id="level" class="form-control w-100">
                                                        <option value="" disabled selected>Select level id</option>
                                                        <option value="L1">L0</option>
                                                        <option value="L1">L1</option>
                                                        <option value="L2">L2</option>
                                                        <option value="L1">L3</option>
                                                        <option value="L1">L4</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="room" class="col-sm-2 col-form-label ">Room</label>
                                                <div class="col-sm-4">
                                                    <select name="nroom" id="room" class="form-control w-100">
                                                        <option value="" disabled selected>Select a Room</option>
                                                        <?php
                                                        include("config.php");

                                                        $sql = "SELECT DISTINCT nroom FROM rooms";
                                                        $result = $db->query($sql);

                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo '<option value="' . $row['nroom'] . '">' . $row['nroom'] . '</option>';
                                                            }
                                                        } else {
                                                            echo "0 results";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                                        data-target="#roomModal">+</button>
                                                </div>
                                            </div>
                                            <!--<div class="form-group row">
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
                                            </div>-->

                                            <!--Order details -->
                                            <div class="col-sm-3 mb-3">
                                                <!-- Adjust column size based on your layout -->
                                                <button class="btn" type="button" data-toggle="collapse"
                                                    data-target="#orderinfo" aria-expanded="false"
                                                    aria-controls="order">
                                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>

                                                    <label for="orderdetails" class="col-form-label">Order
                                                        details</label></button>
                                            </div>

                                            <!-- Optional Company Information -->
                                            <div class="collapse" id="orderinfo">
                                                <!-- Additional Input Fields -->
                                                <div class="form-group row  ">
                                                    <label for="dateOfPurchase" class="col-sm-2  col-form-label  ">Date
                                                        Of Purchase</label>
                                                    <div class="col-sm-4">
                                                        <input type="date" class="form-control " id="dateOfPurchase"
                                                            name="pdate" placeholder="Date Of Purchase">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="intentNo" class="col-sm-2 col-form-label">Intent
                                                        No</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control " id="intentNo"
                                                            name="nintent" placeholder="Intent No">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="poNumber" class="col-sm-2 col-form-label">PO
                                                        Number</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control " id="poNumber"
                                                            name="po_n" placeholder="PO Number">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="invoiceNo" class="col-sm-2 col-form-label">Invoice
                                                        No</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control " id="invoiceNo"
                                                            name="invoice_no" placeholder="Invoice No">
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center">
                                                    <label for="warranty" class="col-sm-2 col-form-label">Warranty(In
                                                        Months)</label>
                                                    <div class="col-sm-4">
                                                        <input type="number" class="form-control" id="warranty"
                                                            name="waranty" placeholder="Warranty" min="1">
                                                    </div>
                                                    <span class="d-none" id="warrantyEndDateContainer">Warranty Ending Date: <span id="warrantyEndDate"></span></span>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="unitCost" class="col-sm-2 col-form-label">Unit
                                                        Cost(With GST)</label>
                                                    <div class="col-sm-4">
                                                        <input type="number" class="form-control " id="unitCost"
                                                            name="unit_cost" placeholder="Unit Cost(With GST)">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="unitCost" class="col-sm-2 col-form-label">No.of.units</label>
                                                    <div class="col-sm-4">
                                                        <input type="number" class="form-control " id="nou"
                                                            name="nou" placeholder="No.of.units">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control " id="status" name="status">
                                                            <option value="Active">Active</option>
                                                            <option value="Inactive">Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Computer Optional details -->
                                            <div class="col-sm-3 mb-3">
                                                <button class="btn" type="button" data-toggle="collapse"
                                                    data-target="#Details" aria-expanded="false" aria-controls="order">
                                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>

                                                    <label for="optionaldetails" class="col-form-label">Machine
                                                        details</label> </button>
                                            </div>
                                            <!-- Computer Optional Details -->
                                            <div class="collapse" id="Details">


                                                <div class="form-group row">
                                                    <label for="pd" class="col-sm-2 col-form-label">Product</label>
                                                    <div class="col-sm-4">
                                                        <select id="productnames" name="product"
                                                            class="form-control w-100">
                                                            <option value="" disabled selected>Select product</option>
                                                            <?php
                                                            include("config.php");

                                                            $sql = "SELECT  productname FROM products";
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

                                                    <div class="col-sm-2">
                                                        <button type="button" class="btn btn-success"
                                                            data-toggle="modal" data-target="#productModal">+</button>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="pcat" class="col-sm-2 col-form-label">Product
                                                        category</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control " id="pcat" name="product_c"
                                                            onchange="toggleOptionalDetails()">
                                                            <option value="" disabled selected>Select product category
                                                            </option>
                                                            <option value="COMPUTER">COMPUTER</option>
                                                            <option value="NETWORK">NETWORK</option>
                                                            <option value="CCTV">CCTV</option>
                                                            <option value="PRINTER">PRINTER</option>
                                                        </select>
                                                    </div>
                                                    <!--<div class="col-sm-2">
                                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#companyModal">+</button>
                                                    </div>-->
                                                </div>
                                                <div class="form-group row">
                                                    <label for="assetID" class="col-sm-2 col-form-label">Asset
                                                        ID</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="assetID"
                                                            name="assetid" placeholder="Asset ID">
                                                    </div>
                                                </div>
                                                <div class="collapse" id="networkInformation">
                                                    <div class="form-group row">
                                                        <label for="ipAddress" class="col-sm-2 col-form-label ">IP
                                                            Address</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control " id="ipAddress"
                                                                name="ip" placeholder="IP Address">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="macAddress" class="col-sm-2 col-form-label">MAC
                                                            Address</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control " id="macAddress"
                                                                name="mac" placeholder="MAC Address">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="model" class="col-sm-2 col-form-label">Model</label>
                                                    <div class="form-group col-sm-4">
                                                        <select class="form-control" id="exampleDataList" name="model">
                                                            <option value="" disabled selected>Select a Model</option>
                                                            <?php
                                                            include("config.php");

                                                            $sql = "SELECT DISTINCT nmodel FROM model";
                                                            $result = $db->query($sql);

                                                            if ($result && $result->num_rows > 0) {
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="' . $row['nmodel'] . '">' . $row['nmodel'] . '</option>';
                                                                }
                                                            } else {
                                                                echo "0 results";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-2">
                                                        <button type="button" class="btn btn-success  "
                                                            data-toggle="modal" data-target="#model">
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="collapse" id="computerDetails">
                                                    <!-- Additional Input Fields -->



                                                    <div class="form-group row">
                                                        <label for="cpu" class="col-sm-2 col-form-label">CPU Serial
                                                            No</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="cpuSerialNo"
                                                                name="cpu_s" placeholder="CPU Serial No">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="monitor" class="col-sm-2 col-form-label">Monitor
                                                            Serial No</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="monitorSerialNo"
                                                                name="monitor_s" placeholder="Monitor Serial No">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="keyboard" class="col-sm-2 col-form-label">Keyboard
                                                            Serial No</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control"
                                                                id="keyboardSerialNo" name="keyb_s"
                                                                placeholder="Keyboard Serial No">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="mouse" class="col-sm-2 col-form-label">Mouse Serial
                                                            No</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="mouseSerialNo"
                                                                name="Mouse_s" placeholder="Mouse Serial No">
                                                        </div>
                                                    </div>
                                                   


                                                    <!--os model-->
                                                    <div class="form-group row">
                                                        <label for="model" class="col-sm-2 col-form-label">OS</label>
                                                        <div class="form-group col-sm-4">
                                                            <select class="form-control" id="exampleDataList5" name="os">
                                                                <option value="" disabled selected>Select OS</option>
                                                                <?php
                                                            include("config.php");

                                                            $sql = "SELECT DISTINCT ostype FROM os";
                                                            $result = $db->query($sql);

                                                            if ($result && $result->num_rows > 0) {
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="' . $row['ostype'] . '">' . $row['ostype'] . '</option>';
                                                                }
                                                            } else {
                                                                echo "0 results";
                                                            }
                                                            ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <button type="button" class="btn btn-success  "
                                                                data-toggle="modal" data-target="#osmodel">
                                                                +
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- end of os model -->
                                                    <div class="form-group row">
                                                        <label for="ram" class="col-sm-2 col-form-label">RAM</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" id="ram" name="ram">
                                                                <option value="32GB">32 GB</option>
                                                                <option value="16GB">16 GB</option>
                                                                <option value="8GB">8 GB</option>
                                                                <option value="4GB">4 GB</option>
                                                                <option value="2GB">2 GB</option>
                                                                <option value="1GB">1 GB</option>
                                                                <option value="64GB">64 GB</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="storageType"
                                                            class="col-sm-2 col-form-label">Storage</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control " id="storageType"
                                                                name="storagetype">
                                                                <option value="HDD">HDD</option>
                                                                <option value="SDD">SSD</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="storageCapacity"
                                                            class="col-sm-2 col-form-label">Storage Capacity</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control storage-capacity"
                                                                id="storage" name="storage">
                                                                <option value="128GB">128GB</option>
                                                                <option value="256GB">256GB</option>
                                                                <option value="512GB">512GB</option>
                                                                <option value="1TB">1TB</option>
                                                                <option value="1TB">2TB</option>
                                                                <option value="1TB">3TB</option>
                                                                <option value="1TB">2TB</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="msOffice" class="col-sm-2 col-form-label">MS
                                                            Office</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" name="msoffice"
                                                                id="msOffice" placeholder="MS Office">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="ocsStatus" class="col-sm-2 col-form-label">OCS
                                                            Status (Yes/No)</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" id="ocsStatus" name="ocssts">
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="createdDate" class="col-sm-2 col-form-label">Created
                                                            Date</label>
                                                        <div class="col-sm-4">
                                                            <input type="date" class="form-control" id="createdDate"
                                                                name="created_date" placeholder="Created Date">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Network Optional Details -->
                                                <div class="collapse" id="networkDetails">
                                                    <div class="form-group row">
                                                        <label for="type" class="col-sm-2 col-form-label">Type</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control " id="type" name="net_type">
                                                                <option value="">Select Type</option>
                                                                <option value="Normal">Normal</option>
                                                                <option value="SFP">SFP</option>
                                                            </select>
                                                        </div>
                                                    </div>



                                                    <!-- Other input fields here -->
                                                </div>


                                                <!-- CCTV Optional Details -->
                                                <div class="collapse" id="cctvDetails">
                                                    <!-- Additional Input Fields -->



                                                    <div class="form-group row">
                                                        <label for="noOfChannel" class="col-sm-2 col-form-label">No Of
                                                            Channel</label>
                                                        <div class="col-sm-4">
                                                            <input type="number" class="form-control " name="nchannels"
                                                                id="noOfChannel" placeholder="No Of Channel">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="storageCapacity"
                                                            class="col-sm-2 col-form-label">Storage Capacity</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control storage-capacity"
                                                                id="storage" name="storage"
                                                                placeholder="Storage Capacity">
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Printer Optional Details -->
                                                <div class="collapse" id="printerDetails">
                                                    <!-- <div class="form-group row">
                                                        <label for="model" class="col-sm-2 col-form-label">Model</label>
                                                        <div class="form-group col-sm-4">
                                                            <select class="form-control" id="exampleDataList3">
                                                                <option value="" disabled selected>Select a Model
                                                                </option>
                                                                <?php
                                                                // include("config.php");

                                                                // $sql = "SELECT DISTINCT nmodel FROM model";
                                                                // $result = $db->query($sql);

                                                                // if ($result && $result->num_rows > 0) {
                                                                //     while ($row = $result->fetch_assoc()) {
                                                                //         echo '<option value="' . $row['nmodel'] . '">' . $row['nmodel'] . '</option>';
                                                                //     }
                                                                // } else {
                                                                //     echo "0 results";
                                                                // }
                                                                ?> 
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <button type="button" class="btn btn-success  "
                                                                data-toggle="modal" data-target="#model3">
                                                                +
                                                            </button>
                                                        </div>
                                                    </div> -->


                                                    <div class="form-group row">
                                                        <label for="scann" class="col-sm-2 col-form-label">Scann
                                                            (Yes/No)</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control " id="scann" name="scann">
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="storageCapacity"
                                                            class="col-sm-2 col-form-label">Storage Capacity</label>
                                                        <div class="col-sm-4">

                                                            <input type="text" class="form-control storage-capacity"
                                                                id="storage" name="storage"
                                                                placeholder="Storage Capacity">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class=" btn btn-primary">Submit</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>



                <div class="row">
                    <div class="col">
                        <div class="card  ">
                            <div class="card-body ">
                                <!--this is on 13-09--->
                                <b>

                                    <p>Download the csv file*</p>

                                </b>

                                <button type="button" class="btn  btn-outline-success " id="downloadButton"> <i
                                        class="mdi mdi-download"> Download the CSV file</i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card ">

                            <div class="card-body ">
                                <!--this is on 13-09--->
                                <b>
                                    <p>Upload the csv file here*</p>
                                </b>
                                <div class="importForm">
                                    <form id="upload" enctype="multipart/form-data">
                                        <input type="file" name="file" id="file" class="btn btn-light ">
                                        <button type="submit" class="btn btn-outline-primary "><i
                                                class=" mdi mdi-upload">Import CSV</i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>





                    <div class="col-12">
                        <div class="card">
                            <div class="card-body ">
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="d-flex justify-content-between items-center">

                                            <div>Products</div>
                                            <div class="btn-group float-right mb-3" id="table-buttons">
                                                <!-- <input type="text" id="search-input" class="form-control ml-2" placeholder="Search"> -->
                                                <button class="btn btn-light btn btn-outline-success mr-2"
                                                    id="full-screen-btn"><i class=" mdi mdi-fullscreen"></i></button>
                                                <!-- <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                    <i class="bi bi-cloud-download"></i> Download
                                                </button> -->
                                                <button id="exportToCSV"
                                                    class="btn btn-light btn btn-outline-danger mr-2"
                                                    data-export-type="csv"><i class=" mdi mdi-download"></i></button>
                                                <!-- <ul class="dropdown-menu" id="download-options"> -->
                                                <!-- <li><button class="dropdown-item" data-export-type="pdf">PDF</button>
                                                    </li> -->
                                                <!-- <li><button class="dropdown-item" data-export-type="csv">CSV</button>
                                                    </li> -->
                                                <!-- <li><button class="dropdown-item" data-export-type="excel">Excel</button>
                                                    </li>
                                                    <li><button class="dropdown-item" data-export-type="json">JSON</button>
                                                    </li> -->
                                                <!-- </ul> -->

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive d-none" id="myTableUhh">
                                    <div class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <table id="zero_config2" class="table dataTable">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <div id="table-info"
                                                        style="position: absolute; top: 0; left: 0; padding: 10px;">
                                                    </div>
                                                    <th scope="col">S.No</th>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Company</th>
                                                    <th scope="col">Block</th>
                                                    <th scope="col">Department</th>
                                                    <th scope="col">Level</th>
                                                    <th scope="col">Room</th>
                                                    <th scope="col">Purchase Date</th>
                                                    <th scope="col">Intent</th>
                                                    <th scope="col">PO Number</th>
                                                    <th scope="col">Invoice Number</th>
                                                    <th scope="col">Warranty</th>
                                                    <th scope="col">Unit Cost</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Product</th>
                                                    <th scope="col">Product Code</th>
                                                    <th scope="col">Model</th>
                                                    <th scope="col">Asset ID</th>
                                                    <th scope="col">CPU</th>
                                                    <th scope="col">Monitor</th>
                                                    <th scope="col">Keyboard</th>
                                                    <th scope="col">Mouse</th>
                                                    <th scope="col">Operating System</th>
                                                    <th scope="col">RAM</th>
                                                    <th scope="col">Storage Type</th>
                                                    <th scope="col">MS Office</th>
                                                    <th scope="col">OCS Status</th>
                                                    <th scope="col">Created Date</th>
                                                    <th scope="col">IP Address</th>
                                                    <th scope="col">MAC Address</th>
                                                    <th scope="col">Number of Channels</th>
                                                    <th scope="col">Scanner</th>
                                                    <th scope="col">Storage Capacity</th>
                                                    <th scope="col">Network Type</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <div class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <table class="table dataTable" id="myWonderFull">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <div id="table-info"
                                                        style="position: absolute; top: 0; left: 0; padding: 10px;">
                                                    </div>
                                                    <th scope="col">S.No</th>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Purchase Date</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Product</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                        </table>


                                    </div>
                                </div>
                            </div>
                        </div>
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
    <?php include 'footer.php'; ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>





    <!-- DataTables JavaScript -->
    <script src="assets/extra-libs/DataTables/datatables.min.js"></script>
    <script src="assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <script src="assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>


    <script>
    $(document).ready(function() {

        /****************************************
         *       Basic Table                   *
         ****************************************/
        // Full Screen button
        document.getElementById('full-screen-btn').addEventListener('click', function() {
            const table = document.getElementById('myTableUhh');
            table.classList.remove('d-none');

            if (!document.fullscreenElement && !document.webkitIsFullScreen && !document
                .msFullscreenElement) {
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
        // on exit from fullscreen
        document.addEventListener('fullscreenchange', function() {
            const table = document.getElementById('myTableUhh');
            if (!document.fullscreenElement && !document.webkitIsFullScreen && !document
                .msFullscreenElement) {
                table.classList.add('d-none');
            }
        });
        $("#exportToCSV").click(function() {
            datatable2.data().button('.buttons-csv').trigger();

        });
        // Export table to various formats
        // $('#download-options button').click(function() {
        //     var exportType = $(this).data('export-type');
        //     exportTableTo(exportType);
        // });

        // function exportTableTo(type) {
        //     switch (type) {
        //         case 'pdf':
        //             datatable2.data().button('.buttons-pdf').trigger();
        //             break;
        //         case 'csv':
        //             datatable2.data().button('.buttons-csv').trigger();
        //             break;
        //         case 'excel':
        //             datatable2.data().button('.buttons-excel').trigger();
        //             break;
        //         case 'json':
        //             table.button('.buttons-json').trigger();
        //             break;php
        //         default:
        //             break;
        //     }
        // }

    });
    </script>




    <script>
    $(document).ready(function() {
        // Initialize DataTable and store it in the window.datatable variable
        window.datatable = $('#myWonderFull').DataTable({
            // add in
            ajax: {
                url: 'table.php?min=true', // Replace with the actual URL to your backend script
                dataSrc: 'data'
            },
            columns: [{
                    title: 'S.No',
                }, {
                    title: 'id',
                    visible: false
                },
                {
                    title: 'Date'
                },
                {
                    title: 'Status'
                },
                {
                    title: 'Product'
                },
                {
                    title: 'Action',
                    orderable: false
                }
            ],



        });
        window.datatable2 = $('#zero_config2').DataTable({
            // add in
            ajax: {
                url: 'table.php?max=true', // Replace with the actual URL to your backend script
                dataSrc: 'data'
            },
            buttons: [{
                extend: 'csv',
                text: 'Download CSV',
                className: 'btn btn-primary',
                // skip last column which is the action column
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }]


        });
    });

    document.getElementById("downloadButton").addEventListener("click", function() { /////alter the csv data
        const csvData =
            "Company,Block,Departement,Level_id,Room_No,Date_of_purchace,intent_no,po_number,Invoice_no,Waranty,Unit_Cost,Status,product,product_category,Model,Assetid,cpu_serial,monitor_serial,Keyboard_serial,Mouse_serial_no,OS,RAM,Storage_type,MsOffice,OCS_STATUS,Created_Date,IP-Address,MAC_Address,NO_of_Channels,Scann(yes/No),Storage_Capacity,Network Type";
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


                        window.datatable.ajax.reload();
                        window.datatable2.ajax.reload();
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

                    } else if (response.status == 202) {
                        console.log(response);
                        swal({
                            title: "Duplicate entries found!",
                            text: "No of " + response.message + ": " + response
                                .duplicates.length,
                            icon: "warning",
                            button: "OK",
                        }).then(() => {
                            window.datatable.ajax.reload();
                            window.datatable2.ajax.reload();
                        })

                        $('#upload')[0].reset()


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


                    window.datatable.ajax.reload();
                    window.datatable2.ajax.reload();

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

    function toggleOptionalDetails() {
        const selectedCategory = document.getElementById("pcat").value;

        // Hide all optional detail sections
        document.getElementById("computerDetails").classList.remove("show");
        document.getElementById("networkDetails").classList.remove("show");
        document.getElementById("cctvDetails").classList.remove("show");
        document.getElementById("printerDetails").classList.remove("show");
        document.getElementById("networkInformation").classList.remove("show");
        document.getElementById("networkInformation").classList.remove("show");
        // document.getElementsByClassName("storage-capacity").classList.toggle("d-none");
        // Show the relevant optional detail section based on the selected category
        if (selectedCategory === "COMPUTER") {
            document.getElementById("computerDetails").classList.add("show");
            document.getElementById("networkInformation").classList.remove("show");
        } else if (selectedCategory === "NETWORK") {
            // display network info

            document.getElementById("networkInformation").classList.add("show");
            document.getElementById("networkDetails").classList.add("show");
        } else if (selectedCategory === "CCTV") {
            document.getElementById("networkInformation").classList.add("show");
            document.getElementById("cctvDetails").classList.add("show");
        } else if (selectedCategory === "PRINTER") {
            document.getElementById("networkInformation").classList.add("show");
            document.getElementById("printerDetails").classList.add("show");
        }
    }


    function closeCollapse() {
        $('#collapseExample').collapse('hide');
    }
    </script>
    <script>
    //add company //
    $(document).ready(function() {
        $("#addCompanyForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "addCompanyForm");

            $.ajax({
                url: "insertcom.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("Company saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#addCompanyForm").trigger("reset");
                        $("#companyModal").modal("hide");
                        $('#supplier-name').load(' #supplier-name > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("block not saved");
                        $("#addCompanyForm")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });
    //add block
    $(document).ready(function() {
        $("#addblockForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "addblockForm");

            $.ajax({
                url: "insertcom.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("Block saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#addblockForm").trigger("reset");
                        $("#blockmodal").modal("hide");
                        $('#block-name').load(' #block-name > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("block not saved saved");
                        $("#addblockForm")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });
    //add departement
    $(document).ready(function() {
        $("#adddprtform").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "adddprtform");

            $.ajax({
                url: "insertcom.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("departement saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#adddprtform").trigger("reset");
                        $("#departementmodal").modal("hide");
                        $('#departement-name').load(' #departement-name > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("departement not saved saved");
                        $("#adddprtform")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });
    //add os
    $(document).ready(function() {
        $("#osmodel1").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "osmodel1");

            $.ajax({
                url: "insertos.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("OS saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#osmodel1").trigger("reset");
                        $("#osmodel").modal("hide");
                        $('#oss').load(' #oss > *');

                        $('#exampleDataList').load(' #exampleDataList > *'); ///
                        $('#exampleDataList1').load(' #exampleDataList1 > *');
                        $('#exampleDataList2').load(' #exampleDataList2 > *');
                        $('#exampleDataList3').load(' #exampleDataList3 > *');
                        $('#exampleDataList5').load(' #exampleDataList5 > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("os not saved saved");
                        $("#osmodel")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });
    //end of the add os


    // add room 
    $(document).ready(function() {
        $("#addroom").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "addroom");

            $.ajax({
                url: "insertcom.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("room saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#addroom").trigger("reset");
                        $("#roomModal").modal("hide");
                        $('#room').load(' #room > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("room not saved saved");
                        $("#addroom")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });
    ////add the model
    $(document).ready(function() {
        $("#addmodel").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "addmodel");

            $.ajax({
                url: "insertcom.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("model saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#addmodel").trigger("reset");
                        $("#model").modal("hide");
                        $('#exampleDataList').load(' #exampleDataList > *'); ///
                        $('#exampleDataList1').load(' #exampleDataList1 > *');
                        $('#exampleDataList2').load(' #exampleDataList2 > *');
                        $('#exampleDataList3').load(' #exampleDataList3 > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("model not saved saved");
                        $("#addmodel")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });

    ///add the model1
    $(document).ready(function() {
        $("#addmodel1").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "addmodel1");

            $.ajax({
                url: "insertcom.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("model saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#addmodel1").trigger("reset");
                        $("#model1").modal("hide");
                        $('#exampleDataList').load(' #exampleDataList > *'); ///
                        $('#exampleDataList1').load(' #exampleDataList1 > *');
                        $('#exampleDataList2').load(' #exampleDataList2 > *');
                        $('#exampleDataList3').load(' #exampleDataList3 > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("model not saved saved");
                        $("#addmodel1")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });
    //add model2
    $(document).ready(function() {
        $("#addmodel2").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "addmodel2");

            $.ajax({
                url: "insertcom.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("model saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#addmodel2").trigger("reset");
                        $("#model2").modal("hide");
                        $('#exampleDataList').load(' #exampleDataList > *'); ///
                        $('#exampleDataList1').load(' #exampleDataList1 > *');
                        $('#exampleDataList2').load(' #exampleDataList2 > *');
                        $('#exampleDataList3').load(' #exampleDataList3 > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("model not saved saved");
                        $("#addmodel2")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });

    $(document).ready(function() {
        $("#addmodel3").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "addmodel3");

            $.ajax({
                url: "insertcom.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("model saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#addmodel3").trigger("reset");
                        $("#model3").modal("hide");
                        $('#exampleDataList').load(' #exampleDataList > *'); ///
                        $('#exampleDataList1').load(' #exampleDataList1 > *');
                        $('#exampleDataList2').load(' #exampleDataList2 > *');
                        $('#exampleDataList3').load(' #exampleDataList3 > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("model not saved saved");
                        $("#addmodel3")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });


    ////

    ////add the product
    $(document).ready(function() {
        $("#productform").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "productform");

            $.ajax({
                url: "insertcom.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);
                    if (response.status === 200) {
                        //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success("Product saved");
                        // swal("Success!", "product Placed Successfully!", "success").then(() => {
                        $("#productform").trigger("reset");
                        $("#productModal").modal("hide");
                        $('#productnames').load(' #productnames > *');

                        // });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("Product not saved saved");
                        $("#productform")[0].reset();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });

    /////insert all the forms
    $(document).ready(function() {
        $("#insert_all").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            let ele = `#storage-${formData.get("product_c")}`
            let storageValue = $(ele).val() || "";

            formData.append("Storage", storageValue)
            formData.append("action", "insert_all");
            $.ajax({
                url: "insertall1.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data); // Log the data received from the server
                    var response = JSON.parse(data);


                    if (response.status === 200) {
                        console.log(window.datatable)
                        window.datatable.ajax.reload();
                        window.datatable2.ajax.reload();
                        //alter the div accordingly//
                        //alter the div accordingly//
                        //alertify.set('notifier', 'position', 'top-right');
                        //alertify.success("Product saved");
                        swal("Success!", "Saved Successfully!", "success").then(() => {
                            $("#insert_all").trigger("reset");
                            window.datatable.ajax.reload();
                            window.datatable2.ajax.reload();


                        });
                    } else if (response.status === 201) {
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.danger("Product not saved saved");
                        $("#productform")[0].reset();
                        window.datatable.ajax.reload();
                    window.datatable2.ajax.reload();
                    }
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        });
    });

    $("#search-form").submit(function(e) {
        e.preventDefault();
        let search = $("#search-input").val();
        $.ajax({
            url: "table.php?search=" + search,
            type: "GET",
            contentType: false,
            processData: false,
            success: function(data) {
                if (data && data.length > 0) {
                    // Clear previous search results
                    $("#searchResult").empty();

                    data.forEach(function(item) {
                        var cardHtml = `
                            <div class="col-6">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="card-title">Asset ID: <span class="ml-1">${item.assetid}</span></div>
                                        <div class="card-title">Product Name: <span class="ml-1">${item.product}</span></div>
                                        <div class="card-title">Product Type: <span class="ml-1">${item.product_c}</span></div>
                                        <div class="card-title">Status: <span class="ml-1">${item.status}</span></div>
                                        <a class="ml-1 btn btn-primary" href="editasset.php?id=${item.id}">Edit Product</a>
                                    </div>
                                </div>
                            </div>
                    `;

                        $("#searchResult").append(cardHtml);
                    });

                    $("#main-content").addClass("d-none");
                    $("#searchResult").removeClass("d-none");
                    $("#searchErrorAlert").addClass("d-none");
                } else {
                    $("#searchErrorAlert").removeClass("d-none");
                    $("#searchResult").addClass("d-none");
                }
            },
            error: function(err) {
                $("#searchErrorAlert").removeClass("d-none");
                $("#searchResult").addClass("d-none");
            }
        });
    });

    $("#closeSearch").click(function() {
        $("#main-content").removeClass("d-none");
        $("#searchResult").addClass("d-none");
        $("#searchErrorAlert").addClass("d-none");
        $("#search-form")[0].reset();
    });

    $(document).ready(function() {
        $('#warranty').on('input', function() {
            // console.log('Hello')
            calculateWarrantyEndDate();
        });


        function calculateWarrantyEndDate() {
            const warranty = $('#warranty');
            const warrantyEndDate = $('#warrantyEndDate');
            const warrantyEndDateContainer = $('#warrantyEndDateContainer');
            warrantyEndDateContainer.removeClass('d-none');
            const currentDate = new Date();
            const months = parseInt(warranty.val());

            const endingDate = new Date(currentDate);
            endingDate.setMonth(currentDate.getMonth() + months);

            warrantyEndDate.text(endingDate.toDateString());
        }
    });
    </script>

</body>

</html>