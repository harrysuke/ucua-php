<?php
$title = 'Dashboard Settings';
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

function checkWidget($did, $cid, $wid, $wname)
{
  global $db;
  $w = '';
  $sid = trim($_SESSION['user']['staffId']);
  $sql = "SELECT staff_no, dashboard_id, widget_id, display FROM dashboard_settings WHERE staff_no = ? AND dashboard_id = ? AND widget_id = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("sii", $sid, $did, $wid);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  if ($row) {
    $w .= '<div>
            <div class="form-check">
              <label class="form-check-label" for="w' . $wid . '">
                ' . $wname . '
              </label>
              <input class="form-check-input" type="checkbox" id="w' . $wid . '" name="w' . $wid . '" value="' . $wid . '|1" checked>
            </div>
          </div>';
  } else {
    $w .= '<div>
            <div class="form-check">
              <label class="form-check-label" for="w' . $wid . '">
                ' . $wname . '
              </label>
              <input class="form-check-input" type="checkbox" id="w' . $wid . '" name="w' . $wid . '" value="' . $wid . '|1" >
            </div>
          </div>';
  }
  return $w;
}

function callWidgets($did, $cid)
{
  global $db;
  $w = '';
  $sql = "SELECT id, widget_name, widget_desc FROM dashboard_widgets WHERE subcat = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", $cid);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $w .= checkWidget($did, $cid, $row['id'], $row['widget_name']);
  }
  $stmt->close();
  return $w;
}

function callWidget($did, $id)
{
  global $db;
  $w = '';
  $sql = "SELECT id, widget_name, widget_desc FROM dashboard_widgets WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $w .= checkWidget($did, 0, $row['id'], $row['widget_name']);
  }
  $stmt->close();
  return $w;
}

function callSave($staffno, $did, $wid, $d)
{
  global $db;
  $sql = "INSERT INTO dashboard_settings (staff_no, dashboard_id, widget_id, display) VALUES (?,?,?,?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("siii", $staffno, $did, $wid, $d);
  $stmt->execute();
  $stmt->close();
}

function funCounter()
{
  global $db;
  $sql = "SELECT COUNT(*) AS counter FROM dashboard_widgets ";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row['counter'];
}

function showDashboard()
{
  global $db;
  $d = '<li class="dropdown-header">Available dashboards</li>';
  try {
    $sid = trim($_SESSION['user']['staffId']);
    $sql = "SELECT id, dashboard_name, dashboard_desc FROM dashboard WHERE createby = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $sid);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $d .= '<li><a class="dropdown-item" href="default.php?page=dashboards&id=' . $row['id'] . '">' . $row['dashboard_name'] . '</a></li>';
    }
    $stmt->close();
    return $d;
  } catch (Exception $e) {
    die($e->getMessage());
  }
}

if (isset($_POST['submit'])) {
  try {
    $sql = "INSERT INTO dashboard (dashboard_name, dashboard_desc, createby, createdate) VALUES (?,?,?,?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssss", $_POST['dashName'], $_POST['desc'], $_SESSION['user']['staffId'], date('Y-m-d H:i:s'));
    $stmt->execute();
    $stmt->close();
    $last_id = $db->insert_id;

    $counter = $_POST['counter'];
    for ($i = 1; $i <= $counter; $i++) {
      if (!empty($_POST['w' . $i])) {
        $widget = explode("|", $_POST['w' . $i]);
        callSave($_SESSION['user']['staffId'], $last_id, $widget[0], $widget[1]);
      }
    }

    header("Location: default.php?page=dashboard_new&id=$last_id");
    exit;
  } catch (mysqli_sql_exception $e) {
    die($e->getMessage());
  } catch (Exception $e) {
    die($e->getMessage());
  }
}

try {
  $did = trim($_GET['id']);
  $sql = "SELECT id, dashboard_name, dashboard_desc FROM dashboard WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("s", $did);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  if ($row) {
    $dashboard_id = $row['id'];
    $dashboard_name = $row['dashboard_name'];
    $dashboard_desc = $row['dashboard_desc'];
  } else {
    $dashboard_id = 0;
    $dashboard_name = '';
    $dashboard_desc = '';
  }
  $stmt->close();
} catch (Exception $e) {
  die($e->getMessage());
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('inc_header.php');
?>
,?
</head>

<body class="pace-done" data-new-gr-c-s-check-loaded="8.904.0" data-gr-ext-installed="" data-sidebar="brand">
  <div class="pace pace-inactive">
    <div class="pace-progress" style="transform: translate3d(100%, 0px, 0px)" data-progress-text="100%"
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
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                  </ol>
                </div>

              </div>
            </div>
          </div>
          <!-- end page title -->

          <!-- start page title -->
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <h5 class="card-title">Add New Dashboard</h5>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">
                <div class="dropdown">
                  <a class="btn btn-link text-muted py-1 font-size-16 shadow-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <?php echo showDashboard(); ?>
                    <li><a class="dropdown-item" href="default.php?page=dashboard_new">New Dashboard</a></li>
                    <?php if (!empty($dashboard_id)) { ?>
                      <li><a class="dropdown-item" href="#">Edit Dashboard</a></li>
                    <?php } ?>
                    <li class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="default.php?page=dashboard_manager">Manage Dashboards</a></li>
                  </ul>
                </div>
              </div>

            </div>
          </div>
          <!-- end page title -->

          <form method="post" action="default.php?page=dashboard_new">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="dashName" class="form-label">Dashboard Name</label>
                      <input class="form-control" type="text" name="dashName" id="dashName" placeholder="Dashboard Name" value="<?php echo $dashboard_name; ?>" required>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="desc" class="form-label">Description</label>
                      <input class="form-control" type="text" name="desc" id="desc" placeholder="Dashboard Description" value="<?php echo $dashboard_desc; ?>" required>
                    </div>
                  </div>
                </div><!--Row end-->
              </div><!--end col-->
            </div>

            <div class="row">
              <div class="col-4">
                <div class="card border border-0">
                  <div class="card-header">
                    <div class="mt-4 mt-md-0">
                      <?php echo callWidget($dashboard_id, 30); ?>
                    </div>
                  </div>
                  <div class="card-body p-4">
                    <div class="mt-4 mt-md-0">
                      <?php echo callWidgets($dashboard_id, 30); ?>
                    </div>
                  </div><!--Card body end-->
                </div><!--Card end-->
                <div class="card border border-0">
                  <div class="card-header">
                    <?php echo callWidget($dashboard_id, 34); ?>
                  </div>
                  <div class="card-body p-4">
                    <div class="mt-4 mt-md-0">
                      <?php echo callWidgets($dashboard_id, 34); ?>
                    </div>
                  </div>
                </div><!--Card end-->

              </div>
              <div class="col-4">
                <div class="card border border-0">
                  <div class="card-header">
                    <?php echo callWidget($dashboard_id, 31); ?>

                  </div>
                  <div class="card-body p-4">
                    <div class="mt-4 mt-md-0">
                      <?php echo callWidgets($dashboard_id, 31); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border border-0">
                  <div class="card-header">
                    <?php echo callWidget($dashboard_id, 32); ?>
                  </div>
                  <div class="card-body">
                    <div class="mt-4 mt-md-0">
                      <?php echo callWidgets($dashboard_id, 32); ?>
                    </div>
                  </div>
                </div><!--Card end-->
                <div class="card border border-0">
                  <div class="card-header">
                    <?php echo callWidget($dashboard_id, 33); ?>
                  </div>
                  <div class="card-body">
                    <div class="mt-4 mt-md-0">
                      <?php echo callWidgets($dashboard_id, 33); ?>
                    </div>
                  </div>
                </div><!--Card end-->

              </div><!--Col end-->
            </div><!--Row end-->

            <div class="row">
              <div class="col-12">
                <div class="modal-footer mb-4">
                  <a href="default.php?page=dashboard" class="btn btn-secondary">Cancel</a>&nbsp;
                  <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                  <input type="hidden" name="counter" value="<?php echo funCounter(); ?>" />
                  <input type="hidden" name="did" value="<?php echo $_GET['id']; ?>" />
                </div>
              </div>
            </div>
          </form>

          <?php //echo json_encode($_SESSION['user']); 
          ?>

        </div>
        <!-- container-fluid -->
      </div>
      <!-- End Page-content -->

      <!-- right offcanvas -->
      <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasRight"
        aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
          <h5 id="offcanvasRightLabel">Learning center</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <h6 class="text-dark">Video tour</h6>
          <p class="text-secondary">Click the view more link below for more video tour.</p>
          <div class="ratio ratio-16x9">
            <iframe
              src="https://player.vimeo.com/video/875812985?badge=0&amp;autopause=0&amp;quality_selector=1&amp;progress_bar=1&amp;player_id=0&amp;app_id=58479"
              title="YouTube video" allowfullscreen></iframe>
          </div>

          <div class="d-grid mt-2">
            <a class="btn btn-sm btn-link font-size-14 text-center" href="default.php?page=learn">
              <span>View more..</span>
            </a>
          </div>
          <hr />

          <h6 class="text-dark mt-4">Help articles</h6>
          <div class="d-flex mb-2">
            <div class="flex-shrink-0 me-3">
              <i class="fa-solid fa-file-pdf fa-2xl pt-4"></i>
            </div>
            <div class="flex-grow-1">
              <a href="docs/UCUA_2.0_Guide_V2.pdf" target="_blank" class="fw-bold text-dark">User guide</a>
              <p class="text-secondary">Check out our User Guide for tips, how-tos, and if you're looking for help.</p>
            </div>
          </div>
          <div class="d-flex mb-2">
            <div class="flex-shrink-0 me-3">
              <i class="fa-regular fa-file-pdf fa-2xl pt-4"></i>
            </div>
            <div class="flex-grow-1">
              <a href="docs/docs.pdf" target="_blank" class="fw-bold text-dark">Extra guide</a>
              <p class="text-secondary">Check out our user guide for tips, how-tos, and if you're looking for help.</p>
            </div>
          </div>
          <hr />

          <h6 class="text-dark mt-4">Support</h6>
          <div class="d-flex mb-2">
            <div class="flex-shrink-0 me-3">
              <i class="fa-regular fa-paper-plane fa-2xl pt-4"></i>
            </div>
            <div class="flex-grow-1">
              <a href="mailto:icthelpdesk@johorport.com.my?subject=Help&body=Feedback" class="fw-bold text-dark">Get
                help</a>
              <p class="text-secondary">Send us comments, feedback or report issues to helpdesk.</p>
            </div>
          </div>
        </div>
      </div>
      <!-- end offcanvas -->

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