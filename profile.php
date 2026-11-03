<?php
include('testFunctions.php');

if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login');
}

function funDepartment()
{
	global $db;
	$option = '';
	$sql1 = "SELECT dept, deptName FROM dept WHERE isdelete=0 ORDER BY deptName";
	$result1 = $db->query($sql1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$option .= '<option value="' . $row1['dept'] . '">' . $row1['deptName'] . ' (' . $row1['dept'] . ')</option>';
	}
	return $option;
}

function showLeaderboard($id)
{
	global $db;
	$l = '';
	$sql = "SELECT staffId, Name, ranking, points FROM  viewleaderboard WHERE staffId = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row1 = $result->fetch_assoc();
	if ($row1) {
		$l .= '<div class="d-flex">
										<div class="flex-shrink-0 user-img online align-self-center me-2">
											<div class="avatar-sm align-self-center">
												<span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
													' . substr(trim($row1['Name']), 0, 1) . '
												</span>
											</div>
										</div>
										<div class="flex-grow-1">
											<h6>' . trim($row1['Name']) . '</h6>
											Ranking ' . trim($row1['ranking']) . '
											with ' . trim($row1['points']) . ' points.
										</div>
									</div>';
	} else {
		$l .= 'No Record';
	}
	return $l;
}

function funMember($d)
{
	global $db;
	$option = '';
	$sql = "SELECT staffId, Name, Designation, Department FROM user WHERE Department=? AND isdelete=0 ORDER BY RAND() LIMIT 6";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $d);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row1 = $result->fetch_assoc()) {
		$option .= '<a href="default.php?page=profile&sid=' . base64_encode($row1['staffId']) . '" class="list-group-item list-group-item-action">
		<div class="d-flex align-items-center">
		<div class="flex-shrink-0 user-img online align-self-center me-2">
		<div class="avatar-sm align-self-center">
			<span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
			' . substr(trim($row1['Name']), 0, 1) . '
			</span>
		</div>    
	</div>
			<div class="flex-grow-1">
				<div>
					<h5 class="font-size-14 mb-1 text-capitalize">' . strtolower($row1['Name']) . '</h5>
					<p class="font-size-13 text-muted mb-0">' . ucwords(strtolower($row1['Designation'])) . ' ' . $row1['Department'] . '</p>
				</div>
			</div>
		</div>
	</a>';
	}
	return $option;
}

function funStatus($Status)
{
	$s = '';
	if ($Status == 1) {
		$s = "Active";
	} else if ($Status == 2) {
		$s = "Inactive";
	} else {
		$s = "Pending";
	}
	return $s;
}

function funTimeline($sid)
{
	global $db;
	$option = '';
	$y = date('Y');
	$sql = "SELECT u.Name, h.staffid, h.activity, h.action, h.datetimestamp FROM history AS h 
	LEFT JOIN user AS u ON u.staffId=h.staffid
	WHERE h.staffid=? AND YEAR(h.datetimestamp)=? ORDER BY h.datetimestamp DESC";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("ss", $sid, $y);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row1 = $result->fetch_assoc()) {
		$option .= '<div class="timeline-post-content ">
		<div class="flex-shrink-0 user-img online align-self-center me-2">
                <div class="avatar-sm align-self-center">
                    <span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                    ' . substr(trim($row1['Name']), 0, 1) . '
                    </span>
                </div>    
            </div>
		<div class="">
			<h6 class="text-capitalize">' . $row1['Name'] . '</h6> <span class="text-body-tertiary">posted as ' . strtolower($row1['action']) . '</span>
			<p class="meta-time-date text-body-tertiary">' . timeago($row1['datetimestamp']) . '</p>
			<div class="">
				<p class="text-secondary">' . ucfirst(strtolower($row1['activity'])) . '</p>
			</div>
		</div>
	</div>';
	}
	return $option;
}

function funTimelinev2($sid)
{
	global $db;
	$option = '';
	$sql = "SELECT v.id, v.staffId, u.Name, v.unsafe, v.form_type, f.submitdate, p.evidenceImage FROM viewucua AS v
	LEFT JOIN user AS u ON u.staffId=v.staffId
	LEFT JOIN ucuaform AS f ON f.Id=v.id
	LEFT JOIN ucuaphoto AS p ON p.ucuaform_Id=v.id 
	WHERE v.staffId=?
	ORDER BY f.submitdate DESC 	LIMIT 10";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $sid);
	$stmt->execute();
	$result1 = $stmt->get_result();
	if ($result1->num_rows > 0) {
		while ($row = mysqli_fetch_array($result1)) {
			$option .= '<div class="card">
			<div class="d-flex align-items-start ms-2 mt-2 mb-2">
					<div class="flex-shrink-0 user-img online align-self-center me-3">
						<div class="avatar-md align-self-center">
							<span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
							' . substr(trim($row['Name']), 0, 1) . '
							</span>
						</div>
					</div>
					<div class="flex-grow-1 overflow-hidden">
						<h5 class="text-truncate font-size-14 mb-1">' . ucwords(strtolower($row['Name'])) . '</h5>
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
	$sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE staffId = ? AND form_type = 'Unsafe Condition' ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	return $row['counter'];
}

function funUA($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE staffId = ? AND form_type = 'Unsafe Action' ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	return $row['counter'];
}

function funConnection($d)
{
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM user WHERE Department = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $d);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	return $row['counter'];
}

try {
	$sid = base64_decode($_GET['sid']);
	$sql = "SELECT u.Name, u.PhoneNo, u.Department, u.Designation, u.AccessLevel, u.Email, u.createdate, u.Status, r.rolename FROM user AS u 
	LEFT JOIN userroles AS r ON r.id=u.AccessLevel
	WHERE u.staffId = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if ($row) {
		$Name = $row['Name'] ?? '';
		$PhoneNo = $row['PhoneNo'] ?? '';
		$Department = $row['Department'] ?? '';
		$Designation = $row['Designation'] ?? '';
		$AccessLevel = $row['AccessLevel'] ?? '';
		$Email = $row['Email'] ?? '';
		$createdate = date('M d, Y', strtotime($row['createdate'])) ?? '';
		$Status = $row['Status'] ?? '';
		$rolename = $row['rolename'] ?? '';
	}
} catch (Exception $e) {
	die($e->getMessage());
}

$title = $Name;
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
										<div class="float-start me-2">
											<?php echo $Name; ?>&nbsp;
										</div>
										<div class="float-start me-2">
											<a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($Name); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
										</div>
										<div class="dropdown float-start">
											<a class="dropdown-toggle text-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
												<i class="fa-solid fa-ellipsis fa-2xs"></i>
											</a>
											<ul class="dropdown-menu">
												<li><a class="dropdown-item" href="default.php?page=profile&sid=<?php echo $_GET['sid']; ?>">View Profile</a></li>
												<li><a class="dropdown-item" href="default.php?page=settings_user">Edit Profile</a></li>
												<li><a class="dropdown-item" href="#">Change Password</a></li>
												<li><a class="dropdown-item" href="default.php?page=user_insights&sid=<?php echo $_GET['sid']; ?>">View Stats</a></li>
												<li>
													<hr class="dropdown-divider">
												</li>
												<li><a class="dropdown-item" href="#">Sign Out</a></li>
											</ul>
										</div>
									</h4>
									<p class="text-secondary"><?php echo $Email; ?></p>

									<div class="clearfix"></div>

									<div class="float-start">
										<ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" data-bs-toggle="" href="default.php?page=profile&sid=<?php echo base64_encode($_SESSION['user']['staffId']); ?>" role="tab">
													<span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
													<span class="d-none d-sm-block">Profile</span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" data-bs-toggle="" href="default.php?page=activity&sid=<?php echo base64_encode($_SESSION['user']['staffId']); ?>" role="tab">
													<span class="d-block d-sm-none"><i class="far fa-user"></i></span>
													<span class="d-none d-sm-block">Activity</span>
												</a>
											</li>
										</ul>
									</div>

								</div>
								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
										<li class="breadcrumb-item active">Profile</li>
									</ol>
									<p class="float-end mt-2"></p>
								</div>
							</div>
						</div>
					</div>
					<!-- end page title -->

					<div class="row edit-profile-wrap">
						<div class="col-lg-8">
							<div class="card-body">
								<div class="timeline-simple">
									<div class="timeline-list">
										<?php echo funTimelinev2(base64_decode($_GET['sid'])); ?>
									</div>
								</div>
								<div class="d-grid bm-4 ps-2 pe-2">
									<a href="default.php?page=feeds" class="btn btn-sm btn-light font-size-14 text-center mb-4">
										<span>View more..</span>
									</a>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="card text-body-emphasis bg-body-secondary">
								<div class="card-body position-relative">
									<div class="position-absolute end-0 top-0 p-3">
										<a href="default.php?page=settings_user" class="text-primary">
											<i class="bi bi-pencil-square"></i>
										</a>
									</div>
									<div class="text-center mt-3">
										<div class="chat-avtar d-inline-flex mx-auto mb-2">
											<div class="avatar-lg align-self-center">
												<span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-1">
													<?php echo substr(trim(ucwords(strtolower($Name))), 0, 1); ?>
												</span>
											</div>
										</div>

										<h5 class="mb-0 text-capitalize">
											<?php echo $Name; ?>
										</h5>
										<p class="text-muted text-sm text-capitalize">
											<?php echo $Designation; ?>
										</p>
										<hr class="my-3 border border-secondary-subtle">
										<div class="row g-3">
											<div class="col-4">
												<h5 class="counter-value"
													data-target="<?php echo funUC(base64_decode($_GET['sid'])); ?>">0
												</h5>
												<small class="text-muted">UC</small>
											</div>
											<div class="col-4 border border-top-0 border-bottom-0">
												<h5 class="counter-value"
													data-target="<?php echo funUA(base64_decode($_GET['sid'])); ?>">0
												</h5>
												<small class="text-muted">UA</small>
											</div>
											<div class="col-4">
												<h5 class="counter-value"
													data-target="<?php echo funConnection($Department); ?>">0</h5>
												<small class="text-muted">Connections</small>
											</div>
										</div>

									</div>
								</div>
							</div>
							<div class="card text-body-emphasis bg-body-secondary">
								<div class="card-body">
									<div class="position-absolute end-0 top-0 p-3">
										<a href="default.php?page=leaderboard" class="text-primary">
											<i class="bi bi-info-circle"></i>
										</a>
									</div>
									<h5 class="card-title mb-3">Leaderboard</h5>
									<?php echo showLeaderboard(base64_decode($_GET['sid'])); ?>
								</div>
							</div>
							<div class="card">
								<div class="card-body">
									<h5 class="card-title mb-3">Basic info</h5>
									<fieldset>
										<legend class="text-muted text-uppercase fs-6">About</legend>
										<div class="row mb-1">
											<label for="horizontal-firstname-input"
												class="col-sm-4 col-form-label">Joined:</label>
											<div class="col-sm-8">
												<?php echo $createdate; ?>
											</div>
										</div>
										<div class="row mb-1">
											<label for="horizontal-firstname-input"
												class="col-sm-4 col-form-label">Status:</label>
											<div class="col-sm-8">
												<?php echo funStatus($Status); ?>
											</div>
										</div>
										<div class="row mb-1">
											<label for="horizontal-firstname-input"
												class="col-sm-4 col-form-label">Role:</label>
											<div class="col-sm-8">
												<?php echo $rolename; ?>
											</div>
										</div>
										<div class="row mb-1">
											<label for="horizontal-firstname-input"
												class="col-sm-4 col-form-label">Department:</label>
											<div class="col-sm-8">
												<?php echo $Department; ?>
											</div>
										</div>
									</fieldset>
									<fieldset>
										<legend class="text-muted text-uppercase fs-6">Contacts</legend>
										<div class="row mb-1">
											<label for="horizontal-firstname-input"
												class="col-sm-4 col-form-label">Email:</label>
											<div class="col-sm-8">
												<?php echo substr($Email, 0, 31); ?>&hellip;
											</div>
										</div>
										<div class="row mb-1">
											<label for="horizontal-firstname-input"
												class="col-sm-4 col-form-label">Contact:</label>
											<div class="col-sm-8">
												<?php echo $PhoneNo; ?>
											</div>
										</div>
									</fieldset>
								</div>
							</div><!-- end card -->
							<div class="card">
								<div class="card-body">
									<h5 class="card-title mb-3">Connections</h5>
									<div class="list-group list-group-flush">
										<?php echo funMember($Department); ?>
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