<?php
include('testFunctions.php');
$title = 'Settings';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if (!hasAccess('settings')) {
    header('Location: default.php?page=unauthorized&p=' . base64_encode($title));
    exit();
}

function onboarding()
{
    global $db;
    $y = date('Y');
    $sql = "SELECT COUNT(*) AS counter FROM `user` WHERE YEAR(createdate)=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $y);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['counter'];
}

function offboarding()
{
    global $db;
    $sql = "SELECT COUNT(*) AS counter FROM `user` WHERE isdelete=1 ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['counter'];
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
                                        <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                                    </h4>
                                    <p class="text-secondary fw-light">
                                        Your role is <?php echo $_SESSION['user']['rolename']; ?>. You have all permission in the system and can fully access and manage this module.
                                    </p>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Settings</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!--div class="row">
                        <div class="col-4">
                            <div class="card card-h-100 bg-primary-subtle">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <h5 class="card-title mb-3">Onboarding</h5>
                                            <h4 class="mb-3">
                                                <span class="counter-value" data-target="<?php echo onboarding(); ?>">0</span>
                                            </h4>
                                        </div>
                                        <div class="col-6">
                                            <div class="sparkline-bar-stats float-end"><?php echo onboarding(); ?></div>
                                        </div>
                                    </div>
                                    <div class="text-nowrap">
                                        <span class="badge bg-dark text-white">
                                            <?php echo onboarding(); ?>
                                        </span>
                                        <span class="ms-1 text-muted font-size-13">Onboarding</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card card-h-100 bg-success-subtle">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <h5 class="card-title mb-3">Offboarding</h5>
                                            <h4 class="mb-3">
                                                <span class="counter-value" data-target="<?php echo offboarding(); ?>">0</span>
                                            </h4>
                                        </div>
                                        <div class="col-6">
                                            <div class="sparkline-bar-stats float-end"><?php echo offboarding(); ?></div>
                                        </div>
                                    </div>
                                    <div class="text-nowrap">
                                        <span class="badge bg-dark text-white">
                                            <?php echo offboarding(); ?>
                                        </span>
                                        <span class="ms-1 text-muted font-size-13">Offboarding</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4"></div>
                    </div--><!--End row-->


                    <div class="row edit-profile-wrap">
                        <div class="col-lg-2 col-sm-3 col-4">
                            <div class="nav-profile mt-4">
                                <ul class="nav nav-light nav-vertical card-header-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a data-bs-toggle="tab" href="#general" class="nav-link active">
                                            <span class="nav-link-text">General Settings</span>
                                        </a>
                                        <a data-bs-toggle="tab" href="#user" class="nav-link ">
                                            <span class="nav-link-text">User Management</span>
                                        </a>
                                        <a data-bs-toggle="tab" href="#cms" class="nav-link ">
                                            <span class="nav-link-text">Content Management</span>
                                        </a>
                                        <a data-bs-toggle="tab" href="#monitoring" class="nav-link ">
                                            <span class="nav-link-text">Monitoring</span>
                                        </a>
                                        <a data-bs-toggle="tab" href="#tools" class="nav-link ">
                                            <span class="nav-link-text">Tools</span>
                                        </a>
                                        <a data-bs-toggle="tab" href="#dev" class="nav-link ">
                                            <span class="nav-link-text">Developer</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-10 col-sm-9 col-8">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="general">
                                    <div class="card bg-light-subtle border-transparent">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-buildings widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> General Settings</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Manage your organization's profile and branches.</h6>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=admin_company"><i class="bi bi-box-arrow-up-right"></i> Company & Branch</a></p>
                                            <p class="text-secondary">Manage company & branch info.</p>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=admin_company_branding"><i class="bi bi-box-arrow-up-right"></i> Branding</a></p>
                                            <p class="text-secondary">Manage visual brand elements.</p>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=appearance"><i class="bi bi-box-arrow-up-right"></i> Appearance</a></p>
                                            <p class="text-secondary">Customize the visual aspects of your application.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="user">
                                    <div class="card bg-light-subtle border-transparent">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-people widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> User Management</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Authenticate and authorize your organization's users and roles with ease.</h6>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=admin_user_management"><i class="bi bi-box-arrow-up-right"></i> Users</a></p>
                                            <p class="text-secondary">Manage individual users.</p>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=admin_user_roles"><i class="bi bi-box-arrow-up-right"></i> Roles</a></p>
                                            <p class="text-secondary">Manage user roles.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="cms">
                                    <div class="card bg-light-subtle border-transparent">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-globe widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> Content Management</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Manage your content with ease.</h6>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=cms"><i class="bi bi-box-arrow-up-right"></i> Content creation & editing</a></p>
                                            <p class="text-secondary">Create and edit content.</p>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=cms"><i class="bi bi-box-arrow-up-right"></i> Content approval & moderation</a></p>
                                            <p class="text-secondary">Approve and moderate content.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tools">
                                    <div class="card bg-light-subtle border-transparent">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-tools widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> Tools</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Access advanced tools and features for developers and system administrators.</h6>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=admin_impersonate"><i class="bi bi-box-arrow-up-right"></i> Impersonation</a></p>
                                            <p class="text-secondary">Assume user roles for testing.</p>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=md5"><i class="bi bi-box-arrow-up-right"></i> Encryption & Decryption</a></p>
                                            <p class="text-secondary">Secure data with encryption.</p>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=sessions"><i class="bi bi-box-arrow-up-right"></i> Sessions</a></p>
                                            <p class="text-secondary">Monitor user sessions.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="monitoring">
                                    <div class="card bg-light-subtle border-transparent">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-shield-lock widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> Monitoring</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Monitor and review Audit Logs and monitor user access and authentication attempts.</h6>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=audit-log"><i class="bi bi-box-arrow-up-right"></i> Audit logs</a></p>
                                            <p class="text-secondary">Review security log activity.</p>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=audit-login"><i class="bi bi-box-arrow-up-right"></i> Active user sessions</a></p>
                                            <p class="text-secondary">Monitor active user sessions.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="dev">
                                    <div class="card bg-light-subtle border-transparent">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-terminal widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> Developer</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Manage and maintain applications.</h6>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=admin_apps"><i class="bi bi-box-arrow-up-right"></i> Applications Manager</a></p>
                                            <p class="text-secondary">Manage apps.</p>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=manage_category"><i class="bi bi-box-arrow-up-right"></i> Category Manager</a></p>
                                            <p class="text-secondary">Manage categories.</p>
                                            <p class="mb-0 fw-bold"><a href="default.php?page=integration"><i class="bi bi-box-arrow-up-right"></i> REST API</a></p>
                                            <p class="text-secondary">Access data through public REST API.</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

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