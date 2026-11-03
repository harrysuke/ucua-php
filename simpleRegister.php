<?php
include('testFunctions.php');
$title = 'Simple register';

if (!isLoggedIn()) {
    //$_SESSION['msg'] = "You must log in first";
    //header('location: login');
}

function funDepartment(){
    global $db;
    $option = '';
    $sql1 = "SELECT * FROM dept ORDER BY deptName";
    $result1 = $db->query($sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $dept = $row1['dept'];
        $deptName = $row1['deptName'];
        $option .= "<option value='$dept'>$deptName ($dept)</option>";
    }
    return $option;
}

function funLocation()
{
    $option = '';
    global $db;
    $sql1 = "SELECT * FROM location";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $LocationId = $row1['LocationId'];
        $LocationName = $row1['LocationName'];
        $option .= "<option value='$LocationId'>$LocationName</option>";
    }
    return $option;
}

include('inc_header.php');
?>

</head>

<body class="pace-done" data-new-gr-c-s-check-loaded="8.904.0" data-gr-ext-installed="" data-sidebar="brand">
    <div class="pace pace-inactive">
        <div class="pace-progress" style="transform: translate3d(100%, 0px, 0px);" data-progress-text="100%"
            data-progress="99">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- ========== Left Navbar Start ========== -->
        <?php include('inc_navbar.php'); ?>
        <!-- Left Navbar End -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('inc_sidebar.php'); ?>
        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Wizard</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                                        <li class="breadcrumb-item active"></li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">UCUA wizard</h4>
                                    <p class="card-title-desc mb-0"></p>
                                </div>
                                <div class="card-body">

                                    <form>
                                    <div class="row gy-3">
                                    <div class="col-6">
                                       <div class="form-group">
                                          <label for="username" class="form-label">Staff ID</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="staffId" name="staffId" placeholder="Enter staff ID">
                                            </div>
                                       </div>
                                    
                                       <div class="form-group">
                                          <label for="email" class="form-label">Staff Name</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                            </div>
                                       </div>
                                       <div class="form-group">
                                          <label for="password" class="form-label">Department</label>
                                            <div class="form-control-wrap">
                                                <select id="Department" name="Department" class="form-control js-select" data-search="true" data-sort="true" required>
                                                    <?php echo funDepartment();?>
                                                </select>
                                            </div>
                                       </div>
                                    
                                    
                                      
                                    </div>
                                    <div class="col-6">
                                    
                                       <div class="form-group">
                                          <label for="password" class="form-label">Email</label>
                                            <div class="form-control-wrap">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                            </div>
                                       </div>
                                    
                                       
                                       <div class="form-group">
                                          <label for="password" class="form-label">Phone Number</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="phoneno" name="phoneno" placeholder="Enter phone number">
                                            </div>
                                       </div>
                                    
                                       
                                    
                                       <div class="form-group">
                                          <label for="password" class="form-label">Designation</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter designation">
                                            </div>
                                       </div>
                                    
                                       <div class="mt-4 float-end">
                                            <input type="hidden"  name="AccessLevel" class="form-control" value="1" readonly>
                                            <input type="hidden"  name="status" class="form-control" value="1" readonly>
                                            <button type="submit" name="register_btn" class="btn btn-primary" type="submit">Next <i class="fa-solid fa-angle-right"></i></button>
                                       </div>
                                    </div>
                                 </div>
                                        <!-- end row -->

                                        <input type="hidden" class="form-control" id="password1" name="password1" value="qwe!@#123">
                                        <input type="hidden" class="form-control" id="password2" name="password2" value="qwe!@#123">
                                    </form>
                                    <!-- end form -->
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include('inc_footer.php'); ?>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!-- Right Sidebar -->
    <?php include('inc_rightbar.php'); ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <?php include('inc_scripts.php'); ?>

    <?php
    if (!empty($_SESSION['status'])) {
        echo '<script type="text/javascript">toastr.success("' . $_SESSION['status'] . '")</script>';
        unset($_SESSION['status']);
    }
    ?>

</body>

</html>