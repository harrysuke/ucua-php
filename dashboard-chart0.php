<?php
$title = 'Key Performance Indicator';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

function dataPointUC()
{
  global $db;
  $subtotal = 0;
  $percentage = 0;
  $y = date('Y');
  $u = 'Unsafe Condition';
  $sql = "SELECT month, incident_count FROM viewtrendanalysis WHERE form_type=? AND year=? ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ss", $u, $y);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $data = array(
      "label" => date('F', mktime(0, 0, 0, $row['month'], 10)),
      "y" => $row['incident_count']
    );
    $dataPoints[] = $data;
  }
  $stmt->close();
  return $dataPoints;
}

function dataPointUA()
{
  global $db;
  $subtotal = 0;
  $percentage = 0;
  $y = date('Y');
  $u = 'Unsafe Action';
  $sql = "SELECT month, incident_count FROM viewtrendanalysis WHERE form_type=? AND year=? ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ss", $u, $y);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $data = array(
      "label" => date('F', mktime(0, 0, 0, $row['month'], 10)),
      "y" => $row['incident_count']
    );
    $dataPoints[] = $data;
  }
  $stmt->close();
  return $dataPoints;
}

function dataPoints1()
{
  global $db;
  $subtotal = 0;
  $percentage = 0;
  $sql = "SELECT OffenceCode,Offence,year,SUM(incident_count) AS counter FROM `viewoffensetrendanalysis` WHERE OffenceCode=001 GROUP BY OffenceCode, year ORDER BY year ASC";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $data = array(
      "label" => $row['year'],
      "y" => $row['counter']
    );
    $dataPoints[] = $data;
  }
  $stmt->close();
  return $dataPoints;
}

function dataPoints2()
{
  global $db;
  $subtotal = 0;
  $percentage = 0;
  $sql = "SELECT OffenceCode,Offence,year,SUM(incident_count) AS counter FROM `viewoffensetrendanalysis` WHERE OffenceCode=015 GROUP BY OffenceCode, year ORDER BY year ASC ";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $data = array(
      "label" => $row['year'],
      "y" => $row['counter']
    );
    $dataPoints[] = $data;
  }
  $stmt->close();
  return $dataPoints;
}

function dataPoints3()
{
  global $db;
  $subtotal = 0;
  $percentage = 0;
  $c = 'B7';
  $sql = "SELECT LocationId, year, SUM(incident_count) AS counter FROM `viewlocationtrendanalysis` WHERE LocationId=? GROUP BY year ORDER BY year ASC; ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("s", $c);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $data = array(
      "label" => $row['year'],
      "y" => $row['counter']
    );
    $dataPoints[] = $data;
  }
  $stmt->close();
  return $dataPoints;
}

function dataPoints4()
{
  global $db;
  $subtotal = 0;
  $percentage = 0;
  $c = 'P1';
  $sql = "SELECT LocationId, year, SUM(incident_count) AS counter FROM `viewlocationtrendanalysis` WHERE LocationId=? GROUP BY year ORDER BY year ASC; ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("s", $c);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $data = array(
      "label" => $row['year'],
      "y" => $row['counter']
    );
    $dataPoints[] = $data;
  }
  $stmt->close();
  return $dataPoints;
}

include('inc_header.php');
?>

</head>

<body class="pace-done sidebar-enable" data-new-gr-c-s-check-loaded="8.906.0" data-gr-ext-installed="" data-sidebar="brand" data-sidebar-size="sm">
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
              <div class="page-title-box d-sm-flex justify-content-between">
                <!--h4 class="mb-sm-0 font-size-18">Dashboard</h4-->

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
                      <a class="nav-link active" data-bs-toggle="" href="default.php?page=dashboard-chart" role="tab">
                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                        <span class="d-none d-sm-block">Analytics</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard-pie" role="tab">
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
                  <?php } ?>
                </ul>

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

          <p class="text-secondary">Visual analytics dashboard.</p>
          <div class="row">
            <div class="col-12">
              <div class="card card-h-100">
                <div class="card-body">
                  <h5 class="card-title me-2">Condition and action widget</h5>
                  <div id="chartContainer" class="mb-5" style="height: 370px; width: 100%;"></div>
                </div>
              </div>
            </div>
          </div><!--end row-->
          <div class="row">
            <div class="col-6">
              <div class="card card-h-100">
                <div class="card-body">
                  <h5 class="card-title me-2">Unsafe action widget</h5>
                  <div id="chartStacked" class="mb-5" style="height: 370px; width: 100%;"></div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card card-h-100">
                <div class="card-body">
                  <h5 class="card-title me-2">Location widget</h5>
                  <div id="chartStacked2" class="mb-5" style="height: 370px; width: 100%;"></div>
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
      funMulti();
      funStacked();
      funStacked2();
    }

    function funMulti() {
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
          text: "Average unsafe conditions and unsafe actions"
        },
        axisY: {
          includeZero: true
        },
        legend: {
          cursor: "pointer",
          verticalAlign: "center",
          horizontalAlign: "right",
          itemclick: toggleDataSeries
        },
        data: [{
          type: "column",
          name: "Unsafe Conditions",
          indexLabel: "{y}",
          yValueFormatString: "#0.##",
          showInLegend: true,
          dataPoints: <?php echo json_encode(dataPointUC(), JSON_NUMERIC_CHECK); ?>
        }, {
          type: "column",
          name: "Unsafe Actions",
          indexLabel: "{y}",
          yValueFormatString: "#0.##",
          showInLegend: true,
          dataPoints: <?php echo json_encode(dataPointUA(), JSON_NUMERIC_CHECK); ?>
        }]
      });
      chart.render();

      function toggleDataSeries(e) {
        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
          e.dataSeries.visible = false;
        } else {
          e.dataSeries.visible = true;
        }
        chart.render();
      }

    }

    function funStacked() {
      var chart = new CanvasJS.Chart("chartStacked", {
        title: {
          text: "Unsafe actions Trends"
        },
        theme: "light2",
        animationEnabled: true,
        toolTip: {
          shared: true,
          reversed: true
        },
        axisY: {
          title: "Cumulative Capacity",
          suffix: " "
        },
        legend: {
          cursor: "pointer",
          itemclick: toggleDataSeries2
        },
        data: [{
          type: "stackedColumn",
          name: "Not wearing basic mandatory PPE",
          showInLegend: true,
          yValueFormatString: "#,##0 ",
          dataPoints: <?php echo json_encode(dataPoints1(), JSON_NUMERIC_CHECK); ?>
        }, {
          type: "stackedColumn",
          name: "Traffic offenses",
          showInLegend: true,
          yValueFormatString: "#,##0 ",
          dataPoints: <?php echo json_encode(dataPoints2(), JSON_NUMERIC_CHECK); ?>
        }]
      });

      chart.render();

      function toggleDataSeries2(e) {
        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
          e.dataSeries.visible = false;
        } else {
          e.dataSeries.visible = true;
        }
        e.chart.render();
      }

    }

    function funStacked2() {
      var chart = new CanvasJS.Chart("chartStacked2", {
        title: {
          text: "Location Trends"
        },
        theme: "light2",
        animationEnabled: true,
        toolTip: {
          shared: true,
          reversed: true
        },
        axisY: {
          title: "Cumulative Capacity",
          suffix: " "
        },
        legend: {
          cursor: "pointer",
          itemclick: toggleDataSeries3
        },
        data: [{
          type: "stackedColumn",
          name: "BBT",
          showInLegend: true,
          yValueFormatString: "#,##0 ",
          dataPoints: <?php echo json_encode(dataPoints3(), JSON_NUMERIC_CHECK); ?>
        }, {
          type: "stackedColumn",
          name: "PORT SAFETY",
          showInLegend: true,
          yValueFormatString: "#,##0 ",
          dataPoints: <?php echo json_encode(dataPoints4(), JSON_NUMERIC_CHECK); ?>
        }]
      });

      chart.render();

      function toggleDataSeries3(e) {
        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
          e.dataSeries.visible = false;
        } else {
          e.dataSeries.visible = true;
        }
        e.chart.render();
      }

    }
  </script>
</body>

</html>