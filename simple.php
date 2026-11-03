<?php
include('testFunctions.php');
$title = 'UCUA';

function showName($n)
{
  global $db;
  $sql = "SELECT Name, Email FROM user WHERE staffId = '" . $n . "'";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
    return $row['Name'];
  } else {
    return $n;
  }
}

if(isset($_POST['submit'])){
  $sql = "SELECT *, userroles.rolename FROM user LEFT JOIN userroles ON userroles.id = user.AccessLevel WHERE staffId ='".$_POST['staffid']."' LIMIT 1";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  if($row){
    $_SESSION['user'] = $row;
    echo '<meta http-equiv="refresh" content="0; URL=default.php?page=dashboard" />';
  }else{
    echo '<meta http-equiv="refresh" content="0; URL=default.php?page=register" />';
		exit;
  }
}

include('inc_header.php');
?>
</head>

<body class="pace-done sidebar-enable" data-new-gr-c-s-check-loaded="8.906.0" data-gr-ext-installed=""
  data-sidebar="brand" data-sidebar-size="sm">
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
    <?php //include('inc_navbar.php'); ?>
    <!-- Left Navbar End -->
    <!-- ========== Left Sidebar Start ========== -->
    <?php //include('inc_sidebar.php'); ?>
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
                <h4 class="mb-sm-0 font-size-18">
                  <?php echo $title; ?>
                </h4>
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"></li>
                    <li class="breadcrumb-item active"></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- end page title -->

          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Staff ID</h4>
                  <p class="card-title-desc mb-0"></p>
                </div>
                <div class="card-body">

                  <form method="post" action="default.php?page=simple">
                    <div class="row">
                      <div class="col-lg-6">
                        <div>
                          <div>
                            <label for="regexp-mask" class="form-label">Staff ID</label>
                            <input type="text" class="form-control" name="staffid" placeholder="Staff ID">
                            <div class="text-muted"></div>
                          </div>
                      </div>
                      <!-- end col -->
                    </div>
                    <!-- end row -->
                    <div class="mt-4">
                      <div class="float-end">
                        <button type="submit" name="submit" class="btn btn-primary">Next <i class="fa-solid fa-angle-right"></i></button>
                      </div>
                    </div>
                  </form>
                  <!-- end form -->
                </div>
                <!-- end card body -->
              </div>
              <!-- end card -->
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

  <!-- Right Sidebar -->
  <?php include('inc_rightbar.php'); ?>
  <!-- /Right-bar -->

  <!-- Right bar overlay-->
  <div class="rightbar-overlay"></div>

  <?php include('inc_scripts.php'); ?>
  <script>
    $(function () {
      var areaChartData = {
        labels: ['<?php echo implode('\',\'', $label) ?>'],
        datasets: [
          {
            label: 'Frequent Asks',
            backgroundColor: 'rgba(81,86,190,0.9)',
            borderColor: 'rgba(81,86,190,0.8)',
            pointRadius: false,
            pointColor: '#5156be',
            pointStrokeColor: 'rgba(81,86,190,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(81,86,190,1)',
            data: [<?php echo implode(',', $data) ?>]
          }
        ]
      }

      var areaChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false,
            }
          }],
          yAxes: [{
            gridLines: {
              display: false,
            }
          }]
        }
      }
      //-------------
      //- BAR CHART -
      //-------------
      var barChartCanvas = $('#barChart').get(0).getContext('2d')
      var barChartData = $.extend(true, {}, areaChartData)
      var temp0 = areaChartData.datasets[0]
      barChartData.datasets[0] = temp0
      console.log()

      var barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
      })

    })
  </script>
</body>

</html>