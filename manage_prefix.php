<?php
$title = 'Manage Prefix';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

//ADD DATA
if (isset($_POST['addbtn'])) {
    $prefix = $_POST['prefix'];
    $prefixdesc = $_POST['prefixdesc'];
    $query = "INSERT INTO `prefix`(`prefix`, `prefixdesc`) VALUES (?,?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $prefix, $prefixdesc);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        //echo " saved";
        $_SESSION['status'] = "New record added successfully ";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=manage_prefix');
    } else {
        //echo "not saved";
        $_SESSION['status'] = "New record was not added successfully";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=manage_prefix');
    }
}

if (isset($_GET['del'])) {
    $sid = $_SESSION['user']['staffId'];
    $d = date("Y-m-d H:is");
    $id = $_GET['del'];
    $sql = "UPDATE prefix SET isdelete=1, deleteby=?, deletedate=? WHERE id = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssi", $sid, $d, $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $_SESSION['status'] = "Record is deleted successfully";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=manage_prefix');
    } else {
        $_SESSION['status'] = "Cannot delete record";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=manage_prefix');
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
                                <h4 class="mb-sm-0 font-size-18">
                                    <?php echo $title; ?>
                                    <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                                </h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <i class="fa-solid fa-plus"></i> New Prefix
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="datatable" class="table dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Prefix Name</th>
                                        <th>Prefix Desc</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $i = 1;
                                    $query = "SELECT id, prefix, prefixdesc FROM prefix WHERE isdelete=0 ORDER BY prefix";
                                    $query_run = mysqli_query($db, $query);
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row["prefix"] ?></td>
                                            <td><?php echo $row["prefixdesc"] ?></td>

                                            <td width="10%">
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    href="default.php?page=manage_prefix&id=<?php echo $row['id']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>&nbsp;
                                                <a class="btn btn-soft-danger waves-effect waves-light"
                                                    href="default.php?page=manage_prefix&del=<?php echo $row['id']; ?>"
                                                    name="delete_btn">
                                                    <i class="bi bi-trash"></i>
                                                </a>

                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Create New Prefix</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="default.php?page=manage_prefix" method="POST">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label" id="exampleModalLabel">Prefix<span class="text-danger">*</span></label>
                        <input type="text" name="prefix" class="form-control" placeholder="Prefix" required>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label class="form-label" id="exampleModalLabel">Prefix Desc<span class="text-danger"> *</span></label>
                        <input type="text" name="prefixDesc" class="form-control" placeholder="Prefix Desc" required>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                        <button type="submit" name="addbtn" class="btn btn-primary">Create</button>
                    </div>

                </div>
            </form>
        </div>


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