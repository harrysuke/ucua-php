<?php
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
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


function dataPoint()
{
  global $db;
  $subtotal = 0;
  $percentage = 0;
  $sql = "SELECT tarikh, hari, total, avg FROM viewtotalusagebyyear WHERE YEAR(tarikh)='" . date('Y') . "' ORDER BY tarikh";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "label" => date('M', strtotime($row['tarikh'])),
      "y" => $row['total']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointDay()
{
  global $db;
  $sql = "SELECT tarikh, hari, total, avg FROM viewtotalusagebyyear WHERE YEAR(tarikh)='" . date('Y') . "' ORDER BY tarikh";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "y" => $row['total'],
      "label" => substr($row['hari'], 0, 3)
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointUser()
{
  global $db;
  $subtotal = 0;
  $percentage = 0;
  $sql = "SELECT Name, total, avg FROM viewtotalbyuser ORDER BY total DESC LIMIT 10";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "y" => $row['total'],
      "label" => ucwords(strtolower($row['Name'] ?? ''))
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointDept()
{
  global $db;
  $sql = "SELECT Department, total, avg FROM viewtotalbydept WHERE Department is not null ORDER BY avg DESC";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "label" => $row['Department'],
      "y" => $row['avg']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

$title = 'Dashboard Usage';
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
              <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard_overview" role="tab">
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
                <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard-kpi" role="tab">
                  <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                  <span class="d-none d-sm-block">Summary</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="" href="default.php?page=dashboard-usage" role="tab">
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

          <p class="text-secondary">Drill through report.</p>
          <div class="row">
            <div class="col-6">
              <div id="chartContainerDept" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="col-6">
              <div id="chartContainerUser" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>

          </div><!--end row-->
          <div class="row">
            <div class="col-6">
              <div id="chartContainer" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="col-6">
              <div id="chartContainerDay" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>
          </div><!--end row-->





          <div class="row">
            <div class="col-md-12 ">
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
  <script>
    window.onload = function() {
      funUsageByYear()
      funUsageByDay();
      funTotalByUser();
      funDepartment();
    }

    function funUsageByYear() {
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
          text: "Usage by month of <?php echo date('Y'); ?>"
        },
        axisX: {
          crosshair: {
            enabled: true,
            snapToDataPoint: true
          }
        },
        axisY: {
          title: "Total",
          includeZero: true,
          crosshair: {
            enabled: true,
            snapToDataPoint: true
          }
        },
        toolTip: {
          enabled: false
        },
        data: [{
          type: "area",
          dataPoints: <?php echo json_encode(dataPoint(), JSON_NUMERIC_CHECK); ?>
        }]
      });
      chart.render();
    }

    function funUsageByDay() {
      var chart = new CanvasJS.Chart("chartContainerDay", {
        animationEnabled: true,
        title: {
          text: "Usage by day"
        },
        axisY: {
          title: "Total"
        },
        data: [{
          type: "line",
          dataPoints: <?php echo json_encode(dataPointDay(), JSON_NUMERIC_CHECK); ?>
        }]
      });
      chart.render();
    }

    function funTotalByUser() {
      var chart = new CanvasJS.Chart("chartContainerUser", {
        animationEnabled: true,
        title: {
          text: "MVP"
        },
        axisY: {
          title: "Points",
          includeZero: true,
          prefix: "",
          suffix: ""
        },
        data: [{
          type: "bar",
          yValueFormatString: "#,##0",
          indexLabel: "{y}",
          indexLabelPlacement: "inside",
          indexLabelFontWeight: "bolder",
          indexLabelFontColor: "white",
          dataPoints: <?php echo json_encode(dataPointUser(), JSON_NUMERIC_CHECK); ?>
        }]
      });
      chart.render();
    }

    function funDepartment() {
      var chart = new CanvasJS.Chart("chartContainerDept", {
        animationEnabled: true,
        title: {
          text: "Department"
        },
        subtitles: [{
          text: "<?php echo date('M, Y'); ?>"
        }],
        data: [{
          type: "doughnut",
          yValueFormatString: "#,##0.00\"%\"",
          indexLabel: "{label} ({y})",
          dataPoints: <?php echo json_encode(dataPointDept(), JSON_NUMERIC_CHECK); ?>
        }]
      });

      chart.render();
    }
  </script>
</body>

</html>