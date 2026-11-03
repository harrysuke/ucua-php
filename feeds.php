<?php
$title = 'Feeds';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login');
}

function funDepartment()
{
	global $db;
	$option = '';
	$sql1 = "SELECT * FROM dept WHERE isdelete=0 ORDER BY deptName";
	$result1 = $db->query($sql1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$option .= '<option value="' . $row1['dept'] . '">' . $row1['deptName'] . ' (' . $row1['dept'] . ')</option>';
	}
	return $option;
}

function funMember($d)
{
	global $db;
	$option = '';
	$sql1 = "SELECT staffId, Name, Designation, Department FROM user WHERE Department='" . $d . "' AND isdelete=0 ORDER BY RAND() LIMIT 6";
	$result1 = $db->query($sql1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$option .= '<a href="default.php?page=profile&sid=' . base64_encode($row1['staffId']) . '" class="list-group-item list-group-item-action">
		<div class="d-flex align-items-start">
            <div class="flex-shrink-0 user-img online align-self-center me-2">
                <div class="avatar-sm align-self-center">
                    <span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                    ' . substr(trim($row1['Name']), 0, 1) . '
                    </span>
                </div>    
            </div>                                              
            <div class="flex-grow-1 overflow-hidden">
            	<h5 class="text-truncate font-size-14 mb-1">' . ucwords(strtolower($row1['Name'])) . '</h5>
                <p class="text-truncate mb-0">' . $row1['Designation'] . ' ' . $row1['Department'] . '</p>
            </div>
        </div>
	</a>';
	}
	return $option;
}

function funMemberAvatar($d)
{
	global $db;
	$option = '';
	$sql1 = "SELECT staffId, Name, Designation, Department FROM user WHERE Department='" . $d . "' AND isdelete=0 ORDER BY RAND() LIMIT 18";
	$result1 = $db->query($sql1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$option .= '<div class="avatar-sm align-self-center">
			<span class="avatar-title rounded-circle  bg-primary-subtle text-primary" title="' . ucwords(strtolower($row1['Name'])) . '">
			' . substr(trim($row1['Name']), 0, 1) . '
			</span>
		</div>';
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

function photoEvidence($id)
{
	$img = '';
	global $db;
	$sql = "SELECT evidenceImage FROM ucuaphoto WHERE ucuaform_Id = '" . $id . "' AND type='Photo Evidence'";
	$result = mysqli_query($db, $sql);
	while ($row = mysqli_fetch_array($result)) {
		if (!empty($row['evidenceImage'])) {
			$img .= '<a href="' . $row['evidenceImage'] . '" class="image-popup-desc" data-title="Photo Evidence" data-description="Photo evidence description. metadata: ' . $row['evidenceImage'] . '">';
			$img .= '<img src="' . $row['evidenceImage'] . '" alt="Photo Evidence" class="img-fluid mx-auto d-block">';
			$img .= '</a>&nbsp;';
		}
	}
	return $img;
}

function ucuaURL($id, $type)
{
	if ($type == 'Unsafe Condition') {
		return '<li class="dropdown-item"><a href="default.php?page=newuiuc_form&id=' . base64_encode($id) . '" class="text-dark"> View details</a></li> 
		<li class="dropdown-item"><a href="default.php?page=history&ucuaid=' . $id . '" class="text-dark"> View history</a></li>';
	}
	if ($type == 'Unsafe Action') {
		return '<li class="dropdown-item"><a href="default.php?page=newuiua_form&id=' . base64_encode($id) . '" class="text-dark"> View details</a></li>
		<li class="dropdown-item"><a href="default.php?page=history&ucuaid=' . $id . '" class="text-dark"> View history</a></li>';
	}
}

function funShowPost($cd, $o, $a)
{
	if (!empty($cd)) {
		return ucfirst(strtolower($cd));
	} else {
		return ucfirst(strtolower($o));
	}
}

function funTimeline($f, $paginationStart, $limit)
{
	global $db;
	$option = '';
	if ($f == 'Unsafe Condition') {
		$sql1 = "SELECT f.Id, u.Name, u.staffId, f.conditionDetails,o.Offence, h.activity, h.action, h.status, h.datetimestamp, f.form_type FROM ucuaform AS f
		LEFT JOIN history AS h ON h.ucuaid=f.Id 
		LEFT JOIN user AS u ON u.staffId=f.staffId
		LEFT JOIN offence AS o ON o.OffenceCode=f.OffenceCode
		WHERE f.staffId='" . $_SESSION['user']['staffId'] . "' AND f.form_type='" . $f . "' AND f.isdelete=0
		ORDER BY h.datetimestamp DESC LIMIT $paginationStart, $limit";
	} elseif ($f == 'Unsafe Action') {
		$sql1 = "SELECT f.Id, u.Name, u.staffId, f.conditionDetails,o.Offence, h.activity, h.action, h.status, h.datetimestamp, f.form_type FROM ucuaform AS f
		LEFT JOIN history AS h ON h.ucuaid=f.Id 
		LEFT JOIN user AS u ON u.staffId=f.staffId
		LEFT JOIN offence AS o ON o.OffenceCode=f.OffenceCode
		WHERE f.staffId='" . $_SESSION['user']['staffId'] . "' AND f.form_type='" . $f . "' AND f.isdelete=0
		ORDER BY h.datetimestamp DESC LIMIT $paginationStart, $limit";
	} elseif ($f == 'all') {
		$sql1 = "SELECT f.Id, u.Name, u.staffId, f.conditionDetails,o.Offence, h.activity, h.action, h.status, h.datetimestamp, f.form_type FROM ucuaform AS f
		LEFT JOIN history AS h ON h.ucuaid=f.Id 
		LEFT JOIN user AS u ON u.staffId=f.staffId
		LEFT JOIN offence AS o ON o.OffenceCode=f.OffenceCode
		WHERE f.isdelete=0
		ORDER BY h.datetimestamp DESC LIMIT $paginationStart, $limit";
	} else {
		$sql1 = "SELECT f.Id, u.Name, u.staffId, f.conditionDetails,o.Offence, h.activity, h.action, h.status, h.datetimestamp, f.form_type FROM ucuaform AS f
		LEFT JOIN history AS h ON h.ucuaid=f.Id 
		LEFT JOIN user AS u ON u.staffId=f.staffId
		LEFT JOIN offence AS o ON o.OffenceCode=f.OffenceCode
		WHERE f.staffId='" . $_SESSION['user']['staffId'] . "' AND f.isdelete=0
		ORDER BY h.datetimestamp DESC LIMIT $paginationStart, $limit";
	}
	$result1 = $db->query($sql1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$option .= '<div class="card">
		<div class="card-body">
			<div class="d-flex align-items-start">
				<div class="flex-grow-1 overflow-hidden">
				<a href="default.php?page=profile&sid=' . base64_encode($row1['staffId']) . '">
				<div class="d-flex align-items-start">
					<div class="flex-shrink-0 user-img online align-self-center me-3">
						<div class="avatar-md align-self-center">
							<span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
							' . substr(trim($row1['Name']), 0, 1) . '
							</span>
						</div>
					</div>
					<div class="flex-grow-1 overflow-hidden">
						<h5 class="text-truncate font-size-14 mb-1">' . ucwords(strtolower($row1['Name'])) . '</h5>
						<p class="font-size-13 text-black-50 mb-0">' . date('d M, Y', strtotime($row1['datetimestamp'])) . '</p>
					</div>
					<div class="flex-shrink-0">
						<div class="font-size-11"></div>
					</div>
				</div>
				</a>
				</div>
				<div class="flex-shrink-0 ms-2">
					<div class="dropdown">
						<a class="btn btn-link text-muted font-size-16 p-1 py-0 dropdown-toggle shadow-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fa-solid fa-ellipsis-vertical"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							' . ucuaURL($row1['Id'], $row1['form_type']) . '
						</ul>
					</div>
				</div>
			</div>
			<div class="position-relative my-2">
			' . photoEvidence($row1['Id']) . '
            </div>
			<div class="pt-3">
				<ul class="list-inline">
					<li class="list-inline-item me-3">
						<a href="javascript: void(0);" class="text-muted">
						<i class="fa-solid fa-hashtag text-black-50 me-1"></i>' . ucwords(strtolower($row1['action'])) . '
						</a>
					</li>
					<li class="list-inline-item me-3">
						<a href="javascript: void(0);" class="text-muted">
						<i class="fa-solid fa-hashtag text-black-50 me-1"></i>' . ucwords(strtolower($row1['status'])) . '
						</a>
					</li>
				</ul>
				<p class="text-muted">' . funShowPost($row1['conditionDetails'], $row1['Offence'], $row1['activity']) . '</p>
			</div>
		</div>
		<div class="ms-4">
		' . showActivity($row1['Id']) . '
		</div>
	</div>';
	}
	return $option;
}

function funUC($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE staffId = '" . $sid . "' AND form_type = 'Unsafe Condition' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row['counter'];
}

function funUA($sid)
{
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE staffId = '" . $sid . "' AND form_type = 'Unsafe Action' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row['counter'];
}

function funConnection($d)
{
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM user WHERE Department = '" . $d . "' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row['counter'];
}

if (!empty($_GET['f'])) {
	$_SESSION['f'] = base64_decode($_GET['f']);
} else {
	$_SESSION['f'] = 'home';
}

function showLeaderboard()
{
	global $db;
	$l = '';
	$sql1 = "SELECT staffId, Name, ranking, points FROM  viewleaderboard LIMIT 5";
	$result1 = $db->query($sql1);
	if ($result1) {
		while ($row1 = mysqli_fetch_array($result1)) {
			$l .= '<div class="d-flex mb-4">
										<div class="flex-shrink-0 user-img online align-self-center me-2">
											<div class="avatar-sm align-self-center">
												<span class="avatar-title rounded-circle bg-primary-subtle text-primary">
													' . $row1['ranking'] . '
												</span>
											</div>
										</div>
										<div class="flex-grow-1">
											<h6><a href="default.php?page=profile&sid=' . base64_encode($row1['staffId']) . '" target="_blank">' . trim(ucwords(strtolower($row1['Name']))) . '</a></h6>
											Ranking ' . trim($row1['ranking']) . '
											with ' . trim($row1['points']) . ' points.
										</div>
									</div>';
		}
	} else {
		$l .= 'No Record';
	}
	return $l;
}

function funTotalRecord($f)
{
	global $db;
	if ($f == 'Unsafe Condition') {
		$sql = "SELECT count(f.id) AS counter FROM ucuaform AS f
		WHERE f.staffId='" . $_SESSION['user']['staffId'] . "' AND f.form_type='" . $f . "' ";
	} elseif ($f == 'Unsafe Action') {
		$sql = "SELECT count(f.id) AS counter FROM ucuaform AS f
		WHERE f.staffId='" . $_SESSION['user']['staffId'] . "' AND f.form_type='" . $f . "' ";
	} elseif ($f == 'all') {
		$sql = "SELECT count(f.id) AS counter FROM ucuaform AS f";
	} else {
		$sql = "SELECT count(f.id) AS counter FROM ucuaform AS f
		WHERE f.staffId='" . $_SESSION['user']['staffId'] . "' ";
	}
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row['counter'];
}

//Pagination
$_SESSION['records-limit'] = 10;
$limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
$p = (isset($_GET['p']) && is_numeric($_GET['p'])) ? $_GET['p'] : 1;
$paginationStart = ($p - 1) * $limit;
$allRecrods = funTotalRecord($_SESSION['f']);
$totalPages = ceil($allRecrods / $limit);
$prev = $p - 1;
$next = $p + 1;

try {
	$sql = "SELECT u.Name, u.PhoneNo, u.Department, u.Designation, u.AccessLevel, u.Email, u.createdate, u.Status, r.rolename FROM user AS u 
	LEFT JOIN userroles AS r ON r.id=u.AccessLevel
	WHERE u.staffId = '" . $_SESSION['user']['staffId'] . "' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	$Name = $row['Name'];
	$PhoneNo = $row['PhoneNo'];
	$Department = $row['Department'];
	$Designation = $row['Designation'];
	$AccessLevel = $row['AccessLevel'];
	$Email = $row['Email'];
	$createdate = $row['createdate'];
	$Status = $row['Status'];
	$rolename = $row['rolename'];
} catch (Exception $e) {
	die($e->getMessage());
}

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
						<div class="col-4">
							<div class="page-title-box d-sm-flex">
								<h4 class="mb-sm-0 font-size-18">
									<?php echo $title . ': ' . ucwords($_SESSION['f']); ?>
									<a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
								</h4>
							</div>
						</div>
						<div class="col-4 ">
							<div class="dropdown float-end">
								<button type="button" class="btn btn-soft-light waves-effect dropdown-toggle " id="dropdownMenuButton"
									data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-sliders"></i> Filter</button>
								<div class="dropdown-menu float-end" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="default.php?page=feeds&f=<?php echo base64_encode('Unsafe Action'); ?>">Unsafe condition</a>
									<a class="dropdown-item" href="default.php?page=feeds&f=<?php echo base64_encode('Unsafe Action'); ?>">Unsafe action</a>
									<a class="dropdown-item" href="default.php?page=feeds&f=<?php echo base64_encode('all'); ?>">All feeds</a>
								</div>
							</div>
						</div>
						<div class="col-4 ">
							<div class="page-title-right float-end">
								<ol class="breadcrumb m-0">
									<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
									<li class="breadcrumb-item active">
										<?php echo $title; ?>
									</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<!-- end page title -->

				<div class="row edit-profile-wrap">
					<div class="col-lg-8">
						<div class="row">
							<?php echo funTimeline($_SESSION['f'], $paginationStart, $limit); ?>
							<!-- Pagination -->
							<nav aria-label="Page navigation">
								<ul class="pagination mt-5">
									<li class="page-item <?php if ($p <= 1) {
																echo 'disabled';
															} ?>">
										<a class="page-link" href="default.php?page=feeds&f=<?php echo base64_encode($_SESSION['f']); ?>&p=
					  <?php if ($p <= 1) {
							echo '#';
						} else {
							echo trim($prev);
						} ?>">
											<span aria-hidden="true">&laquo;</span> Previous
										</a>
									</li>

									<?php for ($i = 1; $i <= $totalPages; $i++): ?>

										<!--li class="page-item <?php if ($p == $i) {
																	echo 'active';
																} ?>">
					  <a class="page-link" href="default.php?page=feeds&p=<?= $i; ?>"> <?= $i; ?> </a>
					</li-->

									<?php endfor; ?>

									<li class="page-item <?php if ($p >= $totalPages) {
																echo 'disabled';
															} ?>">
										<a class="page-link" href="default.php?page=feeds&f=<?php echo base64_encode($_SESSION['f']); ?>&p=
					  <?php if ($p >= $totalPages) {
							echo '#';
						} else {
							echo trim($next);
						} ?>">
											Next <span aria-hidden="true">&raquo;</span>
										</a>
									</li>
								</ul>
							</nav>
						</div><!-- end row -->
					</div>
					<div class="col-lg-4">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title mb-3">Feeds</h5>
								<i class="fa-solid fa-rss"></i>
								<a href="default.php?page=rss" target="_blank">RSS Feeds</a>
							</div>
						</div>

						<div class="card">
							<div class="card-body">
								<h5 class="card-title mb-3">Leaderboard</h5>
								<?php echo showLeaderboard(); ?>
								<div class="d-grid bm-4">
									<a href="default.php?page=leaderboard"
										class="btn btn-sm btn-light font-size-14 text-center">
										<span>See all leaderboards</span>
									</a>
								</div>
							</div>
						</div>
					</div>
					<!--div class="col-lg-4">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title mb-3">People</h5>
								<div class="d-flex flex-wrap gap-2 font-size-16">
									<?php echo funMemberAvatar($Department); ?>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<h5 class="card-title mb-3">Connections</h5>

								<div class="list-group list-group-flush">
									<?php echo funMember($Department); ?>
								</div>
							</div>
						</div>
					</div-->
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