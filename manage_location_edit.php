<?php
$title = 'Manage Location / Update Location';
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

if (isset($_POST['updatebtn'])) {
  $LocationId = $_POST['LocationId'];
  $LocationName = $_POST['LocationName'];
  $sql = "UPDATE `location` SET `LocationId`=?,`LocationName`=? WHERE `LocationId`=? ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("isi", $LocationId, $LocationName, $LocationId);
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    $_SESSION['status'] = "Location Details Has Been Update";
    $_SESSION['status_code'] = "success";
    header('Location: default.php?page=manage_location');
  } else {
    $_SESSION['status'] = "Error Update Data";
    $_SESSION['status_code'] = "error";
    header('Location: default.php?page=manage_location');
  }
}

try {
  $id = $_GET['id'];
  $sql = "SELECT * FROM location WHERE `LocationId` = ? ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $LocationId = $row['LocationId'];
  $LocationName = $row['LocationName'];
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

              <form action="default.php?page=manage_location_edit" method="POST">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <h6 class="modal-title" id="exampleModalLabel">Location Code</h6>
                      <input type="text" name="LocationId" class="form-control" value="<?php echo $LocationId; ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                      <h6 class="modal-title" id="exampleModalLabel">Location Address / Name</h6>
                      <input type="text" name="LocationName" class="form-control"
                        value="<?php echo $LocationName; ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                      <button type="submit" name="updatebtn" class="btn btn-primary">Update</button>
                      <a href="#" onclick="history.back()" class="btn btn-danger">Cancel</a>
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