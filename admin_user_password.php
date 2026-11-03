<?php
$title = 'User Management / Update Password';
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

function accessLevel()
{
  global $db;
  $option = '';
  $query = "SELECT id, rolename FROM userroles WHERE isdelete=0 ORDER BY id";
  $result = mysqli_query($db, $query);
  while ($row = mysqli_fetch_array($result)) {
    $option .= '<option value="' . $row['id'] . '">' . $row['id'] . ' (' . $row['rolename'] . ')</option>';
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

function accessName($n)
{
  $option = '';
  try {
    global $db;
    $query = "SELECT rolename FROM userroles WHERE id = " . $n;
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);
    $option = $row['rolename'];
  } catch (Exception $e) {
    $option = $n;
  }
  return $option;
}

function funDept()
{
  global $db;
  $option = '';
  $query = "SELECT * FROM dept WHERE isdelete=0 ORDER BY deptName";
  $result = mysqli_query($db, $query);
  while ($row = mysqli_fetch_array($result)) {
    $option .= '<option value="' . $row['dept'] . '">' . $row['deptName'] . '(' . $row['dept'] . ')</option>';
  }
  return $option;
}

function funStatus($Status)
{
  $s = '';
  if ($Status == 1) {
    $s = "Active";
  } else if ($Status == 2) {
    $s = "Inactive";
  } else {
    $s = "Pending";
  }
  return $s;
}

try {
  $sql = "SELECT * FROM user WHERE `staffId` = '" . base64_decode($_GET['id']) . "' ";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $staffId = $row['staffId'];
  $Password = $row['Password'];
  $Name = $row['Name'];
  $PhoneNo = $row['PhoneNo'];
  $Department = $row['Department'];
  $Designation = $row['Designation'];
  $AccessLevel = $row['AccessLevel'];
  $Email = $row['Email'];
  $Status = $row['Status'];
} catch (Exception $e) {
  die($e->getMessage());
}

if (isset($_GET['del'])) {
  $sql = "UPDATE USER SET isdelete=1, deleteby='" . $_SESSION['user']['Name'] . "', deletedate='" . date('Y-m-d h:i:s') . "' WHERE staffId = '" . base64_decode($_GET['del']) . "' ";
  mysqli_query($db, $sql);
  header('Location: default.php?page=admin_user_management');
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

  // write the update query
  $sql = "UPDATE `user` SET `Password`='" . $password . "', `Password1`='" . $password . "' WHERE `staffId`='" . $staffId . "' ";
  $result = $db->query($sql);

  if ($result == TRUE) {
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
              <form action="default.php?page=admin_user_password" method="POST">
                <div class="row">
                  <div class="col-6">
                    <div class="mb-3">
                      <h6 class="modal-title" id="exampleModalLabel">Password <span class="text-danger"> *</span></h6>
                      <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <h6 class="modal-title" id="exampleModalLabel">Confirm password <span class="text-danger"> *</span></h6>
                      <input type="password" name="password1" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <button type="submit" name="updatebtn" class="btn btn-primary">Update</button>
                      <a href="default.php?page=admin_user_management" class="btn btn-danger">Cancel</a>
                      <input type="hidden" name="staffId" value="<?php echo $staffId; ?>" />
                    </div>
                  </div>
                </div>
              </form>
            </div><!--card-body-->


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