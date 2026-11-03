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
                                    <div class="card">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-buildings widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> General Settings</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Manage your organization's profile and branches.</h6>

                                            <div class="row row-sm">
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-buildings fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=admin_company">
                                                                        <h5 class="mb-1 text-dark">Company & Branch</h5>
                                                                    </a>
                                                                    <p>Manage company & branch info.</p>
                                                                    <a href="default.php?page=admin_company">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-box-arrow-up-right fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=admin_company_branding">
                                                                        <h5 class="mb-1 text-dark">Branding</h5>
                                                                    </a>
                                                                    <p>Manage visual brand elements.</p>
                                                                    <a href="default.php?page=admin_company_branding">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-palette fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=appearance">
                                                                        <h5 class="mb-1 text-dark">Appearance</h5>
                                                                    </a>
                                                                    <p>Customize the visual aspects of your application.</p>
                                                                    <a href="default.php?page=appearance">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="user">
                                    <div class="card">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-people widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> User Management</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Authenticate and authorize your organization's users and roles with ease.</h6>

                                            <div class="row row-sm">
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-people fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=admin_user_management">
                                                                        <h5 class="mb-1 text-dark">Users</h5>
                                                                    </a>
                                                                    <p>Holds personal data, credentials, etc.</p>
                                                                    <a href="default.php?page=admin_user_management">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-person-check fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=admin_user_roles">
                                                                        <h5 class="mb-1 text-dark">Roles</h5>
                                                                    </a>
                                                                    <p>Determines what actions a user can perform globally.</p>
                                                                    <a href="default.php?page=admin_user_roles">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-box-arrow-up-right fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="#">
                                                                        <h5 class="mb-1 text-dark">User Group</h5>
                                                                    </a>
                                                                    <p>Assign roles (or additional permissions) per‑group and to filter data by group.</p>
                                                                    <a href="">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-box-arrow-up-right fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="#">
                                                                        <h5 class="mb-1 text-dark">Group Role</h5>
                                                                    </a>
                                                                    <p>“Team‑level” permission granularity without duplicating users.</p>
                                                                    <a href="">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="cms">
                                    <div class="card">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-globe widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> Content Management</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Manage your content with ease.</h6>

                                            <div class="row row-sm">
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-card-text fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=cms">
                                                                        <h5 class="mb-1 text-dark">Content creation & editing</h5>
                                                                    </a>
                                                                    <p>Create and edit content.</p>
                                                                    <a href="default.php?page=cms">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-box-arrow-up-right fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=cms">
                                                                        <h5 class="mb-1 text-dark">Content approval & moderation</h5>
                                                                    </a>
                                                                    <p>Approve and moderate content.</p>
                                                                    <a href="default.php?page=cms">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tools">
                                    <div class="card">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-tools widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> Tools</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Access advanced tools and features for developers and system administrators.</h6>

                                            <div class="row row-sm">
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-copy fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=admin_impersonate">
                                                                        <h5 class="mb-1 text-dark">Impersonation</h5>
                                                                    </a>
                                                                    <p>Assume user roles for testing.</p>
                                                                    <a href="default.php?page=admin_impersonate">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-unlock fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=md5">
                                                                        <h5 class="mb-1 text-dark">Encryption & Decryption</h5>
                                                                    </a>
                                                                    <p>Secure data with encryption.</p>
                                                                    <a href="default.php?page=md5">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-box-arrow-up-right fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=sessions">
                                                                        <h5 class="mb-1 text-dark">Sessions</h5>
                                                                    </a>
                                                                    <p>Monitor user sessions.</p>
                                                                    <a href="default.php?page=sessions">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="monitoring">
                                    <div class="card">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-binoculars widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> Monitoring</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Monitor and review audit logs and monitor user access and authentication attempts.</h6>

                                            <div class="row row-sm">
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-box-arrow-up-right fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=audit-log">
                                                                        <h5 class="mb-1 text-dark">Audit logs</h5>
                                                                    </a>
                                                                    <p>Review security log activity.</p>
                                                                    <a href="default.php?page=audit-log">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-box-arrow-up-right fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=audit-login">
                                                                        <h5 class="mb-1 text-dark">User activity logs</h5>
                                                                    </a>
                                                                    <p>Monitor active user sessions.</p>
                                                                    <a href="default.php?page=audit-login">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="dev">
                                    <div class="card">
                                        <div class="card-body overflow-hidden position-relative">
                                            <div>
                                                <i class="bi bi-terminal widget-box-1-icon"></i>
                                            </div>
                                            <h5 class="card-title mb-3"><i class="bi bi-archive"></i> Developer</h5>
                                            <h6 class="card-subtitle mb-2 text-muted fw-light">Manage and maintain applications.</h6>

                                            <div class="row row-sm">
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-app fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=admin_apps">
                                                                        <h5 class="mb-1 text-dark">Applications Manager</h5>
                                                                    </a>
                                                                    <p>Manage apps.</p>
                                                                    <a href="default.php?page=admin_apps">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-tags fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=manage_category">
                                                                        <h5 class="mb-1 text-dark">Category Manager</h5>
                                                                    </a>
                                                                    <p>Manage app category.</p>
                                                                    <a href="default.php?page=manage_category">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card custom-card bg-light-subtle">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-2 col-sm-2 col-md-12">
                                                                    <div class="mt-2 mb-4">
                                                                        <span class="settings-icon border-primary">
                                                                            <i class="bi bi-box-arrow-up-right fa-2xl"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-10 col-sm-10 col-md-12">
                                                                    <a href="default.php?page=integration">
                                                                        <h5 class="mb-1 text-dark">REST API</h5>
                                                                    </a>
                                                                    <p>Access data through public REST API.</p>
                                                                    <a href="default.php?page=integration">Change Settings <i class="fa-solid fa-chevron-right fa-2xs"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
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