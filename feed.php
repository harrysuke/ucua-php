<?php
include('testFunctions.php');
$title = 'UC Form';

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
                    '.substr(trim($row1['Name']), 0, 1).'
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
		if(!empty($row['evidenceImage'])){
			$img .= '<a href="' . $row['evidenceImage'] . '" class="image-popup-desc" data-title="Photo Evidence" data-description="Photo evidence description. metadata: '.$row['evidenceImage'].'">';
        	$img .= '<img src="'.$row['evidenceImage'].'" alt="Photo Evidence" class="img-fluid mx-auto d-block">';
        	$img .= '</a>&nbsp;';
		}
    }
    return $img;
}

function ucuaURL($id, $type)
{
	if ($type == 'Unsafe Condition') {
		return '<a href="default.php?page=newuiuc_form&id=' . base64_encode($id) . '" class="card-link text-primary"><i class="fa-solid fa-arrow-up-right-from-square"></i> View details</a>
		&nbsp;<a href="default.php?page=history&ucuaid='.$id.'" class="card-link text-primary"><i class="fa-solid fa-arrow-up-right-from-square"></i> View history</a>';
	}
	if ($type == 'Unsafe Action') {
		return '<a href="default.php?page=newuiua_form&id=' . base64_encode($id) . '" class="card-link text-primary"><i class="fa-solid fa-arrow-up-right-from-square"></i> View details</a>
		&nbsp;<a href="default.php?page=history&ucuaid='.$id.'" class="card-link text-primary"><i class="fa-solid fa-arrow-up-right-from-square"></i> View history</a>';
	}
}

function funTimeline($uid, $paginationStart, $limit)
{
	global $db;
	$option = '';
	$sql1 = "SELECT h.ucuaid, u.Name, h.staffid, h.activity, h.action, h.status, h.datetimestamp, f.form_type FROM history AS h 
	LEFT JOIN user AS u ON u.staffId=h.staffid
	LEFT JOIN ucuaform AS f ON f.Id=h.ucuaid
	WHERE h.staffid='".$uid."' AND YEAR(h.datetimestamp)='" . date('Y') . "' ORDER BY h.datetimestamp DESC";
	$result1 = $db->query($sql1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$option .= '<div class="col-xl-12">
		<div class="mt-0">
			<div class="d-flex align-items-start">
				<div class="flex-grow-1 overflow-hidden">
				<a href="default.php?page=profile&sid='.base64_encode($row1['staffid']).'">
				<div class="d-flex align-items-start">
					
					<div class="flex-shrink-0 user-img online align-self-center me-3">
						<div class="avatar-md align-self-center">
							<span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
							'.substr(trim($row1['Name']), 0, 1).'
							</span>
						</div>
					</div>
					
					<div class="flex-grow-1 overflow-hidden">
						<h5 class="text-truncate font-size-14 mb-1">'.ucwords(strtolower($row1['Name'])).'</h5>
						<p class="font-size-13 text-black-50 mb-0">'.date('d M, Y', strtotime($row1['datetimestamp'])).'</p>
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
							<li><a class="dropdown-item" href="#">View details</a></li>
							<li><a class="dropdown-item" href="#">View history</a></li>
							<li><a class="dropdown-item" href="#">View activity</a></li>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="position-relative my-2">
			'.photoEvidence($row1['ucuaid']).'
            </div>

			<div class="pt-3">
				<ul class="list-inline">
					<li class="list-inline-item me-3">
						<a href="javascript: void(0);" class="text-muted">
						<i class="fa-solid fa-tag text-muted me-1"></i> '.ucwords(strtolower($row1['action'])).'
						</a>
					</li>
					<li class="list-inline-item me-3">
						<a href="javascript: void(0);" class="text-muted">
						<i class="fa-solid fa-tag text-muted me-1"></i> '.ucwords(strtolower($row1['status'])).'
						</a>
					</li>
				</ul>
				<p class="text-muted">'.ucfirst(strtolower($row1['activity'])).'</p>

				<div>
					'.ucuaURL($row1['ucuaid'], $row1['form_type']).'
				</div>
			</div>
		</div>
		<!-- end post -->
		'.showActivity($row1['ucuaid']).'
		<hr class="my-3">
	</div>
	<!-- end col -->';
	}
	return $option;
}

function funUC($sid){
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE staffId = '".$sid."' AND form_type = 'Unsafe Condition' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row['counter'];
}

function funUA($sid){
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM ucuaform WHERE staffId = '".$sid."' AND form_type = 'Unsafe Action' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row['counter'];
}

function funConnection($d){
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM user WHERE Department = '".$d."' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row['counter'];
}

function funTotalRecord()
{
	global $db;
	$sql = "SELECT count(id) AS counter FROM history AS h WHERE YEAR(h.datetimestamp)='" . date('Y') . "' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row['counter'];
}

//Pagination
$_SESSION['records-limit'] = 10;
$limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
$p = (isset($_GET['p']) && is_numeric($_GET['p'])) ? $_GET['p'] : 1;
$paginationStart = ($p - 1) * $limit;
$allRecrods = funTotalRecord();
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
						<div class="col-12">
							<div class="page-title-box d-sm-flex align-items-center justify-content-between">
								<h4 class="mb-sm-0 font-size-18">Feeds</h4>

								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
										<li class="breadcrumb-item active">Feeds</li>
									</ol>
								</div>

							</div>
						</div>
					</div>
					<!-- end page title -->

					<div class="row edit-profile-wrap">
						<div class="col-lg-8">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<?php echo funTimeline($_SESSION['user']['staffId'],$paginationStart, $limit); ?>

										<!-- Pagination -->
										<nav aria-label="Page navigation">
											<ul class="pagination mt-5">
												<li class="page-item <?php if ($p <= 1) {
													echo 'disabled';
												} ?>">
													<a class="page-link" href="default.php?page=feeds&p=
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
													<a class="page-link" href="default.php?page=feeds&p=
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
							</div><!-- end card -->

						</div>
						<div class="col-lg-4">
						<div class="card">
								<div class="card-body">
									<div class="d-flex align-items-start mt-3 mt-sm-0">
										<div class="flex-shrink-0">
											<div class="avatar-xl me-3">
												<img src="img/blank.jpg" alt=""
													class="img-fluid rounded-circle d-block">
											</div>
										</div>
										<div class="flex-grow-1">
											<div>
												<h5 class="font-size-16 mb-1 text-capitalize">
													<?php echo $Name; ?>
												</h5>
												<p class="text-muted font-size-13 text-capitalize">
													<?php echo $Designation; ?>
												</p>
												<div
													class="d-flex flex-wrap align-items-start gap-2 gap-lg-3 text-muted font-size-13">
													<div><i
															class="mdi mdi-circle-medium me-1 text-success align-middle"></i>
														<?php echo $Department; ?>
													</div>
													<div><i
															class="mdi mdi-circle-medium me-1 text-success align-middle"></i>
														<a href="mailto:<?php echo $Email; ?>?subject=Hi"><?php echo $Email; ?></a>
													</div>
												</div>
											</div>
										</div>
									</div>
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