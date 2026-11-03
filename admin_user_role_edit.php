<?php
$title = 'User Roles / Update Role';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if (isset($_POST['updatebtn'])) {
    $sql = "UPDATE userroles SET rolename='" . $_POST['rolename'] . "', roleabbreviation='" . $_POST['roleabbreviation'] . "', roledescription='" . $_POST['roledescription'] . "', editby='" . $_POST['editby'] . "', editdate='" . date('Y-m-d h:i:s') . "' WHERE id=" . $_POST['id'];

    if (mysqli_query($db, $sql)) {
        $_SESSION['success'] = "Record updated successfully";
    } else {
        $_SESSION['error'] = "Error updating record: " . mysqli_error($db);
    }
    header('location: ' . $_POST['fullurl']);
    exit();
}


try {
    $sql = "SELECT * FROM userroles WHERE id = " . base64_decode($_GET['id']);
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $rolename = $row['rolename'];
    $roleabbreviation = $row['roleabbreviation'];
    $roledescription = $row['roledescription'];
} catch (Exception $e) {
    die($e->getMessage());
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
                                <h4 class="mb-sm-0 font-size-18">
                                    <?php echo $title; ?>
                                </h4>

                                <div class="page-title-right">
                                    <a href="#" onclick="history.back()"><i class="fa-solid fa-xmark"></i></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="card">
                        <div class="card-body p-4">

                            <form action="default.php?page=admin_user_role_edit" method="POST">

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="modal-title" id="rolename">Role Name<span class="text-danger">
                                                *</span></label>
                                        <input type="text" name="rolename" class="form-control"
                                            value="<?php echo $rolename; ?>">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="modal-title" id="roleabbreviation">Role Abbreviation<span
                                                class="text-danger"> *</span></label>
                                        <input type="text" name="roleabbreviation" class="form-control"
                                            value="<?php echo $roleabbreviation; ?>">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="modal-title" id="roledescription">Role Description<span
                                                class="text-danger"> *</span></label>
                                        <textarea type="text" name="roledescription"
                                            class="form-control"><?php echo $roledescription; ?></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" name="updatebtn" class="btn btn-primary">Update</button>
                                        <a href="default.php?page=admin_user_roles" class="btn btn-danger">Cancel</a>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <input type="hidden" name="fullurl" value="<?php echo $fullurl; ?>" />
                                    </div>
                                </div>
                        </div>
                    </div>
                    </form>

                </div><!--card-body-->
            </div>


        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <?php include('inc_footer.php'); ?>

    </div>
    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!-- Right Sidebar -->
    <?php include('inc_rightbar.php'); ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <?php include('inc_scripts.php'); ?>

</body>

</html>