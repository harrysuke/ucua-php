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
  $sql = "SELECT form_type, avg FROM viewtotalbytype";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "label" => $row['form_type'],
      "y" => $row['avg']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointLocation()
{
  global $db;
  $sql = "SELECT LocationName, total, avg FROM viewtotalbylocation WHERE LocationName IS NOT NULL AND avg > 10 ORDER BY avg DESC LIMIT 5";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "label" => $row['LocationName'],
      "y" => $row['avg']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointOffence()
{
  global $db;
  $sql = "SELECT Offence, total, avg FROM viewtotalbyoffence WHERE Offence IS NOT NULL ORDER BY avg DESC LIMIT 5";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "label" => substr($row['Offence'], 0, 50) . '...',
      "y" => $row['avg']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointCompany()
{
  global $db;
  $sql = "SELECT violatorCompany, total, avg FROM viewtotalbycompany ORDER BY avg DESC LIMIT 5";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "label" => $row['violatorCompany'],
      "y" => $row['avg']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function getMonthlyTrendData($db)
{
  $sql = "SELECT 
    DATE_FORMAT(submitdate, '%Y-%m') AS YearMonth,
    COUNT(*)                         AS NewCases
FROM ucuaform
WHERE submitdate >= DATE_SUB(DATE_FORMAT(CURDATE(),'%Y-%m-01'), INTERVAL 11 MONTH)
GROUP BY YearMonth
ORDER BY YearMonth;";

  $result = $db->query($sql);
  $dataPoints = [];

  while ($row = $result->fetch_assoc()) {
    $yearMonth = $row['YearMonth'];
    $newCases = (int)$row['NewCases'];

    // Convert 'YYYY-MM' to JS Date object (1st of month)
    [$year, $month] = explode('-', $yearMonth);
    $month = (int)$month - 1; // JS months are 0-indexed

    $dataPoints[] = "{ x: new Date($year, $month, 1), y: $newCases }";
  }

  return implode(",", $dataPoints);
}

function getMonthlyTrendUC($db)
{
  $sql = "SELECT 
    DATE_FORMAT(submitdate, '%Y-%m') AS YearMonth,
    COUNT(*)                         AS NewCases
FROM ucuaform
WHERE form_type='Unsafe Condition' AND submitdate >= DATE_SUB(DATE_FORMAT(CURDATE(),'%Y-%m-01'), INTERVAL 11 MONTH)
GROUP BY YearMonth
ORDER BY YearMonth;";

  $result = $db->query($sql);
  $dataPoints = [];

  while ($row = $result->fetch_assoc()) {
    $yearMonth = $row['YearMonth'];
    $newCases = (int)$row['NewCases'];

    // Convert 'YYYY-MM' to JS Date object (1st of month)
    [$year, $month] = explode('-', $yearMonth);
    $month = (int)$month - 1; // JS months are 0-indexed

    $dataPoints[] = "{ x: new Date($year, $month, 1), y: $newCases }";
  }

  return implode(",", $dataPoints);
}

function getMonthlyTrendUA($db)
{
  $sql = "SELECT 
    DATE_FORMAT(submitdate, '%Y-%m') AS YearMonth,
    COUNT(*)                         AS NewCases
FROM ucuaform
WHERE form_type='Unsafe Action' AND submitdate >= DATE_SUB(DATE_FORMAT(CURDATE(),'%Y-%m-01'), INTERVAL 11 MONTH)
GROUP BY YearMonth
ORDER BY YearMonth;";

  $result = $db->query($sql);
  $dataPoints = [];

  while ($row = $result->fetch_assoc()) {
    $yearMonth = $row['YearMonth'];
    $newCases = (int)$row['NewCases'];

    // Convert 'YYYY-MM' to JS Date object (1st of month)
    [$year, $month] = explode('-', $yearMonth);
    $month = (int)$month - 1; // JS months are 0-indexed

    $dataPoints[] = "{ x: new Date($year, $month, 1), y: $newCases }";
  }

  return implode(",", $dataPoints);
}

$dataPointsJS = getMonthlyTrendData($db);
$dataPointsUC = getMonthlyTrendUC($db);
$dataPointsUA = getMonthlyTrendUA($db);

$title = 'Executive Summary Report';
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
                <a class="nav-link active" data-bs-toggle="" href="default.php?page=dashboard-kpi" role="tab">
                  <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                  <span class="d-none d-sm-block">Summary</span>
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

          <p class="text-secondary"><?php echo $title; ?></p>
          <div class="row">
            <div class="col-12">
              <div id="chartMonthlyTrend" style="height: 370px; width: 100%;"></div>
            </div>
          </div>
          <!--div class="row">
            <div class="col-6">
              <div id="chartMonthlyUC" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="col-6">
              <div id="chartMonthlyUA" style="height: 370px; width: 100%;"></div>
            </div>
          </div-->
          <div class="row">
            <div class="col-6">
              <div class="card card-h-100">
                <div class="card-body">
                  <h5 class="card-title me-2">Condition & action widget</h5>
                  <div id="chartContainer" class="mb-5" style="height: 370px; width: 100%;"></div>
                </div>
              </div><!-- end card-->
            </div>
            <div class="col-6">
              <div class="card card-h-100">
                <div class="card-body">
                  <h5 class="card-title me-2">Location widget</h5>
                  <div id="chartContainerLocation" class="mb-5" style="height: 370px; width: 100%;"></div>
                </div>
              </div>
            </div>
          </div><!--end row-->
          <div class="row">
            <div class="col-6">
              <div class="card card-h-100">
                <div class="card-body">
                  <h5 class="card-title me-2">Offense widget</h5>
                  <div id="chartContainerOffence" class="mb-5" style="height: 370px; width: 100%;"></div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card card-h-100">
                <div class="card-body">
                  <h5 class="card-title me-2">Company widget</h5>
                  <div id="chartContainerCompany" class="mb-5" style="height: 370px; width: 100%;"></div>
                </div>
              </div>
            </div>
          </div><!--end row-->

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
      funFormType()
      funLocation();
      funOffence();
      funCompany();
      funMonthlyTrend();
      //funMonthlyUC();
      //funMonthlyUA();
    }

    function funFormType() {
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
          text: "Condition & action"
        },
        subtitles: [{
          text: "<?php echo date('M, Y'); ?>"
        }],
        data: [{
          type: "pie",
          yValueFormatString: "#,##0.00\"%\"",
          indexLabel: "{label} ({y})",
          dataPoints: <?php echo json_encode(dataPoint(), JSON_NUMERIC_CHECK); ?>
        }]
      });

      chart.render();
    }

    function funLocation() {
      var chart = new CanvasJS.Chart("chartContainerLocation", {
        animationEnabled: true,
        title: {
          text: "Location"
        },
        subtitles: [{
          text: "<?php echo date('M, Y'); ?>"
        }],
        data: [{
          type: "pie",
          yValueFormatString: "#,##0.00\"%\"",
          indexLabel: "{label} ({y})",
          dataPoints: <?php echo json_encode(dataPointLocation(), JSON_NUMERIC_CHECK); ?>
        }]
      });

      chart.render();
    }

    function funOffence() {
      var chart = new CanvasJS.Chart("chartContainerOffence", {
        animationEnabled: true,
        title: {
          text: "Offence"
        },
        subtitles: [{
          text: "<?php echo date('M, Y'); ?>"
        }],
        data: [{
          type: "pie",
          yValueFormatString: "#,##0.00\"%\"",
          indexLabel: "{label} ({y})",
          dataPoints: <?php echo json_encode(dataPointOffence(), JSON_NUMERIC_CHECK); ?>
        }]
      });

      chart.render();
    }

    function funCompany() {
      var chart = new CanvasJS.Chart("chartContainerCompany", {
        animationEnabled: true,
        title: {
          text: "Company"
        },
        subtitles: [{
          text: "<?php echo date('M, Y'); ?>"
        }],
        data: [{
          type: "pie",
          yValueFormatString: "#,##0.00\"%\"",
          indexLabel: "{label} ({y})",
          dataPoints: <?php echo json_encode(dataPointCompany(), JSON_NUMERIC_CHECK); ?>
        }]
      });

      chart.render();
    }

    function funMonthlyTrend() {
      var chart = new CanvasJS.Chart("chartMonthlyTrend", {
        animationEnabled: true,
        exportEnabled: true,
        title: {
          text: "Monthly New Cases Trend"
        },
        axisX: {
          valueFormatString: "MMM YYYY"
        },
        axisY: {
          title: "New Cases"
        },
        data: [{
            type: "spline",
            name: "Unsafe Conditions",
            showInLegend: true,
            dataPoints: [
              <?= $dataPointsUC ?>
            ]
          },
          {
            type: "spline",
            name: "Unsafe Actions",
            showInLegend: true,
            dataPoints: [
              <?= $dataPointsUA ?>
            ]
          }
        ]
      });
      chart.render();
    }

    function funMonthlyUC() {
      var chart = new CanvasJS.Chart("chartMonthlyUC", {
        animationEnabled: true,
        title: {
          text: "Unsafe Condition Monthly New Cases Trend"
        },
        axisX: {
          valueFormatString: "MMM YYYY"
        },
        axisY: {
          title: "New Cases"
        },
        data: [{
          type: "spline",
          name: "Unsafe Conditions",
          showInLegend: true,
          dataPoints: [
            <?= $dataPointsUC ?>
          ]
        }]
      });
      chart.render();
    }

    function funMonthlyUA() {
      var chart = new CanvasJS.Chart("chartMonthlyUA", {
        animationEnabled: true,
        title: {
          text: "Unsafe Action Monthly New Cases Trend"
        },
        axisX: {
          valueFormatString: "MMM YYYY"
        },
        axisY: {
          title: "New Cases"
        },
        data: [{
          type: "spline",
          name: "New Cases",
          showInLegend: true,
          dataPoints: [
            <?= $dataPointsUA ?>
          ]
        }]
      });
      chart.render();
    }
  </script>
</body>

</html>