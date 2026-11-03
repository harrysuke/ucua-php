<?php
include('testFunctions.php');
$title = 'App Center';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if (!hasAccess('discover_apps')) {
    header('Location: default.php?page=unauthorized&p=' . base64_encode($title));
    exit();
}

function funShowCat()
{
    global $db;
    $a = '';
    $sql = "SELECT id, category FROM app_category ORDER BY category";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $a .= '<a href="default.php?page=app_center&cat=' . $row['id'] . '">' . $row['category'] . '</a><br/>';
    }
    $stmt->close();
    return $a;
}

function funApps($c)
{
    global $db;
    $a = '';
    if ($c == 0) {
        $sql = "SELECT appurl, appname, appdesc FROM apps WHERE isdelete=0 ORDER BY appname";
        $stmt = $db->prepare($sql);
    } else {
        $sql = "SELECT appurl, appname, appdesc FROM apps WHERE isdelete=0 AND categoryID = ? ORDER BY appname";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $c);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $a .= '<div class="col-xl-2 col-sm-4">
                                <a href="' . $row['appurl'] . '" target="_blank" class="waves-effect waves-light">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <div class="faq-count">
                                                <div class="rounded avatar-lg">
                                                    <h1 class="avatar-title bg-success-subtle text-success">
                                                        ' . substr(trim($row['appname']), 0, 1) . '
                                                    </h1>
                                                </div>
                                            </div>
                                            <h5 class="text-dark mt-3">' . $row['appname'] . '</h5>
                                            <p class="text-muted mt-3 mb-0">' . substr($row['appdesc'], 0, 50) . '&hellip;</p>
                                        </div>
                                    </div>
                                </a>
                            </div>';
        }
    } else {
        $a .= '<div class="col-xl-2 col-sm-4">
                <div class="card border-0">
                                        <div class="card-body">
                                            <div class="faq-count">
                                                <div class="avatar-lg">
                                                    <h1 class="avatar-title bg-primary-subtle text-primary">
                                                        :(
                                                    </h1>
                                                </div>
                                            </div>
                                            <h5 class="mt-3">No records found</h5>
                                            <p class="text-muted mt-3 mb-0">No records found.</p>
                                        </div>
                                    </div>
            </div>';
    }
    $a .= '<div class="col-xl-2 col-sm-4">
                                <a href="default.php?page=admin_apps" target="_blank" class="waves-effect waves-light">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <div class="faq-count">
                                                <div class="rounded avatar-lg">
                                                    <h1 class="avatar-title bg-secondary-subtle text-secondary"><i class="bi bi-plus-lg"></i></h1>
                                                </div>
                                            </div>
                                            <h5 class="text-dark mt-3">New App</h5>
                                            <p class="text-muted mt-3 mb-0">Add new apps here&hellip;</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-sm-4">
                                <a href="default.php?page=admin_apps" target="_blank" class="waves-effect waves-light">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <div class="faq-count">
                                                <div class="rounded avatar-lg">
                                                    <h1 class="avatar-title bg-secondary-subtle text-secondary"><i class="bi bi-pencil"></i></h1>
                                                </div>
                                            </div>
                                            <h5 class="text-dark mt-3">Manage App</h5>
                                            <p class="text-muted mt-3 mb-0">Manage new apps here&hellip;</p>
                                        </div>
                                    </div>
                                </a>
                            </div>';
    return $a;
    $stmt->close();
    $db->close();
}

if (empty($_GET['cat'])) {
    $c = 0;
} else {
    $c = $_GET['cat'];
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
                                        Discover Apps for <?php echo ucwords(strtolower($_SESSION['company']['brandName'])); ?>&nbsp;
                                        <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis fa-2xs"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                                <li><a class="dropdown-item" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Browse apps</a></li>
                                                <li><a class="dropdown-item" href="default.php?page=admin_apps">Manage apps</a></li>
                                                <li><a class="dropdown-item" href="default.php?page=manage_category">Manage category</a></li>
                                            </ul>
                                        </div>
                                    </h4>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <?php echo funApps($c); ?>
                            </div>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Browse apps</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <a href="default.php?page=app_center&cat=0">All Apps</a><br />
            <!--Staff Picks<br />
                                Featured apps<br />
                                Trending app<br />
                                Newest apps<br /-->
            </p>
            <p>
                <strong>Categories</strong><br />
                <?php echo funShowCat(); ?>
            </p>
            <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                <p>
                    <strong>Manage</strong><br />
                    <!--My Apps<br />
                                Installed apps<br /-->
                    <a href="default.php?page=admin_apps">App Management Settings</a><br />
                    <a href="default.php?page=manage_category">App Category Settings</a><br />
                </p>
            <?php } ?>
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