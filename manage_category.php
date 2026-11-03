<?php
$title = 'Manage Category';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

//ADD DATA
if (isset($_POST['addbtn'])) {
    $category = $_POST['category'];
    $description = $_POST['description'];
    $query = "INSERT INTO `app_category`(`category`, `description`) VALUES (?,?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $category, $description);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        //echo " saved";
        $_SESSION['status'] = "New record added successfully ";
        $_SESSION['status_code'] = "success";
        header('Location: manage_category.php');
    } else {
        //echo "not saved";
        $_SESSION['status'] = "New record was not added successfully";
        $_SESSION['status_code'] = "error";
        header('Location: manage_category.php');
    }
}

if (isset($_GET['del'])) {
    $sid = $_SESSION['user']['staffId'];
    $d = date("Y-m-d H:is");
    $id = $_GET['del'];
    $sql = "UPDATE app_category SET isdelete=1, deleteby=?, deletedate=? WHERE id = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssi", $sid, $d, $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $_SESSION['status'] = "Record is deleted successfully";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=manage_category');
    } else {
        $_SESSION['status'] = "Cannot delete record";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=manage_category');
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
                                </h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <i class="fa-solid fa-plus"></i> New Category
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
                                        <th width="5%">No</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $i = 1;
                                    $query = "SELECT * FROM app_category WHERE isdelete=0 ORDER BY category";
                                    $query_run = mysqli_query($db, $query);
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row["category"] ?></td>
                                            <td><?php echo $row["description"] ?></td>
                                            <td width="10%">
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    href="default.php?page=manage_category_edit&id=<?php echo $row['id']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>&nbsp;
                                                <a class="btn btn-soft-danger waves-effect waves-light"
                                                    href="default.php?page=manage_category&del=<?php echo $row['id']; ?>"
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
            <h5 id="offcanvasRightLabel">Create New Category</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="default.php?page=manage_location" method="POST">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label" id="category">Category<span class="text-danger">*</span></label>
                        <input type="text" name="category" id="category" class="form-control" placeholder="Category" required>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label class="form-label" id="description">Description<span class="text-danger"> *</span></label>
                        <textarea name="description" id="description" class="form-control" placeholder="Description" required></textarea>
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