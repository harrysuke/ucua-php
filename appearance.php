<?php
$title = 'Appearance';
include('testFunctions.php');

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
                                    </h4>
                                    <p class="card-title-desc">Manage settings for UI appearance.</p>
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

                    <div class="row">
                        <div class="col-8">

                            <div class="card">
                                <div class="card-header">
                                    Appearance
                                </div>
                                <div class="card-body">
                                    <h6 class="mb-3 pt-2">Layout Mode</h6>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="layout-mode"
                                            id="layout-mode-light" value="light">
                                        <label class="form-check-label" for="layout-mode-light">Light</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="layout-mode"
                                            id="layout-mode-dark" value="dark">
                                        <label class="form-check-label" for="layout-mode-dark">Dark</label>
                                    </div>

                                    <h6 class="mt-4 mb-3 pt-2">Layout Width</h6>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="layout-width"
                                            id="layout-width-fuild" value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                                        <label class="form-check-label" for="layout-width-fuild">Fluid</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="layout-width"
                                            id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                                        <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                                    </div>

                                    <h6 class="mt-4 mb-3 pt-2">Layout Position</h6>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="layout-position"
                                            id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                                        <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="layout-position"
                                            id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                                        <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                                    </div>

                                    <h6 class="mt-4 mb-3 pt-2">Topbar Color</h6>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="topbar-color"
                                            id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                                        <label class="form-check-label" for="topbar-color-light">Light</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="topbar-color"
                                            id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                                        <label class="form-check-label" for="topbar-color-dark">Dark</label>
                                    </div>

                                    <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Size</h6>

                                    <div class="form-check sidebar-setting">
                                        <input class="form-check-input" type="radio" name="sidebar-size"
                                            id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                                        <label class="form-check-label" for="sidebar-size-default">Default</label>
                                    </div>
                                    <div class="form-check sidebar-setting">
                                        <input class="form-check-input" type="radio" name="sidebar-size"
                                            id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                                        <label class="form-check-label" for="sidebar-size-compact">Compact</label>
                                    </div>
                                    <div class="form-check sidebar-setting">
                                        <input class="form-check-input" type="radio" name="sidebar-size"
                                            id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                                        <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
                                    </div>

                                    <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Color</h6>

                                    <div class="form-check sidebar-setting">
                                        <input class="form-check-input" type="radio" name="sidebar-color"
                                            id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                                        <label class="form-check-label" for="sidebar-color-light">Light</label>
                                    </div>
                                    <div class="form-check sidebar-setting">
                                        <input class="form-check-input" type="radio" name="sidebar-color"
                                            id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                                        <label class="form-check-label" for="sidebar-color-dark">Dark</label>
                                    </div>
                                    <div class="form-check sidebar-setting">
                                        <input class="form-check-input" type="radio" name="sidebar-color"
                                            id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                                        <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                        </div>

                    </div>
                    <!-- end row -->
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