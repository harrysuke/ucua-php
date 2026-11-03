<?php
include('testFunctions.php');

if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login');
}

function dataPointYearUC($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS sum, YEAR(observerdatetime) AS tahun FROM ucuaform WHERE form_type='Unsafe Condition' AND staffId = ? GROUP BY YEAR(observerdatetime)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$dataPoints = [];
	while ($row = mysqli_fetch_array($result)) {
		$dataPoints[] = array(
			"label" => $row['tahun'],
			"y" => $row['sum']
		);
	}
	return $dataPoints;
}

function dataPointYearUA($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS sum, YEAR(observerdatetime) AS tahun FROM ucuaform WHERE form_type='Unsafe Action' AND staffId = ? GROUP BY YEAR(observerdatetime)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$dataPoints = [];
	while ($row = mysqli_fetch_array($result)) {
		$dataPoints = array(
			"label" => $row['tahun'],
			"y" => $row['sum']
		);
	}
	return $dataPoints;
}

function dataPointMonthUC($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS sum, MONTH(observerdatetime) AS bulan FROM ucuaform WHERE staffId = ? AND form_type='Unsafe Condition' AND YEAR(observerdatetime)='" . date('Y') . "' GROUP BY MONTH(observerdatetime) ORDER BY MONTH(observerdatetime)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$dataPoints = [];
	while ($row = mysqli_fetch_array($result)) {
		$dataPoints = array(
			"label" => date("F", mktime(0, 0, 0, $row['bulan'], 10)),
			"y" => $row['sum']
		);
	}
	return $dataPoints;
}

function dataPointMonthUA($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS sum, MONTH(observerdatetime) AS bulan FROM ucuaform WHERE staffId = ? AND form_type='Unsafe Action' AND YEAR(observerdatetime)='" . date('Y') . "' GROUP BY MONTH(observerdatetime) ORDER BY MONTH(observerdatetime)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$dataPoints = [];
	while ($row = mysqli_fetch_array($result)) {
		$dataPoints = array(
			"label" => date("F", mktime(0, 0, 0, $row['bulan'], 10)),
			"y" => $row['sum']
		);
	}
	return $dataPoints;
}

function getMonthlyTrendUC($db, $sid)
{
	$sql = "SELECT 
    DATE_FORMAT(submitdate, '%Y-%m') AS YearMonth,
    COUNT(*)                         AS NewCases
FROM ucuaform
WHERE staffId = ? AND form_type='Unsafe Condition' AND submitdate >= DATE_SUB(DATE_FORMAT(CURDATE(),'%Y-%m-01'), INTERVAL 11 MONTH)
GROUP BY YearMonth
ORDER BY YearMonth;";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
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

function getMonthlyTrendUA($db, $sid)
{
	$sql = "SELECT 
    DATE_FORMAT(submitdate, '%Y-%m') AS YearMonth,
    COUNT(*)                         AS NewCases
FROM ucuaform
WHERE staffId = ? AND form_type='Unsafe Action' AND submitdate >= DATE_SUB(DATE_FORMAT(CURDATE(),'%Y-%m-01'), INTERVAL 11 MONTH)
GROUP BY YearMonth
ORDER BY YearMonth;";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
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

try {
	$sidx = base64_decode($_GET['sid']);
	$sql = "SELECT u.Name, u.PhoneNo, u.Department, u.Designation, u.AccessLevel, u.Email, u.createdate, u.Status, r.rolename FROM user AS u 
	LEFT JOIN userroles AS r ON r.id=u.AccessLevel
	WHERE u.staffId = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $sidx);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if ($row) {
		$Name = ucwords(strtolower($row['Name'])) ?? '';
		$PhoneNo = $row['PhoneNo'] ?? '';
		$Department = $row['Department'] ?? '';
		$Designation = $row['Designation'] ?? '';
		$AccessLevel = $row['AccessLevel'] ?? '';
		$Email = $row['Email'] ?? '';
		$createdate = date('M d, Y', strtotime($row['createdate']));
		$Status = $row['Status'] ?? '';
		$rolename = $row['rolename'] ?? '';
	}
} catch (Exception $e) {
	die($e->getMessage());
}


$title = 'User Stats';
$sid = base64_decode($_GET['sid']);
$dataPointsUC = getMonthlyTrendUC($db, $sid);
$dataPointsUA = getMonthlyTrendUA($db, $sid);
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
					<?php include('inc_alert.php'); ?>
					<!-- start page title -->
					<div class="row">
						<div class="col-12">
							<div class="page-title-box d-sm-flex align-items-center justify-content-between">
								<div class="float-start">
									<h4 class="mb-sm-0 font-size-18">
										<div class="float-start me-2">
											<?php echo $title; ?>
										</div>
										<div class="float-start me-2">
											<a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
										</div>
										<div class="dropdown float-start">
											<a class="dropdown-toggle text-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
												<i class="fa-solid fa-ellipsis fa-2xs"></i>
											</a>
											<ul class="dropdown-menu">
												<li><a class="dropdown-item" href="default.php?page=profile&sid=<?php echo $_GET['sid']; ?>">View Profile</a></li>
												<li><a class="dropdown-item" href="#">Edit Profile</a></li>
												<li><a class="dropdown-item" href="#">Change Password</a></li>
												<li><a class="dropdown-item" href="default.php?page=user_insights&sid=<?php echo $_GET['sid']; ?>">View Stats</a></li>
												<li>
													<hr class="dropdown-divider">
												</li>
												<li><a class="dropdown-item" href="#">Sign Out</a></li>
											</ul>
										</div>
									</h4>
									<div class="clearfix"></div>
									<p class="text-secondary"><?php echo $Name; ?> stats.</p>
								</div>
								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
										<li class="breadcrumb-item active">Stats</li>
									</ol>
									<p class="float-end mt-2"></p>
								</div>
							</div>
						</div>
					</div>
					<!-- end page title -->
					<div class="row">
						<div class="col-12">
							<div id="chartMonthlyTrend" style="height: 370px; width: 100%;"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<!--div id="chartContainerYear" class="mb-5" style="height: 370px; width: 100%;"></div-->
							<div id="chartContainerYearUCUA" class="mb-5" style="height: 370px; width: 100%;"></div>
						</div>
						<div class="col-6">
							<div id="chartContainerMonthUCUA" class="mb-5" style="height: 370px; width: 100%;"></div>
						</div><!--end row-->

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
			window.onload = function() {
				funMonthlyTrend();
				funYearUCUA();
				funMonthUCUA();
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
					data: [{
							type: "stackedColumn",
							name: "Unsafe Condition",
							showInLegend: true,
							yValueFormatString: "#,##0",
							dataPoints: <?= json_encode(dataPointYearUC(base64_decode($_GET['sid'])), JSON_NUMERIC_CHECK); ?>
						},
						{
							type: "stackedColumn",
							name: "Unsafe Action",
							showInLegend: true,
							yValueFormatString: "#,##0",
							dataPoints: <?php
										$sid = base64_decode($_GET['sid'] ?? '');
										echo json_encode(dataPointYearUA($sid), JSON_NUMERIC_CHECK);
										?>
						}
					]
				});
				chart.render();
			}

			function funMonthUCUA() {
				var chart = new CanvasJS.Chart("chartContainerMonthUCUA", {
					title: {
						text: "UCUA GROUP BY MONTH FOR <?php echo date("Y"); ?>"
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
							dataPoints: <?php
										$sid = base64_decode($_GET['sid'] ?? '');
										echo json_encode(dataPointMonthUC($sid), JSON_NUMERIC_CHECK);
										?>
						},
						{
							type: "stackedColumn",
							name: "Unsafe Action",
							showInLegend: true,
							yValueFormatString: "#,##0",
							dataPoints: <?php
										$sid = base64_decode($_GET['sid'] ?? '');
										echo json_encode(dataPointMonthUA(base64_decode($_GET['sid'])), JSON_NUMERIC_CHECK);
										?>
						}
					]
				});
				chart.render();
			}
		</script>

</body>

</html>