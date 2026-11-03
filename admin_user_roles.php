<?php
$title = 'User Roles';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if (isset($_POST['registerrolebtn'])) {
    $rolename = $_POST['rolename'];
    $roleabbreviation = $_POST['roleabbreviation'];
    $roledescription = $_POST['roledescription'];
    $staffId = $_SESSION['user']['staffId'];
    $createdate = date('Y-m-d h:i:s');

    $sql = "INSERT INTO userroles (rolename, roleabbreviation, roledescription, createby, createdate) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssss", $rolename, $roleabbreviation, $roledescription, $staffId, $createdate);
    if ($stmt->execute()) {
        $_SESSION['success'] = "New record created successfully";
    } else {
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $stmt->error;
    }
    $stmt->close();
    header('location: default.php?page=admin_user_roles');
    exit();
}

if (isset($_GET['del'])) {
    $roleId = $_GET['del'];
    $isDeleted = 1;
    $staffId = $_SESSION['user']['staffId'];
    $deletedate = date('Y-m-d h:i:s');
    try {
        $query = "UPDATE userroles SET isdelete = ?, deleteby = ?, deletedate = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("isss", $isDeleted, $staffId, $deletedate, $roleId);
        $stmt->execute();
        header('location: default.php?page=admin_user_roles');
        exit();
        $stmt->close();
    } catch (Exception $e) {
        die($e->getMessage());
    }
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <i class="fa-solid fa-plus"></i> New role
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
                                        <th>No</td>
                                        <th>Role Name</th>
                                        <th>Role Abbreviation</th>
                                        <th>Role Description</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $query = "SELECT * FROM userroles WHERE isdelete=0";
                                    $query_run = mysqli_query($db, $query);
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                    ?>

                                        <tr>
                                            <td width="5%"><?php echo $i; ?></td>
                                            <td><?php echo $row["rolename"] ?></td>
                                            <td><?php echo $row["roleabbreviation"] ?></td>
                                            <td><?php echo $row["roledescription"] ?></td>
                                            <td width="20%" class="text-end">
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Update role."
                                                    href="default.php?page=admin_user_role_edit&id=<?php echo base64_encode($row['id']); ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Set user permissions."
                                                    href="default.php?page=admin_user_rights&id=<?php echo $row['id']; ?>&n=<?php echo base64_encode($row["rolename"]); ?>">
                                                    <i class="bi bi-unlock"></i>
                                                </a>
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Set page permissions."
                                                    href="default.php?page=admin_page_permissions&id=<?php echo $row['id']; ?>&n=<?php echo base64_encode($row["rolename"]); ?>">
                                                    <i class="bi bi-file-earmark"></i>
                                                </a>
                                                <a class="btn btn-soft-danger waves-effect waves-light"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Delete role."
                                                    href="default.php?page=admin_user_roles&del=<?php echo $row['id']; ?>"
                                                    name="delete_btn">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    } ?>
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
            <h5 id="offcanvasRightLabel">Create New Role</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="default.php?page=admin_user_roles" method="POST">
                <div class="form-group row">
                    <div class="col-md-12 mb-3">
                        <label class="modal-title" id="rolename" style="color:#000000">Role Name:<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" name="rolename" placeholder="Role Name" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="modal-title" id="roleabbreviation">Role Abbreviation<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="roleabbreviation" placeholder="Role Abbreviation" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="modal-title" id="roledescription">Role Description<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="roledescription" placeholder="Role Description" required></textarea>
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