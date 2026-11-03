<?php
include('testFunctions.php');

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if (!hasAccess('company_profile')) {
    header('Location: default.php?page=unauthorized&p=' . base64_encode($title));
    exit();
}

try {
    $id = $_GET['id'];
    $sql = "SELECT id, branchCode, branchName, branchRegNo, website, tel, fax, email, address FROM admbranch WHERE id=? OR branchCode=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $branchCode = $row['branchCode'];
        $branchName = $row['branchName'];
        $branchRegNo = $row['branchRegNo'];
        $tel = $row['tel'];
        $fax = $row['fax'];
        $email = $row['email'];
        $address = $row['address'];
        $website = $row['website'];
    }
} catch (Exception $e) {
    die($e->getMessage());
}

$title = $branchName . ' Branch';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
        <?php include('inc_sidebar_admin.php'); ?>
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
                                <div class="float-start">
                                    <h4 class="mb-sm-0 font-size-18">
                                        <?php echo $title; ?>
                                        <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($dashboard_name); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                                    </h4>
                                    <p class="text-secondary fw-light">
                                        Your role is <?php echo $_SESSION['user']['rolename']; ?>. You have all permission in the system and can fully access and manage this module.
                                    </p>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row mt-2">
                        <div class="col-8">
                            <form method="post" action="admin_branch.php">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 id="two">Branch information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="branchCode" class="form-label">Branch code</label>
                                                    <input class="form-control" type="text" value="<?php echo $branchCode; ?>" id="branchCode">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="branchName" class="form-label">Branch name</label>
                                                    <input class="form-control" type="text" value="<?php echo $branchName; ?>" id="branchName">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="branchRegNo" class="form-label">Branch reg.no.</label>
                                                    <input class="form-control" type="text" value="<?php echo $branchRegNo; ?>" id="companyRbranchRegNoegNo">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="website" class="form-label">Website</label>
                                                    <input class="form-control" type="text" value="<?php echo $website; ?>" id="website">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card-body -->
                                </div><!--end card-->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 id="three">Contact information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="tel" class="form-label">Telephone</label>
                                                    <input class="form-control" type="text" value="<?php echo $tel; ?>" id="tel">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fax" class="form-label">Fax</label>
                                                    <input class="form-control" type="text" value="<?php echo $fax; ?>" id="fax">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input class="form-control" type="text" value="<?php echo $email; ?>" id="email">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <textarea class="form-control" id="address"><?php echo $address; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer mb-4">
                                    <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>&nbsp;
                                        <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>

                        <div class="col-4"></div>
                    </div><!-- end row-->



                    <div aria-hidden="true">
                        <div class="placeholder-glow col-6">
                            <table class="table table-nowrap align-middle justify-content-center mb-0"></table>
                        </div>
                    </div>

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include('inc_footer.php'); ?>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- right offcanvas -->
    <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Create User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">


        </div>
    </div>


    <!-- Right Sidebar -->
    <?php include('inc_rightbar.php'); ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <?php include('inc_scripts.php'); ?>

</body>

</html>