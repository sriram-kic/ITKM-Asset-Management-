<?php
// Include the database configuration file
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $items = $_POST['items'];
    $block = $_POST['block'];
    $pname = $_POST['pname'];
    $ptype = $_POST['ptype'];
    $dept = $_POST['dept'];
    $quantity = $_POST['quantity'];
    $cabin = $_POST['cabin'];
    $reason = $_POST['reason'];

    // Prepare the SQL statement
    if ($reason !== '') {
        // If reason is not empty, include it in the SQL query
        $sql = "INSERT INTO request (items, block, pname, ptype, dept, quantity, cabin, status)
                VALUES ('$items', '$block', '$pname', '$ptype', '$dept', '$quantity', '$cabin', '$reason')";
    } else {
        // If reason is empty, exclude it from the SQL query
        $sql = "INSERT INTO request (items, block, pname, ptype, dept, quantity, cabin)
                VALUES ('$items', '$block', '$pname', '$ptype', '$dept', '$quantity', '$cabin')";
    }

    if ($db->query($sql) === TRUE) {
        // Data inserted successfully
        echo json_encode(array('status' => 'success', 'message' => 'Data inserted successfully.'));
    } else {
        // Error in SQL query
        echo json_encode(array('status' => 'error', 'message' => 'Error: ' . $sql . '<br>' . $db->error));
    }

    // Close the database connection
    $db->close();
}

// handle the delete request and it should delete the request and all the items associated with it
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if DELETE data is provided
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data && isset($data['id'])) {
        $id = $data['id'];

        // Delete the request
        $sql = "DELETE FROM request WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response = array('message' => 'Request deleted successfully.');
        } else {
            $response = array('message' => 'Failed to delete request.');
        }
        $stmt->close();
    } else {
        // Return an error response if DELETE data is missing or invalid
        http_response_code(400);
        echo json_encode(array('message' => 'Invalid data provided.'));
    }

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
                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Requests
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-hover" onclick="openChartsModal()">
                            <div class="box bg-success text-center">
                                <h1 class="font-light text-white"><i class="mdi mdi-chart-areaspline"></i></h1>
                                <h6 class="text-white">Existing items replace</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hover" onclick="openElementsModal()">
                            <div class="box bg-cyan text-center">
                                <h1 class="font-light text-white"><i class="mdi mdi-pencil"></i></h1>
                                <h6 class="text-white">New request</h6>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="modal fade" id="chartsModal" tabindex="-1" role="dialog" aria-labelledby="chartsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form id="save-req" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title" id="chartsModalLabel">Existing Items Replacement Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type">Type of request <span style="color: red;">*</span></label>
                                            <select class="form-control btn-rounded" name="items" id="type" required>
                                                <option value="">Select mode</option>
                                                <option value="Existing">Existing</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cabin">Cabin No <span style="color: red;">*</span></label>
                                            <input type="text" name="cabin" class="form-control btn-rounded" required id="cabin">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pname">Name of the Product <span style="color: red;">*</span></label>
                                            <input type="text" name="pname" id="pname" class="form-control btn-rounded" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="block">Block <span style="color: red;">*</span></label>
                                            <select class="form-control btn-rounded" name="block" id="block" required>
                                                <option value="" disabled selected>Select block</option>
                                                <?php
                                                include("config.php");
                                                $sql = "SELECT nblock FROM blocks";
                                                $result = $db->query($sql);

                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<option value="' . $row['nblock'] . '">' . $row['nblock'] . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">No blocks found</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="dept">Department <span style="color: red;">*</span></label>
                                            <select class="form-control btn-rounded" name="dept" id="dept" required>
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
                                        <div class="mb-3">
                                            <label for="ptype">Type of Product <span style="color: red;">*</span></label>
                                            <select class="form-control btn-rounded" name="ptype" id="ptype" required>
                                                <option value="">Select type</option>
                                                <option value="computer">Computer</option>
                                                <option value="monitor">Monitor</option>
                                                <option value="mouse">Mouse</option>
                                                <option value="keyboard">Keyboard</option>
                                                <option value="printer">Printer</option>
                                                <option value="speaker">Speaker</option>
                                                <option value="projector">Projector</option>
                                                <option value="router">Router</option>
                                                <option value="AC">AC</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity">No of pcs <span style="color: red;">*</span></label>
                                    <input type="number" name="quantity" class="form-control btn-rounded" required id="quantity">
                                </div>
                                <div class="mb-3">
                                    <label for="replacementReason">Reason for Replacement <span style="color: red;">*</span></label>
                                    <textarea class="form-control" name="status" id="replacementReason" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-rounded btn-danger shadow" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-rounded btn-success shadow">Submit request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>





            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <form id="save-request" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Create New Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type">Type of request <span style="color: red;">*</span></label>
                                            <select class="form-control btn-rounded" name="items" id="type" required>
                                                <option value="">Select mode</option>
                                                <option value="New">New</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cabin">Cabin No <span style="color: red;">*</span></label>
                                            <input type="text" name="cabin" class="form-control btn-rounded" required id="cabin">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pname">Name of the Product <span style="color: red;">*</span></label>
                                            <input type="text" name="pname" id="pname" class="form-control btn-rounded" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="block">Block <span style="color: red;">*</span></label>
                                            <select class="form-control btn-rounded" name="block" id="block" required>
                                                <option value="" disabled selected>Select block</option>
                                                <?php
                                                include("config.php");
                                                $sql = "SELECT nblock FROM blocks";
                                                $result = $db->query($sql);

                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<option value="' . $row['nblock'] . '">' . $row['nblock'] . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">No blocks found</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="dept">Department <span style="color: red;">*</span></label>
                                            <select class="form-control btn-rounded" name="dept" id="dept" required>
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
                                        <div class="mb-3">
                                            <label for="ptype">Type of Product <span style="color: red;">*</span></label>
                                            <select class="form-control btn-rounded" name="ptype" id="ptype" required>
                                                <option value="">Select type</option>
                                                <option value="computer">Computer</option>
                                                <option value="monitor">Monitor</option>
                                                <option value="mouse">Mouse</option>
                                                <option value="keyboard">Keyboard</option>
                                                <option value="printer">Printer</option>
                                                <option value="speaker">Speaker</option>
                                                <option value="projector">Projector</option>
                                                <option value="router">Router</option>
                                                <option value="AC">AC</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity">No of pcs <span style="color: red;">*</span></label>
                                    <input type="number" name="quantity" class="form-control btn-rounded" required id="quantity">
                                </div>
                                <div class="mb-3">
                                    <label for="replacementReason">Reason for Replacement <span style="color: red;">*</span></label>
                                    <textarea class="form-control" name="reason" id="replacementReason" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-rounded btn-danger shadow" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-rounded btn-success shadow">Submit request</button>
                            </div>
                        </form>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h5 class="card-title">Products</h5> -->

                                <div class="table-responsive">
                                    <div id="zero_config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="zero_config" class="table table-bordered dataTable" role="grid" aria-describedby="zero_config_info">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">S.No</th>
                                                            <th scope="col">items</th>
                                                            <th scope="col">Block</th>
                                                            <th scope="col">Product name</th>
                                                            <th scope="col">Product type</th>
                                                            <th scope="col">Department</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">Cabin</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="table-body">
                                                        <?php
                                                        include 'config.php';
                                                        // fetch all the items when the status is ACCEPTED_HOD OR ACCEPTED_MN OR REJECTED_MN and fetch the lab name from the facility table   
                                                        $sql = "SELECT * from request";
                                                        $result = $db->query($sql);
                                                        if ($result->num_rows > 0) {
                                                            // output data of each row
                                                            $sno = 1;
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo "<tr>";
                                                                echo "<td >" . $sno . "</td>";
                                                                echo "<td >" . $row["items"] . "</td>";
                                                                echo "<td >" . $row["block"] . "</td>";
                                                                echo "<td >" . $row["pname"] . "</td>";
                                                                echo "<td >" . $row["ptype"] . "</td>";
                                                                echo "<td >" . $row["dept"] . "</td>";
                                                                echo "<td >" . $row["quantity"] . "</td>";
                                                                echo "<td >" . $row["cabin"] . "</td>";
                                                                echo "<td >";
                                                                switch ($row["status"]) {

                                                                    case 'ACCEPTED_HOD':
                                                                        echo "<div class='text-success'>Accepted By HOD</div>";
                                                                        break;
                                                                    case 'REJECTED_HOD':
                                                                        echo "<div class='text-warning'>Rejected By HOD</div>";
                                                                        break;
                                                                    case 'REJECTED_MN':
                                                                        echo "<div class='text-danger'>Rejected By Manager</div>";
                                                                        break;
                                                                    case 'ACCEPTED_MN':
                                                                        echo "<div class='text-success'>Accepted By Manager</div>";
                                                                        break;
                                                                    case 'APPROVED':
                                                                        echo "<div class='text-success'>Approved By ITKM</div>";
                                                                        break;
                                                                    case 'REJECTED_ITKM':
                                                                        echo "<div class='text-success'>Rejected By ITKM</div>";
                                                                        break;
                                                                    case 'COMPLETED':
                                                                        echo "<div class='text-success'>Completed</div>";
                                                                        break;
                                                                    default:
                                                                        echo "<div class='text-dange'>Pending</div>";
                                                                        break;
                                                                }
                                                                echo "</td>";
                                                                $status = $row["status"];
                                                                echo "<td><button class='btn btn-sm btn-danger shadow' onclick='delete_request(" . $row["id"] . ")'><i class='mdi mdi-delete'></i>  Cancel</button></td>";

                                                                echo "</tr>";
                                                                $sno++;
                                                            }
                                                        } else {
                                                            echo "0 results";
                                                        }
                                                        $db->close();
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
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
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
            <?php include 'footer.php'; ?>
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
    <script>
        /****************************************
         *       Basic Table                   *
         ****************************************/
        $("#zero_config").DataTable();
    </script>
    <script>
        $("#save-request").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "save_request");
            $.ajax({
                url: "#",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $("#exampleModalCenter").modal("hide");
                    swal("Success!", "Request Placed Successfully!", "success").then(() => {
                        $("#table-body").load(" #table-body > *");
                        $("#save-request")[0].reset();
                    });
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");

                }
            });
        });

        $("#save-req").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append("action", "save_request");
            $.ajax({
                url: "#",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $("#chartsModal").modal("hide");
                    swal("Success!", "Request Placed Successfully!", "success").then(() => {
                        $("#table-body").load(" #table-body > *");
                        $("#save-req")[0].reset();
                    });
                },
                error: function(err) {
                    swal("Error!", "Something went wrong!", "error");

                }
            });
        });



        function delete_request(id) {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this request!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        console.log(id);

                        // Send a DELETE request to the server
                        $.ajax({
                            url: "#",
                            type: "DELETE",
                            data: JSON.stringify({
                                id
                            }),
                            success: function(result) {
                                result = JSON.parse(result);
                                // Reload the page
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
                                    text: "An error occurred while deleting the item.",
                                    icon: "error",
                                    button: "OK",
                                });
                            }
                        });
                    } else {
                        swal("Deletion cancelled!");
                    }
                });
        }

        function openChartsModal() {
            // Code to open the charts modal
            $('#chartsModal').modal('show');
        }

        function openElementsModal() {
            // Code to open the elements modal
            $('#exampleModalCenter').modal('show');
        }
    </script>



</body>

</html>