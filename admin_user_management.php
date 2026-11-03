<?php
$title = 'User Management';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function accessName($n)
{
    try {
        global $db;
        $option = '';
        $query = "SELECT rolename FROM userroles WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $n);
        $stmt->execute();
        $stmt->store_result();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $option = $row['rolename'];
        $stmt->close();
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

if (isset($_GET['getUser']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    $sql = "SELECT * FROM user WHERE `staffId` = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultset = $stmt->get_result();
    header('Content-Type: application/json');
    if ($row = $resultset->fetch_assoc()) {
        $result = array(
            'staffId' => $row['staffId'],
            'Password' => $row['Password'],
            'Name' => $row['Name'],
            'PhoneNo' => $row['PhoneNo'],
            'Department' => $row['Department'],
            'Designation' => $row['Designation'],
            'AccessLevel' => $row['AccessLevel'],
            'Email' => $row['Email'],
            'Status' => $row['Status']
        );
        echo json_encode($result);
    } else {
        echo json_encode(array('error' => 'Record not found'));
    }
    $stmt->close();
    exit;
}

if (isset($_GET['del'])) {
    $id = base64_decode($_GET['del']);
    $isDeleted = 1;
    $staffId = $_SESSION['user']['staffId'];
    $deleteDate = date("Y-m-d h:i:s");

    $query = "UPDATE user SET isdelete = ?, deleteby = ?, deletedate = ? WHERE staffId = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("isss", $isDeleted, $staffId, $deleteDate, $id);
    if ($stmt->execute()) {
        $_SESSION['status'] = "User is deleted succefully";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=admin_user_management');
    } else {
        $_SESSION['status'] = "Delete user failed";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=admin_user_management');
    }
    $stmt->close();
    exit;
}

if (isset($_POST['updatebtn'])) {
    $staffId = trim($_POST['staffId']);
    $password = md5($_POST['password']);
    $name = $_POST['name'];
    $phonenum = $_POST['phonenum'];
    $Department = $_POST['Department'];
    $designation = $_POST['designation'];
    $AccessLevel = $_POST['AccessLevel'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $sql = "UPDATE `user` SET `staffId`=?,`Name`=?, PhoneNo = ?, Department=?, Designation=?, AccessLevel=?, Email=?, Status=? WHERE `staffId`=? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssssssis", $staffId, $name, $phonenum, $Department, $designation, $AccessLevel, $email, $status, $staffId);
    $stmt->execute();
    $stmt->close();
    if ($stmt->affected_rows > 0) {
        $_SESSION['status'] = "User Has Been Approved";
        $_SESSION['status_code'] = "success";
        header('Location: default.php?page=admin_user_management');
        exit;
    } else {
        $_SESSION['status'] = "Error ";
        $_SESSION['status_code'] = "error";
        header('Location: default.php?page=admin_user_management');
        exit;
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
    $sql = "INSERT INTO `user`(`staffId`, `Password`, `Password1`, `Name`, `PhoneNo`, `Department` , `Designation`, `AccessLevel`, `Email`, `Status`) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssssssssi", $staffId, $password, $password1, $name, $phonenum, $Department, $designation, $AccessLevel, $email, 3);
    $stmt->execute();
    $stmt->close();
    if ($stmt->affected_rows > 0) {
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
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasNew"
                                            aria-controls="offcanvasNew">
                                            <i class="fa-solid fa-plus"></i> New user
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs mb-2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="" href="default.php?page=admin_user_management" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">User</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="" href="default.php?page=admin_user_management_d" role="tab">
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
                                    $c = $_SESSION['user']['companyCode'];
                                    $sql = "SELECT u.staffId, u.Name, u.AccessLevel, u.Status, u.Department,r.rolename FROM user AS u 
									LEFT JOIN userroles AS r ON r.id=u.AccessLevel
									WHERE u.isdelete=0 AND u.companyCode=?
                                    ORDER BY u.Name";
                                    $stmt = $db->prepare($sql);
                                    $stmt->bind_param("s", $c);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="avatar-sm align-self-center">
                                                    <span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                        <?php echo substr(trim($row["Name"]), 0, 1); ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><?php echo $row["Department"]; ?></td>
                                            <td><?php echo $row["staffId"] ?></td>
                                            <td class="text-capitalize">
                                                <?php echo strtolower($row["Name"]) ?>
                                            </td>
                                            <td><?php echo $row["rolename"]; ?> (<?php echo $row["AccessLevel"]; ?>)</td>
                                            <td><?php echo funStatus($row['Status']); ?></td>
                                            <td>
                                                <!--href="default.php?page=admin_user_edit&id=<?php echo base64_encode($row['staffId']); ?>"-->
                                                <button class="btn btn-soft-primary waves-effect waves-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEdit" aria-controls="offcanvasEdit" data-id="<?php echo htmlentities($row["staffId"]); ?>" id="getUser">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    href="default.php?page=profile&sid=<?php echo base64_encode($row['staffId']); ?>" target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    href="default.php?page=admin_user_password&id=<?php echo base64_encode($row['staffId']); ?>">
                                                    <i class="bi bi-unlock"></i>
                                                </a>
                                                <a class="btn btn-soft-danger waves-effect waves-light" href="default.php?page=admin_user_edit&del=<?php echo base64_encode($row['staffId']); ?>">
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

    <!-- new offcanvas -->
    <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasNew" aria-labelledby="offcanvasNewLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasNewLabel">Create User</h5>
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
    <!-- end offcanvas -->

    <!-- edit offcanvas -->
    <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasEdit" aria-labelledby="offcanvasEditLabel">
        <div class="offcanvas-header">
            <div id="data-loader" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <h5 id="offcanvasEditLabel">Edit User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="default.php?page=admin_user_management" method="POST">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Staff ID<span class="text-danger"> *</span></label>
                            <input type="text" id="staffId2" name="staffId" class="form-control" placeholder=" username" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Password<span class="text-danger"> *</span></label>
                            <input type="password" id="password2" name="password" class="form-control" placeholder=" password"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Staff Name<span class="text-danger"> *</span></label>
                            <input type="text" id="name2" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Phone Number<span class="text-danger">*</span></label>
                            <input type="number" id="phonenum2" name="phonenum" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel" style="color:#000000">Department Name:<span class="text-danger"> *</span></label>
                            <!--<input type="text" class="form-control" name="assignmentRef" required>-->
                            <select id="Department2" name="Department" class="form-control " required>
                                <option value="" disabled selected>Please Select Department</option>
                                <?php echo funDepartment(); ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Designation<span class="text-danger">*</span></label>
                            <input type="text" id="designation2" name="designation" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Access Level<span class="text-danger">*</span></label>
                            <select id="accessLevel2" name="accessLevel" class="form-control " required>
                                <option selected="selected"></option>
                                <?php echo funRoles(); ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Email<span class="text-danger"> *</span></label>
                            <input type="email" id="email2" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Status<span class="text-danger"> *</span></label>
                            <select id="status2" name="status" class="form-control " required>
                                <option selected="selected"></option>
                                <option Value="1">Active</option>
                                <option Value="2">Inactive</option>
                                <option Value="3">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                            <button type="submit" name="updatebtn" class="btn btn-primary">Update</button>

                        </div>
                    </div>
                </div>
        </div>
        </form>
    </div>
    <!-- end offcanvas -->


    <!-- Right Sidebar -->
    <?php include('inc_rightbar.php'); ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <?php include('inc_scripts.php'); ?>
    <script>
        $(document).ready(function() {
            $(document).on('click', '#getUser', function(e) {
                e.preventDefault();
                $('#offcanvasEdit').offcanvas('show');
                $('#data-loader').show();
                var id = $(this).data('id');
                console.log(id);
                getRow(id);
            });

            function getRow(id) {
                $.ajax({
                    type: 'GET',
                    url: 'admin_user_management.php',
                    data: {
                        id: id,
                        getUser: 1
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#data-loader').hide();
                        $('#staffId2').val(response.staffId);
                        $('#password2').val(response.Password);
                        $('#name2').val(response.Name);
                        $('#phonenum2').val(response.PhoneNo);
                        $('#Department2').val(response.Department);
                        $('#designation2').val(response.Designation);
                        $('#accessLevel2').val(response.AccessLevel);
                        $('#email2').val(response.Email);
                        $('#status2').val(response.Status);
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        $('#data-loader').hide();
                        console.error(status, error);
                    }
                });
            }

        });
    </script>

</body>

</html>