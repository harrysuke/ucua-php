<?php
include('testFunctions.php');
$title = 'API Settings';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if (!hasAccess('api_integration')) {
    header('Location: default.php?page=unauthorized&p=' . base64_encode($title));
    exit();
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
                                    <p class="card-title-desc">All Endpoints REST API integration.</p>
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
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table table-nowrap align-middle justify-content-center mb-4">
                                    <tr>
                                        <th>Method</th>
                                        <th>Endpoint</th>
                                        <th>Clipboard</th>
                                    </tr>
                                    <tr>
                                        <td>GET</td>
                                        <td><input id="kt_clipboard_1" type="text" class="form-control" value="default.php?page=api" /></td>
                                        <td>
                                            <button class="btn btn-light clipboard" data-clipboard-target="#kt_clipboard_1">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>GET</td>
                                        <td><input id="kt_clipboard_2" type="text" class="form-control" value="default.php?page=api&id=1" /></td>
                                        <td>
                                            <button class="btn btn-light clipboard" data-clipboard-target="#kt_clipboard_2">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>POST</td>
                                        <td><input id="kt_clipboard_3" type="text" class="form-control" value="default.php?page=api" /></td>
                                        <td>
                                            <button class="btn btn-light clipboard" data-clipboard-target="#kt_clipboard_3">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>PUT</td>
                                        <td><input id="kt_clipboard_4" type="text" class="form-control" value="default.php?page=api" /></td>
                                        <td>
                                            <button class="btn btn-light clipboard" data-clipboard-target="#kt_clipboard_4">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>DELETE</td>
                                        <td><input id="kt_clipboard_5" type="text" class="form-control" value="default.php?page=api&id=0" /></td>
                                        <td>
                                            <button class="btn btn-light clipboard" data-clipboard-target="#kt_clipboard_5">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- end col -->
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