<?php
include('testFunctions.php');
$title = 'Knowledge Base';
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
                                    <p class="card-title-desc">A comprehensive collection of self-serve articles and guides to help you get started.</p>
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
                        <div class="col-lg-12">

                            <div class="row mt-5">
                                <div class="col-xl-4 col-sm-6">
                                    <a href="docs/UCUA_2.5_Guide_v3.pdf" target="_blank">
                                        <div class="card bg-light-subtle border-transparent">
                                            <div class="card-body overflow-hidden position-relative">
                                                <div>
                                                    <i class="fa-solid fa-graduation-cap widget-box-1-icon"></i>
                                                </div>
                                                <div class="faq-count">
                                                    <div class="avatar-md">
                                                        <span class="avatar-title bg-primary-subtle rounded-circle">
                                                            <i class="bi bi-mortarboard-fill font-size-20 text-primary"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <h5 class="mt-3">User Guide</h5>
                                                <p class="text-muted mt-3 mb-0">Get help using and administering UCUX in the workplace. </p>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                    </a>
                                    <!-- end card -->
                                </div>
                                <!-- end col -->

                                <div class="col-xl-4 col-sm-6">
                                    <a href="default.php?page=svideos&s=" target="_blank">
                                        <div class="card bg-light-subtle border-transparent">
                                            <div class="card-body overflow-hidden position-relative">
                                                <div>
                                                    <i class="fa-regular fa-circle-play widget-box-1-icon"></i>
                                                </div>
                                                <div class="faq-count">
                                                    <div class="avatar-md">
                                                        <span class="avatar-title bg-primary-subtle rounded-circle">
                                                            <i class="bi bi-box2-fill font-size-20 text-primary"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <h5 class="mt-3">Product demo</h5>
                                                <p class="text-muted mt-3 mb-0">Watch our short Product Demo to discover the amazing features that we offers.</p>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </a>
                                </div>
                                <!-- end col -->

                                <div class="col-xl-4 col-sm-6">
                                    <a href="../ucua300/index.php?page=login" target="_blank">
                                        <div class="card bg-light-subtle border-transparent">
                                            <div class="card-body overflow-hidden position-relative">
                                                <div>
                                                    <i class="fa-solid fa-car widget-box-1-icon"></i>
                                                </div>
                                                <div class="faq-count">
                                                    <div class="avatar-md">
                                                        <span class="avatar-title bg-primary-subtle rounded-circle">
                                                            <i class="bi bi-car-front-fill font-size-20 text-primary"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <h5 class="mt-3">
                                                    Test drive</h5>
                                                <p class="text-muted mt-3 mb-0">Take a spin and Test Drive this app to explore and experience it firsthand.</p>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </a>
                                </div>
                                <!-- end col -->

                                <div class="col-xl-4 col-sm-6">
                                    <a href="default.php?page=page&t=changelog" target="_blank">
                                        <div class="card bg-light-subtle border-transparent">
                                            <div class="card-body overflow-hidden position-relative">
                                                <div>
                                                    <i class="fa-regular fa-newspaper widget-box-1-icon"></i>
                                                </div>
                                                <h5 class="mt-3">What's new?</h5>
                                                <p class="text-muted mt-3 mb-0">Get the inside scoop on the latest updates and explore the new features.</p>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </a>
                                </div>
                                <!-- end col -->

                                <div class="col-xl-4 col-sm-6">
                                    <a href="default.php?page=page&t=changelog" target="_blank">
                                        <div class="card bg-light-subtle border-transparent">
                                            <div class="card-body overflow-hidden position-relative">
                                                <div>
                                                    <i class="fa-regular fa-newspaper widget-box-1-icon"></i>
                                                </div>
                                                <h5 class="mt-3">Changelog</h5>
                                                <p class="text-muted mt-3 mb-0">Detailed list of all changes, providing a comprehensive record of updates, fixes, & improvements.</p>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </a>
                                </div>
                                <!-- end col -->

                                <div class="col-xl-4 col-sm-6">
                                    <a href="mailto:icthelpdesk@johorport.com.my?subject=Help&body=Please help" target="_blank">
                                        <div class="card bg-light-subtle border-transparent">
                                            <div class="card-body overflow-hidden position-relative">
                                                <div>
                                                    <i class="fa-regular fa-circle-question widget-box-1-icon"></i>
                                                </div>
                                                <h5 class="mt-3">Get help</h5>
                                                <p class="text-muted mt-3 mb-0">Find answers to common questions, submit a ticket, or call our helpdesk to get the assistance you need.</p>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </a>
                                </div>
                                <!-- end col -->

                                <div class="col-xl-4 col-sm-6">
                                    <a href="mailto:icthelpdesk@johorport.com.my?subject=Feedback&body=Feedback" target="_blank">
                                        <div class="card bg-primary-subtle text-primary border-transparent">
                                            <div class="card-body overflow-hidden position-relative">
                                                <div>
                                                    <i class="fa-regular fa-envelope widget-box-1-icon"></i>
                                                </div>
                                                <h5 class="mt-3 text-primary">Send feedback</h5>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </a>
                                </div>
                                <!-- end col -->
                                <div class="col-xl-4 col-sm-6">
                                    <a href="mailto:icthelpdesk@johorport.com.my?subject=Bug&body=bug" target="_blank">
                                        <div class="card bg-primary-subtle text-primary border-transparent">
                                            <div class="card-body overflow-hidden position-relative">
                                                <div>
                                                    <i class="fa-solid fa-bug widget-box-1-icon"></i>
                                                </div>
                                                <h5 class="mt-3 text-primary">Report a bug</h5>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </a>
                                </div>
                                <!-- end col -->
                                <div class="col-xl-4 col-sm-6">
                                    <a href="mailto:icthelpdesk@johorport.com.my?subject=Request&body=request" target="_blank">
                                        <div class="card bg-primary-subtle text-primary border-transparent">
                                            <div class="card-body overflow-hidden position-relative">
                                                <div>
                                                    <i class="fa-regular fa-lightbulb widget-box-1-icon"></i>
                                                </div>
                                                <h5 class="mt-3 text-primary">Request feature</h5>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->
                                    </a>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->



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