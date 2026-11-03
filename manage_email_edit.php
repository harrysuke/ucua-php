<?php
$title = 'Manage Notification / Email Update';
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

function funDept($d)
{
  try {
    global $db;
    $query = "SELECT deptName FROM dept WHERE deptId='" . $d . "' ORDER BY deptName";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);
    if ($row) {
      return $row['deptName'];
    } else {
      return '';
    }
  } catch (Exception $e) {
    return $e->getMessage();
  }
}

function funRole($r)
{
  try {
    global $db;
    $query = "SELECT rolename FROM userroles WHERE id='" . $r . "' ORDER BY rolename";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);
    if ($row) {
      return $row['rolename'];
    } else {
      return '';
    }
  } catch (Exception $e) {
    return $e->getMessage();
  }
}

function funDeptAll()
{
  try {
    global $db;
    $option = '';
    $sql1 = "SELECT deptId, dept, deptName FROM dept WHERE isdelete=0 ORDER BY deptName";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
      $option .= '<option value="' . $row1['deptId'] . '">' . $row1['deptName'] . ' (' . $row1['dept'] . ')</option>';
    }
    return $option;
  } catch (Exception $e) {
    return $e->getMessage();
  }
}

function funRoleAll()
{
  try {
    global $db;
    $option = '';
    $sql1 = "SELECT id, rolename FROM userroles WHERE isdelete=0 ORDER BY id";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
      $option .= '<option value="' . $row1['id'] . '">' . $row1['id'] . ' (' . $row1['rolename'] . ')</option>';
    }
    return $option;
  } catch (Exception $e) {
    return $e->getMessage();
  }
}

if (isset($_POST['updatebtn'])) {
  $sql = "UPDATE `email` SET `HSEname`='" . $_POST['HSEname'] . "',`Email`='" . $_POST['Email'] . "', `DeptName`='" . $_POST['DeptName'] . "', `AccessLevel`='" . $_POST['AccessLevel'] . "' WHERE `EmailId`='" . $_POST['EmailId'] . "' ";
  $result = $db->query($sql);

  if ($result == TRUE) {
    $_SESSION['status'] = "Record added successfully";
    $_SESSION['status_code'] = "success";
  } else {
    $_SESSION['status'] = "Record updated failed";
    $_SESSION['status_code'] = "error";
  }
  //echo $sql;
  header('Location: default.php?page=manage_email');
}

try {
  $sql = "SELECT EmailId, HSEname, Email, DeptName, AccessLevel FROM email WHERE EmailId = '" . trim($_GET['id']) . "' ";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $EmailId = $row['EmailId'];
  $HSEname = $row['HSEname'];
  $Email = $row['Email'];
  $DeptName = $row['DeptName'];
  $AccessLevel = $row['AccessLevel'];
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

              <form action="default.php?page=manage_email_edit" method="POST">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <h6 class="modal-title">Name</h6>
                      <input type="text" name="HSEname" class="form-control" value="<?php echo $HSEname; ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                      <h6 class="modal-title">Email</h6>
                      <input type="text" name="Email" class="form-control" value="<?php echo $Email; ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                      <h6 class="modal-title">Department</h6>
                      <select name="DeptName" class="form-control js-select" required>
                        <option value="<?php echo $DeptName; ?>" selected><?php echo funDept($DeptName); ?></option>
                        <?php echo funDeptAll(); ?>
                      </select>
                    </div>
                    <div class="col-md-12 mb-3">
                      <h6 class="modal-title">AccessLevel</h6>
                      <select id="accessLevel" name="AccessLevel" class="form-control js-select" required>
                        <option value="<?php echo $AccessLevel; ?>" selected><?php echo funRole($AccessLevel); ?></option>
                        <?php echo funRoleAll(); ?>
                      </select>
                    </div>
                    <div class="col-md-12 mb-3">
                      <button type="submit" name="updatebtn" class="btn btn-primary">Update</button>
                      <a href="#" onclick="history.back()" class="btn btn-danger">Cancel</a>
                      <input type="hidden" name="EmailId" value="<?php echo $EmailId; ?>" />
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