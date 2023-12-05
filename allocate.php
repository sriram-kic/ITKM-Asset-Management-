<?php
// Include the database configuration file (config.php)
require_once('config.php');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form data (add validation/sanitization as needed)
    $supplier = $_POST['suppliern'];
    $saddress = $_POST['supp_address'];
    $indentn = $_POST['indent_no'];
    $indentdate = $_POST['indent_date'];
    $orderno = $_POST['order_no'];
    $orderdate = $_POST['order_date'];
    $invoicen = $_POST['invoice_no'];
    $invoiced = $_POST['invoice_date'];
    $material = $_POST['material_name'];
    $quantity = $_POST['quantity'];
    $value = $_POST['value'];
    $gst = $_POST['gst'];
    $remarks = $_POST['remarks'];
    $brand = $_POST['brand'];

    // Handle receipt file
    $uploadDir = 'uploads/';
    $fileName = str_replace(' ', '', basename($_FILES['receipt']['name']));
    $uploadFile = $uploadDir . basename(uniqid() . $fileName);

    if (move_uploaded_file($_FILES['receipt']['tmp_name'], $uploadFile)) {
        // File uploaded successfully
        $receiptPath = $uploadFile;

        // Insert data into the database using prepared statements
        $sql = "INSERT INTO orders (suppliern, supp_address, indent_no, indent_date, order_no, order_date, invoice_no, invoice_date, material_name, quantity, value, gst, remarks, receipt, brand) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('sssssssssisssss', $supplier, $saddress, $indentn, $indentdate, $orderno, $orderdate, $invoicen, $invoiced, $material, $quantity, $value, $gst, $remarks, $receiptPath, $brand);

        if ($stmt->execute()) {
            $res = [
                'status' => 200,
                'message' => 'Data inserted successfully'
            ];
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 201,
                'message' => 'Failed to insert data'
            ];
            echo json_encode($res);
            return;
        }

        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if DELETE data is provided
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data && isset($data['id'])) {
        $id = $data['id'];

        // Delete the order
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Delete the items associated with the order
            $sql = "DELETE FROM orders WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $response = ['status' => 'success', 'message' => 'Order deleted successfully.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to delete items.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to delete order.'];
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
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">Orders</h4>

                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Library
                                    </li>
                                </ol>
                            </nav>
                            <div class="border-top">
                                <div class="card-body">
                                    <button type="button" class="btn  btn-rounded btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                        Add Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <form id="save-order" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Add New Order</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Left Column -->
                                        <div class="mb-3">
                                            <label for="supplier-name">Supplier Name*</label>
                                            <input type="text" name="suppliern" id="supplier-name" class="form-control btn-rounded" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="iname">supplier_address*</label>
                                            <input type="text" name="supp_address" id="supplier_address" class="form-control  btn-rounded" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="indent_no">Indent_no*</label>
                                            <input type="text" name="indent_no" id="supplier_address" class="form-control  btn-rounded" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="indent_date">Indent_Date*</label>
                                            <input type="date" name="indent_date" id="supplier_address" class="form-control  btn-rounded" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="invoice_no">Invoice_No*</label>
                                            <input type="text" name="invoice_no" id="supplier_address" class="form-control  btn-rounded" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="invoice_date">Invoice_date*</label>
                                            <input type="date" name="invoice_date" id="supplier_address" class="form-control  btn-rounded" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Right Column -->
                                        <div class="mb-3">
                                            <label for="order_no">Order_no*</label>
                                            <input type="text" name="order_no" class="form-control  btn-rounded" required id="orderno">
                                        </div>
                                        <div class="mb-3">
                                            <label for="order_date">Order_date*</label>
                                            <input type="date" name="order_date" class="form-control  btn-rounded" required id="orderdate">
                                        </div>

                                        <div class="mb-3">
                                            <label for="valye">per_unit_cost*</label>
                                            <input class="form-control  btn-rounded" type="text" name="value" id="quantity" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="valye">GST*</label>
                                            <input class="form-control  btn-rounded" type="text" name="gst" id="quantity" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="receipt">Receipt File:</label>
                                            <input class="form-control  btn-rounded" type="file" id="receipt" name="receipt" required accept=".pdf, image/*" />
                                        </div>


                                    </div>
                                </div>
                                <div class=row>
                                    <div class=col-md-4>
                                        <div class="mb-3">
                                            <label for="type">Type of Product*</label>
                                            <select class="form-control  btn-rounded" name="material_name" id="type" required>
                                                <option value="">Select type</option>
                                                <option value="computer">Computer</option>
                                                <option value="keyboard">keyboard</option>
                                                <option value="printer">printer</option>
                                                <option value="desktop">desktop</option>

                                                <!-- Add other options here -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class=col-md-4>
                                        <div class="mb-3">
                                            <label for="quantity">Quantity*</label>
                                            <input min="1" class="form-control  btn-rounded" type="number" name="quantity" id="quantity" required>
                                        </div>
                                    </div>
                                    <div class=col-md-4>
                                        <div class="mb-3">
                                            <label for="valye">Brand*</label>
                                            <input class="form-control  btn-rounded" type="text" name="brand" id="quantitys" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <label for="remarks">Remark*</label>
                                    <textarea class="form-control  btn-rounded" id="remarks" name="remarks" required></textarea>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-rounded btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-rounded btn-primary">Save changes</button>
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
                                                <table id="zero_config" class="table table-striped table-bordered dataTable" role="grid" aria-describedby="zero_config_info">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">S.No</th>
                                                            <th scope="col">Supplier Name</th>
                                                            <th scope="col">Indent No</th>
                                                            <th scope="col">Indent Date</th>
                                                            <th scope="col">Order No</th>
                                                            <th scope="col">Order Date</th>
                                                            <th scope="col">Invoice No</th>
                                                            <th scope="col">Invoice Date</th>
                                                            <th scope="col">Material Name</th>
                                                            <th scope="col">Brand</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">Value</th>
                                                            <th scope="col">GST</th>
                                                            <th scope="col">Remarks</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="table-body">
                                                        <?php
                                                        include 'config.php';
                                                        if ($db->connect_error) {
                                                            die("Connection failed: " . $db->connect_error);
                                                        }

                                                        // Fetch data from the "orders" table
                                                        $sql = "SELECT * FROM orders";
                                                        $result = $db->query($sql);
                                                        $sn = 1;
                                                        if ($result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo "<tr>";
                                                                echo "<th scope='row'>" . $sn . "</th>";
                                                                echo "<td>" . $row["suppliern"] . "</td>";
                                                                echo "<td>" . $row["indent_no"] . "</td>";
                                                                echo "<td>" . $row["indent_date"] . "</td>";
                                                                echo "<td>" . $row["order_no"] . "</td>";
                                                                echo "<td>" . $row["order_date"] . "</td>";
                                                                echo "<td>" . $row["invoice_no"] . "</td>";
                                                                echo "<td>" . $row["invoice_date"] . "</td>";
                                                                echo "<td>" . $row["material_name"] . "</td>";
                                                                echo "<td>" . $row["brand"] . "</td>";
                                                                echo "<td>" . $row["quantity"] . "</td>";
                                                                echo "<td>" . $row["value"] . "</td>";
                                                                echo "<td>" . $row["gst"] . "</td>";
                                                                echo "<td>" . $row["remarks"] . "</td>";
                                                                echo "<td>";
                                                                echo "<a href='order.php ? id=" . $row["id"] . "' class='btn btn-sm btn-rounded btn-primary'> Show </a>"; //add the order.php
                                                                echo "<button onclick='delete_order(" . $row["id"] . ")' type='button' class='btn  btn-rounded  btn-sm btn-danger  delete-product'>Delete</button>";
                                                                echo "</td>";
                                                                echo "</tr>";
                                                                $sn++;
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
    <script>
        /****************************************
         *       Basic Table                   *
         ****************************************/
        $("#zero_config").DataTable();
    </script>
    <script>
        $(document).ready(function() {
            $("#save-order").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: '#', // Replace with the actual URL to your PHP script
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res) {
                        console.log(res);
                        if (res.status == 200) {
                            $("#exampleModalCenter").modal("hide");
                            swal("Success!", res.message, "success").then(() => {
                                // Refresh the table or perform any other necessa4ry actions
                                $("#table-body").load(" #table-body > *");
                                $("#save-order")[0].reset();

                            });
                        } else {
                            swal("Error!", res.message, "error");
                            $("#exampleModalCenter").modal("hide");
                            $("#save-order")[0].reset();
                            // Handle error or refresh the table
                        }
                    },
                    error: function(err) {
                        swal("Error!", "Something went wrong!", "error");
                        $("#exampleModalCenter").modal("hide");
                        // Handle error or refresh the table
                    }
                });
            });
        });


        function delete_order(id) {
            // ask the user if they are sure they want to delete the order
            const res = confirm("Are you sure you want to delete this order?");
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
    </script>
</body>

</html>