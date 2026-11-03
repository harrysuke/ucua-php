<?php
$title = 'App Manager';
include('testFunctions.php');

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
                                    <p class="text-secondary fw-light">
                                        Your role is <?php echo $_SESSION['user']['rolename']; ?>. You have all permission in the system and can fully access and manage this module.
                                    </p>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <i class="fa-solid fa-plus"></i> New app
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">

                            <table id="datatable" class="table dt-responsive w-100">
                                <thead>
                                    <tr>
                                        <th>Icon</td>
                                        <th>App Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $sql = "SELECT * FROM apps ";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                    ?>

                                        <tr>
                                            <td>
                                                <div class="avatar-sm align-self-center">
                                                    <span class="avatar-title bg-primary-subtle text-primary">
                                                        <?php echo substr(trim($row["appname"]), 0, 1); ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><?php echo $row["appname"] ?></td>
                                            <td><?php echo $row["appdesc"] ?></td>
                                            <td><?php echo $row["status"] ?></td>
                                            <td width="15%" class="text-end">
                                                <a href="<?php echo $row["appurl"] ?>"
                                                    class="btn btn-soft-primary waves-effect waves-light"
                                                    target="_blank"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Launch app"><i class="bi bi-play-fill"></i></a>
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Edit app"
                                                    href="#">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a class="btn btn-soft-danger waves-effect waves-light"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Delete app"
                                                    href="#"
                                                    name="delete_btn">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                    $stmt->close();
                                    $db->close();
                                    ?>
                                </tbody>
                            </table>
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
            <h5 id="offcanvasRightLabel">New App</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="default.php?page=admin_user_roles" method="POST">
                <div class="form-group row">
                    <div class="col-md-12 mb-3">
                        <label class="modal-title" id="rolename" style="color:#000000">App Name:<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" name="rolename" placeholder="App Name" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="modal-title" id="roledescription">App Description<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="roledescription" placeholder="App Description" required></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="modal-title" id="roleabbreviation">App URL<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="roleabbreviation" placeholder="App URL" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                        <button type="submit" name="registerrolebtn" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
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