<?php
$title = 'Manage Offences / Offence Update';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

//ADD DATA
if (isset($_POST['addbtn'])) {
    try {
        $OffenceCode = $_POST['OffenceCode'];
        $Category = $_POST['Category'];
        $offKeyword = $_POST['offKeyword'];
        $Offence = $_POST['Offence'];
        $query = "INSERT INTO `offence`(`OffenceCode`, `Category`, `offKeyword`, `Offence`) VALUES (?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssss", $OffenceCode, $Category, $offKeyword, $Offence);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            mysqli_query($db, $query);
            $_SESSION['status'] = "New offence code added successfully ";
            $_SESSION['status_code'] = "success";
            header('Location: default.php?page=manage_offence');
            exit;
        } else {
            $_SESSION['status'] = "Offence code already exist";
            $_SESSION['status_code'] = "error";
            header('Location: default.php?page=manage_offence');
            exit;
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

//DELETE DATA 

if (isset($_POST['delete_btn'])) {
    $id = $_POST['delete_id'];
    $sql = "DELETE FROM offence WHERE OffenceCode = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $_SESSION['status'] = "Record has been deleted succefully";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=manage_offence');
    } else {
        $_SESSION['status'] = "Record delete failed";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=manage_offence');
    }
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $sql = "DELETE FROM offence WHERE OffenceCode = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $_SESSION['status'] = "Record has been deleted succefully";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=manage_offence');
    } else {
        $_SESSION['status'] = "Record delete failed";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=manage_offence');
    }
}


//Update Function
if (isset($_POST['updatebtn'])) {
    $OffenceCode = $_POST['OffenceCode'];
    $Category = $_POST['Category'];
    $offKeyword = $_POST['offKeyword'];
    $Offence = $_POST['Offence'];
    $sql = "UPDATE `offence` SET `OffenceCode`=?,`Category`=?,offKeyword =?, Offence = ? WHERE `OffenceCode`=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssss", $OffenceCode, $Category, $offKeyword, $Offence, $OffenceCode);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $_SESSION['status'] = "Offence Details Has Been Update";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=manage_offence');
    } else {
        $_SESSION['status'] = "Error Update Data";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=manage_offence');
    }
}

try {
    $id = $_GET['id'];
    $sql = "SELECT * FROM offence WHERE `OffenceCode` = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $OffenceCode = $row['OffenceCode'];
    $Category = $row['Category'];
    $offKeyword = $row['offKeyword'];
    $Offence = $row['Offence'];
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
                                <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

                                <div class="page-title-right">
                                    <a href="#" onclick="history.back()"><i class="fa-solid fa-xmark"></i></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="card">
                        <div class="card-body p-4">
                            <form action="default.php?page=manage_offence_edit" method="POST">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="modal-title" id="exampleModalLabel">Offence Code</h6>
                                            <input type="text" name="OffenceCode" class="form-control"
                                                value="<?php echo $OffenceCode; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="modal-title" id="exampleModalLabel">Category</h6>
                                            <select id="Category" name="Category" class="form-control"
                                                value="<?php echo $Category; ?>">
                                                <option selected="selected"></option>
                                                <option Value="Major">Major</option>
                                                <option Value="Minor">Minor</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <h6 class="modal-title" id="exampleModalLabel">Type of Offence</h6>
                                            <input type="text" name="offKeyword" class="form-control"
                                                value="<?php echo $offKeyword; ?>">
                                        </div>
                                        <div class="col-md-12">
                                            <h6 class="modal-title" id="exampleModalLabel">Offence Description</h6>
                                            <input type="text" name="Offence" class="form-control" value="<?php echo $Offence; ?>">
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" name="updatebtn" class="btn btn-primary">Update</button>
                                            <a href="default.php?page=manage_offence" class="btn btn-danger">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

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


    <!-- Right Sidebar -->
    <?php include('inc_rightbar.php'); ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <?php include('inc_scripts.php'); ?>

</body>

</html>