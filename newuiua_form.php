<?php
$title = 'UA Form';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function photoEvidence($id, $type)
{
    $img = '';
    global $db;
    $sql = "SELECT evidenceImage FROM ucuaphoto WHERE ucuaform_Id = '" . $id . "' AND type='" . $type . "' ";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $img .= '<a href="' . $row['evidenceImage'] . '" class="image-popup-desc" data-title="' . $type . '" data-description="' . $type . ' description.">';
        $img .= '<img src="' . $row['evidenceImage'] . '" alt="' . $type . '" class="img-thumbnail w-25">';
        $img .= '</a>&nbsp;';
    }
    return $img;
}

function funNotifyRelatedDepartment($dept)
{
    global $db;
    $option = '';
    try {
        $sql = "SELECT u.Name, u.Email FROM dept AS d 
        LEFT JOIN user AS u ON u.Department=d.dept
        WHERE d.dept = '" . $dept . "' ";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) {
            $option .= '<option value="' . $row['Email'] . '">' . ucwords(strtolower($row['Name'])) . ' (' . $row['Email'] . ')</option>';
        }
    } catch (Exception $e) {
        $option .= $e->getMessage();
    }
    return $option;
}

function funDepartment()
{
    global $db;
    $option = '';
    $sql1 = "SELECT * FROM dept ORDER BY deptName";
    $result1 = $db->query($sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $option .= '<option value="' . $row1['dept'] . '">' . $row1['deptName'] . ' (' . $row1['dept'] . ')</option>';
    }
    return $option;
}

function funEmail($e)
{
    global $db;
    $option = '';
    $sql1 = "SELECT HSEname, Email FROM email WHERE AccessLevel='" . $e . "' AND isdelete=0 ORDER BY HSEname";
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
        } elseif ($row['isrejected'] == 1 && $row['isclosed'] == 0) {
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

//hse reps/hos/hod <> u see/u act (ucua)
//public user after approved can see activities else cannot see (ucua), ucuaform.HOSreviewName, ucuaform.HODreviewName
try {
    $reportid = base64_decode($_GET['id']);
    $sql = "SELECT ucuaform.Id, ucuaform.staffId, ucuaform.OffenceCode,ucuaform.conditionDetails, ucuaform.ActionTaken, ucuaform.otherValue,ucuaform.evidenceImage, ucuaform.observerName, ucuaform.observerDepartment, ucuaform.observerEmail, ucuaform.observerConditionDetails, ucuaform.submitdate, ucuaform.ViolatorName, ucuaform.ViolatorDept, ucuaform.violatorCompany,ucuaform.ViolatorIc, ucuaform.ViolatorPass, ucuaform.violatorDatetime, ucuaform.closedEvidence,location.LocationName, ucuaform.isclosed, ucuaform.closedby, ucuaform.closeddate, ucuaform.closedEvidence, ucuaform.isnotifysherep, ucuaform.notifysherepdate, ucuaform.isapproved, ucuaform.approveddate, ucuaform.isHosApproved, ucuaform.HosApprovedDate, ucuaform.isHODapproved, ucuaform.HODapprovedDate, ucuaform.followup, ucuaform.fineamount, ucuaform.briefingFrom, ucuaform.briefingUntil, ucuaform.bannedMonths, ucuaform.bannedMonths2, ucuaform.otherAction, ucuaform.followupdate, ucuaform.approvedby, ucuaform.approveddate, ucuaform.isapproved, ucuaform.isreopen, ucuaform.HOSreviewName, ucuaform.HODreviewName, ucuaform.isHOSrejected, ucuaform.isHODrejected, ucuaform.violatorContactNo FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId WHERE `Id` = '$reportid'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    if ($row) {
        $LocationName = $row['LocationName'] ?? '';
        $OffenceCode = $row['OffenceCode'] ?? '';
        $conditionDetails = $row['conditionDetails'] ?? '';
        $test = $row["evidenceImage"] ?? '';
        $ActionTaken = $row['ActionTaken'] ?? '';
        $otherValue = $row['otherValue'] ?? '';
        $observerName = strtolower($row['observerName']) ?? '';
        $observerDepartment = $row['observerDepartment'] ?? '';
        $observerEmail = $row['observerEmail'] ?? '';
        $observerConditionDetails = $row['observerConditionDetails'] ?? '';
        $submitdate = $row['submitdate'] ?? '';
        $ViolatorName = strtolower($row['ViolatorName']) ?? '';
        $ViolatorDept = $row['ViolatorDept'] ?? '';
        $violatorCompany = $row['violatorCompany'] ?? '';
        $ViolatorIc = $row['ViolatorIc'] ?? '';
        $ViolatorPass = $row['ViolatorPass'] ?? '';
        $violatorDatetime = $row['violatorDatetime'] ?? '';
        $violatorContactNo = $row['violatorContactNo'] ?? '';
        $isnotifysherep = $row['isnotifysherep'] ?? '';
        $notifysherepdate = $row['notifysherepdate'] ?? '';
        $isapproved = $row['isapproved'] ?? '';
        $approveddate = $row['approveddate'] ?? '';
        $isHosApproved = $row['isHosApproved'] ?? '';
        $HosApprovedDate = $row['HosApprovedDate'] ?? '';
        $isHODapproved = $row['isHODapproved'] ?? '';
        $HODapprovedDate = $row['HODapprovedDate'] ?? '';
        $isclosed = $row['isclosed'] ?? '';
        $closedby = $row['closedby'] ?? '';
        $closeddate = $row['closeddate'] ?? '';
        $closedEvidence = $row['closedEvidence'] ?? '';
        $followup = $row['followup'] ?? '';
        $fineamount = $row['fineamount'] ?? '';
        $briefingFrom = $row['briefingFrom'] ?? '';
        $briefingUntil = $row['briefingUntil'] ?? '';
        $bannedMonths = $row['bannedMonths'] ?? '';
        $bannedMonths2 = $row['bannedMonths2'] ?? '';
        $otherAction = $row['otherAction'] ?? '';
        $followupdate = $row['followupdate'] ?? '';
        $approvedby = $row['approvedby'] ?? '';
        $isapproved = $row['isapproved'] ?? '';
        $isreopen = $row['isreopen'] ?? '';
        $HOSreviewName = $row['HOSreviewName'] ?? '';
        $HODreviewName = $row['HODreviewName'] ?? '';
        $isHOSrejected = $row['isHOSrejected'] ?? '';
        $isHODrejected = $row['isHODrejected'] ?? '';
        $staffId = $row['staffId'] ?? '';
    }
} catch (Exception $e) {
    die($e);
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
                                <h4 class="mb-sm-0 font-size-18">Unsafe Action Form</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">Home</li>
                                        <li class="breadcrumb-item active">Publish</li>
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
                                        <a href="default.php?page=ua_form_edit&ucuaid=<?php echo $_GET['id']; ?>" class="btn btn-light btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="default.php?page=newuiua_form&id=<?php echo $_GET['id']; ?>" class="btn btn-light btn-sm">
                                            <i class="bi bi-eye"></i>
                                            Preview
                                        </a>
                                        <a class="btn btn-light btn-sm" href="tcpdf-report.php?id=<?php echo $_GET['id']; ?>">
                                            <i class="bi bi-printer"></i> Print report
                                        </a>
                                        <button class="btn btn-light btn-sm" onclick="window.print()">
                                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                                        </button>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="" href="default.php?page=newuiua_form&id=<?php echo $_GET['id']; ?>" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fa-solid fa-eye"></i></span>
                                                    <span class="d-none d-sm-block">Preview</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="" href="default.php?page=ua_form_edit&ucuaid=<?php echo $_GET['id']; ?>" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fa-solid fa-pencil"></i></span>
                                                    <span class="d-none d-sm-block">Edit</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <p></p>
                                    <form method="post" action="default.php?page=newuiua_form2" enctype="multipart/form-data">
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
                                                    <b>UNSAFE ACT (PERBUATAN TIDAK SELAMAT)</b><br />
                                                    <div class="mb-3 row">
                                                        <label for="location" class="col-sm-2 col-form-label">Location :</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" id="location" name="location" value="<?php echo $LocationName; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="offenceCode" class="col-sm-2 col-form-label">Offence Code :</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" id="offenceCode" name="offenceCode" value="<?php echo $OffenceCode; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="offenceCode" class="col-sm-2 col-form-label">Evidence :</label>
                                                        <div class="col-sm-10"><?php echo base64_decode($_GET['id']); ?>
                                                            <?php echo photoEvidence(base64_decode($_GET['id']), 'Photo Evidence'); ?>
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
                                                        <div class="col-4 themed-grid-col">
                                                            <table width="100%" class="noborder">
                                                                <?php if ($ActionTaken !== null && strpos($ActionTaken, 'stopWork') !== false) { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken1" value="stopWork" checked /></td>
                                                                        <td align="left">STOP WORK</td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken1" value="stopWork" /></td>
                                                                        <td align="left">STOP WORK</td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if ($ActionTaken !== null && strpos($ActionTaken, 'verbalWarning') !== false) { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken2" value="verbalWarning" checked /></td>
                                                                        <td align="left">VERBAL WARNING</td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken2" value="verbalWarning" /></td>
                                                                        <td align="left">VERBAL WARNING</td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>

                                                        </div>
                                                        <div class="col-4 themed-grid-col">
                                                            <table width="100%" class="noborder">
                                                                <?php if ($ActionTaken !== null && strpos($ActionTaken, 'revokeEntryPermit') !== false) { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken3" value="revokeEntryPermit" checked /></td>
                                                                        <td align="left">REVOKE ENTRY PERMIT</td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken3" value="revokeEntryPermit" /></td>
                                                                        <td align="left">REVOKE ENTRY PERMIT</td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if ($ActionTaken !== null && strpos($ActionTaken, 'notifyRelatedDepartment') !== false) { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken4" value="notifyRelatedDepartment" checked /></td>
                                                                        <td align="left">NOTIFY RELATED DEPARTMENT</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td>
                                                                            <input type="hidden" name="notifyRelatedDepartment" class="form-control">
                                                                            <?php //echo funNotifyRelatedDepartment($_SESSION['user']['Department']); 
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken4" value="notifyRelatedDepartment" /></td>
                                                                        <td align="left">NOTIFY RELATED DEPARTMENT</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td>
                                                                            <input type="hidden" name="notifyRelatedDepartment" class="form-control">
                                                                            <?php //echo funNotifyRelatedDepartment($_SESSION['user']['Department']); 
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>
                                                        </div>
                                                        <div class="col-4 themed-grid-col">
                                                            <table width="100%" class="noborder">
                                                                <?php if ($ActionTaken !== null && strpos($ActionTaken, 'others') !== false) { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken5" value="others" checked /></td>
                                                                        <td align="left">OTHERS</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td>
                                                                            <input type="text" id="otherValue" name="otherValue" class="w-75 blank" value="<?php echo $otherValue; ?>" />
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken5" value="others" /></td>
                                                                        <td align="left">OTHERS</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td>
                                                                            <input type="text" id="otherValue" name="otherValue" class="w-75 blank" />
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr bgcolor="#EFEFEF" align="center">
                                                <td width="50%"><b>OBSERVER</b></td>
                                                <td width="50%"><b>VIOLATOR</b></td>
                                            </tr>
                                            <tr align="left">
                                                <td width="50%" valign="top">

                                                    <div id="">
                                                        <div class="mb-3">
                                                            <label for="observerName" class="form-label">Name:</label>
                                                            <input type="text" class="form-control text-capitalize" id="observerName" placeholder="Name" value="<?php echo $observerName; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="observerDepartment" class="form-label">Department:</label>
                                                            <input type="text" class="form-control" id="observerDepartment" placeholder="Department" value="<?php echo $observerDepartment; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="observerEmail" class="form-label">Email:</label>
                                                            <input type="email" class="form-control" name="observerEmail" id="observerEmail" placeholder="Email" value="<?php echo $observerEmail; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="observerDatetime" class="form-label">Date & Time:</label>
                                                            <input type="datetime-local" class="form-control" id="observerDatetime" placeholder="Date & Time" value="<?php echo $submitdate; ?>">
                                                        </div>
                                                    </div><!--Overlay-->
                                                </td>
                                                <td width="50%" valign="top">
                                                    <div class="mb-3">
                                                        <label for="violatorName" class="form-label">Name:</label>
                                                        <input type="text" class="form-control text-capitalize" id="violatorName" name="violatorName" placeholder="Name" value="<?php echo $ViolatorName; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorContactNo" class="form-label">Contact no.:</label>
                                                        <input type="text" class="form-control" id="violatorContactNo" name="violatorContactNo" placeholder="Contact No." value="<?php echo $violatorContactNo; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorDepartment" class="form-label">Department</label>
                                                        <input type="text" class="form-control" id="violatorDepartment" name="violatorDepartment" placeholder="Department" value="<?php echo $ViolatorDept; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorCompanyName" class="form-label">Company's Name</label>
                                                        <input type="text" class="form-control" id="violatorCompanyName" name="violatorCompanyName" placeholder="Company's Name" value="<?php echo $violatorCompany; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorICPassport" class="form-label">IC/Passport</label>
                                                        <input type="text" class="form-control" id="violatorICPassport" name="violatorICPassport" placeholder="IC/Passport" value="<?php echo $ViolatorIc; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorDatetime" class="form-label">Date & Time</label>
                                                        <input type="datetime-local" class="form-control" id="violatorDatetime" name="violatorDatetime" placeholder="Date & Time" value="<?php echo $violatorDatetime; ?>">
                                                    </div>
                                                    <p align="center">
                                                        <?php echo photoEvidence(base64_decode($_GET['id']), 'Violator Evidence'); ?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr bgcolor="#EFEFEF">
                                                <td colspan="2" align="left"><b>3. FOLLOW-UP ACTION (To be filled by PSSD only)</b></td>
                                            </tr>
                                            <tr align="left">
                                                <td colspan="2">
                                                    <b>ACTION BY HSE PSSD</b><br />
                                                    <div class="row mb-3 ms-5">
                                                        <div class="col-6 themed-grid-col">

                                                            <table cellpadding="0" cellspacing="0" class="noborder">
                                                                <?php if ($followup !== null && strpos($followup, 'Verbal Warning') !== false) { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd1" value="Verbal Warning" class="form-check-input chkGroup" checked>
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">VERBAL WARNING</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd2" value="Verbal Warning" class="form-check-input chkGroup">
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">VERBAL WARNING</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if ($followup !== null && strpos($followup, 'Domestic Inquiry (DI)') !== false) { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd3" value="Domestic Inquiry (DI)" class="form-check-input chkGroup" checked>
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">DOMESTIC INQUIRY</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd4" value="Domestic Inquiry (DI)" class="form-check-input chkGroup">
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">DOMESTIC INQUIRY</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if ($followup !== null && strpos($followup, 'Blacklisted') !== false) { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd5" value="Blacklisted" class="form-check-input chkGroup" checked>
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">BLACKLISTED</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd6" value="Blacklisted" class="form-check-input chkGroup">
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">BLACKLISTED</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if ($followup !== null && strpos($followup, 'Notice/ Memo of safety violation') !== false) { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd7" value="Notice/ Memo of safety violation" class="form-check-input chkGroup" checked>
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">NOTICE/ MEMO OF SAFETY VIOLATION</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd8" value="Notice/ Memo of safety violation" class="form-check-input chkGroup">
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">NOTICE/ MEMO OF SAFETY VIOLATION</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if ($followup !== null && strpos($followup, 'Report to related parties/ Department') !== false) { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd9" value="Report to related parties/ Department" class="form-check-input chkGroup" checked>
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">REPORT TO RELATED PARTIES/ DEPARTMENT</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr align="left">
                                                                        <td valign="top">
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd10" value="Report to related parties/ Department" class="form-check-input chkGroup">
                                                                        </td>
                                                                        <td valign="top">
                                                                            <label class="form-check-label" for="actionTakenByHsePssd">REPORT TO RELATED PARTIES/ DEPARTMENT</label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>

                                                        </div>

                                                        <div class="col-6 themed-grid-col">
                                                            <table class="noborder">
                                                                <?php if ($followup !== null && strpos($followup, 'Fine RM ') !== false) { ?>
                                                                    <tr align="left">
                                                                        <td>
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd11" value="Fine RM " class="form-check-input chkGroup" checked>
                                                                        </td>
                                                                        <td>
                                                                            FINE RM <input type="text" name="fine" class="blank" value="<?php echo $fineamount; ?>" />
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr align="left">
                                                                        <td>
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd12" value="Fine RM " class="form-check-input chkGroup">
                                                                        </td>
                                                                        <td>
                                                                            FINE RM <input type="text" name="fine" class="blank" value="<?php echo $fineamount; ?>" />
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if ($followup !== null && strpos($followup, 'Attend Induction (PEP)') !== false) { ?>
                                                                    <tr align="left">
                                                                        <td>
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd13" value="Attend Induction (PEP)" class="form-check-input chkGroup" checked>
                                                                        </td>
                                                                        <td>
                                                                            ATTEND INDUCTION (PEP)
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr align="left">
                                                                        <td>
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd14" value="Attend Induction (PEP)" class="form-check-input chkGroup">
                                                                        </td>
                                                                        <td>
                                                                            ATTEND INDUCTION (PEP)
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if ($followup !== null && strpos($followup, 'Attend briefing (VEP) - FROM ') !== false) { ?>
                                                                    <!--tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Attend briefing (VEP) - FROM " checked>
                                    </td>
                                    <td>
                                        ATTEND BRIEFING (VEP) - FROM 
                                        <input type="date" name="from" class="blank" value="<?php echo $briefingFrom; ?>"/> UNTIL
                                        <input type="date" name="until" class="blank" value="<?php echo $briefingUntil; ?>" />
                                    </td>
                                </tr-->
                                                                <?php } else { ?>
                                                                    <!--tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Attend briefing (VEP) - FROM ">
                                    </td>
                                    <td>
                                        ATTEND BRIEFING (VEP) - FROM 
                                        <input type="date" name="from" class="blank" value="<?php echo $briefingFrom; ?>"/> UNTIL
                                        <input type="date" name="until" class="blank" value="<?php echo $briefingUntil; ?>" />
                                    </td>
                                </tr-->
                                                                <?php } ?>
                                                                <?php if ($followup !== null && strpos($followup, 'Banned ') !== false) { ?>
                                                                    <tr align="left">
                                                                        <td>
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd15" value="Banned " class="form-check-input chkGroup" checked>
                                                                        </td>
                                                                        <td>
                                                                            BANNED
                                                                            <input type="date" name="month" class="blank" value="<?php echo $bannedMonths; ?>" /> UNTIL
                                                                            <input type="date" name="month2" class="blank" value="<?php echo $bannedMonths2; ?>" />
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr align="left">
                                                                        <td>
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd16" value="Banned " class="form-check-input chkGroup">
                                                                        </td>
                                                                        <td>
                                                                            BANNED
                                                                            <input type="date" name="month" class="blank" value="<?php echo $bannedMonths; ?>" /> UNTIL
                                                                            <input type="date" name="month2" class="blank" value="<?php echo $bannedMonths2; ?>" />
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if ($followup !== null && strpos($followup, 'Banned ') !== false) { ?>
                                                                    <tr align="left">
                                                                        <td>
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd17" value="Others " class="form-check-input chkGroup" checked>
                                                                        </td>
                                                                        <td>
                                                                            OTHERS <input type="text" name="others" class="blank" value="<?php echo $otherAction; ?>" />
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr align="left">
                                                                        <td>
                                                                            <input type="checkbox" name="actionTakenByHsePssd[]" id="actionTakenByHsePssd18" value="Others " class="form-check-input chkGroup">
                                                                        </td>
                                                                        <td>
                                                                            OTHERS <input type="text" name="others" class="blank" value="<?php echo $otherAction; ?>" />
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>

                                                        </div>
                                                    </div>

                                                    <div class="row ms-5">
                                                        <div class="col-4">
                                                            DATE OF ACTION TAKEN:<br />
                                                            <?php echo $followupdate; ?>
                                                        </div>
                                                        <div class="col-4">
                                                            ACTION TAKEN BY:<br />
                                                            PSSD HSE OFFICER
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr bgcolor="#EFEFEF">
                                                <td colspan="2" align="left"><b>4. STATUS</b></td>
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
                                                                            <select name="notifyHos" id="notifyHos" class="form-control js-select" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                                                                <option value=""></option>
                                                                                <?php
                                                                                $HOSreviewName2 = explode(',', $HOSreviewName);
                                                                                if (!empty($HOSreviewName2[1])) {
                                                                                    echo '<option value="' . $HOSreviewName2[1] . '" selected>' . $HOSreviewName2[1] . '</option>';
                                                                                } else {
                                                                                    echo '<option value=""></option>';
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
                                                            DATE: <?php echo funStatusDate(base64_decode($_GET['id']), 6); ?><br />
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
                                                                            <select name="notifyHod" id="notifyHod" class="form-control js-select" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                                                                <option value=""></option>
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
                                                            DATE: <?php echo $HODapprovedDate; ?><br />
                                                            STATUS: <?php echo funStatus(base64_decode($_GET['id']), 7); ?><br />
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php if ($_SESSION['user']['AccessLevel'] != 1) { ?>
                                                <tr align="left">
                                                    <td colspan="2">
                                                        <div class="row ms-5">
                                                            <div class="col-11">
                                                                Remark:<br />
                                                                <textarea name="pssdRemark" id="pssdRemark" class="form-control" required></textarea><br />

                                                                <table width="100%" class="noborder">
                                                                    <tr>
                                                                        <td width="20%" align="left">
                                                                            <label for="formFile" class="form-label">Take Action Upload : </label>
                                                                        </td>
                                                                        <td>
                                                                            <div class="custom-file">
                                                                                <input class="form-control" type="file" name="files2[]" id="files2" accept="image/png, image/gif, image/jpeg" multiple="multiple">
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <p></p>
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>

                                        <p></p>

                                        <table border="0" width="95%" cellpadding="5" cellspacing="5" align="center" class="d-print-none" style="margin-left: auto; margin-right: auto;">
                                            <tr>
                                                <td>
                                                    <div class="alert alert-danger" role="alert">
                                                        <strong>Note:</strong> Once you press the 'Save' button, you cannot update the information anytime you want
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <p></p>

                                        <div class="card-footer">
                                            <center>
                                                <!--button type="reset"class="btn btn-secondary btn-icon-split btn-sm">
                    <span class="text text-white">
                        Reset
                    </span>
                </button-->
                                                <?php if ($_SESSION['user']['AccessLevel'] != 1 && $_SESSION['user']['AccessLevel'] != 4) { ?>
                                                    <button type="submit" class="btn btn-secondary btn-icon-split btn-sm d-print-none" name="feedbackbtn2" id="feedbackbtn2" onclick="return validateArray()">
                                                        <span class="text text-white">
                                                            <i class="fa-solid fa-floppy-disk"></i> Save
                                                        </span>
                                                    </button>
                                                <?php } ?>
                                                <?php if ($_SESSION['user']['AccessLevel'] == 4) { ?><!--PSSD HSE Officer-->
                                                    <button type="submit" class="btn btn-secondary btn-icon-split btn-sm d-print-none" name="feedbackbtn1">
                                                        <span class="text text-white">
                                                            <i class="fa-solid fa-floppy-disk"></i> Save
                                                        </span>
                                                    </button>

                                                    <?php if ($isapproved == 0) { ?>
                                                        <button type="submit" class="btn btn-primary btn-icon-split btn-sm d-print-none" name="submitbtn" onclick="return validateOfficer()">
                                                            <span class="text text-white">
                                                                <i class="fa-solid fa-circle-check"></i> Approved
                                                            </span>
                                                        </button>
                                                        <button type="submit" class="btn btn-danger btn-icon-split btn-sm d-print-none" name="reject2btn" onclick="return validateHOS();">
                                                            <span class="text text-white">
                                                                <i class="fa-solid fa-circle-xmark"></i> Reject
                                                            </span>
                                                        </button>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if ($_SESSION['user']['AccessLevel'] == 6) { ?><!--PSSD HOS-->
                                                    <?php if ($isHOSrejected != 1) { ?>
                                                        <button type="submit" class="btn btn-primary btn-icon-split btn-sm d-print-none" name="reviewedBtn">
                                                            <span class="text text-white">
                                                                <i class="fa-solid fa-circle-check"></i> Review
                                                            </span>
                                                        </button>
                                                        <button type="submit" name="btnHosReject" class="btn btn-danger btn-icon-split btn-sm d-print-none" onclick="return validateHOS();"><i class="fas fa-xmark"></i> Reject</button>
                                                    <?php } ?>
                                                    <?php if ($isHosApproved != 1) { ?>
                                                        <!--button type="submit" class="btn btn-danger btn-icon-split btn-sm d-print-none" name="reassignBtn">
                    <span class="text text-white">
                        <i class="fa-solid fa-circle-xmark"></i> Reassign
                    </span>
                </button-->
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if ($_SESSION['user']['AccessLevel'] == 7) { ?><!--PSSD HOD-->
                                                    <button type="submit" class="btn btn-primary btn-icon-split btn-sm d-print-none" name="closeCasebtn">
                                                        <span class="text text-white">
                                                            <i class="fa-solid fa-circle-check"></i> Close Case
                                                        </span>
                                                    </button>
                                                    <?php if ($isHODrejected != 1) { ?>
                                                        <button type="submit" name="btnHodReject" class="btn btn-danger btn-icon-split btn-sm d-print-none"><i class="fas fa-xmark"></i> Reject</button>
                                                    <?php } ?>
                                                    <?php if ($isHODapproved != 1) { ?>
                                                        <!--button type="submit" class="btn btn-danger btn-icon-split btn-sm d-print-none" name="HodReassignBtn">
                    <span class="text text-white">
                        <i class="fa-solid fa-circle-xmark"></i> Reassign
                    </span>
                </button-->
                                                    <?php } ?>
                                                <?php } ?>
                                            </center>
                                        </div>

                                        <input type="hidden" name="reportId" value="<?php echo base64_decode($_GET["id"]); ?>" />
                                        <input type="hidden" name="url" value="<?php echo $fullurl; ?>" />
                                        <input type="hidden" name="url2" value="<?php echo base64_encode($fullurl); ?>" />
                                        <input type="hidden" name="notifyPssdHseOfficer" value="<?php echo $_SESSION['user']['Email']; ?>" />
                                        <input type="hidden" name="user" value="<?php echo $_SESSION['user']['staffId']; ?>" />
                                        <input type="hidden" name="createby" value="<?php echo $_SESSION['user']['staffId']; ?>" />
                                        <input type="hidden" id="createby2" name="createby2" value="<?php echo findUserEmail($staffId); ?>">
                                        <input type="hidden" name="ObsEmail" value="<?php echo $ObsEmail; ?>" />
                                        <input type="hidden" name="IDD" value="<?php echo base64_decode($_GET['id']); ?>" />
                                        <input type="hidden" name="id" value="<?php echo base64_decode($_GET['id']); ?>" />
                                        <input type="hidden" name="reopen" value="<?php echo $isreopen; ?>" />
                                        <input type="hidden" name="accessLevel" id="accessLevel" value="<?php echo $_SESSION['user']['AccessLevel']; ?>" />
                                        <input type="hidden" name="from" class="blank" value="<?php echo date('Y-m-d'); ?>" />
                                        <input type="hidden" name="until" class="blank" value="<?php echo date('Y-m-d'); ?>" />
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

                if (document.getElementById('actionTaken5').checked) {
                    document.getElementById('otherValue').style.display = 'block';
                    document.getElementById("otherValue").style.visibility = "visible";
                }

                switch (parseInt(accessLevel)) {
                    case 1:
                        document.getElementById('actionTaken1').disabled = true;
                        document.getElementById('actionTaken2').disabled = true;
                        document.getElementById('actionTaken3').disabled = true;
                        document.getElementById('actionTaken4').disabled = true;
                        document.getElementById('actionTaken5').disabled = true;
                        jQuery("input.chkGroup").attr("disabled", true);
                        document.getElementById('location').disabled = false;
                        document.getElementById('conditionDetails').disabled = false;
                        document.getElementById('observerName').disabled = false;
                        document.getElementById('observerDepartment').disabled = false;
                        document.getElementById('observerEmail').disabled = false;
                        document.getElementById('observerConditionDetails').disabled = false;
                        document.getElementById('pssdRemark').style.display = "none";
                        document.getElementById('files2').style.display = "none";
                        document.getElementById('feedbackbtn1').style.display = "none";
                        document.getElementById('pssdRemark').style.visibility = "hidden";
                        document.getElementById('files2').style.visibility = "hidden";
                        document.getElementById('feedbackbtn1').style.visibility = "hidden";
                        console.log(accessLevel);
                        break;
                    case 2:
                        msg = 'required';
                        jQuery("input.chkGroup").attr("disabled", true);
                        console.log(accessLevel);
                        break;
                    case 3:
                        msg = 'disabled';
                        jQuery("input.chkGroup").attr("disabled", true);
                        console.log(accessLevel);
                        break;
                    case 4:
                        document.getElementById('location').disabled = true;
                        document.getElementById('conditionDetails').disabled = true;
                        document.getElementById('actionTaken1').disabled = true;
                        document.getElementById('actionTaken2').disabled = true;
                        jQuery("input.chkGroup").attr("disabled", false);
                        document.getElementById('observerName').disabled = true;
                        document.getElementById('observerDepartment').disabled = true;
                        document.getElementById('observerEmail').disabled = true;
                        document.getElementById('observerConditionDetails').disabled = true;
                        document.getElementById('others').disabled = true;
                        document.getElementById('HSEreviewName').disabled = true;
                        document.getElementById('PICreviewName').disabled = true;
                        document.getElementById('HOSreviewName').disabled = true;
                        document.getElementById('HODreviewName').disabled = true;
                        document.getElementById('btnReasign').disabled = true;
                        console.log(accessLevel);
                        break;
                    case 5:
                        msg = 'disabled';
                        jQuery("input.chkGroup").attr("disabled", true);
                        console.log(accessLevel);
                        break;
                    case 6:
                        msg = 'disabled';
                        jQuery("input.chkGroup").attr("disabled", true);
                        console.log(accessLevel);
                        break;
                    case 7:
                        msg = 'disabled';
                        jQuery("input.chkGroup").attr("disabled", true);
                        console.log(accessLevel);
                        break;
                    case 8:
                        document.getElementById('location').disabled = true;
                        document.getElementById('conditionDetails').disabled = true;
                        document.getElementById('actionTaken1').disabled = true;
                        document.getElementById('actionTaken2').disabled = true;
                        jQuery("input.chkGroup").attr("disabled", true);
                        document.getElementById('observerName').disabled = true;
                        document.getElementById('observerDepartment').disabled = true;
                        document.getElementById('observerEmail').disabled = true;
                        document.getElementById('observerConditionDetails').disabled = true;
                        document.getElementById('others').disabled = true;
                        document.getElementById('HSEreviewName').disabled = false;
                        document.getElementById('PICreviewName').disabled = false;
                        document.getElementById('HOSreviewName').disabled = false;
                        document.getElementById('HODreviewName').disabled = false;
                        document.getElementById('btnReasign').disabled = false;
                        console.log(accessLevel);
                        break;
                    case 9:
                        msg = 'required';
                        jQuery("input.chkGroup").attr("disabled", true);
                        console.log(accessLevel);
                        break;
                    case 10:
                        msg = 'disabled';
                        jQuery("input.chkGroup").attr("disabled", true);
                        console.log(accessLevel);
                        break;
                    case 11:
                        msg = 'required';
                        jQuery("input.chkGroup").attr("disabled", true);
                        console.log(accessLevel);
                        break;
                    default:
                        msg = 'disabled';
                        jQuery("input.chkGroup").attr("disabled", true);
                        console.log(accessLevel);
                        break;
                }
                console.log(msg);
            } catch (err) {
                console.error(err.message);
            }
        }


        document.getElementById('otherValue').style.display = 'none';

        $('#actionTaken1').click(function() {
            document.getElementById('otherValue').style.display = 'none';
        });

        $('#actionTaken2').click(function() {
            document.getElementById('otherValue').style.display = 'none';
        });

        $('#actionTaken3').click(function() {
            document.getElementById('otherValue').style.display = 'none';
        });

        $('#actionTaken4').click(function() {
            document.getElementById('otherValue').style.display = 'none';
        });

        $('#actionTaken5').click(function() {
            document.getElementById('otherValue').style.display = 'block';
        });


        $('#btnReasign').click(function() {
            var PICreviewName = document.getElementById('PICreviewName').value;
            var HSEreviewName = document.getElementById('HSEreviewName').value;
            var notifyPssdHseOfficer = document.getElementById('notifyPssdHseOfficer').value;
            var url = document.getElementById('url').value;

            $.ajax({
                type: 'post',
                data: {
                    btnReasign: 1,
                    PICreviewName: PICreviewName,
                    HSEreviewName: HSEreviewName,
                    notifyPssdHseOfficer: notifyPssdHseOfficer,
                    url: url,
                },
                url: 'default.php?page=newuiuc_form',
                success: function(data) {
                    alertify.success(`Notification has been sent.`);
                    console.log(data);
                },
                error: function(data) {
                    console.error(data);
                }
            });
        });

        function validateOfficer() {
            var accessLevel = document.getElementById("accessLevel").value;
            var notifyHos = document.getElementById("notifyHos").value;
            //PSSD HSE OFFICER
            if (accessLevel == 4) {
                if (notifyHos == '') {
                    alert("Reviewed by (Head of Section/PSSD) cannot be empty.");
                    return false;
                }
            }
        }

        function validateArray() {
            var accessLevel = document.getElementById("accessLevel").value;
            var actionTakenByHsePssd = document.getElementById("actionTakenByHsePssd").value;
            //HOS
            if (accessLevel == 6) {
                if (actionTakenByHsePssd.length == 0) {
                    alert(" Please Enter Value!");
                    return false;
                }
            }
        }

        function validateHOS() {
            var accessLevel = document.getElementById("accessLevel").value;
            var notifyHos = document.getElementById("notifyHos").value;
            var notifyHod = document.getElementById("notifyHod").value;

            //PSSD HSE OFFICER
            if (accessLevel == 4) {
                if (notifyHos == '') {
                    //alert("Reviewed by (Head of Section/PSSD) cannot be empty.");
                    //return false;
                }
            }

            //HOS
            if (accessLevel == 6) {
                //if(notifyHod == ''){
                //alert("Reviewed by (Head of Department/PSSD) cannot be empty.");
                //return false;
                //}
            }

            return true;
        }
    </script>

    <span id="alert"></span>

</body>

</html>