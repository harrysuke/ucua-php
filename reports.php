<?php
$title = 'All report';
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

function showName($n){
  global $db;
  $sql = "SELECT Name, Email FROM user WHERE staffId = '".$n."'";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_assoc($result);
  if($row){
    return $row['Name'];
  }else{
    return 'NA';
  }
}

function showDept($n){
  global $db;
  $sql = "SELECT Department FROM user WHERE staffId = '".$n."'";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_assoc($result);
  if(mysqli_num_rows($result) > 0){
    return $row['Department'];
  }else{
    return $n;
  }
}

function funLink($id, $type){
	if($type = 'Unsafe Condition'){
		return '<a href="default.php?page=newuiuc_form&id='.base64_encode($id).'" target="_blank">'.$id.'</a>';
	}
	if($type = 'Unsafe Action'){
		return '<a href="default.php?page=newuiua_form&id='.base64_encode($id).'" target="_blank">'.$id.'</a>';
	}
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
                <h4 class="mb-sm-0 font-size-18">
				<a href="index.php?page=report-department" class="btn btn-light waves-effect">
				<i class="fa-solid fa-arrow-left"></i>
				</a>&nbsp;
				<?php echo $title;?> from <?php echo $_POST['dateStart'];?> to <?php echo $_POST['dateEnd'];?>
				</h4>

                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Report</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- end page title -->
		  
		  

           <div class="row">
              <table id="datatable-buttons" class="table table-bordered dt-responsive  nowrap w-100">
                <thead class="text-uppercase">
                    <tr>
                        <th>No</th>
                        <th>REPORT ID</th>
						<th>TYPE</th>
                        <th>DATE </th>
                        <th>DEPARTMENT</th>
                        <th>HSE REPRESENTATIVE </th>
                        <th>OBSERVER </th>
                        <th>UCUA ISSUE </th>
                        <th>VIOLATOR</th>
                        <th>LOCATION </th>
                        <th>ACTION </th>
                        <th>DATE OF ACTION TAKEN </th>
                        <th>STATUS </th>
                    </tr>
                </thead>
				<tfoot>
                    <tr>
                        <th>No</th>
                        <th>REPORT ID</th>
						<th>TYPE</th>
                        <th>DATE </th>
                        <th>DEPARTMENT</th>
                        <th>HSE REPRESENTATIVE </th>
                        <th>OBSERVER </th>
                        <th>UCUA ISSUE </th>
                        <th>VIOLATOR</th>
                        <th>LOCATION </th>
                        <th>ACTION </th>
                        <th>DATE OF ACTION TAKEN </th>
                        <th>STATUS </th>
                    </tr>
                </tfoot>
                <tbody>
                <?php
                    $staffid=$_SESSION['user']['staffId'];
					$sql = "SELECT uf.Id, uf.submitdate AS DATE, 
                        uf.staffId,
                                    u.Department,
                                    CASE
                                    WHEN uf.ViolatorDept IS NULL THEN 'NA'
                                    WHEN uf.ViolatorDept = '' THEN 'NA'
                                    ELSE uf.ViolatorDept
                                    END AS DEPARTMENT, 
                                    CASE 
                                    WHEN u.Name IS NULL THEN 'NA'
                                    WHEN u.Name = '' THEN 'NA'
                                    ELSE u.Name
                                    END AS Name, 
									CASE 
									WHEN uf.observerName IS NULL THEN 'NA'
									WHEN uf.observerName = '' THEN 'NA'
									ELSE uf.observerName 
									END AS observerName,
                                    uf.HSEReps, 
                                    CASE
                                    WHEN uf.conditionDetails IS NULL THEN 'NA'
                                    WHEN uf.conditionDetails = '' THEN 'NA'
                                    ELSE uf.conditionDetails
                                    END AS conditionDetails,
                                    CASE WHEN uf.ViolatorName = '' THEN uf.LocationId
                                    ELSE uf.ViolatorName
                                    END AS 'VIOLATORLOCATION', l.LocationName, 
									CASE
									WHEN uf.ViolatorName IS NULL THEN 'NA'
									WHEN uf.ViolatorName = '' THEN 'NA'
									ELSE uf.ViolatorName
									END AS ViolatorName,
                                    CASE
                                    WHEN uf.ActionTaken IS NULL THEN 'NA'
                                    WHEN uf.ActionTaken = '' THEN 'NA'
                                    ELSE uf.ActionTaken
                                    END AS ACTION, 
                                    CASE 
                                    WHEN uf.actiontaken_date IS NULL THEN 'NA'
                                    WHEN uf.actiontaken_date = '' THEN 'NA'
                                    ELSE uf.actiontaken_date
                                    END AS actiontaken_date, 
                                    CASE
                                    WHEN uf.status IS NULL THEN 'NA'
                                    WHEN uf.status = '' THEN 'NA'
                                    ELSE uf.status
                                    END AS status, 
                                    CASE 
                                    WHEN uf.form_type IS NULL THEN 'NA'
                                    WHEN uf.form_type = '' THEN 'NA'
                                    ELSE uf.form_type
                                    END AS form_type
                                    FROM ucuaform AS uf 
                                    LEFT JOIN location AS l ON uf.LocationId = l.LocationId 
                                    LEFT JOIN user AS u ON u.staffId = uf.HSEReps OR u.staffId = uf.HSEReps0
									WHERE uf.submitdate BETWEEN '".$_POST['dateStart']."' AND '".$_POST['dateEnd']."'";
					
					if($_SESSION['user']['AccessLevel'] == 8){//HSE REPS
						$sql .= " AND u.Department = '".$_SESSION['user']['Department']."' ";
					}else{
                        $sql .= " ";
                    }
                        $result = mysqli_query($db, $sql);
                    $tmpCount = 1; 
                    while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td width='5%'><?php echo $tmpCount; ?></td>
                    <td><?php echo funLink($row['Id'], $row['form_type']); ?></td>
					<td><?php echo $row['form_type'];?></td>
                    <td><?php echo $row['DATE']; ?></td>
                    <td><?php echo $row['Department']; ?></td>
                    <td><?php echo showName($row['HSEReps']); ?></td>
                    <td><?php echo $row['observerName']; ?></td>
                    <td class="d-flex flex-wrap"><?php echo $row['conditionDetails']; ?></td>
                    <td><?php echo $row['ViolatorName']; ?></td>
                    <td><?php echo $row['LocationName']; ?></td>
                    <td><?php echo $row['ACTION']; ?></td>
                    <td><?php echo $row['actiontaken_date']; ?></td>
                    <td><a href="default.php?page=history&ucuaid=<?php echo $row['Id']; ?>" target="_blank"><?php echo $row['status'];?></a></td>
                </tr>
                <?php $tmpCount++; } ?>
            </table>
        
                                    
                        </div> <!-- end row -->
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
    $(function() {
		var areaChartData = {
      labels  : ['<?php echo implode('\',\'',$label) ?>'],
      datasets: [
        {
          label               : 'Frequent Asks',
          backgroundColor     : 'rgba(81,86,190,0.9)',
          borderColor         : 'rgba(81,86,190,0.8)',
          pointRadius          : false,
          pointColor          : '#5156be',
          pointStrokeColor    : 'rgba(81,86,190,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(81,86,190,1)',
          data                : [<?php echo implode(',',$data) ?>]
        }
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
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
	      responsive              : true,
	      maintainAspectRatio     : false,
	      datasetFill             : false
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