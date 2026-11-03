<?php
include('testFunctions.php');
$title = 'Company Settings';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if (isset($_POST['btnSubmitCompany'])) {
    try {
        $companyName = $_POST['companyName'];
        $companyRegNo = $_POST['companyRegNo'];
        $groupType = $_POST['groupType'];
        $description = $_POST['description'];
        $deptInCharge = $_POST['deptInCharge'];
        $createBy = $_POST['createBy'];
        $createDate = $_POST['createDate'];
        $sql = "INSERT INTO companies (companyName, companyRegNo, groupType, description, deptInCharge, createBy, createDate) VALUES (?, ?, ?, ?, ?, ?, ?) ";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssssss", $companyName, $companyRegNo, $groupType, $description, $deptInCharge, $createBy, $createDate);
        $stmt->execute();
        $stmt->close();
        header('location: default.php?page=admin_company');
        exit;
    } catch (Exception $e) {
        die($e->getMessage());
    }
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
                                <div class="start-start">
                                    <h4 class="mb-sm-0 font-size-18">
                                        <?php echo $title; ?>
                                    </h4>
                                    <p class="text-secondary fw-light">
                                        Add, search, and manage your organizations all in one place.
                                    </p>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">
                                        <button type="button" class="btn btn-sm btn-light waves-effect waves-light"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <i class="fa-solid fa-plus"></i> New Company
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="datatable" class="table dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>No</td>
                                        <th>Company Code</th>
                                        <th>Company Name</th>
                                        <th>Company Reg No.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $query = "SELECT id, companyCode, companyName, companyRegNo FROM admcompany WHERE isDelete=0 ORDER BY companyName";
                                    $query_run = mysqli_query($db, $query);
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                    ?>
                                        <tr>
                                            <td width="5%">
                                                <?php echo $i; ?>
                                            </td>
                                            <td>
                                                <?php echo ucwords(strtolower($row["companyCode"])); ?>
                                            </td>
                                            <th>
                                                <a href="default.php?page=company&id=<?php echo base64_encode($row['id']); ?>" target="_blank">
                                                    <?php echo ucwords(strtolower($row["companyName"])); ?>
                                                </a>
                                            </th>
                                            <td>
                                                <?php echo $row["companyRegNo"]; ?>
                                            </td>
                                            <td width="10%">
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    href="default.php?page=admin_company_profile&id=<?php echo $row['id']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>&nbsp;
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    href="default.php?page=admin_company_view&id=<?php echo $row['companyCode']; ?>">
                                                    <i class="bi bi-diagram-2"></i>
                                                </a>&nbsp;
                                                <a class="btn btn-soft-danger waves-effect waves-light" name="delete_btn"
                                                    href="default.php?page=manage_company_edit&del=<?php echo $row['id']; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    } ?>
                                </tbody>
                            </table>

                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- right offcanvas -->
            <form method="post" action="default.php?page=admin_company">
                <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasRight"
                    aria-labelledby="offcanvasRightLabel">
                    <div class="offcanvas-header">
                        <h5 id="offcanvasRightLabel">New Company</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="form-group mb-3">
                            <label for="companyName" class="col-form-label">Company Name:</label>
                            <input type="text" class="form-control" name="companyName" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="companyRegNo" class="col-form-label">Company Reg.No.:</label>
                            <input type="text" class="form-control" name="companyRegNo" />
                        </div>
                        <div class="form-group mb-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">
                                Close
                            </button>
                            <button type="submit" name="btnSubmitCompany" class="btn btn-primary">
                                Save
                            </button>
                            <input type="hidden" name="groupType" value="External" />
                            <input type="hidden" name="deptInCharge" value="PSSD" />
                            <input type="hidden" name="createBy" value="<?php echo $_SESSION['user']['Name']; ?>" />
                            <input type="hidden" name="createDate" value="<?php echo date("Y-m-d h:i:s"); ?>" />
                            <input type="hidden" name="fileToUpload" value="0" />
                            <input type="hidden" name="url" value="<?php echo $fullurl; ?>" />
                        </div>
                    </div>
                </div>
            </form>
            <!-- end offcanvas -->


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

</body>

</html>