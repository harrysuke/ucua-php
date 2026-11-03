<?php
include('testFunctions.php');

$title = 'Branding';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

try {
    $b = $_SESSION['company']['brandName'];
    $sql = "SELECT id, brandName, brandDesc, brandColor, brandLogo, brandFavicon FROM branding WHERE brandName=? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $b);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $brandName = $row['brandName'];
        $brandDesc = $row['brandDesc'];
        $brandColor = $row['brandColor'];
        $brandLogo = $row['brandLogo'];
        $brandFavicon = $row['brandFavicon'];
    }
} catch (Exception $e) {
    die($e->getMessage());
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
                            <form method="post" action="admin_company_profile.php">
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Branding information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="brandName" class="form-label">Brand name</label>
                                            <input class="form-control" type="text" value="<?php echo $brandName; ?>" id="brandName">
                                        </div>
                                        <div class="mb-3">
                                            <label for="brandDesc" class="form-label">Brand description</label>
                                            <textarea class="form-control" id="brandDesc" rows="4"><?php echo $brandDesc; ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="brandColor" class="form-label">Brand color</label>
                                            <input type="color" class="form-control form-control-color mw-100" id="brandColor" value="<?php echo $brandColor; ?>" title="Choose your color">
                                        </div>
                                    </div><!-- end card-body -->
                                </div><!--end card-->
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Brand logo</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <img src="img/<?php echo $brandLogo; ?>" alt="<?php echo $brandName; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Upload logo</label>
                                            <input class="form-control" type="file" id="logo">
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Brand favicon</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <img src="img/<?php echo $brandFavicon; ?>" alt="<?php echo $brandName; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="favicon" class="form-label">Upload favicon</label>
                                            <input class="form-control" type="file" id="favicon">
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