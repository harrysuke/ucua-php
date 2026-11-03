<?php
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

function funYear()
{
  global $db;
  $y = '';
  $sql = "SELECT YEAR(observerDatetime) as tahun FROM `ucuaform` WHERE YEAR(observerDatetime) <> 0 GROUP BY YEAR(observerDatetime)";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $y .= '<option value="default.php?page=dashboard_overview&y=' . $row['tahun'] . '">' . $row['tahun'] . '</option>';
  }
  return $y;
}

function funUC($l, $id)
{
  global $db;
  if ($l == 6 || $l == 7 || $l == 8 || $l == 9) {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE form_type = 'Unsafe Condition' ";
  } else {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE form_type = 'Unsafe Condition' AND staffId='" . $id . "' ";
  }
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funUA($l, $id)
{
  global $db;
  if ($l == 6 || $l == 7 || $l == 8 || $l == 9) {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE form_type = 'Unsafe Action' ";
  } else {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE form_type = 'Unsafe Action' AND staffId='" . $id . "' ";
  }
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funHseRepsNotify($l, $id)
{
  global $db;
  if ($l == 6 || $l == 7 || $l == 8 || $l == 9) {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isnotifysherep=1 ";
  } else {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isnotifysherep=1 AND staffId='" . $id . "' ";
  }
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funHosNotify($l, $id)
{
  global $db;
  if ($l == 6 || $l == 7 || $l == 8 || $l == 9) {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isNotifyHos=1 ";
  } else {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isNotifyHos=1 AND staffId='" . $id . "' ";
  }
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funHosApproved($l, $id)
{
  global $db;
  if ($l == 6 || $l == 7 || $l == 8 || $l == 9) {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isHosApproved=1 ";
  } else {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isHosApproved=1 AND staffId='" . $id . "' ";
  }
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funHodApproved($l, $id)
{
  global $db;
  if ($l == 6 || $l == 7 || $l == 8 || $l == 9) {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isHODapproved=1 ";
  } else {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isHODapproved=1 AND staffId='" . $id . "' ";
  }
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funUcuaClose($l, $id)
{
  global $db;
  if ($l == 6 || $l == 7 || $l == 8 || $l == 9) {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isClosed=1 ";
  } else {
    $sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE isClosed=1 AND staffId='" . $id . "' ";
  }
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funTotalUsers($l, $id)
{
  global $db;
  $sql = "SELECT COUNT(*) AS counter FROM user";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funActiveUsers($l, $id)
{
  global $db;
  $sql = "SELECT COUNT(*) AS counter FROM user WHERE Status=1";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funInactiveUsers($l, $id)
{
  global $db;
  $sql = "SELECT COUNT(*) AS counter FROM user WHERE Status=2";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function funPendingUsers($l, $id)
{
  global $db;
  $sql = "SELECT COUNT(*) AS counter FROM user WHERE Status=3";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

function sparkline($int)
{
  $array = str_split($int);
  return implode(",", $array);
}

function historyLogs($id)
{
  global $db;
  $activity = '';
  $sql = "SELECT * FROM history WHERE staffid = '" . $id . "' AND YEAR(datetimestamp)='" . date('Y') . "' ORDER BY datetimestamp desc";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $activity .= '<li class="activity-list activity-border">
                        <div class="activity-icon avatar-md">
                            <img src="img/blank.jpg" alt="" class="rounded-circle avatar-md">
                        </div>
                        <div class="timeline-list-item text-start">
                            <div class="d-flex">
                                <div class="flex-grow-1 overflow-hidden me-4">
                                    <h5 class="font-size-14 mb-1 text-capitalize">' . $row['staffid'] . '</h5>
                                    <p class="text-truncate text-muted font-size-13">' . $row['action'] . ': ' . $row['activity'] . '</p>
                                </div>
                                <div class="flex-shrink-0 text-end me-3">
                                    <h6 class="mb-1">' . $row['status'] . '</h6>
                                    <div class="font-size-13">' . date('d M, Y', strtotime($row['datetimestamp'])) . '</div>
                                </div>
                                <div class="flex-shrink-0 text-end">
                                    <div class="dropdown"></div>
                                </div>
                            </div>
                        </div> 
                    </li>';
  }
  return $activity;
}

$title = 'Dashboard Overview';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('inc_header.php');
?>

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
          <div class="row mb-0">
            <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="float-start">
                  <h4 class="mb-sm-0 font-size-18">
                    <?php echo $title; ?>
                    <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                  </h4>
                  <p>Welcome to your <?php echo $_SESSION['company']['brandName']; ?> <?php echo $_SESSION['user']['rolename']; ?> account</p>
                </div>
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

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard" role="tab">
                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                <span class="d-none d-sm-block">Quick Start</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="" href="default.php?page=dashboard_overview" role="tab">
                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                <span class="d-none d-sm-block">Overview</span>
              </a>
            </li>
            <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
              <li class="nav-item">
                <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard-chart" role="tab">
                  <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                  <span class="d-none d-sm-block">KPI</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard-usage" role="tab">
                  <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                  <span class="d-none d-sm-block">Usage</span>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">More&hellip;</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="default.php?page=feeds">Feeds</a></li>
                  <li><a class="dropdown-item" href="default.php?page=leaderboard">Leaderboard</a></li>
                  <li><a class="dropdown-item" href="default.php?page=favorite">Favorites</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="default.php?page=workspaces">Workspaces</a></li>
                </ul>
              </li>
            <?php } ?>
          </ul>

          <div class="float-start text-secondary">Here's what happened with <?php echo $_SESSION['company']['brandName']; ?> in <?php echo date('M, Y'); ?>.</div>
          <div class="float-end">
            <select class="form-select form-select-sm" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
              <option value="" selected="">Filter</option>
              <?php echo funYear(); ?>
            </select>
          </div>
          <div class="clearfix"></div>

          <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
            <div class="row">
              <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-h-100 bg-primary-subtle">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-6">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total users</span>
                        <h4 class="mb-3">
                          <span class="counter-value" data-target="<?php echo funTotalUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                        </h4>
                      </div>
                      <div class="col-6">
                        <div class="sparkline-bar-stats float-end"><?php echo sparkline(funTotalUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                      </div>
                    </div>
                    <div class="text-nowrap">
                      <span class="badge bg-dark text-white">
                        <?php echo funTotalUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                      </span>
                      <span class="ms-1 text-muted font-size-13">Total users</span>
                    </div>
                  </div><!-- end card body -->
                </div><!-- end card -->
              </div><!-- end col -->
              <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-h-100 bg-success-subtle">
                  <!-- card body -->
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-6">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Active users</span>
                        <h4 class="mb-3">
                          <span class="counter-value" data-target="<?php echo funActiveUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                        </h4>
                      </div>
                      <div class="col-6">
                        <div class="sparkline-bar-stats float-end"><?php echo sparkline(funActiveUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                      </div>
                    </div>
                    <div class="text-nowrap">
                      <span class="badge bg-dark text-white">
                        <?php echo funActiveUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                      </span>
                      <span class="ms-1 text-muted font-size-13">Active users</span>
                    </div>
                  </div><!-- end card body -->
                </div><!-- end card -->
              </div><!-- end col -->
              <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-h-100 bg-warning-subtle">
                  <!-- card body -->
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-6">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Pending users</span>
                        <h4 class="mb-3">
                          <span class="counter-value" data-target="<?php echo funPendingUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                        </h4>
                      </div>
                      <div class="col-6">
                        <div class="sparkline-bar-stats float-end"><?php echo sparkline(funPendingUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                      </div>
                    </div>
                    <div class="text-nowrap">
                      <span class="badge bg-dark text-white">
                        <?php echo funPendingUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                      </span>
                      <span class="ms-1 text-muted font-size-13">Pending users</span>
                    </div>
                  </div><!-- end card body -->
                </div><!-- end card -->
              </div><!-- end col -->
              <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-h-100 bg-secondary-subtle">
                  <!-- card body -->
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-6">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Inactive users</span>
                        <h4 class="mb-3">
                          <span class="counter-value" data-target="<?php echo funInactiveUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                        </h4>
                      </div>

                      <div class="col-6">
                        <div class="sparkline-bar-stats float-end"><?php echo sparkline(funInactiveUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                      </div>
                    </div>
                    <div class="text-nowrap">
                      <span class="badge bg-dark text-white">
                        <?php echo funInactiveUsers($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                      </span>
                      <span class="ms-1 text-muted font-size-13">Inactive users</span>
                    </div>
                  </div><!-- end card body -->
                </div><!-- end card -->
              </div><!-- end col -->
            </div><!--end row-->
          <?php } ?>




          <div class="row">
            <div class="col-xl-3 col-md-6">
              <!-- card -->
              <div class="card card-h-100 bg-light-subtle">
                <!-- card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-6">
                      <span class="text-muted mb-3 lh-1 d-block text-truncate">Unsafe Condition</span>
                      <h4 class="mb-3">
                        <span class="counter-value" data-target="<?php echo funUC($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                      </h4>
                    </div>

                    <div class="col-6">
                      <div class="sparkline-bar-stats float-end"><?php echo sparkline(funUC($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                    </div>
                  </div>
                  <div class="text-nowrap">
                    <span class="badge bg-success-subtle text-success">
                      <?php echo funUC($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                    </span>
                    <span class="ms-1 text-muted font-size-13">Unsafe condition created</span>
                  </div>
                </div><!-- end card body -->
              </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
              <!-- card -->
              <div class="card card-h-100 bg-light-subtle">
                <!-- card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-6">
                      <span class="text-muted mb-3 lh-1 d-block text-truncate">Unsafe Action</span>
                      <h4 class="mb-3">
                        <span class="counter-value" data-target="<?php echo funUA($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                      </h4>
                    </div>
                    <div class="col-6">
                      <div class="sparkline-bar-stats float-end"><?php echo sparkline(funUA($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                    </div>
                  </div>
                  <div class="text-nowrap">
                    <span class="badge bg-success-subtle text-success">
                      <?php echo funUA($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                    </span>
                    <span class="ms-1 text-muted font-size-13">Unsafe action created</span>
                  </div>
                </div><!-- end card body -->
              </div><!-- end card -->
            </div><!-- end col-->

            <div class="col-xl-3 col-md-6">
              <!-- card -->
              <div class="card card-h-100 bg-light-subtle">
                <!-- card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-6">
                      <span class="text-muted mb-3 lh-1 d-block text-truncate">Hse Reps Notified</span>
                      <h4 class="mb-3">
                        <span class="counter-value" data-target="<?php echo funHseRepsNotify($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                      </h4>
                    </div>
                    <div class="col-6">
                      <div class="sparkline-bar-stats float-end"><?php echo sparkline(funHseRepsNotify($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                    </div>
                  </div>
                  <div class="text-nowrap">
                    <span class="badge bg-success-subtle text-success">
                      <?php echo funHseRepsNotify($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                    </span>
                    <span class="ms-1 text-muted font-size-13">Hse Reps Notified</span>
                  </div>
                </div><!-- end card body -->
              </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
              <!-- card -->
              <div class="card card-h-100 bg-light-subtle">
                <!-- card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-6">
                      <span class="text-muted mb-3 lh-1 d-block text-truncate">HOS Notified</span>
                      <h4 class="mb-3">
                        <span class="counter-value" data-target="<?php echo funHosNotify($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                      </h4>
                    </div>
                    <div class="col-6">
                      <div class="sparkline-bar-stats float-end"><?php echo sparkline(funHosNotify($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                    </div>
                  </div>
                  <div class="text-nowrap">
                    <span class="badge bg-success-subtle text-success">
                      <?php echo funHosNotify($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                    </span>
                    <span class="ms-1 text-muted font-size-13">HOS Notified</span>
                  </div>
                </div><!-- end card body -->
              </div><!-- end card -->
            </div><!-- end col -->
          </div><!-- end row-->

          <div class="row">
            <div class="col-xl-3 col-md-6">
              <!-- card -->
              <div class="card card-h-100 bg-light-subtle">
                <!-- card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-6">
                      <span class="text-muted mb-3 lh-1 d-block text-truncate">HOS Approved</span>
                      <h4 class="mb-3">
                        <span class="counter-value" data-target="<?php echo funHosApproved($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                      </h4>
                    </div>

                    <div class="col-6">
                      <div class="sparkline-bar-stats float-end"><?php echo sparkline(funHosApproved($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                    </div>
                  </div>
                  <div class="text-nowrap">
                    <span class="badge bg-primary-subtle text-primary">
                      <?php echo funHosApproved($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                    </span>
                    <span class="ms-1 text-muted font-size-13">HOS Approved</span>
                  </div>
                </div><!-- end card body -->
              </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
              <!-- card -->
              <div class="card card-h-100 bg-light-subtle">
                <!-- card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-6">
                      <span class="text-muted mb-3 lh-1 d-block text-truncate">HOD Notified</span>
                      <h4 class="mb-3">
                        <span class="counter-value" data-target="<?php echo funHosApproved($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                      </h4>
                    </div>
                    <div class="col-6">
                      <div class="sparkline-bar-stats float-end"><?php echo sparkline(funHosApproved($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                    </div>
                  </div>
                  <div class="text-nowrap">
                    <span class="badge bg-primary-subtle text-primary">
                      <?php echo funHosApproved($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                    </span>
                    <span class="ms-1 text-muted font-size-13">HOD Notified</span>
                  </div>
                </div><!-- end card body -->
              </div><!-- end card -->
            </div><!-- end col-->

            <div class="col-xl-3 col-md-6">
              <!-- card -->
              <div class="card card-h-100 bg-light-subtle">
                <!-- card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-6">
                      <span class="text-muted mb-3 lh-1 d-block text-truncate">HOD Approved</span>
                      <h4 class="mb-3">
                        <span class="counter-value" data-target="<?php echo funHodApproved($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                      </h4>
                    </div>
                    <div class="col-6">
                      <div class="sparkline-bar-stats float-end"><?php echo sparkline(funHodApproved($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                    </div>
                  </div>
                  <div class="text-nowrap">
                    <span class="badge bg-primary-subtle text-primary">
                      <?php echo funHodApproved($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                    </span>
                    <span class="ms-1 text-muted font-size-13">HOD Approved</span>
                  </div>
                </div><!-- end card body -->
              </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
              <!-- card -->
              <div class="card card-h-100 bg-light-subtle">
                <!-- card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-6">
                      <span class="text-muted mb-3 lh-1 d-block text-truncate">UCUA Closed</span>
                      <h4 class="mb-3">
                        <span class="counter-value" data-target="<?php echo funUcuaClose($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>">0</span>
                      </h4>
                    </div>
                    <div class="col-6">
                      <div class="sparkline-bar-stats float-end"><?php echo sparkline(funUcuaClose($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId'])); ?></div>
                    </div>
                  </div>
                  <div class="text-nowrap">
                    <span class="badge bg-primary-subtle text-primary">
                      <?php echo funUcuaClose($_SESSION['user']['AccessLevel'], $_SESSION['user']['staffId']); ?>
                    </span>
                    <span class="ms-1 text-muted font-size-13">UCUA Closed</span>
                  </div>
                </div><!-- end card body -->
              </div><!-- end card -->
            </div><!-- end col -->
          </div><!-- end row-->

          <div class="row">
            <div class="col-md-12 themed-grid-col">
              <!--div class="card">
                <div class="card-header text-start d-flex">
                  <h4 class="card-title mb-0 flex-grow-1">Recent Logs</h4>
                  <div class="flex-shrink-0">
                  </div>
                </div>
                <div class="card-body px-0">
                  <div class="px-3" data-simplebar>
                    <ul class="list-unstyled activity-wid mb-0">
                    <?php echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>'; ?>
                    </ul>
                  </div>
                </div>
              </div-->
              <!-- end card -->
              <!--div class="card">
                <div class="card-header text-start d-flex">
                  <h4 class="card-title mb-0 flex-grow-1">Recent Logs</h4>
                  <div class="flex-shrink-0">
                  </div>
                </div>
                <div class="card-body px-0">
                  <div class="px-3" data-simplebar style="max-height: 270px">
                    <ul class="list-unstyled activity-wid mb-0">
                      <?php echo historyLogs($_SESSION['user']['staffId']); ?>
                    </ul>
                  </div>
                </div>
              </div-->
              <!-- end card -->
            </div>
          </div>
        </div>
        <!-- container-fluid -->
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