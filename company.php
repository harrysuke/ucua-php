<?php
include('testFunctions.php');

if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login');
}

function funDesc($sid)
{
	global $db;
	$sql = "SELECT Offence FROM  offence WHERE OffenceCode = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if ($row) {
		return $row['Offence'];
	} else {
		return $sid;
	}
}

function funTimelinev2($sid)
{
	global $db;
	$option = '';
	$sql = "SELECT v.id, v.company, v.unsafe, v.form_type, u.submitdate, p.evidenceImage 
	FROM viewucuabycompany AS v
	 LEFT JOIN companies AS c ON v.company=c.companyName
	 LEFT JOIN ucuaform AS u ON v.id=u.id
	 LEFT JOIN ucuaphoto AS p ON p.ucuaform_Id=v.id 
	 WHERE c.id = ?
	 GROUP BY v.id, v.company, v.unsafe, v.form_type, u.submitdate, p.evidenceImage
	 LIMIT 10";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$option .= '<div class="card">
			<div class="d-flex align-items-start ms-2 mt-2 mb-2">
					<div class="flex-shrink-0 user-img online align-self-center me-3">
						<div class="avatar-md align-self-center">
							<span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
							' . substr(trim($row['company']), 0, 1) . '
							</span>
						</div>
					</div>
					<div class="flex-grow-1 overflow-hidden">
						<h5 class="text-truncate font-size-14 mb-1">' . ucwords(strtolower($row['company'])) . '</h5>
						<p class="font-size-13 text-black-50 mb-0">' . date('d M, Y', strtotime($row['submitdate'])) . '</p>
					</div>
					<div class="flex-shrink-0">
						<div class="font-size-11"></div>
					</div>
				</div>
			  <img src="' . $row['evidenceImage'] . '" class="card-img-top" alt="...">
          <div class="card-body">
                                                                <div class="pt-3">
                                                                    <p class="text-muted">' . $row['unsafe'] . '</p>
                                                                </div>
																' . showActivity($row['id']) . '
                                                            </div>
                                                            </div>';
		}
	} else {
		$option .= '<div class="row align-items-center text-center py-sm-5">
      <div class="col-lg-8 mx-auto">
        <img src="img/no-docs-empty.a608ad8c0a024bfad0168036c4c74588.svg"/>
        <h4 class="mt-2 mb-3 text-secondary">No record found</h4>
      </div>
    </div>';
	}
	return $option;
}

function funUC($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM ucuaform AS u
	LEFT JOIN companies AS c ON c.companyName=u.violatorCompany
	WHERE c.id = ? AND u.form_type = 'Unsafe Condition' ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	return $row['counter'];
}

function funUA($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM ucuaform AS u
	LEFT JOIN companies AS c ON c.companyName=u.violatorCompany
	WHERE c.id = ? AND u.form_type = 'Unsafe Action' ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	return $row['counter'];
}

function funConnection($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM user AS u
	LEFT JOIN ucuaform AS f ON f.staffId=u.staffId
	LEFT JOIN companies AS c ON c.companyName=f.violatorCompany
	WHERE c.id = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	return $row['counter'];
}

function funCompany($id)
{
	global $db;
	$option = '';
	$sql1 = "SELECT d.id, d.companyName, d.groupType, d.description, d.sectInCharge FROM companies AS c 
	LEFT JOIN companies AS d ON d.sectInCharge=c.sectInCharge
	WHERE c.id = ? AND d.id <> c.id ORDER BY RAND() LIMIT 6";
	$stmt = $db->prepare($sql1);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result1 = $stmt->get_result();
	while ($row1 = $result1->fetch_assoc()) {
		$option .= '<a href="default.php?page=company&id=' . base64_encode($row1['id']) . '" targte="_blank" class="list-group-item list-group-item-action">
		<div class="d-flex align-items-center">
		<div class="flex-shrink-0 user-img online align-self-center me-2">
		<div class="avatar-sm align-self-center">
			<span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
			' . substr(trim($row1['companyName']), 0, 1) . '
			</span>
		</div>    
	</div>
			<div class="flex-grow-1">
				<div>
					<h5 class="font-size-14 mb-1 text-capitalize">' . strtolower($row1['companyName']) . '</h5>
					<p class="font-size-13 text-muted mb-0">' . $row1['description'] . ' ' . $row1['sectInCharge'] . '</p>
				</div>
			</div>
		</div>
	</a>';
	}
	return $option;
}

try {
	$sid = base64_decode($_GET['id']);
	$sql = "SELECT c.companyName, c.companyRegNo, c.description, c.deptInCharge, c.sectInCharge FROM companies AS c WHERE c.id =  ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if ($row) {
		$companyName = ucwords(strtolower($row['companyName']));
		$companyRegNo = $row['companyRegNo'];
		$description = $row['description'];
		$deptInCharge = $row['deptInCharge'];
		$sectInCharge = $row['sectInCharge'];
	} else {
		$companyName = '';
		$companyRegNo = '';
		$description = '';
		$deptInCharge = '';
		$sectInCharge = '';
	}
} catch (Exception $e) {
	die($e->getMessage());
}

$title = $companyName;
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include('inc_header.php');
?>
<style>
	.userpage-content {
		background-image: url("img/lines.jpg");
		background-position: 100% -105px;
		background-size: cover;
		background-repeat: no-repeat;
		padding: 80px 0;
		position: relative
	}

	.userpage-user-img {
		position: relative;
		margin-top: -60px
	}
</style>
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
										<?php echo $title; ?>
										<a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
									</h4>
									<p><?php echo ucwords(strtolower($description)); ?> <?php echo ucwords(strtolower($sectInCharge)); ?></p>
								</div>
								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
										<li class="breadcrumb-item active">Profile</li>
									</ol>
									<p class="float-end mt-2"><a href="#" class="btn btn-light btn-sm waves-effect waves-light"><i class="bi bi-bar-chart-line"></i> Analytics</a></p>
								</div>
							</div>
						</div>
					</div>
					<!-- end page title -->

					<div class="row edit-profile-wrap">
						<div class="col-lg-8">
							<div class="timeline-simple">
								<div class="timeline-list">
									<?php echo funTimelinev2(base64_decode($_GET['id'])); ?>
								</div>
							</div>
						</div><!-- end card -->
						<div class="col-lg-4">
							<div class="card">
								<div class="card-body position-relative">
									<div class="position-absolute end-0 top-0 p-3">
										<a href="default.php?page=admin_company_edit&id=<?php echo base64_decode($_GET['id']); ?>" class="text-primary">
											<i class="fa-regular fa-pen-to-square"></i>
										</a>
									</div>
									<div class="text-center mt-3">
										<div class="chat-avtar d-inline-flex mx-auto mb-2">
											<div class="avatar-lg align-self-center">
												<span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-1">
													<?php echo substr(trim($companyName), 0, 1); ?>
												</span>
											</div>
										</div>

										<h5 class="mb-0 text-capitalize">
											<?php echo $companyName; ?>
										</h5>
										<p class="text-muted text-sm text-capitalize">
											<?php echo strtolower($description); ?>
										</p>
										<hr class="my-3 border border-secondary-subtle">
										<div class="row g-3">
											<div class="col-4">
												<h5 class="counter-value"
													data-target="<?php echo funUC(base64_decode($_GET['id'])); ?>">0
												</h5>
												<small class="text-muted">UC</small>
											</div>
											<div class="col-4 border border-top-0 border-bottom-0">
												<h5 class="counter-value"
													data-target="<?php echo funUA(base64_decode($_GET['id'])); ?>">0
												</h5>
												<small class="text-muted">UA</small>
											</div>
											<div class="col-4">
												<h5 class="counter-value"
													data-target="<?php echo funConnection(base64_decode($_GET['id'])); ?>">0</h5>
												<small class="text-muted">Connections</small>
											</div>
										</div>

									</div>
								</div>
							</div>
							<div class="card">
								<h5 class="card-title mt-3 mb-3 ms-3">Similar pages</h5>
								<div class="list-group list-group-flush">
									<?php echo funCompany(base64_decode($_GET['id'])); ?>
								</div>
							</div>
							<!-- end card body -->
						</div>
						<!-- end card -->


					</div>
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

</body>

</html>