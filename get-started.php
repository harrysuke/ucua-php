<?php
include('testFunctions.php');
$title = 'Getting Started';
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
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-xl-8 col-8">
                                            <div class="">
                                                <h1>
                                                    👋 Hi <?php echo $_SESSION['user']['Name']; ?>, Welcome to <?php echo $_SESSION['company']['brandName']; ?>
                                                </h1>
                                                <p class="text-muted">Ready to get started with <?php echo $_SESSION['company']['brandName']; ?>? Follow these steps to get familiar with us:</p>
                                            </div>

                                        </div>
                                        <!-- end col -->
                                        <div class="col-xl-4 col-4">
                                            <ul class="list-inline user-chat-nav text-end mb-0">
                                                <li class="list-inline-item">
												<a href="default.php?page=dashboard"><i class="bi bi-x"></i></a>
												</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- end row -->

                                    <div class="row">
                                        <div class="col-8">
                                            <div class="row">
                                                <div class="col-xl-6 col-sm-6">
                                                    <a href="default.php?page=uc_form">
                                                        <div class="card border border-primary">
                                                            <div class="card-body overflow-hidden position-relative">
                                                                <div>
                                                                    <i class="bi bi-building widget-box-1-icon text-primary"></i>
                                                                </div>
                                                                <div class="faq-count">
                                                                    <h5 class="text-primary">01.</h5>
                                                                </div>
                                                                <h5 class="mt-3">Submit Unsafe Condition</h5>
                                                                <p class="text-muted mt-3 mb-0">Create a comprehensive report detailing the unsafe condition, ensuring a thorough and accurate documentation of the incident.</p>
                                                            </div>
                                                            <!-- end card body -->
                                                        </div>
                                                        <!-- end card -->
                                                    </a>
                                                </div>
                                                <!-- end col -->
                                                <div class="col-xl-6 col-sm-6">
                                                    <a href="default.php?page=ua_form">
                                                        <div class="card border border-success">
                                                            <div class="card-body overflow-hidden position-relative">
                                                                <div>
                                                                    <i class="bi bi-person-walking widget-box-1-icon text-primary"></i>
                                                                </div>
                                                                <div class="faq-count">
                                                                    <h5 class="text-primary">02.</h5>
                                                                </div>
                                                                <h5 class="mt-3">Submit Unsafe Action</h5>
                                                                <p class="text-muted mt-3 mb-0">Document the unsafe action taken, providing a detailed account of the event to facilitate a prompt and effective response.</p>
                                                            </div>
                                                            <!-- end card body -->
                                                        </div>
                                                        <!-- end card -->
                                                    </a>
                                                </div>
                                                <!-- end col -->

                                                <div class="col-xl-6 col-sm-6">
                                                    <a href="default.php?page=uc_reportlist-individual">
                                                        <div class="card">
                                                            <div class="card-body overflow-hidden position-relative">
                                                                <div>
                                                                    <i class="bi bi-printer widget-box-1-icon text-primary"></i>
                                                                </div>
                                                                <div class="faq-count">
                                                                    <h5 class="text-primary">03.</h5>
                                                                </div>
                                                                <h5 class="mt-3">View Unsafe Condition Report</h5>
                                                                <p class="text-muted mt-3 mb-0">Review and verify the accuracy of your initial report submission for an unsafe condition.</p>
                                                            </div>
                                                            <!-- end card body -->
                                                        </div>
                                                        <!-- end card -->
                                                    </a>
                                                </div>
                                                <!-- end col -->

                                                <div class="col-xl-6 col-sm-6">
                                                    <a href="default.php?page=ua_reportlist-individual">
                                                        <div class="card">
                                                            <div class="card-body overflow-hidden position-relative">
                                                                <div>
                                                                    <i class="bi bi-printer widget-box-1-icon text-primary"></i>
                                                                </div>
                                                                <div class="faq-count">
                                                                    <h5 class="text-primary">04.</h5>
                                                                </div>
                                                                <h5 class="mt-3">View Unsafe Action Report</h5>
                                                                <p class="text-muted mt-3 mb-0">Review and verify the accuracy of your initial report submission for an unsafe action.</p>
                                                            </div>
                                                            <!-- end card body -->
                                                        </div>
                                                        <!-- end card -->
                                                    </a>
                                                </div>
                                                <!-- end col -->




                                            </div>
                                            <!-- end row -->
                                            <div class="row">
                                                <div class="col-12">

                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-body overflow-hidden position-relative">
                                                    <div>
                                                        <i class="bi bi-link-45deg widget-box-1-icon"></i>
                                                    </div>
                                                    <h4 class="card-title mb-3">Useful Links</h4>
                                                    <div class="d-flex mb-3">
                                                        <div class="flex-shrink-0 me-3">
                                                            <i class="fa-solid fa-book-open"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6><a href="docs/UCUA_2.5_Guide_v3.pdf" target="_blank"
                                                                    class="fw-bolder text-dark">Getting Started</a></h6>
                                                            <p class="card-text">Check out our user guide for detailed
                                                                instructions, tips, and how-tos.</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex mb-3">
                                                        <div class="flex-shrink-0 me-3">
                                                            <i class="fa-brands fa-youtube"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6><a href="default.php?page=svideos&s="
                                                                    class="fw-bolder text-dark">Product Demo</a></h6>
                                                            <p class="card-text">Watch our short demo to discover the amazing
                                                                features that we offers!
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <i class="fa-solid fa-car-side"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6><a href="../ucua300/index.php?page=login"
                                                                    class="fw-bolder text-dark">Test Drive</a></h6>
                                                            <p class="card-text">Take a spin and test drive this app to explore and
                                                                experience it firsthand.
                                                            </p>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <!-- end card -->


                                        </div>
                                    </div>


                                </div>
                                <!-- end  card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
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