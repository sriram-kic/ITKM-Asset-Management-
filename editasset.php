<?php
// Include the database configuration file (config.php)
require_once('config.php');


$id = $_GET['id'];
if (!isset($_GET['id']) || empty($_GET['id'])) {

    echo "<script>alert('No asset ID provided.');window.location.href = '/'</script>";
}

// fetch a particular asset details from the database based on the asset id
$sql = "SELECT * FROM eitems WHERE id = '$id'";
$result = $db->query($sql);

$data_eitem = $result->fetch_assoc();
echo "<script>console.log('" . json_encode($data_eitem) . "')</script>";

$assetid = $data_eitem['assetid'];
$company = $data_eitem['company'];
$block = $data_eitem['block'];
$deprt = $data_eitem['deprt'];
$nlevel = $data_eitem['nlevel'];
$nroom = $data_eitem['nroom'];
$pdate = $data_eitem['pdate'];
$nintent = $data_eitem['nintent'];
$po_n = $data_eitem['po_n'];
$invoice_no = $data_eitem['invoice_no'];
$waranty = $data_eitem['waranty'];
$unit_cost = $data_eitem['unit_cost'];
$status = $data_eitem['status'];
$product = $data_eitem['product'];
$product_c = $data_eitem['product_c'];
$model = $data_eitem['model'];
$cpu_s = $data_eitem['cpu_s'];
$monitor_s = $data_eitem['monitor_s'];
$keyb_s = $data_eitem['keyb_s'];
$Mouse_s = $data_eitem['Mouse_s'];
$os = $data_eitem['os'];
$ram = $data_eitem['ram'];
$storagetype = $data_eitem['storagetype'];
$msoffice = $data_eitem['msoffice'];
$ocssts = $data_eitem['ocssts'];
$created_date = $data_eitem['created_date'];
$ip = $data_eitem['ip'];
$mac = $data_eitem['mac'];
echo "<script>console.log('" . $ip . "')</script>";
$nchannels = $data_eitem['nchannels'];
$scann = $data_eitem['scann'];
$Storage = $data_eitem['Storage'];
$net_type = $data_eitem['net_type'];



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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css">
    <link rel="stylesheet" type="text/css" href="assets/extra-libs/multicheck/multicheck.css" />
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet" />
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
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i> My Balance</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-email m-r-5 m-l-5"></i> Inbox</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-settings m-r-5 m-l-5"></i> Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                                <div class="dropdown-divider"></div>
                                <div class="p-l-30 p-10">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-success btn-rounded">View Profile</a>
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
                <a href="assets.php" class="btn btn-primary mb-4"><i class="mdi mdi-chevron-left"></i> Go back</a>
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">Edit</h4>

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
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form id="editassert">
                                        <!-- Left column content here -->
                                        <div class="edit content">
                                            <div class="form-group row">
                                                <label for="company" class="col-sm-4 col-form-label">Company</label>
                                                <div class="col-sm-8" id="addcomp">
                                                    <select name="company" id="supplier-name" class="form-control w-100">
                                                        <option value="" disabled selected>Select company name</option>
                                                        <?php
                                                        include("config.php");

                                                        $sql = "SELECT DISTINCT ncompany FROM company";
                                                        $result = $db->query($sql);

                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo '<option value="' . $row['ncompany'] . '" ' . ($row['ncompany'] == $company ? "selected" : "") . '>' . $row['ncompany'] . '</option>';
                                                            }
                                                        } else {
                                                            echo "0 results";
                                                        }
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- edit comp -->
                                        <div class="form-group row">
                                            <label for="dept" class="col-sm-4 col-form-label">Department</label>
                                            <div class="col-sm-8">
                                                <select name="deprt" id="departement-name" class="form-control w-100">
                                                    <option value="" disabled selected>Select a departement</option>
                                                    <?php
                                                    include("config.php");

                                                    $sql = "SELECT DISTINCT ndept FROM departement";
                                                    $result = $db->query($sql);

                                                    if ($result && $result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo '<option value="' . $row['ndept'] . '" ' . ($row['ndept'] == $deprt ? "selected" : "") . '>' . $row['ndept'] . '</option>';
                                                        }
                                                    } else {
                                                        echo "0 results";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                        <!-- edit departement -->

                                        <!-- edit room -->

                                        <div class="form-group row">
                                            <label for="room" class="col-sm-4 col-form-label ">Room</label>
                                            <div class="col-sm-8">
                                                <select name="nroom" id="room" class="form-control w-100">
                                                    <option value="" disabled selected>Select a Room</option>
                                                    <?php
                                                    include("config.php");

                                                    $sql = "SELECT DISTINCT nroom FROM rooms";
                                                    $result = $db->query($sql);

                                                    if ($result && $result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo '<option value="' . $row['nroom'] . '" ' . ($row['nroom'] == $nroom ? "selected" : "") . '>' . $row['nroom'] . '</option>';
                                                        }
                                                    } else {
                                                        echo "0 results";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>

                                </div>

                                <div class="col-md-6">
                                    <!-- Right column content here -->

                                    <div class="form-group row">
                                        <label for="block" class="col-sm-2 col-form-label">Block</label>
                                        <div class="col-sm-8">
                                            <select name="block" id="block-name" class="form-control w-100">
                                                <option value="" disabled selected>Select a block</option>
                                                <?php
                                                include("config.php");

                                                $sql = "SELECT DISTINCT nblock FROM blocks";
                                                $result = $db->query($sql);

                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<option value="' . $row['nblock'] . '" ' . ($row['nblock'] == $block ? "selected" : "") . '>' . $row['nblock'] . '</option>';
                                                    }
                                                } else {
                                                    echo "0 results";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                    <!-- level-id -->
                                    <div class="form-group row">
                                        <label for="nlevel" class="col-sm-2 col-form-label">Level ID</label>
                                        <div class="col-sm-8">
                                            <select name="nlevel" id="level" class="form-control w-100">
                                                <option value="" disabled selected>Select level id</option>
                                                <option value="L0" <?php echo ($nlevel == "L0") ? "selected" : ""; ?>>L0</option>
                                                <option value="L1" <?php echo ($nlevel == "L1") ? "selected" : ""; ?>>L1</option>
                                                <option value="L2" <?php echo ($nlevel == "L2") ? "selected" : ""; ?>>L2</option>
                                                <option value="L3" <?php echo ($nlevel == "L3") ? "selected" : ""; ?>>L3</option>
                                                <option value="L4" <?php echo ($nlevel == "L4") ? "selected" : ""; ?>>L4</option>
                                            </select>

                                        </div>
                                    </div>

                                </div>

                            </div>
                            <hr>

                            <b>Order Details</b>
                            <div class="row">
                                <div class="col-md-6">
                                    <br>
                                    <!-- left column-1 -->
                                    <div class="form-group row  ">
                                        <label for="dateOfPurchase" class="col-sm-4  col-form-label">Date
                                            Of Purchase</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control " id="dateOfPurchase" name="pdate" placeholder="Date Of Purchase" value="<?php echo $pdate ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="poNumber" class="col-sm-4 col-form-label">PO
                                            Number</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " id="poNumber" name="po_n" value="<?php echo $po_n ?>" placeholder="PO Number">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="status" class="col-sm-4 col-form-label">Status</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="status" name="status">
                                                <option value="">Set Status</option>
                                                <option value="Active" <?php echo ($status == "Active") ? "selected" : ""; ?>>Active</option>
                                                <option value="Inactive" <?php echo ($status == "Inactive") ? "selected" : ""; ?>>Inactive</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label for="warranty" class="col-sm-4 col-form-label">Warranty</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="warranty" name="waranty" placeholder="Warranty" min="1" value="<?php echo $waranty ?>">
                                        </div>

                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <!-- right column-1 -->
                                    <br>
                                    <div class="form-group row">
                                        <label for="intentNo" class="col-sm-4  col-form-label">Intent
                                            No</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " id="intentNo" name="nintent" value="<?php echo $nintent ?>" placeholder="Intent No">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="invoiceNo" class="col-sm-4 col-form-label">Invoice
                                            No</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " id="invoiceNo" name="invoice_no" value="<?php echo $invoice_no ?>" placeholder="Invoice No">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="unitCost" class="col-sm-4 col-form-label">Unit
                                            Cost(With GST)</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control " id="unitCost" name="unit_cost" value="<?php echo $unit_cost ?>" placeholder="Unit Cost(With GST)">
                                        </div>
                                    </div>
                                    <span class=" col-sm-8 d-none " id="warrantyEndDateContainer"><b>Warranty Date:</b> <span id="warrantyEndDate"></span></span>

                                </div>



                            </div>
                            <hr>
                            <b>Machine Details</b>
                            <div class="row">
                                <div class="col-6">
                                    <br>
                                    <!-- left column-2-->

                                    <div class="form-group row">
                                        <label for="pd" class="col-sm-4 col-form-label">Product</label>
                                        <div class="col-sm-8">
                                            <select id="productnames" name="product" class="form-control w-100">
                                                <option value="" disabled selected>Select product</option>
                                                <?php
                                                include("config.php");

                                                $sql = "SELECT  productname FROM products";
                                                $result = $db->query($sql);

                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // echo '<option value="' . $row['productname'] . '">' . $row['productname'] . '</option>';
                                                        echo '<option value="' . $row['productname'] . '" ' . ($row['productname'] == $product ? "selected" : "") . '>' . $row['productname'] . '</option>';
                                                    }
                                                } else {
                                                    echo "0 results";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label for="assetID" class="col-sm-4 col-form-label">Asset ID</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="assetID" name="assetid" value="<?php echo $assetid ?>" placeholder="Asset ID">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-6">
                                    <!-- right column-2 -->
                                    <br>
                                    <div class="form-group row">
                                        <label for="pcat" class="col-sm-4 col-form-label">Product
                                            category</label>
                                        <div class="col-sm-8">

                                            <select class="form-control" id="pcat" name="product_c" onchange="toggleOptionalDetails()" disabled>
                                                <option value="" disabled selected>Select product category</option>
                                                <option value="Computer" <?php echo ($product_c == "Computer") ? "selected" : ""; ?>>COMPUTER</option>
                                                <option value="Network" <?php echo ($product_c == "Network") ? "selected" : ""; ?>>NETWORK</option>
                                                <option value="CCTV" <?php echo ($product_c == "CCTV") ? "selected" : ""; ?>>CCTV</option>
                                                <option value="Printer" <?php echo ($product_c == "Printer") ? "selected" : ""; ?>>PRINTER</option>
                                            </select>

                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label for="model" class="col-sm-4 col-form-label">Model</label>
                                        <div class="form-group col-sm-8">
                                            <select class="form-control" id="exampleDataList" name="model">
                                                <option value="" disabled selected>Select a Model</option>
                                                <?php
                                                include("config.php");

                                                $sql = "SELECT DISTINCT nmodel FROM model";
                                                $result = $db->query($sql);

                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<option value="' . $row['nmodel'] . '" ' . ($row['nmodel'] == $model ? "selected" : "") . '>' . $row['nmodel'] . '</option>';
                                                    }
                                                } else {
                                                    echo "0 results";
                                                }
                                                ?>
                                            </select>
                                        </div>


                                    </div>

                                </div>

                            </div>
                            <hr>
                            <?php if ($product_c == "COMPUTER") : ?>
                                <b>Computer details</b>
                                <div class="row">
                                    <div class="col-6">
                                        <!-- left align-2 -->
                                        <br>
                                        <div class="form-group row">
                                            <label for="cpu" class="col-sm-4 col-form-label">CPU Serial
                                                No</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="cpuSerialNo" value="<?php echo $cpu_s ?>" name="cpu_s" placeholder="CPU Serial No">
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label for="keyboard" class="col-sm-4 col-form-label">Keyboard
                                                Serial No</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="keyboardSerialNo" value="<?php echo $keyb_s ?>" name="keyb_s" placeholder="Keyboard Serial No">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="os" class="col-sm-4 col-form-label">OS</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="os" value="<?php echo $os ?>" name="os" placeholder="OS">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="storageType" class="col-sm-4 col-form-label">Storage</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="storageType" name="storagetype">
                                                    <option value="HDD" <?php echo ($storagetype == "HDD") ? "selected" : ""; ?>>HDD</option>
                                                    <option value="SSD" <?php echo ($storagetype == "SSD") ? "selected" : ""; ?>>SSD</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="msOffice" class="col-sm-4 col-form-label">MS
                                                Office</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="msoffice" value="<?php echo $msoffice ?>" id="msOffice" placeholder="MS Office">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="createdDate" class="col-sm-4 col-form-label">Created
                                                Date</label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control" id="createdDate" name="created_date" value="<?php echo $created_date ?>" placeholder="Created Date">
                                            </div>
                                        </div>

                                    </div>



                                    <div class="col-6">
                                        <!-- right align-2 -->
                                        <br>
                                        <div class="form-group row">
                                            <label for="monitor" class="col-sm-4 col-form-label">Monitor
                                                Serial No</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="monitorSerialNo" value="<?php echo $monitor_s ?>" name="monitor_s" placeholder="Monitor Serial No">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="mouse" class="col-sm-4 col-form-label">Mouse Serial
                                                No</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="mouseSerialNo" value="<?php echo $Mouse_s ?>" name="Mouse_s" placeholder="Mouse Serial No">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="ram" class="col-sm-4 col-form-label">RAM</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="ram" value="<?php echo $ram ?>" name="ram" placeholder="RAM">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="storageCapacity" class="col-sm-4 col-form-label">Storage Capacity</label>
                                            <div class="col-sm-8">
                                                <select class="form-control storage-capacity" id="storageCapacity-Computer" name="storageCapacity">
                                                    <option value="128GB" <?php echo ($Storage == "128GB") ? "selected" : ""; ?>>128GB</option>
                                                    <option value="256GB" <?php echo ($Storage == "256GB") ? "selected" : ""; ?>>256GB</option>
                                                    <option value="512GB" <?php echo ($Storage == "512GB") ? "selected" : ""; ?>>512GB</option>
                                                    <option value="1TB" <?php echo ($Storage == "1TB") ? "selected" : ""; ?>>1TB</option>
                                                    <option value="2TB" <?php echo ($Storage == "2TB") ? "selected" : ""; ?>>2TB</option>
                                                    <option value="3TB" <?php echo ($Storage == "3TB") ? "selected" : ""; ?>>3TB</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="ocsStatus" class="col-sm-4 col-form-label">OCS
                                                Status (Yes/No)</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="ocsStatus" name="ocssts" value="<?php echo $ocssts ?>" placeholder="OCS Status">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            <?php endif; ?>
                            <div class="row">
                                <?php if ($product_c == "NETWORK") : ?>
                                    <div class="col-6">
                                        <!-- left contaner-network -->
                                        <b>Network Details</b>
                                        <br>
                                        <div class="form-group row">
                                            <label for="type" class="col-sm-4 col-form-label">Type</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="type" name="net_type">
                                                    <option value="" <?php echo ($net_type == "") ? "selected" : ""; ?>>Select Type</option>
                                                    <option value="Normal" <?php echo ($net_type == "Normal") ? "selected" : ""; ?>>Normal</option>
                                                    <option value="SFP" <?php echo ($net_type == "SFP") ? "selected" : ""; ?>>SFP</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($product_c == "CCTV") : ?>
                                    <div class="col-6">
                                        <b>CCTV Details</b>
                                        <br>

                                        <!-- right container-network -->
                                        <div class="form-group row">
                                            <label for="noOfChannel" class="col-sm-4 col-form-label">No Of
                                                Channel</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control " name="nchannels" value="<?php echo $nchannels ?>" id="noOfChannel" placeholder="No Of Channel">
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label for="storageCapacity" class="col-sm-4 col-form-label">Storage Capacity</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control storage-capacity" value="<?php echo $Storage ?>" id="storageCapacity-CCTV" placeholder="Storage Capacity">
                                            </div>
                                        </div>


                                    </div>
                                <?php endif; ?>
                            </div>
                                   
                            <?php if ($product_c == "PRINTER") : ?>
                                <hr>
                                <b>Printer details</b>
                                <br>
                                <div class="row">
                                    <div class="col-6">
                                        <!-- left-printer -->

                                        <div class="form-group row">
                                            <label for="scann" class="col-sm-4 col-form-label">Scann
                                                (Yes/No)</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="scann" name="scann">
                                                    <option value="Yes" <?php echo ($scann == "Yes") ? "selected" : ""; ?>>Yes</option>
                                                    <option value="No" <?php echo ($scann == "No") ? "selected" : ""; ?>>No</option>
                                                </select>
                       
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-6">
                                        <!-- right-printer -->
                                        <div class="form-group row">
                                            <label for="storageCapacity" class="col-sm-4 col-form-label">Storage Capacity</label>
                                            <div class="col-sm-8">

                                                <input type="text" class="form-control storage-capacity" value="<?php echo $Storage ?>" id="storageCapacity-Printer" placeholder="Storage Capacity">
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <hr>
                            <?php endif; ?>
                            <b>Ip & Mac Address</b>
                            <br>

                            <div class="row">
                                <div class="col-6">
                                    <!-- ip left -->
                                    <div class="form-group row">
                                        <label for="ipAddress" class="col-sm-4 col-form-label ">IP
                                            Address</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " id="ipAddress" value="<?php echo $ip ?>" name="ip" placeholder="IP Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <!-- mac right -->
                                    <div class="form-group row">
                                        <label for="macAddress" class="col-sm-4 col-form-label">MAC
                                            Address</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " id="macAddress" value="<?php echo $mac ?>" name="mac" placeholder="MAC Address">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="text-right">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>

                        </div>
                    </div>


                    <form>
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
    <script src="assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="assets/extra-libs/DataTables/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js" type="text/javascript"></script>
    <script>
        /****************************************
         *       Basic Table                   *
         ****************************************/
        $("#zero_config").DataTable();
    </script>

    <script>
        $(document).ready(function() {
            $("#editassert").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append("id", "<?php echo $id ?>");
                formData.append("action", "editassert");

                $.ajax({
                    url: "editpro.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data); // Log the data received from the server
                        var response = JSON.parse(data);
                        if (response.status === 200) {
                            //$("#table-bodyss").load(" #table-bodyss > *"); //alter the div accordingly//
                            swal("Success!", "Asset edited successfully!", "success").then((value) => {
                                window.location.href = "assets.php";
                            });
                        } else if (response.status === 201) {
                            swal("Error!", "Something went wrong!", "error");
                        }
                    },
                    error: function(err) {
                        swal("Error!", "Something went wrong!", "error");
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#warranty').on('input', function() {
                console.log('Hello')
                calculateWarrantyEndDate();
            });
            calculateWarrantyEndDate()

            function calculateWarrantyEndDate() {
                const warranty = $('#warranty');
                const warrantyEndDate = $('#warrantyEndDate');
                const warrantyEndDateContainer = $('#warrantyEndDateContainer');
                warrantyEndDateContainer.removeClass('d-none');
                const currentDate = new Date();
                const months = parseInt(warranty.val());

                const endingDate = new Date(currentDate);
                endingDate.setMonth(currentDate.getMonth() + months);

                warrantyEndDate.text(endingDate.toLocaleDateString());
            }
        });
    </script>

</body>

</html>