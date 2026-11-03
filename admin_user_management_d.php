<?php
$title = 'User Management';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function accessName($n)
{
    try {
        global $db;
        $option = '';
        $query = "SELECT rolename FROM userroles WHERE id = " . $n;
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_array($result);
        $option = $row['rolename'];
    } catch (Exception $e) {
        $option = $n;
    }
    return $option;
}

function funStatus($status)
{
    $msg = '';
    if ($status == 1) {
        $msg = "<h6 style='color:green'>Active";
    } else if ($status == 2) {
        $msg = "<h6 style='color:red'>Inactive";
    } else {
        $msg = "<h6 style='color:orange'>Pending";
    }
    return $msg;
}

function funDepartment()
{
    global $db;
    $option = '';
    $sql1 = "SELECT dept, deptName FROM dept WHERE isdelete=0 ORDER BY deptName";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $option .= '<option value="' . $row1['dept'] . '">' . $row1['deptName'] . ' (' . $row1['dept'] . ')</option>';
    }
    return $option;
}

function funRoles()
{
    global $db;
    $option = '';
    $sql1 = "SELECT id, rolename FROM userroles WHERE isdelete=0 ORDER BY id";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $option .= '<option value="' . $row1['id'] . '">' . $row1['id'] . ' (' . $row1['rolename'] . ')</option>';
    }
    return $option;
}

if (isset($_GET['del'])) {
    $id = base64_decode($_GET['del']);

    $query = "UPDATE FROM user SET isdelete=1, deleteby='" . $_SESSION['user']['staffId'] . "', deletedate='" . date("Y-m-d h:i:s") . "' WHERE staffId = '$id'";
    $query_run = mysqli_query($db, $query);

    if ($query_run) {
        $_SESSION['status'] = "User is deleted succefully";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=admin_user_management');
    } else {
        $_SESSION['status'] = "Delete user failed";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=admin_user_management');
    }
}

//Add New User Button
if (isset($_POST['registerbtn'])) {
    $staffId = $_POST['staffId'];
    $password = $_POST['password'];
    $password1 = $_POST['password'];
    $name = $_POST['name'];
    $phonenum = $_POST['phonenum'];
    $Department = $_POST['Department'];
    $designation = $_POST['designation'];
    $AccessLevel = $_POST['accessLevel'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $query = "INSERT INTO `user`(`staffId`, `Password`, `Password1`, `Name`, `PhoneNo`, `Department` , `Designation`, `AccessLevel`, `Email`, `Status`) VALUES ('$staffId','$password',' $password1',' $name','$phonenum','$Department',' $designation',' $AccessLevel',' $email','3')";
    $query_run = mysqli_query($db, $query);

    //echo $query;

    if ($query_run) {
        //echo " saved";
        $_SESSION['status'] = "User is successfully added";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=admin_user_management');
    } else {
        //echo "not saved";
        $_SESSION['status'] = "User Failed to Add";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=admin_user_management');
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs mb-2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link " data-bs-toggle="" href="default.php?page=admin_user_management" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">User</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="" href="default.php?page=admin_user_management_d" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">Deleted</span>
                            </a>
                        </li>
                    </ul>

                    <div class="row">
                        <div class="col-lg-12">

                            <table id="datatable" class="table dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Avatar</td>
                                        <th>Dept</td>
                                        <th>Staff Id</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $sql = "SELECT u.staffId, u.Name, u.AccessLevel, u.Status, u.Department,r.rolename FROM user AS u 
									LEFT JOIN userroles AS r ON r.id=u.AccessLevel
									WHERE u.isdelete=1 ORDER BY u.Name";
                                    $result = mysqli_query($db, $sql);
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <tr>
                                            <td width="5%">
                                                <div class="avatar-sm align-self-center">
                                                    <span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                        <?php echo substr(trim($row["Name"]), 0, 1); ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="text-primary"><b><?php echo $row["Department"]; ?></b></td>
                                            <td><?php echo $row["staffId"] ?></td>
                                            <td class="text-capitalize"><?php echo strtolower($row["Name"]) ?></td>
                                            <td><?php echo $row["rolename"]; ?> (<?php echo $row["AccessLevel"]; ?>)</td>
                                            <td><?php echo funStatus($row['Status']); ?></td>
                                            <td width="5%">
                                                <a class="btn btn-success"
                                                    href="default.php?page=admin_user_edit&id=<?php echo base64_encode($row['staffId']); ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a class="btn btn-danger" href="default.php?page=admin_user_edit&del=<?php echo base64_encode($row['staffId']); ?>">
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
            <h5 id="offcanvasRightLabel">Create User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">

            <form action="default.php?page=admin_user_management" method="POST">

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Staff ID<span class="text-danger"> *</span></label>
                            <input type="text" name="staffId" class="form-control" placeholder=" username" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Password<span class="text-danger"> *</span></label>
                            <input type="password" name="password" class="form-control" placeholder=" password"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Staff Name<span class="text-danger"> *</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Phone Number<span class="text-danger">*</span></label>
                            <input type="number" name="phonenum" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel" style="color:#000000">Department Name:<span class="text-danger"> *</span></label>
                            <!--<input type="text" class="form-control" name="assignmentRef" required>-->
                            <select id="Department" name="Department" class="form-control " required>
                                <option value="" disabled selected>Please Select Department</option>
                                <?php echo funDepartment(); ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Designation<span class="text-danger">*</span></label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Access Level<span class="text-danger">*</span></label>
                            <select id="accessLevel" name="accessLevel" class="form-control " required>
                                <option selected="selected"></option>
                                <?php echo funRoles(); ?>
                                <!--option Value="User">User</option>
                                <option Value="Admin">Admin</option>
                                <option Value="HSE Reps">HSE Reps</option>
                                <option Value="Head Of Section">Head of Section</option>
                                <option Value="General Manager">Manager</option-->
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Email<span class="text-danger"> *</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Status<span class="text-danger"> *</span></label>
                            <select name="status" class="form-control " required>
                                <option selected="selected"></option>
                                <option Value="1">Active</option>
                                <option Value="2">Inactive</option>
                                <option Value="3">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                            <button type="submit" name="registerbtn" class="btn btn-primary">Create</button>
                        </div>

                    </div>
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