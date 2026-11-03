<?php
$title = 'UC Form';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function photoEvidence($id)
{
    $img = '';
    global $db;
    $sql = "SELECT evidenceImage FROM ucuaphoto WHERE ucuaform_Id = '" . $id . "' AND type='Photo Evidence'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $img .= '<a href="' . $row['evidenceImage'] . '" class="image-popup-desc" data-title="Photo Evidence" data-description="Photo evidence description. metadata: ' . $row['evidenceImage'] . '">';
        $img .= '<img src="' . $row['evidenceImage'] . '" alt="Photo Evidence" class="img-thumbnail w-25">';
        $img .= '</a>&nbsp;';
    }
    return $img;
}

function closedEvidence($id)
{
    $img = '';
    global $db;
    $sql = "SELECT evidenceImage FROM ucuaphoto WHERE ucuaform_Id = '" . $id . "' AND type='Close Evidence'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $img .= '<a href="' . $row['evidenceImage'] . '" class="image-popup-desc" data-title="Photo Evidence" data-description="Photo evidence description.">';
        $img .= '<img id="myImg" src="' . $row['evidenceImage'] . '" alt="Photo Evidence" width="200">';
        $img .= '</a>&nbsp;';
    }
    return $img;
}

function funLocation()
{
    $option = '';
    global $db;
    $sql1 = "SELECT LocationId, LocationName FROM location";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $option .= '<option value="' . $row1['LocationId'] . '">' . $row1['LocationName'] . '</option>';
    }
    return $option;
}

function funDepartment()
{
    $option = '';
    global $db;
    $sql = "SELECT dept AS deptCode, deptName FROM dept ORDER BY deptName";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $option .= '<option value="' . $row['deptCode'] . '">' . $row['deptName'] . ' (' . $row['deptCode'] . ')</option>';
    }
    return $option;
}

function funEmail($e)
{
    global $db;
    $option = '';
    $sql1 = "SELECT HSEname, Email FROM email WHERE AccessLevel='" . trim($e) . "' AND isdelete=0 ORDER BY HSEname";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $option .= '<option value="' . $row1['Email'] . '">' . $row1['HSEname'] . ' (' . $row1['Email'] . ')</option>';
    }
    return $option;
}

function findUserEmail($s)
{
    try {
        global $db;
        $sql1 = "SELECT Email FROM user WHERE staffId='" . trim($s) . "' AND isdelete=0";
        $result1 = mysqli_query($db, $sql1);
        $row1 = mysqli_fetch_array($result1);
        return $row1['Email'];
    } catch (Exception $e) {
        return 'khairij@gmail.com';
    }
}

function findUserName($s)
{
    try {
        global $db;
        $sql1 = "SELECT Name FROM user WHERE staffId='" . trim($s) . "' AND isdelete=0";
        $result1 = mysqli_query($db, $sql1);
        $row1 = mysqli_fetch_array($result1);
        if ($row1) {
            return strtoupper($row1['Name']);
        } else {
            return $s;
        }
    } catch (Exception $e) {
        return $e;
    }
}

function checkObserverDept($d, $id)
{
    global $db;
    if (empty($d)) {
        $sql = "SELECT Department FROM user WHERE staffId='" . $id . "' ";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);
        return $row['Department'];
    } else {
        return $d;
    }
}

function funHsereps()
{
    $option = '';
    global $db;
    $sql = "SELECT staffId, Name, Email FROM user WHERE AccessLevel IN(4,5,8) AND isdelete=0 ORDER BY Name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $option .= '<option value="0, ' . $row['Email'] . ', ' . $row['staffId'] . '">' . $row['Name'] . ' (' . $row['Email'] . ')</option>';
    }
    return $option;
}

function funDeptName($d)
{
    global $db;
    $sql = "SELECT deptName FROM dept WHERE deptId = '" . $d . "' ";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {
        return $row['deptName'];
    } else {
        return 'Please choose';
    }
}

function funRelatedDept()
{
    $option = '';
    global $db;
    $sql = "SELECT staffId, Name, Email FROM user WHERE Department = '" . $_SESSION['user']['Department'] . "' AND isdelete=0 ORDER BY Name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $option .= '<option value="0, ' . $row['Email'] . ', ' . $row['staffId'] . '">' . $row['Name'] . ' (' . $row['Email'] . ')</option>';
    }
    return $option;
}

function funRelatedDept2()
{
    $option = '';
    global $db;
    $sql = "SELECT staffId, Name, Email FROM user WHERE AccessLevel = 4 ORDER BY Name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $option .= '<option value="' . $row['Email'] . '">' . $row['Name'] . ' (' . $row['Email'] . ')</option>';
    }
    return $option;
}

function funRelatedDept3($d)
{
    $option = '<option value="' . $d . '">' . funDeptName($d) . '</option>';
    global $db;
    $sql = "SELECT deptId, deptName FROM dept WHERE isdelete=0 ORDER BY deptName";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $option .= '<option value="' . $row['deptId'] . '">' . $row['deptName'] . '</option>';
    }
    return $option;
}

function funhse($e)
{
    $option = '';
    global $db;
    $sql = "SELECT Name, Email FROM user WHERE Email = '" . $e . "' ORDER BY Name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $option .= $row['Name'] . ' (' . $row['Email'] . ')';
    }
    return $option;
}

function funpic($e)
{
    $option = '';
    global $db;
    $sql = "SELECT Name, Email FROM user WHERE Email = '" . $e . "' ORDER BY Name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $option .= $row['Name'] . ' (' . $row['Email'] . ')';
    }
    return $option;
}

function funHodStatus($status)
{
    if ($status == 1) {
        $status = '<span class="badge text-bg-success">APPROVED</span>';
    } else {
        $status = '<span class="badge text-bg-warning">PENDING</span>';
    }
    return $status;
}

function funStatus($s, $a)
{

    global $db;
    $status = '';
    $sql = "SELECT isverified, isapproved, isrejected, isHOSrejected, isHODrejected, isconfirm, isNotifyHos, isHosApproved, isHodApproved, isclosed, isreopen, isupload,
        CASE 
            WHEN isverified = 0 THEN 0
            WHEN isverified = 1 THEN 1
            WHEN isverified = 2 THEN 2
			WHEN isrejected = 1 THEN 3
        END AS status
    FROM ucuaform WHERE id = '" . $s . "' ";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    if ($a == 4) { //PSSD HSE OFFICER
        if ($row['isapproved'] == 0 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
            $status = '<span class="badge text-bg-warning">PENDING</span>';
        } elseif ($row['isapproved'] == 1 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
            $status = '<span class="badge text-bg-success">APPROVED</span>';
        } elseif ($row['isrejected'] == 1 && $row['isclosed'] != 1) {
            $status = '<span class="badge text-bg-danger">REJECTED</span>';
        } elseif ($row['isclosed'] == 1) {
            $status = '<span class="badge text-bg-secondary">CLOSED</span>';
        }
    }

    if ($a == 6) { //HOS
        if ($row['isHosApproved'] == 0 && $row['isHOSrejected'] == 0 && $row['isclosed'] == 0) {
            $status = '<span class="badge text-bg-warning">PENDING</span>';
        } elseif ($row['isHosApproved'] == 1 && $row['isHOSrejected'] != 1 && $row['isclosed'] != 1) {
            $status = '<span class="badge text-bg-success">APPROVED</span>';
        } elseif ($row['isHOSrejected'] == 1) {
            $status = '<span class="badge text-bg-danger">REJECTED</span>';
        } elseif ($row['isclosed'] == 1) {
            $status = '<span class="badge text-bg-secondary">CLOSED</span>';
        }
    }
    if ($a == 7) { //HOD
        if ($row['isHodApproved'] == 0 && $row['isHODrejected'] == 0 && $row['isclosed'] == 0) {
            $status = '<span class="badge text-bg-warning">PENDING</span>';
        } elseif ($row['isHodApproved'] == 1 && $row['isHODrejected'] != 1 && $row['isclosed'] != 1) {
            $status = '<span class="badge text-bg-success">APPROVED</span>';
        } elseif ($row['isHODrejected'] == 1) {
            $status = '<span class="badge text-bg-danger">REJECTED</span>';
        } elseif ($row['isclosed'] == 1) {
            $status = '<span class="badge text-bg-secondary">CLOSED</span>';
        }
    }
    return $status;
}

function funStatusName($s, $a)
{

    global $db;
    $status = '';
    $sql = "SELECT isverified, isapproved, approvedby, isrejected, rejectedby, isHOSrejected, isHODrejected, isconfirm, isNotifyHos, isHosApproved, isHodApproved, isclosed, isreopen, isupload,
        CASE 
            WHEN isverified = 0 THEN 0
            WHEN isverified = 1 THEN 1
            WHEN isverified = 2 THEN 2
			WHEN isrejected = 1 THEN 3
        END AS status
    FROM ucuaform WHERE id = '" . $s . "' ";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    if ($a == 4) { //PSSD HSE OFFICER
        if ($row['isapproved'] == 0 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
            $status = 'PSSD HSE OFFICER';
        } elseif ($row['isapproved'] == 1 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
            $status = $row['approvedby'];
        } elseif ($row['isrejected'] == 1 && $row['isclosed'] != 1) {
            $status = $row['rejectedby'];
        } elseif ($row['isclosed'] == 1) {
            $status = 'PSSD HSE OFFICER';
        }
    }

    return $status;
}

function funStatusDate($s, $a)
{

    global $db;
    $status = '';
    $sql = "SELECT isverified, verifieddate, isapproved, approveddate, isrejected, rejecteddate, isHOSrejected, HOSrejecteddate, isHODrejected, HODrejecteddate, isconfirm, confirmdate, isNotifyHos, isHosApproved, HosApprovedDate, isHODApproved, HODapprovedDate, isclosed, closeddate, isreopen, isupload,
        CASE 
            WHEN isverified = 0 THEN 0
            WHEN isverified = 1 THEN 1
            WHEN isverified = 2 THEN 2
			WHEN isrejected = 1 THEN 3
        END AS status
    FROM ucuaform WHERE id = '" . $s . "' ";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    if ($a == 4) { //PSSD HSE OFFICER
        if ($row['isapproved'] == 0 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
            $status = '';
        } elseif ($row['isapproved'] == 1 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
            $status = $row['approveddate'];
        } elseif ($row['isrejected'] == 1 && $row['isclosed'] != 1) {
            $status = $row['rejecteddate'];
        } elseif ($row['isclosed'] == 1) {
            $status = $row['closeddate'];
        }
    }

    if ($a == 6) { //HOS
        if ($row['isHosApproved'] == 0 && $row['isHOSrejected'] == 0 && $row['isclosed'] == 0) {
            $status = '';
        } elseif ($row['isHosApproved'] == 1 && $row['isHOSrejected'] != 1 && $row['isclosed'] != 1) {
            $status = $row['HosApprovedDate'];
        } elseif ($row['isHOSrejected'] == 1) {
            $status = $row['HOSrejecteddate'];
        } elseif ($row['isclosed'] == 1) {
            $status = $row['closeddate'];
        }
    }
    if ($a == 7) { //HOD
        if ($row['isHodApproved'] == 0 && $row['isHODrejected'] == 0 && $row['isclosed'] == 0) {
            $status = '';
        } elseif ($row['isHodApproved'] == 1 && $row['isHODrejected'] != 1 && $row['isclosed'] != 1) {
            $status = $row['HODapprovedDate'];
        } elseif ($row['isHODrejected'] == 1) {
            $status = $row['HODrejecteddate'];
        } elseif ($row['isclosed'] == 1) {
            $status = $row['closeddate'];
        }
    }
    return $status;
}

function funPssdHseStatus($status)
{
    if ($status == 1) {
        $status = '<span class="badge text-bg-success">APPROVED</span>';
    } else {
        $status = '<span class="badge text-bg-warning">PENDING</span>';
    }
    return $status;
}

if (isset($_POST['btnReasign'])) {
    global $db;

    try {
        $PICreviewName = $_POST['PICreviewName'];
        $HSEreviewName = $_POST['HSEreviewName'];

        $hserep = explode(', ', $HSEreviewName);
        $pic = explode(', ', $PICreviewName);

        $notifyPssdHseOfficer = $_POST['notifyPssdHseOfficer'];
        $accessLevel = $_POST['accessLevel'];
        $id = $_POST['id'];

        $hsereps1 = $hserep[2] . ', ' . $hserep[1];
        $hsereps2 = $pic[2] . ', ' . $pic[1];

        if ($accessLevel == 4) {
            $sql2 = "UPDATE ucuaform SET HSEReps = '" . $hserep[2] . "', HSEReps0='" . $pic[2] . "', hsereps1 = '" . $hsereps1 . "', hsereps2 = '" . $hsereps2 . "', isnotifysherep=1, notifysherepby='" . $_SESSION['user']['staffId'] . "', notifysherepdate='" . date('Y-m-d h:i:s') . "' WHERE Id = '" . $id . "'; ";
            mysqli_query($db, $sql2);

            $sql3 = "INSERT INTO history (ucuaid, staffid, accessLevel, action, activity, status, datetimestamp) VALUES (" . $id . ", '" . $_SESSION['user']['staffId'] . "', '" . $_SESSION['user']['AccessLevel'] . "', 'NOTIFICATION', 'Email notification sent to " . $hserep[2] . "', 'SENT', '" . date('Y-m-d h:i:s') . "')";
            mysqli_query($db, $sql3);
        }


        $history = 'HSE Reps reasign to ' . $hserep[1] . ' and ' . $pic[1];
        $url = $_POST['url'];
        $fullurl = 'http://192.168.1.40/ucua/notification.php';
        $fullurl .= '?notifyHos=' . $hserep[1];
        $fullurl .= '&notifyPssdHseOfficer=' . $notifyPssdHseOfficer;
        $fullurl .= '&history=' . $history;
        $fullurl .= '&email_username=' . base64_encode('username');
        $fullurl .= '&email_password=' . base64_encode('password');
        $fullurl .= '&url=' . $url;
        $fullurl .= '&notifyRelatedDept=' . 'MULTI, 1, ' . trim($pic[1]);
        //echo '<meta http-equiv="refresh" content="0; URL=' . $fullurl . '" />';
        echo $fullurl;
        exit();
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

try {
    $reportid = base64_decode($_GET['id']);
    $sql = "SELECT ucuaform.Id, ucuaform.staffId, ucuaform.conditionDetails, ucuaform.ActionTaken, ucuaform.evidenceImage, ucuaform.observerName, ucuaform.observerDepartment, ucuaform.observerEmail, ucuaform.observerConditionDetails, ucuaform.closedEvidence,location.LocationName, ucuaform.isclosed, ucuaform.closedby, ucuaform.closeddate, ucuaform.closedEvidence, ucuaform.isnotifysherep, ucuaform.notifysherepdate, ucuaform.isapproved, ucuaform.approveddate, ucuaform.isHosApproved, ucuaform.HosApprovedDate, ucuaform.isHODapproved, ucuaform.HODapprovedDate, ucuaform.isUserClosed, ucuaform.isrejected, ucuaform.hsereps1, ucuaform.hsereps2, ucuaform.relatedDept, ucuaform.HOSreviewName, ucuaform.HODreviewName, ucuaform.createby FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId WHERE `Id` = '$reportid'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    if (!empty($row)) {
        $staffId = $row['staffId'] ?? '';
        $LocationName = $row['LocationName'] ?? '';
        $conditionDetails = $row['conditionDetails'] ?? '';
        $test = $row["evidenceImage"] ?? '';
        $ActionTaken = $row['ActionTaken'] ?? '';
        $observerName = $row['observerName'] ?? '';
        $observerDepartment = $row['observerDepartment'] ?? '';
        $observerEmail = $row['observerEmail'] ?? '';
        $observerConditionDetails = $row['observerConditionDetails'] ?? '';
        $isnotifysherep = $row['isnotifysherep'] ?? '';
        $notifysherepdate = $row['notifysherepdate'] ?? '';
        $isapproved = $row['isapproved'] ?? '';
        $approveddate = $row['approveddate'] ?? '';
        $isHosApproved = $row['isHosApproved'] ?? '';
        $HosApprovedDate = $row['HosApprovedDate'] ?? '';
        $isHODapproved = $row['isHODapproved'] ?? '';
        $HODapprovedDate = $row['HODapprovedDate'] ?? '';
        $isUserClosed = $row['isUserClosed'] ?? '';
        $isclosed = $row['isclosed'] ?? '';
        $closedby = $row['closedby'] ?? '';
        $closeddate = $row['closeddate'] ?? '';
        $closedEvidence = $row['closedEvidence'] ?? '';
        $isrejected = $row['isrejected'] ?? '';
        $hsereps1 = $row['hsereps1'] ?? '';
        $hsereps2 = $row['hsereps2'] ?? '';
        $relatedDept = $row['relatedDept'] ?? '';
        $HOSreviewName = $row['HOSreviewName'] ?? '';
        $HODreviewName = $row['HODreviewName'] ?? '';
        $createby = $row['createby'] ?? '';
    }
} catch (Exception $e) {
    die($e->getMessage());
}

include('inc_header.php');
?>

</head>

<body class="pace-done" data-new-gr-c-s-check-loaded="8.904.0" data-gr-ext-installed="" data-sidebar="brand">
    <div class="pace pace-inactive">
        <div class="pace-progress" style="transform: translate3d(100%, 0px, 0px);" data-progress-text="100%" data-progress="99">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">
        <img src="img/loading.gif" id="loader" style="display:none;">

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
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Unsafe Condition Form</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">Home</li>
                                        <li class="breadcrumb-item active">Preview</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card shadow mb-4">
                                <div class="card-header align-items-center d-flex d-print-none">
                                    <div class="card-title mb-0 flex-grow-1">
                                        <a href="default.php?page=uc_form_edit&ucuaid=<?php echo $_GET['id']; ?>" class="btn btn-light btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="default.php?page=newuiuc_form&id=<?php echo $_GET['id']; ?>" class="btn btn-light btn-sm">
                                            <i class="bi bi-eye"></i>
                                            Preview
                                        </a>
                                        <button class="btn btn-light btn-sm" onclick="window.print()">
                                            <i class="bi bi-printer"></i> Print report
                                        </button>
                                        <button class="btn btn-light btn-sm" onclick="window.print()">
                                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                                        </button>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="" href="default.php?page=newuiuc_form&id=<?php echo $_GET['id']; ?>" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fa-solid fa-eye"></i></span>
                                                    <span class="d-none d-sm-block">Preview</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="" href="default.php?page=uc_form_edit&ucuaid=<?php echo $_GET['id']; ?>" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fa-solid fa-pencil"></i></span>
                                                    <span class="d-none d-sm-block">Edit</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <p></p>
                                    <form
                                        name="frm"
                                        action="default.php?page=newuiuc_form2"
                                        method="post"
                                        enctype="multipart/form-data">
                                        <table width="95%" cellpadding="5" cellspacing="5" class="uitable shadow mb-4">
                                            <tr>
                                                <td align="center" valign="center">
                                                    <p align="left">
                                                        <img src="img/JPB-Logo.png" />
                                                    </p>
                                                </td>
                                                <td align="center" valign="center">
                                                    <h3 class="form">FORM</h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Department Name : PSSD (HSE)</b></td>
                                                <td><b>Document No.: FM-PSSD-<?php echo base64_decode($_GET['id']); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center"><b>U-SEE U-ACT (UCUA)</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center"></td>
                                            </tr>
                                            <tr bgcolor="#EFEFEF">
                                                <td colspan="2" align="left"><b>1. U-SEE</b></td>
                                            </tr>
                                            <tr align="left">
                                                <td colspan="2">
                                                    <b>UNSAFE CONDITION (KONDISI TIDAK SELAMAT)</b><br />
                                                    <div class="mb-3 row">
                                                        <label for="location" class="col-sm-4 col-form-label">Location :</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" id="location" name="location" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?php echo $LocationName; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="offenceCode" class="col-sm-4 col-form-label">Condition Details :</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-sm">
                                                                <textarea class="form-control" id="conditionDetails" name="conditionDetails" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"><?php echo strval($conditionDetails); ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="offenceCode" class="col-sm-4 col-form-label">Evidence :</label>
                                                        <div class="col-sm-8">
                                                            <?php echo photoEvidence(base64_decode($_GET['id'])); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr bgcolor="#EFEFEF">
                                                <td colspan="2" align="left"><b>2. U-ACT</b></td>
                                            </tr>
                                            <tr align="left">
                                                <td colspan="2"><b>IMMEDIATE CORRECTIVE ACTION TAKEN (TINDAKAN SEGERA YANG DIAMBIL)</b><br />
                                                    <div class="row mb-3 ms-5">
                                                        <div class="col-12 themed-grid-col">
                                                            <table width="100%" class="noborder">
                                                                <?php
                                                                //funRelatedDept3($relatedDept)
                                                                $action = explode(', ', $ActionTaken ?? '');
                                                                if ($action[0] != 'Others') {
                                                                    echo '<tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken1" value="Notify Related Department" checked  /></td>
                                    <td align="left" class="input-group input-group-sm">NOTIFY RELATED DEPARTMENT: 
									<select name="relatedDept2" id="relatedDept2" class="form-control js-select">' . funRelatedDept3($relatedDept) . '</select>
									</td>
                                </tr>
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken2" value="Others" /></td>
                                    <td align="left">
                                        OTHERS: <input type="text" id="others" name="others" class="w-75 blank">
                                    </td>
                                </tr>';
                                                                } else {
                                                                    echo '<tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken1" value="Notify Related Department" aria-label="..."  /></td>
                                    <td align="left" class="input-group input-group-sm">NOTIFY RELATED DEPARTMENT: 
										<select name="relatedDept2" id="relatedDept2" class="form-control js-select">' . funRelatedDept3($relatedDept) . '</select>
									</td>
                                </tr>
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken2" value="Others" checked  /></td>
                                    <td align="left">
                                        OTHERS: <input type="text" id="others" name="others" class="w-75 blank" value="' . $action[1] . '" />
                                    </td>
                                </tr>';
                                                                }
                                                                ?>
                                                            </table>

                                                        </div>

                                                    </div>
                                                </td>
                                            </tr>
                                            <tr bgcolor="#EFEFEF" align="center">
                                                <td width="50%"><b>OBSERVER</b></td>
                                                <td width="50%"><b>HSE REPS</b></td>
                                            </tr>
                                            <tr align="left">
                                                <td width="50%" valign="top">

                                                    <div class="mb-3">
                                                        <label for="observerName" class="form-label">Name:</label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="observerName"
                                                            name="observerName"
                                                            placeholder="Name"
                                                            value="<?php echo $observerName; ?>"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="observerDepartment" class="form-label">Department:</label>
                                                        <select class="form-control js-select bg-light" id="observerDepartment" name="observerDepartment" required>
                                                            <option value="<?php echo checkObserverDept($observerDepartment, $staffId); ?>">
                                                                <?php echo checkObserverDept($observerDepartment, $staffId); ?>
                                                            </option>
                                                            <?php //echo funDepartment(); 
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="observerEmail" class="form-label">Email:</label>
                                                        <input type="email" class="form-control" id="observerEmail" name="observerEmail" placeholder="Email" value="<?php echo $observerEmail; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <!--label for="observerConditionDetails" class="form-label">Condition Details:</label-->
                                                        <input type="hidden" class="form-control text-capitalize" id="observerConditionDetails" name="observerConditionDetails" placeholder="Condition Details" value="<?php echo $observerConditionDetails; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Case status:</label>
                                                        <?php if ($isUserClosed == 1) {
                                                            echo '<span class="badge text-bg-success">REQUESTED CLOSE</span>';
                                                        } else {
                                                            echo '<span class="badge text-bg-dark">OPEN</span>';
                                                        } ?>

                                                    </div>

                                                </td>
                                                <td width="50%" valign="top">
                                                    <b>REVIEWED BY (HSE REPS)</b><br />
                                                    <table width="100%" class="noborder">
                                                        <tr>
                                                            <td>Name:</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <?php if ($isnotifysherep == 0) { ?>
                                                                        <select name="HSEreviewName" id="HSEreviewName" class="form-control js-select">
                                                                            <option value=""></option>
                                                                            <?php echo funHsereps(); ?>
                                                                        </select>
                                                                    <?php } else { ?>
                                                                        <input type="text" name="HSEreviewName" id="HSEreviewName" class="form-control" value="<?php echo funpic($hsereps1); ?> (<?php echo $hsereps1; ?>)" />
                                                                    <?php } ?>
                                                                    <input type="hidden" name="HSEreviewName2" value="khairi@johorport.com.my, mtaufik@johorport.com.my" />
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div class="clearfix"></div>
                                                    DESIGNATION: HSE REPS<br />
                                                    DATE: <?php echo $row['notifysherepdate']; ?><br />
                                                    STATUS: <?php echo funPssdHseStatus($row['isnotifysherep']); ?><br />
                                                    <p>&nbsp;</p>
                                                    <b>REVIEWED BY (PIC/RELATED DEPARTMENT)</b><br />
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="noborder">
                                                        <tr>
                                                            <td width="10%">Name:</td>
                                                            <td>
                                                                <div class="input-group input-group-sm">
                                                                    <?php if ($isnotifysherep == 0) { ?>
                                                                        <select name="PICreviewName" id="PICreviewName" class="form-control js-select">
                                                                            <option value=""></option>
                                                                            <?php //echo funRelatedDept();
                                                                            ?>
                                                                            <?php echo funHsereps(); ?>
                                                                        </select>
                                                                    <?php } else { ?>
                                                                        <input type="text" name="PICreviewName" id="PICreviewName" class="form-control" value="<?php echo funpic($hsereps2); ?> (<?php echo $hsereps2; ?>)" />
                                                                    <?php } ?>
                                                                    <input type="hidden" name="PICreviewName2" value="maslinda@johorport.com.my" />
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div class="clearfix"></div>
                                                    DESIGNATION: HSE REPS<br />
                                                    DATE: <?php echo $row['notifysherepdate']; ?><br />
                                                    STATUS: <?php echo funPssdHseStatus($row['isnotifysherep']); ?><br />
                                                    <p>&nbsp;</p>
                                                    <center>
                                                        <?php if ($isnotifysherep == 0) { ?>
                                                            <button
                                                                type="button"
                                                                class="btn btn-success btn-sm d-print-none"
                                                                id="btnReasign"
                                                                name="btnReasign">
                                                                <span class="text text-white">
                                                                    <i class="fa-solid fa-circle-exclamation"></i> Reasign
                                                                </span>
                                                            </button>
                                                        <?php } ?>
                                                    </center>
                                                </td>
                                            </tr>
                                            <tr bgcolor="#EFEFEF">
                                                <td colspan="2" align="left"><b>3. FOLLOW-UP ACTION & STATUS</b></td>
                                            </tr>

                                            <tr align="left">
                                                <td colspan="2">
                                                    <div class="row ms-5">
                                                        <div class="col-6">
                                                            <b>APPROVED BY (PSSD HSE OFFICER)</b><br />
                                                            NAME: <?php echo findUserName(funStatusName(base64_decode($_GET['id']), 4)); ?><br />
                                                            DESIGNATION: PSSD HSE OFFICER<br />
                                                            DATE: <?php echo funStatusDate(base64_decode($_GET['id']), 4); ?><br />
                                                            STATUS: <?php echo funStatus(base64_decode($_GET['id']), 4); ?><br />
                                                            <p>&nbsp;</p>
                                                            <b>REVIEWED BY (HEAD OF SECTION/PSSD)</b><br />
                                                            <div class="clearfix"></div>

                                                            <table width="100%" class="noborder">
                                                                <tr>
                                                                    <td width="10%">NAME:</td>
                                                                    <td>
                                                                        <div class="input-group input-group-sm">
                                                                            <select name="HOSreviewName" id="HOSreviewName" class="form-control js-select">
                                                                                <?php
                                                                                $HOSreviewName2 = explode(',', $HOSreviewName);
                                                                                if (!empty($HOSreviewName2[1])) {
                                                                                    echo '<option value="' . $HOSreviewName2[1] . '" selected>' . $HOSreviewName2[1] . '</option>';
                                                                                } else {
                                                                                    echo '<option value="' . $HOSreviewName . '">' . $HOSreviewName . '</option>';
                                                                                    echo funEmail(6);
                                                                                }
                                                                                ?>
                                                                                <!--option value="zamri@johorport.com.my">HOO: Mohamad Zamri Manjanib (zamri@johorport.com.my)</option>
												<option value="norzaini@johorport.com.my">HOS HSE: Nor Zaini Mohamad (norzaini@johorport.com.my)</option>
												<option value="saravanan@johorport.com.my">HOS Security: Saravanan Kanesan (saravanan@johorport.com.my)</option-->
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                            <div class="clearfix"></div>
                                                            DESIGNATION: PSSD HOS<br />
                                                            DATE: <?php echo $row['HosApprovedDate']; ?><br />
                                                            STATUS: <?php echo funStatus(base64_decode($_GET['id']), 6); ?><br />
                                                        </div>
                                                        <div class="col-6">
                                                            <b>REVIEWED BY (HEAD OF DEPARTMENT/PSSD)</b><br />
                                                            <div class="clearfix"></div>

                                                            <table width="100%" class="noborder">
                                                                <tr>
                                                                    <td width="10%">NAME:</td>
                                                                    <td>
                                                                        <div class="input-group input-group-sm">
                                                                            <select name="HODreviewName" id="HODreviewName" class="form-control js-select">
                                                                                <?php
                                                                                if (!empty($HODreviewName)) {
                                                                                    echo '<option value="' . $HODreviewName . '" selected>' . $HODreviewName . '</option>';
                                                                                } else {
                                                                                    echo '<option value=""></option>';
                                                                                    echo funEmail(7);
                                                                                }
                                                                                ?>
                                                                                <!--option value="jml@johorport.com.my">Jamaludin (jml@johorport.com.my)</option-->
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                            <div class="clearfix"></div>
                                                            DESIGNATION: PSSD HOD<br />
                                                            DATE: <?php echo $row['HODapprovedDate']; ?><br />
                                                            STATUS: <?php echo funStatus(base64_decode($_GET['id']), 7); ?><br />
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr align="left" class="d-print-none">
                                                <td colspan="2">
                                                    <div class="row ms-5">
                                                        <div class="col-11">
                                                            Remark:<br />
                                                            <textarea name="remark" id="remark" class="form-control" required></textarea><br />

                                                            <table width="100%" class="noborder">
                                                                <tr>
                                                                    <td width="20%" align="left">
                                                                        <label for="formFile" class="form-label">Take Action Upload : </label>
                                                                    </td>
                                                                    <td>
                                                                        <div class="custom-file">
                                                                            <input
                                                                                class="form-control"
                                                                                type="file"
                                                                                name="files[]"
                                                                                id="files"
                                                                                accept="image/*"
                                                                                multiple="multiple">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td class="mb-3">
                                                                        <p></p>
                                                                        <div id="preview"></div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <p></p>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        </table>
                                        <p></p>

                                        <table border="0" width="95%" cellpadding="5" cellspacing="5" align="center" class="d-print-none">
                                            <tr>
                                                <td>
                                                    <div class="alert alert-danger" role="alert">
                                                        <strong>Note:</strong> Once you press the 'Save' button, you can update the information anytime you want
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <p></p>

                                        <div class="card-footer">
                                            <center>
                                                <?php
                                                //PUBLIC USER
                                                if ($_SESSION['user']['AccessLevel'] == 1) { ?>
                                                    <?php if ($isclosed != 1) { ?>
                                                        <button type="submit" name="btnSave" id="btnSave" class="btn btn-secondary btn-icon-split btn-sm d-print-none">
                                                            <span class="text text-white">
                                                                <i class="fa-solid fa-floppy-disk"></i> Save
                                                            </span>
                                                        </button>
                                                    <?php } ?>
                                                    <?php if ($isUserClosed == 0) { ?>
                                                        <button type="submit" name="btnClose" class="btn btn-primary btn-icon-split btn-sm d-print-none">
                                                            <span class="text text-white">
                                                                <i class="fa-solid fa-circle-check"></i> Close case
                                                            </span>
                                                        </button>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php
                                                //HSE REPS
                                                if ($_SESSION['user']['AccessLevel'] == 8) { ?>
                                                    <?php if ($isclosed != 1) { ?>
                                                        <button type="submit" name="btnSave" id="btnSave" class="btn btn-secondary btn-icon-split btn-sm d-print-none">
                                                            <span class="text text-white">
                                                                <i class="fa-solid fa-floppy-disk"></i> Save
                                                            </span>
                                                        </button>
                                                    <?php } ?>
                                                    <button type="submit" name="btnClose" class="btn btn-primary btn-icon-split btn-sm d-print-none" onclick="return HSEcloseCase()">
                                                        <span class="text text-white">
                                                            <i class="fa-solid fa-circle-check"></i> Close case
                                                        </span>
                                                    </button>
                                                <?php } ?>
                                                <?php
                                                //PSSD HSE OFFICER
                                                if ($_SESSION['user']['AccessLevel'] == 4) { ?>
                                                    <button type="submit" name="btnSave" class="btn btn-secondary btn-icon-split btn-sm d-print-none">
                                                        <span class="text text-white">
                                                            <i class="fa-solid fa-floppy-disk"></i> Save
                                                        </span>
                                                    </button>
                                                    <?php if ($isapproved == 0) { ?>
                                                        <button type="submit" name="btnApprove4" class="btn btn-primary btn-icon-split btn-sm d-print-none" onclick="return formValidation()">
                                                            <span class="text text-white">
                                                                <i class="fa-solid fa-circle-check"></i> Approved
                                                            </span>
                                                        </button>
                                                    <?php } ?>
                                                    <?php if ($isapproved == 0 && $isrejected == 0) { ?>
                                                        <button type="submit" name="btnReject4" class="btn btn-danger btn-icon-split btn-sm d-print-none">
                                                            <span class="text text-white">
                                                                <i class="fa-solid fa-circle-xmark"></i> Reject
                                                            </span>
                                                        </button>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php
                                                //HOS
                                                if ($_SESSION['user']['AccessLevel'] == 6) { ?>
                                                    <?php if ($isHosApproved != 1) { ?>
                                                        <button type="submit" name="submitbtn6" class="btn btn-primary" onclick="return formValidation()"><i class="fas fa-check-circle"></i> Review</button>
                                                        <button type="submit" name="btnHosReject" class="btn btn-danger"><i class="fas fa-xmark"></i> Reject</button>
                                                        <!-- <button type="submit" name="reject2btn" class="btn btn-danger"><i class="fas fa-ban"></i>Reject</button> -->
                                                        <!--button type="submit" class="btn btn-danger d-print-none" name="HosReassignBtn">
					<span class="text text-white">
						<i class="fa-solid fa-circle-xmark"></i> Reassign
					</span>
				</button-->
                                                    <?php } ?>
                                                    <button type="submit" name="feedbackbtn" class="btn btn-secondary d-print-none"><i class="fas fa-comment-dots"></i> Feedback</button>
                                                <?php } ?>
                                                <?php
                                                //HOD
                                                if ($_SESSION['user']['AccessLevel'] == 7) { ?>
                                                    <?php if ($isHODapproved != 1) { ?>
                                                        <button type="submit" name="submitbtn7" class="btn btn-primary"><i class="fas fa-check-circle"></i>Approved</button>
                                                        <button type="submit" name="btnHodReject" class="btn btn-danger"><i class="fas fa-xmark"></i> Reject</button>
                                                        <!-- <button type="submit" name="reject2btn" class="btn btn-danger"><i class="fas fa-ban"></i>Reject</button> -->
                                                        <!--button type="submit" class="btn btn-danger d-print-none" name="HodReassignBtn">
					<span class="text text-white">
						<i class="fa-solid fa-circle-xmark"></i> Reassign
					</span>
				</button-->
                                                    <?php } ?>
                                                    <button type="submit" name="feedbackbtn" class="btn btn-secondary d-print-none"><i class="fas fa-comment-dots"></i> Feedback</button>
                                                <?php } ?>
                                            </center>
                                        </div>

                                        <input type="hidden" id="accessLevel" name="accessLevel" value="<?php echo $_SESSION['user']['AccessLevel']; ?>">
                                        <input type="hidden" id="staffId" name="staffId" value="<?php echo $_SESSION['user']['staffId']; ?>">
                                        <input type="hidden" id="Name" name="Name" value="<?php echo $_SESSION['user']['Name']; ?>">
                                        <input type="hidden" id="fullurl" name="fullurl" value="<?php echo $fullurl; ?>">
                                        <input type="hidden" id="url" name="url" value="<?php echo base64_encode($fullurl); ?>">
                                        <input type="hidden" id="id" name="id" value="<?php echo base64_decode($_GET['id']); ?>">
                                        <input type="hidden" id="createby" name="createby" value="<?php echo findUserEmail($createby); ?>">
                                        <input type="hidden" id="notifyPssdHseOfficer" name="notifyPssdHseOfficer" value="<?php echo $_SESSION['user']['Email']; ?>">
                                    </form>

                                </div><!--card-body-->

                            </div><!--Card-->

                            <div class="card shadow">
                                <div class="card-body">
                                    <h5 class="card-title">Activities</h5>
                                    <p class="card-text">
                                        <?php echo showActivity(base64_decode($_GET['id'])); ?>
                                    </p>
                                </div>
                            </div>

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
        window.onload = myOnLoad;

        function myOnLoad() {
            try {
                var accessLevel = document.getElementById("accessLevel").value;
                var msg = '';
                console.log(accessLevel);
                switch (parseInt(accessLevel)) {
                    case 1: //public user
                        document.getElementById('location').disabled = false;
                        document.getElementById('conditionDetails').disabled = false;
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('observerName').disabled = false;
                        document.getElementById('observerDepartment').disabled = false;
                        document.getElementById('observerEmail').disabled = false;
                        document.getElementById('observerConditionDetails').disabled = false;

                        document.getElementById('HSEreviewName').disabled = true;
                        document.getElementById('PICreviewName').disabled = true;
                        document.getElementById('HOSreviewName').disabled = true;
                        document.getElementById('HODreviewName').disabled = true;
                        document.getElementById('btnReasign').disabled = true;
                        break;
                    case 2: //public selected user
                        msg = 'required';
                        break;
                    case 3: //PSSD Admin
                        msg = 'disabled';
                        break;
                    case 4: //PSSD HSE Officer
                        //document.getElementById('location').disabled = true;
                        //document.getElementById('conditionDetails').disabled = true;
                        //document.getElementById('actionTaken1').disabled = true;
                        //document.getElementById('actionTaken2').disabled = true;
                        //document.getElementById('observerName').disabled = true;
                        //document.getElementById('observerDepartment').disabled = true;
                        //document.getElementById('observerEmail').disabled = true;
                        //document.getElementById('observerConditionDetails').disabled = true;
                        //document.getElementById('others').disabled = true;

                        //document.getElementById('HSEreviewName').disabled = true;
                        //document.getElementById('PICreviewName').disabled = true;
                        //document.getElementById('HOSreviewName').disabled = true;
                        //document.getElementById('HODreviewName').disabled = true;
                        //document.getElementById('btnReasign').disabled = true;
                        break;
                    case 5: //PSSD Personnel
                        msg = 'disabled';
                        break;
                    case 6: //PSSD HOS
                        msg = 'disabled';
                        break;
                    case 7: //PSSD HOD
                        document.getElementById('observerName').disabled = true;
                        document.getElementById('observerDepartment').disabled = true;
                        document.getElementById('observerEmail').disabled = true;
                        document.getElementById('HSEreviewName').disabled = true;
                        document.getElementById('PICreviewName').disabled = true;
                        document.getElementById('HOSreviewName').disabled = true;
                        break;
                    case 8: //HSE Reps
                        document.getElementById('location').disabled = true;
                        document.getElementById('conditionDetails').disabled = true;
                        document.getElementById('actionTaken1').disabled = true;
                        document.getElementById('actionTaken2').disabled = true;
                        document.getElementById('observerName').disabled = true;
                        document.getElementById('observerDepartment').disabled = true;
                        document.getElementById('observerEmail').disabled = true;
                        document.getElementById('observerConditionDetails').disabled = true;
                        document.getElementById('others').disabled = true;

                        document.getElementById('HSEreviewName').disabled = true;
                        document.getElementById('PICreviewName').disabled = true;
                        document.getElementById('HOSreviewName').disabled = false;
                        document.getElementById('HODreviewName').disabled = false;
                        document.getElementById('btnReasign').disabled = true;
                        break;
                    case 9: //ICT Admin
                        msg = 'required';
                        break;
                    case 10: //HSE PCU
                        msg = 'disabled';
                        break;
                    case 11: //Tester
                        msg = 'required';
                        break;
                    default:
                        msg = 'disabled';
                        break;
                }
                console.log(msg);
            } catch (err) {
                console.error(err.message);
            }
        }

        function formValidation() {
            var accessLevel = document.getElementById("accessLevel").value;
            switch (parseInt(accessLevel)) {
                case 8: //HSE Reps
                    let HSEreviewName = document.forms["frm"]["HSEreviewName"].value;
                    let PICreviewName = document.forms["frm"]["PICreviewName"].value;
                    if (HSEreviewName == "") {
                        alert("Review by HSE Reps selectbox cannot be empty.");
                        return false;
                    }
                    if (PICreviewName == "") {
                        alert("Review by PIC/related department selectbox cannot be empty.");
                        return false;
                    }
                    break;
                case 4: //PSSD HSE Officer
                    let HOSreviewName = document.forms["frm"]["HOSreviewName"].value;
                    if (HOSreviewName == "") {
                        alert("Review by Head of Section/PSSD selectbox cannot be empty.");
                        return false;
                    }
                    break;
                case 6: //PSSD HOS
                    let HODreviewName = document.forms["frm"]["HODreviewName"].value;
                    if (HODreviewName == "") {
                        alert("Review by Head of Department/PSSD selectbox cannot be empty.");
                        return false;
                    }
                    break;
                default:
                    break;
            }
        }

        function HSEcloseCase() {
            let remark = document.forms["frm"]["remark"].value;
            let files = document.forms["frm"]["files"].value;
            if (remark == "") {
                alert("Remark cannot be empty.");
                document.getElementById("remark").focus();
                return false;
            }
            if (files == "") {
                alert("Please upload photo evidence.");
                return false;
            }
        }

        function checkReasignBtn() {
            let HSEreviewName = document.forms["frm"]["HSEreviewName"].value;
            let PICreviewName = document.forms["frm"]["PICreviewName"].value;

            if (HSEreviewName == "") {
                alert("Review by HSE Reps selectbox cannot be empty.");
                return false;
            }
            if (PICreviewName == "") {
                alert("Review by PIC/related department selectbox cannot be empty.");
                return false;
            }
        }

        $('#btnReasign').click(function() {
            var PICreviewName = document.getElementById('PICreviewName').value;
            var HSEreviewName = document.getElementById('HSEreviewName').value;
            var notifyPssdHseOfficer = document.getElementById('notifyPssdHseOfficer').value;
            var url = document.getElementById('url').value;
            var id = document.getElementById('id').value;
            var accessLevel = document.getElementById("accessLevel").value;

            if (HSEreviewName == "") {
                alert("Review by HSE Reps selectbox cannot be empty.");
                return false;
            }
            if (PICreviewName == "") {
                alert("Review by PIC/related department selectbox cannot be empty.");
                return false;
            }

            console.log('Access level: ' + accessLevel + '; HSE: ' + HSEreviewName + '; ID: ' + id);

            $.ajax({
                type: 'post',
                data: {
                    btnReasign: 1,
                    PICreviewName: PICreviewName,
                    HSEreviewName: HSEreviewName,
                    notifyPssdHseOfficer: notifyPssdHseOfficer,
                    url: url,
                    id: id,
                    accessLevel: accessLevel,
                },
                url: 'default.php?page=newuiuc_form',
                success: function(data) {
                    alertify.success(`Notification has been sent.`);
                    $(window).attr('location', data);
                    console.log(data);
                },
                error: function(data) {
                    console.error(data);
                }
            });
        });
    </script>

    <span id="alert"></span>

</body>

</html>