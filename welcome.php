<?php
$title = 'Welcome';
include('testFunctions.php');

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function greetings($u)
{
    /* This sets the $time variable to the current hour in the 24 hour clock format */
    $time = date("H");
    /* Set the $timezone variable to become the current timezone */
    //$timezone = date("e");
    /* If the time is less than 1200 hours, show good morning */
    if ($time < 12) {
        return 'Good morning ' . $u;
    } else
        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        if ($time >= 12 && $time < 17) {
            return 'Good afternoon ' . $u;
        } else {
            /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
            //if ($time >= "17" && $time < "19") {
            return 'Good evening ' . $u;
        } //else
    /* Finally, show good night if the time is greater than or equal to 1900 hours */
    //if ($time >= "19") {
    //return "Good night, ";
    //}
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
                                        <?php echo greetings($_SESSION['user']['Name']); ?>, welcome to <?php echo getBrandByCompanyID(1); ?>!
                                    </h4>
                                    <p class="card-title-desc">Looking forward for your activity with <?php echo getBrandByCompanyID(1); ?>, here is a small getting started checklist.</p>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-8">
                            <div class="card bg-light-subtle text-dark shadow-primary card-h-100">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="bi bi-airplane widget-box-1-icon"></i>
                                    </div>
                                    <h4 class="card-title mb-3">Getting started checklist</h4>
                                    <p class="card-subtitle mb-3">Follow these steps to get up and running quickly.</p>
                                    <a href="default.php?page=uc_form" class="text-dark">
                                        <div class="d-flex mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm mx-auto">
                                                    <span class="avatar-title  bg-primary-subtle rounded-circle">
                                                        1
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong class="card-title mb-3">Submit Your First Unsafe Condition Report</strong>
                                                <p class="card-text">Create a comprehensive report detailing the unsafe condition, ensuring a thorough and accurate documentation of the incident.</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="default.php?page=ua_form" class="text-dark">
                                        <div class="d-flex mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm mx-auto">
                                                    <span class="avatar-title  bg-primary-subtle rounded-circle">
                                                        2
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong class="card-title mb-3">Submit Your First Unsafe Action Report</strong>
                                                <p class="card-text">Document the unsafe action taken, providing a detailed account of the event to facilitate a prompt and effective response.</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="default.php?page=uc_reportlist-individual" class="text-dark">
                                        <div class="d-flex mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm mx-auto">
                                                    <span class="avatar-title  bg-primary-subtle rounded-circle">
                                                        3
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong class="card-title mb-3">View Your First Unsafe Condition Report</strong>
                                                <p class="card-text">Review and verify the accuracy of your initial report submission for an unsafe condition.</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="default.php?page=ua_reportlist-individual" class="text-dark">
                                        <div class="d-flex mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm mx-auto">
                                                    <span class="avatar-title  bg-primary-subtle rounded-circle">
                                                        4
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong class="card-title mb-3">View Your First Unsafe Action Report</strong>
                                                <p class="card-text">Review and verify the accuracy of your initial report submission for an unsafe action.</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="default.php?page=reports_all" class="text-dark">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm mx-auto">
                                                    <span class="avatar-title  bg-primary-subtle rounded-circle">
                                                        5
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong class="card-title mb-3">Explore All Reports</strong>
                                                <p class="card-text">Browse through a comprehensive list of all reports to locate the specific report you wish to view.</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <div class="col-4">

                        </div>
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