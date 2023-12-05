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

            $cabinno =  strtoupper(mysqli_real_escape_string($db, $column[0]));
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
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="col-md-12 ">
                    <div class="card">
                        <div class="card-body shadow">
                            <h5 class="card-title ">Department and Lab</h5>

                            <?php
                            $sqls = "SELECT * FROM eitems";
                            $results = $db->query($sqls);

                            if ($results->num_rows > 0) {
                                // Fetch the data from the result set and assign it to $dept and $lab
                                $row = $results->fetch_assoc();
                                $dept = $row['dept'];
                                $lab = $row['lab'];
                                $block = $row['block'];

                                // Display the department and lab information in bold and red
                                echo '<strong style="color: red;">Department:</strong><b>' . $dept . "</b><br>";
                                echo '<strong style="color: red; ">Lab:</strong><b> ' . $lab . "</b><br>";
                                echo '<strong style="color: red;">Block:</strong><b> ' . $block . "</b>";
                            }
                            ?>



                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col">
                        <div class="card shadow ">
                            <div class="card-body "><!--this is on 13-09--->
                                <b>
                                    <p>Download the csv file*</p>
                                </b>

                                <button type="button" class="btn  btn-rounded btn-outline-success shadow" id="downloadButton">Download the CSV file</button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card ">

                            <div class="card-body shadow"><!--this is on 13-09--->
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
                    <div class="col-12 ">
                        <form id="tables">
                            <div class="card">
                                <div class="card-body shadow"><!--this is on 13-09--->
                                    <div class="table-responsive">
                                        <div id="zero_config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="zero_config" class="table table-striped table-bordered dataTable" role="grid" aria-describedby="zero_config_info">
                                                        <thead class="table-dark">
                                                            <tr>

                                                                <th scope="col">S.No</th>
                                                                <th scope="col">Brand</th>
                                                                <th scope="col">specification</th>
                                                                <th scope="col">cabin No</th>
                                                                <th scope="col">serial no</th>
                                                                <th scope="col">date</th>
                                                                <th scope="col">Type of the system</th>
                                                                <th scope="col"> Action</th>


                                                            </tr>
                                                        </thead>

                                                        <tbody id="table-body">
                                                            <?php


                                                            // Create a connection to the database

                                                            // Check the connection
                                                            if ($db->connect_error) {
                                                                die("Connection failed: " . $db->connect_error);
                                                            }

                                                            // Fetch all the items and then create the table rows and also include the number of items associated with each order by one-to-many relationship
                                                            $sql = "SELECT * from eitems ";
                                                            $result = $db->query($sql);
                                                            $sn = 1;
                                                            if ($result->num_rows > 0) {
                                                                // Output data of each row
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo "<tr><th scope='row'>" . $sn . "</th><td> " . $row["brand"] . "</td><td>" . $row["specs"] . "</td><td>" . $row["cabinno"] . "</td><td>" . $row["serial"] .  "</td><td>" . $row["date"] . "</td><td>" .  $row["system"] . "</td><td>
                                                                            <button onclick=\"delete_order('" . $row["serial"] . "')\" type='button' class='btn btn-rounded btn-outline-danger btn-sm delete-product shadow'>Delete</button></td></tr>";
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
                        </form>
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
                                text: "No of " + response.message + ": " + response.duplicates.length,
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
    </script>
</body>

</html>