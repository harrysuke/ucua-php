<?php
$title = 'All Reports';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
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
                                        <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                                    </h4>
                                    <p class="card-title-desc">The simplified view helps you focus on the most common tasks.</p>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Reports</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-6">
                            <div class="list-group">
                                <a href="default.php?page=uc_reportlist-individual" class="list-group-item list-group-item-action">
                                    <span class="float-start">
                                        <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                    </span>
                                    <span class="float-start">
                                        <h5>Individual unsafe condition report</h5>
                                        <p>Generate an individual report with details about unsafe conditions.</p>
                                    </span>
                                </a>
                                <a href="default.php?page=ua_reportlist-individual" class="list-group-item list-group-item-action">
                                    <span class="float-start">
                                        <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                    </span>
                                    <span class="float-start">
                                        <h5>Individual unsafe action report</h5>
                                        <p>Generate an individual report with details about unsafe action.</p>
                                    </span>
                                </a>
                                <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
                                    <a href="default.php?page=report-uc" class="list-group-item list-group-item-action">
                                        <span class="float-start">
                                            <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                        </span>
                                        <span class="float-start">
                                            <h5 class="text-truncate">Unsafe condition report</h5>
                                            <p class="text-truncate">Generate report for unsafe conditions.</p>
                                        </span>
                                    </a>
                                    <a href="default.php?page=uc_reportlist_all2" class="list-group-item list-group-item-action">
                                        <span class="float-start">
                                            <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                        </span>
                                        <span class="float-start">
                                            <h5>All unsafe condition report</h5>
                                            <p>Generate a report with details about all unsafe conditions.</p>
                                        </span>
                                    </a>
                                    <a href="default.php?page=report-ua" class="list-group-item list-group-item-action">
                                        <span class="float-start">
                                            <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                        </span>
                                        <span class="float-start">
                                            <h5>Unsafe action report</h5>
                                            <p>Generate a report with details about unsafe actions from chosen dates.</p>
                                        </span>
                                    </a>
                                    <a href="default.php?page=ua_reportlist_all" class="list-group-item list-group-item-action">
                                        <span class="float-start">
                                            <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                        </span>
                                        <span class="float-start">
                                            <h5>All unsafe actions report</h5>
                                            <p>Generate a report with details about all unsafe actions.</p>
                                        </span>
                                    </a>
                                    <a href="default.php?page=report-department" class="list-group-item list-group-item-action mb-4">
                                        <span class="float-start">
                                            <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                        </span>
                                        <span class="float-start">
                                            <h5>Department report</h5>
                                            <p>Generate a report with details by department.</p>
                                        </span>
                                    </a>
                                <?php } ?>

                            </div>
                        </div><!--End col-->
                        <div class="col-6">
                            <div class="list-group">
                                <a href="default.php?page=report_trend_analysis_offence" class="list-group-item list-group-item-action">
                                    <span class="float-start">
                                        <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                    </span>
                                    <span class="float-start">
                                        <h5>Trend analysis by Offense report</h5>
                                        <p>Generate a trend analysis report by offense.</p>
                                    </span>
                                </a>
                                <a href="default.php?page=report_trend_analysis_company" class="list-group-item list-group-item-action">
                                    <span class="float-start">
                                        <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                    </span>
                                    <span class="float-start">
                                        <h5>Trend analysis by Company report</h5>
                                        <p>Generate a trend analysis report by company.</p>
                                    </span>
                                </a>

                                <a href="default.php?page=report_trend_analysis_location" class="list-group-item list-group-item-action">
                                    <span class="float-start">
                                        <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                    </span>
                                    <span class="float-start">
                                        <h5>Trend analysis by Location report</h5>
                                        <p>Generate a trend analysis report by location.</p>
                                    </span>
                                </a>
                                <a href="default.php?page=report_trend_analysis_dept" class="list-group-item list-group-item-action">
                                    <span class="float-start">
                                        <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                    </span>
                                    <span class="float-start">
                                        <h5>Trend analysis by Department report</h5>
                                        <p>Generate a trend analysis report by department.</p>
                                    </span>
                                </a>
                                <a href="default.php?page=report_backlog" class="list-group-item list-group-item-action">
                                    <span class="float-start">
                                        <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                    </span>
                                    <span class="float-start">
                                        <h5>Open‑case backlog report</h5>
                                        <p>Generate an open‑case backlog report.</p>
                                    </span>
                                </a>
                                <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                                    <a href="default.php?page=audit-log" class="list-group-item list-group-item-action">
                                        <span class="float-start">
                                            <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                        </span>
                                        <span class="float-start">
                                            <h5>Audit logs report</h5>
                                            <p>Generate a report for audit logs.</p>
                                        </span>
                                    </a>
                                    <a href="default.php?page=audit-login" class="list-group-item list-group-item-action mb-4">
                                        <span class="float-start">
                                            <i class="bi bi-file-text me-3 align-middle fs-4"></i>
                                        </span>
                                        <span class="float-start">
                                            <h5>Login activity report</h5>
                                            <p>Generate a report for all login activities.</p>
                                        </span>
                                    </a>
                                <?php } ?>
                            </div><!--End col-->
                        </div><!--End row-->
                    </div><!-- End container-fluid -->
                </div><!--End page-content-->


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