<?php
include('testFunctions.php');
$title = 'Settings overview';
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

                    <div class="row">
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-4"><i class="bi bi-buildings me-3"></i>Company & Branch</h5>
                                    <p class="card-text me-4">Manage company & branch info.</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-4"><i class="bi bi-buildings me-3"></i>Branding</h5>
                                    <p class="card-text me-4">Manage visual brand elements.</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-4"><i class="bi bi-palette me-3"></i>Appearance</h5>
                                    <p class="card-text me-4">Customize the visual aspects of your application.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="card bg-light-subtle border-transparent">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="bi bi-buildings widget-box-1-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-3">General Settings</h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-light">Manage your organization's profile and branches.</h6>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=admin_company">Company & Branch</a></p>
                                    <p class="text-secondary">Manage company & branch info.</p>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=admin_company_branding">Branding</a></p>
                                    <p class="text-secondary">Manage visual brand elements.</p>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=appearance">Appearance</a></p>
                                    <p class="text-secondary">Customize the visual aspects of your application.</p>
                                </div>
                            </div>
                            <div class="card bg-light-subtle border-transparent">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="bi bi-people widget-box-1-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-3">User Management</h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-light">Authenticate and authorize your organization's users and roles with ease.</h6>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=admin_user_management">Users</a></p>
                                    <p class="text-secondary">Manage individual users.</p>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=admin_user_roles">Roles</a></p>
                                    <p class="text-secondary">Manage user roles.</p>
                                </div>
                            </div>






                        </div><!--End col-->
                        <div class="col-4">
                            <div class="card bg-light-subtle border-transparent">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="bi bi-globe widget-box-1-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-3">Content Management</h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-light">Manage your content with ease.</h6>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=cms">Content creation & editing</a></p>
                                    <p class="text-secondary">Create and edit content.</p>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=cms">Content approval & moderation</a></p>
                                    <p class="text-secondary">Approve and moderate content.</p>
                                </div>
                            </div>
                            <div class="card bg-light-subtle border-transparent">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="bi bi-tools widget-box-1-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-3">Tools</h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-light">Access advanced tools and features for developers and system administrators.</h6>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=admin_impersonate">Impersonate</a></p>
                                    <p class="text-secondary">Assume user roles for testing.</p>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=md5">Encryption & Decryption</a></p>
                                    <p class="text-secondary">Secure data with encryption.</p>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=sessions">Sessions</a></p>
                                    <p class="text-secondary">Monitor user sessions.</p>
                                </div>
                            </div>








                        </div><!--End col-->
                        <div class="col-4">
                            <div class="card bg-light-subtle border-transparent">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="bi bi-shield-lock widget-box-1-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-3">Advanced Security</h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-light">Monitor and review Audit Logs and monitor user access and authentication attempts.</h6>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=audit-log">Audit logs</a></p>
                                    <p class="text-secondary">Review security log activity.</p>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=audit-login">Active user sessions</a></p>
                                    <p class="text-secondary">Monitor active user sessions.</p>
                                </div>
                            </div>
                            <div class="card bg-light-subtle border-transparent">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="bi bi-terminal widget-box-1-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-3">Developer</h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-light">Manage and maintain applications.</h6>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=admin_apps">Applications Manager</a></p>
                                    <p class="text-secondary">Manage apps.</p>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=manage_category">Category Manager</a></p>
                                    <p class="text-secondary">Manage categories.</p>
                                    <p class="mb-0 fw-bold"><a href="default.php?page=integration">REST API</a></p>
                                    <p class="text-secondary">Access data through public REST API.</p>
                                </div>
                            </div>


                            <!--div class="card">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="fa-regular fa-square-check widget-box-1-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-3">Management</h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-light">Centralize the management of your organization's core settings and configurations.</h6>
                                    <p><a href="default.php?page=dashboard_manager">Dashboard</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="default.php?page=workspaces">Workspaces</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="default.php?page=favorite">Favorites</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="default.php?page=admin_company">Company</a>
                                        <a href="default.php?page=admin_location">Location</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="default.php?page=admin_department">Department</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="#">Section</a> <a href="default.php?page=admin_offence">Offense</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="default.php?page=admin_email">Email</a>
                                        <a href="#">Email templates</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="#">Module</a>
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="bi bi-printer widget-box-1-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-3">Reporting</h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-light">Identify trends, optimize user engagement, and make data-driven decisions to improve the overall UX.</h6>
                                    <p><a href="#">User activity tracking</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="#">User login frequency</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="#">User retention</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="#">User demographics</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="#">Page views</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="#">Clicks</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="#">Bounce rates</a> <span class="fw-lighter text-body-tertiary ms-2 me-2">&middot;</span>
                                        <a href="#">Conversion rates</a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body overflow-hidden position-relative">
                                    <div>
                                        <i class="bi bi-controller widget-box-1-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-3">Configuration</h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-light">Unlock advanced features and personalization options.</h6>
                                    <p><a href="#">Gamification</a></p>
                                </div>
                            </div-->





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