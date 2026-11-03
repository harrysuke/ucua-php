<?php
include('testFunctions.php');
$title = 'Preferences';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login');
}

function funCompany()
{
	global $db;
	$option = '';
	$sql1 = "SELECT companyCode, companyName FROM admcompany WHERE isdelete=0 ORDER BY companyName";
	$result1 = $db->query($sql1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$option .= '<option value="' . $row1['companyCode'] . '">' . $row1['companyName'] . ' (' . $row1['companyCode'] . ')</option>';
	}
	return $option;
}

function funBranch()
{
	global $db;
	$option = '';
	$sql1 = "SELECT branchCode, branchName FROM admbranch WHERE isdelete=0 ORDER BY branchName";
	$result1 = $db->query($sql1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$option .= '<option value="' . $row1['branchCode'] . '">' . $row1['branchName'] . ' (' . $row1['branchCode'] . ')</option>';
	}
	return $option;
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

function AccountActivity($sid)
{
	$a = '';
	$y = date('Y');
	global $db;
	$sql = "SELECT staffid, ipaddress, useragent, datetimestamp FROM history WHERE staffid = ? AND useragent <> '' AND YEAR(datetimestamp)=? ORDER BY datetimestamp";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("ss", $sid, $y);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
		$a .= '<tr>
                    <td scope="row">' . substr($row['useragent'], 0, 100) . '</td>
                    <td scope="row">' . $row['ipaddress'] . '</td>
					<td scope="row">' . $row['datetimestamp'] . '</td>
                </tr>';
	}
	$stmt->close();
	return $a;
}

if (isset($_POST['btnProfile'])) {
	try {
		$name = filter_var($_POST['name'] ?? '');
		$phone = filter_var($_POST['phone'] ?? '');
		$department = filter_var($_POST['department'] ?? '');
		$designation = filter_var($_POST['designation'] ?? '');
		$email = filter_var($_POST['email'] ?? '');
		$sid = trim($_SESSION['user']['staffId']);
		$sql = "UPDATE user SET Name = :name, PhoneNo = :phone, Department = :department, Designation = :designation, Email = :email WHERE staffId = :staffId";
		$stmt = $db->prepare($sql);
		$stmt->execute([
			':name' => $name,
			':phone' => $phone,
			':department' => $department,
			':designation' => $designation,
			':email' => $email,
			':staffId' => $sid
		]);

		$_SESSION['statusType'] = "success";
		$_SESSION['status'] = "Record updated successfully.";
	} catch (PDOException $e) {
		$_SESSION['statusType'] = "warning";
		$_SESSION['status'] = "Error " . $e->getMessage();
	}
	echo '<script>alert("Record updated successfully. Please login to apply changes.");</script><meta http-equiv="refresh" content="0; url=default.php?page=settings" />';
	exit;
}

if (isset($_POST['btnChangePassword'])) {
	try {
		$password = md5($_POST['password']);
		$sid = trim($_SESSION['user']['staffId']);
		$sql = "UPDATE user SET Password = :password, Password1 = :password1 WHERE staffId = :staffId";
		$stmt = $db->prepare($sql);
		$stmt->execute([
			':password' => $password,
			':password1' => $password,
			':staffId' => $sid
		]);
		$_SESSION['statusType'] = "success";
		$_SESSION['status'] = "Record updated successfully.";
	} catch (PDOException $e) {
		$_SESSION['statusType'] = "warning";
		$_SESSION['status'] = "Error " . $e->getMessage();
	}
	echo '<script>alert("Record updated successfully. Please login to apply changes.");</script><meta http-equiv="refresh" content="0; url=default.php?page=settings" />';
	exit;
}

try {
	$sid = $_SESSION['user']['staffId'];
	$sql = "SELECT Name, PhoneNo, Department, Designation, Email FROM user WHERE staffId = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$Name = $row['Name'] ?? '';
	$PhoneNo = $row['PhoneNo'] ?? '';
	$Department = $row['Department'] ?? '';
	$Designation = $row['Designation'] ?? '';
	$Email = $row['Email'] ?? '';
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
								<h4 class="mb-sm-0 font-size-18">
									<?php echo $title; ?>
								</h4>

								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
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
						<div class="col-lg-2 col-sm-3 col-4">
							<div class="nav-profile mt-4">
								<div class="nav-header">

								</div>
								<ul class="nav nav-light nav-vertical card-header-tabs" role="tablist">
									<li class="nav-item">
										<a data-bs-toggle="tab" href="#tab_block_0" class="nav-link active">
											<span class="nav-link-text me-4">Account</span>
										</a>
									</li>
									<li class="nav-item">
										<a data-bs-toggle="tab" href="#tab_block_1" class="nav-link">
											<span class="nav-link-text me-4">General </span>
										</a>
									</li>
									<!--li class="nav-item">
										<a data-bs-toggle="tab" href="#tab_block_3" class="nav-link">
											<span class="nav-link-text">Organization</span>
										</a>
									</li-->
									<li class="nav-item">
										<a data-bs-toggle="tab" href="#tab_block_2" class="nav-link">
											<span class="nav-link-text me-4">Profile</span>
										</a>
									</li>
									<li class="nav-item">
										<a data-bs-toggle="tab" href="#tab_block_6" class="nav-link">
											<span class="nav-link-text me-4">Sidebar </span>
										</a>
									</li>
									<li class="nav-item">
										<a data-bs-toggle="tab" href="#tab_block_7" class="nav-link">
											<span class="nav-link-text me-4">Navigation</span>
										</a>
									</li>
									<li class="nav-item">
										<a data-bs-toggle="tab" href="#tab_block_4" class="nav-link">
											<span class="nav-link-text me-4">Security</span>
										</a>
									</li>
									<li class="nav-item">
										<a data-bs-toggle="tab" href="#tab_block_5" class="nav-link">
											<span class="nav-link-text me-4">Activity</span>
										</a>
									</li>

									<!--li class="nav-item">
										<a href="#" class="nav-link">
											<span class="nav-link-text">Notifications</span>
										</a>
									</li>
									<li class="nav-item">
										<a data-scroll="" href="#" class="nav-link">
											<span class="nav-link-text">Connections</span>
										</a>
									</li>
									<li class="nav-item">
										<a data-scroll="" href="#" class="nav-link">
											<span class="nav-link-text">Billing Info</span>
										</a>
									</li-->
								</ul>
							</div>
						</div>
						<div class="col-lg-10 col-sm-9 col-8">
							<div class="tab-content">
								<div class="tab-pane fade show active" id="tab_block_0">
									<div class="title-lg fs-4"><span>Account</span></div>
									<p class="mb-4">Manage your user account information and settings.</p>
									<div class="pb-3">
										<div class="row">
											<div class="col-xl-2">
												<div>
													<h5 class="font-size-15">User ID</h5>
												</div>
											</div>
											<div class="col-xl">
												<div class="text-muted">
													<p class="mb-2">
														<?php echo $_SESSION['user']['staffId']; ?>
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="pb-3">
										<div class="row">
											<div class="col-xl-2">
												<div>
													<h5 class="font-size-15">Password</h5>
												</div>
											</div>
											<div class="col-xl">
												<div class="text-muted">
													<p class="mb-2">
														<a href="#" id="myTab" onclick="showTabPassword()">Change
															Password</a>
													</p>
												</div>
											</div>
										</div>
									</div>

									<div class="pb-3">
										<div class="row">
											<div class="col-xl-2">
												<div>
													<h5 class="font-size-15">Name</h5>
												</div>
											</div>
											<div class="col-xl">
												<div class="text-muted">
													<p class="mb-2">
														<?php echo $_SESSION['user']['Name']; ?>
													</p>
													<p class="mb-2">
														<a href="#" id="myTab" onclick="showTabName()">Change Name</a>
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="pb-3">
										<div class="row">
											<div class="col-xl-2">
												<div>
													<h5 class="font-size-15">Company</h5>
												</div>
											</div>
											<div class="col-xl">
												<div class="text-muted">
													<p class="mb-2">
														<?php echo $_SESSION['company']['companyName']; ?>
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="pb-3">
										<div class="row">
											<div class="col-xl-2">
												<div>
													<h5 class="font-size-15">Branch</h5>
												</div>
											</div>
											<div class="col-xl">
												<div class="text-muted">
													<p class="mb-2 text-uppercase">
														<?php echo $_SESSION['company']['branchName']; ?>
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="pb-3">
										<div class="row">
											<div class="col-xl-2">
												<div>
													<h5 class="font-size-15">Designation</h5>
												</div>
											</div>
											<div class="col-xl">
												<div class="text-muted">
													<p class="mb-2">
														<?php echo $_SESSION['user']['Designation']; ?>
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="pb-3">
										<div class="row">
											<div class="col-xl-2">
												<div>
													<h5 class="font-size-15">Department</h5>
												</div>
											</div>
											<div class="col-xl">
												<div class="text-muted">
													<p class="mb-2">
														<?php echo $_SESSION['user']['Department']; ?>
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="pb-3">
										<div class="row">
											<div class="col-xl-2">
												<div>
													<h5 class="font-size-15">Email</h5>
												</div>
											</div>
											<div class="col-xl">
												<div class="text-muted">
													<p class="mb-2">
														<?php echo $_SESSION['user']['Email']; ?>
													</p>
													<p class="mb-2">
														<a href="#" id="myTab" onclick="showTabName()">Change Email</a>
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="pb-3">
										<div class="row">
											<div class="col-xl-2">
												<div>
													<h5 class="font-size-15">Phone No.</h5>
												</div>
											</div>
											<div class="col-xl">
												<div class="text-muted">
													<p class="mb-2">
														<?php echo $_SESSION['user']['PhoneNo']; ?>
													</p>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="tab-pane fade " id="tab_block_1">
									<form method="post" action="default.php?page=settings">
										<!--div class="row gx-3">
											<div class="col-sm-12">
												<div class="form-group">
													<div class="media align-items-center">
														<div class="float-start me-3">
															<div class="avatar avatar-rounded avatar-xxl">
																<img src="img/blank.jpg" alt="user"
																	class="rounded-circle avatar-lg">
															</div>
														</div>
														<div class="float-start">
															<div class="">
																Upload Photo
																<input type="file" class="form-control">
															</div>
															<div class="form-text text-muted">
																For better preview recommended size is 450px x 450px.
																Max size 5mb.
															</div>
														</div>
													</div>
												</div>
											</div>
										</div-->
										<div class="title-lg fs-4"><span>General</span></div>
										<p class="mb-4">Here you can edit public information about yourself.</p>
										<div class="row gx-3">
											<div class="col-sm-6">
												<div class="title title-xs title-wth-divider text-primary text-uppercase my-4">
													<span>Personal info</span>
												</div>

												<div class="form-group mb-3">
													<label class="form-label">Name</label>
													<input class="form-control" type="text" name="name"
														value="<?php echo $Name; ?>" />
												</div>
												<div class="form-group mb-3">
													<label class="form-label">Company</label>
													<select name="company" class="form-control" data-trigger
														id="choices-single-default"
														placeholder="This is a search placeholder" required>
														<option value="<?php echo $_SESSION['company']['companyName']; ?>">
															<?php echo $_SESSION['company']['companyName']; ?>
														</option>
														<?php echo funCompany(); ?>
													</select>
												</div>
												<div class="form-group mb-3">
													<label class="form-label">Branch</label>
													<select name="branch" class="form-control" data-trigger
														id="choices-single-default"
														placeholder="This is a search placeholder" required>
														<option value="<?php echo $_SESSION['company']['branchCode']; ?>">
															<?php echo $_SESSION['company']['branchName']; ?>
														</option>
														<?php echo funBranch(); ?>
													</select>
												</div>
												<div class="form-group mb-3">
													<label class="form-label">Designation</label>
													<input class="form-control" type="text" name="designation"
														value="<?php echo $Designation; ?>" />
												</div>
												<div class="form-group mb-3">
													<label class="form-label">Department</label>
													<select name="department" class="form-control" data-trigger
														id="choices-single-default"
														placeholder="This is a search placeholder" required>
														<option value="<?php echo $Department; ?>">
															<?php echo $Department; ?>
														</option>
														<?php echo funDepartment(); ?>
													</select>
												</div>



												<div class="title title-xs title-wth-divider text-primary text-uppercase my-4">
													<span>Contact info</span>
												</div>
												<div class="form-group mb-3">
													<label class="form-label">Email</label>
													<input class="form-control" type="text" name="email"
														value="<?php echo $Email; ?>" />
												</div>

												<div class="form-group mb-3">
													<label class="form-label">Phone</label>
													<input class="form-control" type="text" name="phone"
														value="<?php echo $PhoneNo; ?>" />
												</div>
											</div>

										</div>



										<!--div class="row gx-3">
											<div class="col-sm-12">
												<div class="form-group">
													<div class="form-label-group">
														<label class="form-label">Bio</label>
														<small class="text-muted">1200</small>
													</div>
													<textarea class="form-control" rows="8" placeholder="Write an internal note"></textarea>
													<small class="form-text text-muted">
														Brief bio about yourself. This will be displayed on your profile page.
													</small>
												</div>
											</div>
										</div-->
										<!--div class="title title-xs title-wth-divider text-primary text-uppercase my-4">
											<span>Additional Info</span></div>
										<div class="row gx-3">
											<div class="col-sm-12">
												<div class="form-group">
													<label class="form-label">Personal Website</label>
													<input class="form-control" type="text" value="jpb.com" />
												</div>
											</div>
											<div class="col-sm-12">
												<div class="form-group">
													<label class="form-label">Phone</label>
													<input class="form-control" type="text" value="xxxxxxx987" />
												</div>
												<div class="form-check">
													<input type="checkbox" class="form-check-input"
														id="customCheckList4">
													<label class="form-check-label" for="customCheckList4">
														Keep my number private
													</label>
												</div>
											</div>
										</div-->
										<button type="submit" name="btnProfile" class="btn btn-primary mt-5">Save Changes</button>
										<p>&nbsp;</p>
									</form>
								</div>
								<div class="tab-pane fade" id="tab_block_2">
									<div class="title-lg fs-4"><span>Profile</span></div>
									<p class="mb-4">Here you can edit your account setting information.</p>
									<form>
										<div class="row gx-3">
											<div class="col-sm-6">
												<div class="form-group mb-3">
													<label class="form-label">Username</label>
													<input class="form-control" type="text"
														value="<?php echo $_SESSION['user']['staffId']; ?>" />
												</div>

												<div class="form-group mb-3">
													<label class="form-label">Email</label>
													<input class="form-control" type="text"
														value="<?php echo $_SESSION['user']['Email']; ?>" />
												</div>

												<div class="form-group mb-3">
													<label class="form-label">Timezone</label>
													<input class="form-control" type="text" value="Asia/Kuala Lumpur" />
												</div>
												<div class="form-group mb-3">
													<button type="button" class="btn btn-primary mt-5">Save Changes</button>
												</div>
											</div>
										</div>

										<div class="mt-3 mb-3">
											<hr />
										</div>

										<div class="title title-xs title-wth-divider text-danger text-uppercase my-4">
											<span>Danger zone</span>
										</div>
										<div class="row gx-3 ">
											<div class="col-sm-3">
												<div class="form-group">
													<a href="#" class="h5 d-block mb-0">Delete Account</a>
													<small class="form-text text-muted">
														Delete account and all your data
													</small>
												</div>
											</div>
											<div class="col-sm-3 text-end">
												<div class="form-group">
													<button type="button" class="btn btn-danger">Close account</button>
												</div>
											</div>
										</div>
										<p>&nbsp;</p>

									</form>
								</div>
								<div class="tab-pane fade" id="tab_block_3">
									<div class="title-lg fs-4"><span>Organization</span></div>
									<p class="mb-4">Change your organization settings.</p>
									<form>
										<div class="mb-3">
											<img src="img/JPB-Logo.png" class="rounded me-2"
												data-holder-rendered="true" />
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="mb-3">
													<label for="logo" class="form-label">Logo URL</label>
													<input class="form-control" type="text"
														value="<?php echo dirname($fullurl) . '/img/JPB-Logo.png'; ?>"
														id="logo">
												</div>
												<div class="mb-3">
													<label for="company" class="form-label">Company name</label>
													<input class="form-control" type="text" value="Johor Port Berhad"
														id="company">
												</div>
											</div>
											<div class="col-sm-6">
												<div class="mb-3">
													<label for="color" class="form-label">Brand color</label>
													<input class="form-control" type="text" value="#00008B" id="color">
												</div>
												<div class="mb-3">
													<label for="web" class="form-label">Website</label>
													<input class="form-control" type="text" value="www.johorport.com.my"
														id="web">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="title title-xs title-wth-divider text-primary text-uppercase my-4">
												<span>Contact info</span>
											</div>
											<div class="col-sm-6">
												<div class="mb-3">
													<label for="Email" class="form-label">Email</label>
													<input class="form-control" type="text" value="jpb@johorport.com.my"
														id="Email">
												</div>
												<div class="mb-3">
													<label for="Address" class="form-label">Address</label>
													<textarea class="form-control" id="Address">P.O Box 151, 81707 Pasir Gudang, Johor, Malaysia</textarea>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="mb-3">
													<label for="Phone" class="form-label">Phone</label>
													<input class="form-control" type="text" value="+607 - 253 5888"
														id="Phone">
												</div>
												<div class="mb-3">
													<label for="fax" class="form-label">Fax</label>
													<input class="form-control" type="text" value="+607 - 251 0980"
														id="fax">
												</div>
											</div>
										</div>



										<button type="button" class="btn btn-secondary mt-5 mb-5">Save Changes</button>
									</form>
								</div>
								<div class="tab-pane fade" id="tab_block_4">
									<div class="title-lg fs-4"><span>Login & Security</span></div>
									<p class="mb-4">Here you can edit your login & security settings information.</p>

									<form method="post" action="default.php?page=settings">
										<div class="title title-xs title-wth-divider text-primary text-uppercase my-4">
											<span>Password Settings</span>
										</div>
										<div class="row gx-3">
											<div class="col-sm-6">
												<div class="form-group mb-2">
													<label class="form-label">Old password</label>
													<input type="password" name="password0" class="form-control" placeholder="Old password"
														required />
												</div>
												<div class="form-group mb-2">
													<label class="form-label">New password</label>
													<input type="password" name="password" class="form-control" placeholder="New password"
														required />
												</div>
												<div class="form-group mb-2">
													<label class="form-label">Confirm password</label>
													<input type="password" name="password2" class="form-control" placeholder="Confirm password"
														required />
												</div>
												<div class="form-group mb-2 text-body-tertiary mt-4">
													<p class="fw-semibold mt-2">Password Requirements</p>
													<ul class="ps-3 mb-0">
														<li class="mb-1">Minimum 8 characters long - Maximum 12 characters</li>
														<li class="mb-1">At least one uppercase character: A, B, C, ... Z</li>
														<li class="mb-1">At least one lowercase character: a, b, c, ... z</li>
														<li class="mb-1">At least one number: 0, 1, 2, 3, 4, 5, 6, 7, 8, 9
														<li class="mb-1">At least one special character: ~!@#$%^&*()_+={}|\[]:;<>,./</li>
													</ul>
												</div>

												<div class="form-group mb-4">
													<button type="submit" name="btnChangePassword" class="btn btn-primary mt-3">Change password</button>
												</div>
											</div>
										</div>
									</form>
									<!--div class="title title-xs title-wth-divider text-primary text-uppercase my-4">
										<span>Additional Security</span>
									</div>
									<div class="row gx-3">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="form-label ">2-Step Verification (2FA)</label>
												<small class="form-text text-muted d-block">
													2-step verification drastically reduces the chances of having
													the personal information in your Google account stolen by
													someone else. Why? Because hackers would have to not only get
													your password and your username, they'd have to get a hold of
													your phone. A <a href="#" class="text-primary">6-digit</a> code
													may be sent to a number you’ve previously provided. Codes can be
													sent in a text message (SMS) or through a voice call, which
													depends on the setting you chose. To verify it’s you, enter the
													code on the sign-in screen.
												</small>
												<button type="button" class="btn btn-primary mt-3">Add
													Authentication</button>

											</div>
										</div>
									</div-->

								</div>
								<div class="tab-pane fade" id="tab_block_5">
									<div class="title-lg fs-4"><span>Recent account activity</span></div>
									<p class="mb-4">These are the sessions which are currently or recently active.</p>
									<div class="table-responsive">
										<table class="table table-nowrap align-middle justify-content-center mb-0">
											<tr>
												<th>Client</th>
												<th>IP address</th>
												<th>Last activity</th>
											</tr>
											<?php echo AccountActivity($_SESSION['user']['staffId']); ?>
										</table>
									</div>
								</div>
								<div class="tab-pane fade" id="tab_block_6">
									<div class="title-lg fs-4"><span>Sidebar</span></div>
									<p class="mb-4">The following items will be shown in your sidebar.</p>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="formCheck2" checked>
										<label class="form-check-label" for="formCheck2">
											Main menu
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="formCheck2" checked>
										<label class="form-check-label" for="formCheck2">
											Management
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="formCheck2">
										<label class="form-check-label" for="formCheck2">
											Favorites
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="formCheck2" checked>
										<label class="form-check-label" for="formCheck2">
											Resources
										</label>
									</div>

								</div>
								<div class="tab-pane fade" id="tab_block_7">
									<div class="title-lg fs-4"><span>Navigation</span></div>
									<p class="mb-4">The following items will be shown in your navigation bar.</p>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="formCheck2" checked>
										<label class="form-check-label" for="formCheck2">
											Create new
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="formCheck2">
										<label class="form-check-label" for="formCheck2">
											Invitations
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="formCheck2" checked>
										<label class="form-check-label" for="formCheck2">
											Favorites
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="formCheck2" checked>
										<label class="form-check-label" for="formCheck2">
											Resources
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="formCheck2" checked>
										<label class="form-check-label" for="formCheck2">
											Notifications
										</label>
									</div>

								</div>
							</div>
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
	<script>
		function showTabPassword() {
			$('.nav-light a[href="#tab_block_4"]').tab('show');
		}

		function showTabName() {
			$('.nav-light a[href="#tab_block_1"]').tab('show');
		}
	</script>

</body>

</html>