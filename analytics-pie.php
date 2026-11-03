<?php
$title = 'Dashboard';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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


function dataPoint(){
  global $db;
  $subtotal = 0;
  $percentage = 0;
  $sql = "SELECT form_type, avg FROM viewtotalbytype";
  $result = mysqli_query($db,$sql);
  while($row = mysqli_fetch_array($result)){
    $data = array(
      "label"=>$row['form_type'] , "y"=> $row['avg']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointYear(){
  global $db;
  $sql = "SELECT COUNT(*) AS sum, YEAR(observerdatetime) AS tahun FROM ucuaform GROUP BY YEAR(observerdatetime)";
  $result = mysqli_query($db,$sql);
  while($row = mysqli_fetch_array($result)){
    $data = array(
      "label"=>$row['tahun'] , "y"=> $row['sum']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointYearUC(){
  global $db;
  $sql = "SELECT COUNT(*) AS sum, YEAR(observerdatetime) AS tahun FROM ucuaform WHERE form_type='Unsafe Condition' GROUP BY YEAR(observerdatetime)";
  $result = mysqli_query($db,$sql);
  while($row = mysqli_fetch_array($result)){
    $data = array(
      "label"=>$row['tahun'] , "y"=> $row['sum']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointYearUA(){
  global $db;
  $sql = "SELECT COUNT(*) AS sum, YEAR(observerdatetime) AS tahun FROM ucuaform WHERE form_type='Unsafe Action' GROUP BY YEAR(observerdatetime)";
  $result = mysqli_query($db,$sql);
  while($row = mysqli_fetch_array($result)){
    $data = array(
      "label"=>$row['tahun'] , "y"=> $row['sum']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointMonthUC()
{
  global $db;
  $sql = "SELECT COUNT(*) AS sum, MONTH(observerdatetime) AS bulan FROM ucuaform WHERE form_type='Unsafe Condition' AND YEAR(observerdatetime)='" . date('Y') . "' GROUP BY MONTH(observerdatetime)";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "label" => date("F",mktime(0,0,0,$row['bulan'],10)),
      "y" => $row['sum']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointMonthUA()
{
  global $db;
  $sql = "SELECT COUNT(*) AS sum, MONTH(observerdatetime) AS bulan FROM ucuaform WHERE form_type='Unsafe Action' AND YEAR(observerdatetime)='" . date('Y') . "' GROUP BY MONTH(observerdatetime)";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data = array(
      "label" => date("F",mktime(0,0,0,$row['bulan'],10)),
      "y" => $row['sum']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointLocation(){
  global $db;
  $sql = "SELECT LocationName, total, avg FROM viewtotalbylocation WHERE LocationName IS NOT NULL AND avg > 10 ORDER BY avg DESC";
  $result = mysqli_query($db,$sql);
  while($row = mysqli_fetch_array($result)){
    $data = array(
      "label"=>$row['LocationName'] , "y"=> $row['avg']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointOffence(){
  global $db;
  $sql = "SELECT offence, total, avg FROM viewtotalbyoffence WHERE offence is not null ORDER BY avg DESC";
  $result = mysqli_query($db,$sql);
  while($row = mysqli_fetch_array($result)){
    $data = array(
      "label"=>substr($row['offence'],0,50).'...' , "y"=> $row['avg']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

function dataPointCompany(){
  global $db;
  $sql = "SELECT violatorCompany, total, avg FROM viewtotalbycompany ORDER BY avg DESC LIMIT 20";
  $result = mysqli_query($db,$sql);
  while($row = mysqli_fetch_array($result)){
    $data = array(
      "label"=>$row['violatorCompany'] , "y"=> $row['avg']
    );
    $dataPoints[] = $data;
  }
  return $dataPoints;
}

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
                    <a class="nav-link " data-bs-toggle="" href="default.php?page=analytics" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                      <span class="d-none d-sm-block">Overview</span>
                    </a>
                  </li>
                  <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
                  <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="" href="default.php?page=analytics-pie" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                      <span class="d-none d-sm-block">KPI</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " data-bs-toggle="" href="default.php?page=analytics-usage" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                      <span class="d-none d-sm-block">Usage</span>
                    </a>
                  </li>
                  <?php } ?>
                  <li class="nav-item">
                    <a class="nav-link " data-bs-toggle="" href="default.php?page=leaderboard" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                      <span class="d-none d-sm-block">Leaderboard</span>
                    </a>
                  </li>
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

          <p class="text-secondary">Key performance indicators.</p>
          <div class="row">
			<div class="col-6">
              <!--div id="chartContainerYear" class="mb-5" style="height: 370px; width: 100%;"></div-->
			  <div id="chartContainerYearUCUA" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>
			<div class="col-6">
              <div id="chartContainerMonthUCUA" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>
			
          </div><!--end row-->
          <div class="row">
			<div class="col-6">
              <div id="chartContainer" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>
			<div class="col-6">
              <div id="chartContainerLocation" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>
            
            
          </div><!--end row-->
		  <div class="row">
			<div class="col-6">
              <div id="chartContainerOffence" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>
			<div class="col-6">
				<div id="chartContainerCompany" class="mb-5" style="height: 370px; width: 100%;"></div>
            </div>
		  </div><!--end row-->


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
                    <?php echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';?>
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
  funFormType()
  funLocation();
  funOffence();
  funCompany();
  //funYear();
  funYearUCUA();
  funMonthUCUA();
}

function funFormType(){
  var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	title: {
		text: "Condition & action"
	},
	subtitles: [{
		text: "<?php echo date('M, Y');?>"
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

function funLocation(){
  var chart = new CanvasJS.Chart("chartContainerLocation", {
	animationEnabled: true,
	exportEnabled: true,
	title: {
		text: "Location"
	},
	subtitles: [{
		text: "<?php echo date('M, Y');?>"
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

function funOffence(){
  var chart = new CanvasJS.Chart("chartContainerOffence", {
	animationEnabled: true,
	exportEnabled: true,
	title: {
		text: "Offence"
	},
	subtitles: [{
		text: "<?php echo date('M, Y');?>"
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

function funCompany(){
  var chart = new CanvasJS.Chart("chartContainerCompany", {
	animationEnabled: true,
	exportEnabled: true,
	title: {
		text: "Company"
	},
	subtitles: [{
		text: "<?php echo date('M, Y');?>"
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

function funYear(){
  var chart = new CanvasJS.Chart("chartContainerYear", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1",
	title: {
		text: "UCUA BY YEAR"
	},
	data: [{
		type: "column",
		dataPoints: <?php echo json_encode(dataPointYear(), JSON_NUMERIC_CHECK); ?>
	}]
});

chart.render();
}

function funYearUCUA() {
    var chart = new CanvasJS.Chart("chartContainerYearUCUA", {
        title: {
            text: "UCUA GROUP BY YEAR"
        },
        theme: "light1",
        animationEnabled: true,
		exportEnabled: true,
        toolTip: {
            shared: true,
            reversed: true
        },
        legend: {
            cursor: "pointer"
        },
        data: [
            {
                type: "stackedColumn",
                name: "Unsafe Condition",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: <?= json_encode(dataPointYearUC(), JSON_NUMERIC_CHECK); ?>
            },
            {
                type: "stackedColumn",
                name: "Unsafe Action",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: <?= json_encode(dataPointYearUA(), JSON_NUMERIC_CHECK); ?>
            }
        ]
    });
    chart.render();
}

function funMonthUCUA() {
      var chart = new CanvasJS.Chart("chartContainerMonthUCUA", {
        title: {
          text: "UCUA GROUP BY MONTH FOR <?php echo date("Y");?>"
        },
        theme: "light1",
        animationEnabled: true,
        exportEnabled: true,
        toolTip: {
          shared: true,
          reversed: true
        },
        legend: {
          cursor: "pointer"
        },
        data: [{
            type: "stackedColumn",
            name: "Unsafe Condition",
            showInLegend: true,
            yValueFormatString: "#,##0",
            dataPoints: <?= json_encode(dataPointMonthUC(), JSON_NUMERIC_CHECK); ?>
          },
          {
            type: "stackedColumn",
            name: "Unsafe Action",
            showInLegend: true,
            yValueFormatString: "#,##0",
            dataPoints: <?= json_encode(dataPointMonthUA(), JSON_NUMERIC_CHECK); ?>
          }
        ]
      });
      chart.render();
    }

function toggleDataSeries(e) {
    if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        e.dataSeries.visible = false;
    } else {
        e.dataSeries.visible = true;
    }
    e.chart.render();
}
</script>
</body>
</html>