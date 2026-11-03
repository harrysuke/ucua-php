<?php
$title = 'UA Form';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function funRunningNo()
{
    global $db;
    $sql1 = "SELECT MAX(Id) as 'runningno' FROM ucuaform";
    $result1 = mysqli_query($db, $sql1);
    $row1 = mysqli_fetch_array($result1);
    return $row1['runningno'];
}

function funNotifyRelatedDepartment($dept)
{
    global $db;
    $option = '';
    try {
        $sql = "SELECT u.Name, u.Email FROM dept AS d 
        LEFT JOIN user AS u ON u.Department=d.dept
        WHERE d.dept = ? ";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $dept);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $option .= '<option value="' . $row['Email'] . '">' . ucwords(strtolower($row['Name'])) . ' (' . $row['Email'] . ')</option>';
        }
    } catch (Exception $e) {
        $option .= $e->getMessage();
    }
    return $option;
}

function funLocation()
{
    global $db;
    $option = '';
    try {
        $sql1 = "SELECT LocationId, LocationName FROM location WHERE isdelete=0 ORDER BY LocationName";
        $result1 = $db->query($sql1);
        while ($row1 = mysqli_fetch_array($result1)) {
            $option .= '<option value="' . $row1['LocationId'] . '">' . $row1['LocationName'] . '</option>';
        }
    } catch (Exception $e) {
        $option .= $e->getMessage();
    }
    return $option;
}

function funOffence()
{
    global $db;
    $option = '';
    try {
        $sql1 = "SELECT OffenceCode, offKeyword FROM offence";
        $result1 = $db->query($sql1);
        while ($row1 = mysqli_fetch_array($result1)) {
            $option .= '<option value="' . $row1['OffenceCode'] . '">' . $row1['OffenceCode'] . '-' . $row1['offKeyword'] . '</option>';
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
    $sql1 = "SELECT dept, deptName FROM dept WHERE isdelete=0 ORDER BY deptName";
    $result1 = $db->query($sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $option .= '<option value="' . $row1['dept'] . '">' . $row1['deptName'] . ' (' . $row1['dept'] . ')</option>';
    }
    return $option;
}

function funCompany()
{
    global $db;
    $option = '';
    $query = "SELECT DISTINCT companyName FROM companies WHERE deptInCharge = 'PSSD' ";
    $result = mysqli_query($db, $query);
    while ($row = mysqli_fetch_array($result)) {
        $option .= '<option value="' . strtoupper($row['companyName']) . '">' . strtoupper($row['companyName']) . '</option>';
    }
    return $option;
}

include('inc_header.php');
?>


</head>

<body class="pace-done" data-new-gr-c-s-check-loaded="8.904.0" data-gr-ext-installed="" data-sidebar="brand" onload="myOnLoad()">
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

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="mb-sm-0 font-size-18">Submit new unsafe action
                                            <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                                        </h4>
                                        <p class="text-secondary">Every user with an account can create new unsafe report.</p>
                                        <!--a href="docs/UCUA_2.0_Guide_V2.pdf" target="_blank" class="me-3"><i class="fa-solid fa-book-open"></i> How this works</a>
                                        <a href="default.php?page=svideos&s=" target="_blank"><i class="fa-brands fa-youtube"></i> See them in action</a-->
                                    </div>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">Home</li>
                                        <li class="breadcrumb-item active">Unsafe Action</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="default.php?page=UAcode_user" enctype="multipart/form-data">
                                <div class="card shadow mb-4">
                                    <div class="card-header">
                                        <div class="float-left">
                                            <h5>Unsafe Action Form</h5>
                                        </div>
                                        <div class="float-right">
                                            <!--button class="btn btn-primary">Print</button>
                                        <button class="btn btn-danger">Save as PDF</button-->
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <p></p>
                                    <div class="card-body">
                                        <table border="0" width="95%" cellpadding="5" cellspacing="5" class="uitable shadow mb-4">
                                            <tr>
                                                <td align="center" valign="center"><img src="img/JPB-Logo.png" /></td>
                                                <td align="center" valign="center">
                                                    <h3 class="form">FORM</h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Department Name : PSSD (HSE)</b></td>
                                                <td><b>Document No.: FM-PSSD-<?php echo funRunningNo(); ?></b></td>
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
                                                    <div class="mb-4 row">
                                                        <label for="location" class="col-sm-4 col-form-label">
                                                            Location:<br />
                                                            <a href="default.php?page=admin_location" class="btn btn-secondary btn-sm waves-effect waves-light"><i class="fa-solid fa-plus"></i> New location</a>
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-sm">
                                                                <select name="location" class="form-select js-select"
                                                                    required>
                                                                    <option>Please choose</option>
                                                                    <?php echo funLocation(); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4 row">
                                                        <label for="offenceCode" class="col-sm-4 col-form-label">Offence
                                                            Code:</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-sm">
                                                                <select name="offenceCode[]" multiple="multiple" class="form-select js-select"
                                                                    required>
                                                                    <option>Please choose</option>
                                                                    <?php echo funOffence(); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4 row">
                                                        <label for="offenceCode"
                                                            class="col-sm-4 col-form-label">Evidence :<br />
                                                            <small>Upload one or more photo Evidence.</small>
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="file" name="files[]"
                                                                id="files" accept="image/png, image/gif, image/jpeg"
                                                                multiple="multiple" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4 row">
                                                        <label for="offenceCode" class="col-sm-4 "></label>
                                                        <div class="col-sm-8" id="preview"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr bgcolor="#EFEFEF">
                                                <td colspan="2" align="left">
                                                    <b>2. U-ACT</b>
                                                    <?php //echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr align="left">
                                                <td colspan="2"><b>IMMEDIATE CORRECTIVE ACTION TAKEN (TINDAKAN SEGERA
                                                        YANG DIAMBIL)</b><br />
                                                    <div class="row mb-4 ms-5">
                                                        <div class="col-4 themed-grid-col">

                                                            <?php //echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
                                                            ?>

                                                            <table width="100%" class="noborder" border="0">
                                                                <?php if (!empty($_SESSION['accesslevel21']) && $_SESSION['accesslevel21'] == 1) { ?>
                                                                    <tr>
                                                                        <td width="5%">
                                                                            <input class="form-check-input" type="radio" name="actionTaken" id="actionTaken1" value="stopWork" />
                                                                        </td>
                                                                        <td align="left">STOP WORK</td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if (!empty($_SESSION['accesslevel20']) && $_SESSION['accesslevel20'] == 1) { ?>
                                                                    <tr>
                                                                        <td><input class="form-check-input" type="radio" name="actionTaken" id="actionTaken2" value="verbalWarning" />
                                                                        </td>
                                                                        <td align="left">VERBAL WARNING</td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>

                                                        </div>
                                                        <div class="col-4 themed-grid-col">
                                                            <table width="100%" class="noborder" border="0">
                                                                <?php if (!empty($_SESSION['accesslevel22']) && $_SESSION['accesslevel22'] == 1) { ?>
                                                                    <tr>
                                                                        <td width="5%">
                                                                            <input class="form-check-input" type="radio" name="actionTaken" id="actionTaken3" value="revokeEntryPermit" />
                                                                        </td>
                                                                        <td align="left">REVOKE ENTRY PERMIT</td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php if (!empty($_SESSION['accesslevel19']) && $_SESSION['accesslevel19'] == 1) { ?>
                                                                    <tr>
                                                                        <td width="5%">
                                                                            <input class="form-check-input" type="radio" name="actionTaken" id="actionTaken4" value="notifyRelatedDepartment" />
                                                                        </td>
                                                                        <td align="left">NOTIFY RELATED DEPARTMENT</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td><input type="hidden" name="notifyRelatedDepartment" />
                                                                            <!--select name="notifyRelatedDepartment"
                                                                                class="form-select js-select">
                                                                                <?php echo funNotifyRelatedDepartment($_SESSION['user']['Department']); ?>
                                                                            </select-->
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </table>

                                                        </div>
                                                        <div class="col-4 themed-grid-col">
                                                            <table width="100%" class="noborder">
                                                                <tr>
                                                                    <td width="5%">
                                                                        <input class="form-check-input" type="radio" name="actionTaken" id="actionTaken5" value="others" />
                                                                    </td>
                                                                    <td align="left">OTHERS</td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td>
                                                                        <input type="text" id="otherValue" name="otherValue" />
                                                                        <pre><?php //print_r($_SESSION);
                                                                                ?></pre>
                                                                    </td>
                                                                </tr>
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
                                                            <input type="text" class="form-control text-capitalize"
                                                                id="observerName" name="observerName" placeholder="Name"
                                                                value="<?php echo $_SESSION['user']['Name']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="observerDepartment"
                                                                class="form-label">Department:</label>
                                                            <input type="text" class="form-control"
                                                                id="observerDepartment" name="observerDepartment" placeholder="Department"
                                                                value="<?php echo $_SESSION['user']['Department']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="observerEmail" class="form-label">Email:</label>
                                                            <input type="email" class="form-control" id="observerEmail" name="observerEmail"
                                                                placeholder="Email"
                                                                value="<?php echo $_SESSION['user']['Email']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="observerDatetime" class="form-label">Date &
                                                                Time:</label>
                                                            <input type="datetime-local" class="form-control"
                                                                id="observerDatetime" name="observerDatetime" placeholder="Date & Time"
                                                                value="<?php echo date('Y-m-d h:i:s'); ?>">
                                                        </div>
                                                    </div><!--Overlay-->
                                                </td>
                                                <td width="50%" valign="top">
                                                    <div class="mb-3">
                                                        <label for="violatorName" class="form-label">Name:</label>
                                                        <input type="text" class="form-control" id="violatorName"
                                                            name="violatorName" placeholder="Name" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorContactNo" class="form-label">Contact no.:</label>
                                                        <input type="text" class="form-control" id="violatorContactNo"
                                                            name="violatorContactNo" placeholder="Contact No." required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorDepartment"
                                                            class="form-label">Department</label>
                                                        <select name="violatorDepartment" class="form-select js-select"
                                                            required>
                                                            <?php echo funDepartment(); ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorCompanyName" class="form-label">Company's Name
                                                            <a href="default.php?page=admin_company" class="btn btn-secondary btn-circle btn-sm"
                                                                title="Add new company">
                                                                <i class="fa-solid fa-plus"></i> New company
                                                            </a>
                                                        </label>
                                                        <select class="form-select js-select" id="violatorCompanyName" name="violatorCompanyName" required>
                                                            <?php echo funCompany(); ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorICPassport"
                                                            class="form-label">IC/Passport</label>
                                                        <input type="text" class="form-control" id="violatorICPassport"
                                                            name="violatorICPassport" placeholder="IC/Passport"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="violatorDatetime" class="form-label">Date &
                                                            Time</label>
                                                        <input type="datetime-local" class="form-control"
                                                            id="violatorDatetime" name="violatorDatetime"
                                                            placeholder="Date & Time" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="formFile" class="form-label">Pass Picture Upload :
                                                        </label>
                                                        <input class="form-control" type="file" name="files2[]"
                                                            id="files2" accept="image/png, image/gif, image/jpeg"
                                                            multiple="multiple" required>
                                                    </div>
                                                    <div class="mb-3" id="preview2"></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <center>
                                            <button type="reset" class="btn btn-secondary btn-icon-split btn-sm">
                                                <span class="text text-white">
                                                    Reset
                                                </span>
                                            </button>

                                            <button type="submit" name="draft_btn"
                                                class="btn btn-secondary btn-icon-split btn-sm">
                                                <span class="text text-white">
                                                    <i class="fa-solid fa-database"></i> Save as Draft
                                                </span>
                                            </button>

                                            <button type="submit" name="submit_btn"
                                                class="btn btn-primary btn-icon-split btn-sm">
                                                <span class="text text-white">
                                                    <i class="fa-solid fa-circle-check"></i> Submit
                                                </span>
                                            </button>
                                        </center>
                                    </div><!--card-body-->
                                </div><!--Card-->
                                <input type="hidden" name="url" value="<?php echo base64_encode($fullurl); ?>" />
                                <input type="hidden" name="accessLevel" id="accessLevel" value="<?php echo $_SESSION['user']['AccessLevel']; ?>" />
                            </form>



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

                if (parseInt(accessLevel) == 1) {
                    $("input[type=radio]").attr('disabled', true);
                }

                switch (parseInt(accessLevel)) {
                    case 1:
                        document.getElementById('actionTaken1').disabled = true;
                        document.getElementById('actionTaken2').disabled = true;
                        document.getElementById('actionTaken3').disabled = true;
                        document.getElementById('actionTaken4').disabled = true;
                        document.getElementById('actionTaken5').disabled = true;
                        $("input[type=radio]").attr('disabled', true);
                        console.log(accessLevel);
                        break;
                    case 2:
                        document.getElementById('actionTaken1').disabled = true;
                        document.getElementById('actionTaken2').disabled = true;
                        document.getElementById('actionTaken3').disabled = true;
                        document.getElementById('actionTaken4').disabled = true;
                        document.getElementById('actionTaken5').disabled = true;

                        break;
                    case 3:
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('actionTaken3').disabled = false;
                        document.getElementById('actionTaken4').disabled = false;
                        document.getElementById('actionTaken5').disabled = false;
                        break;
                    case 4:
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('actionTaken3').disabled = false;
                        document.getElementById('actionTaken4').disabled = false;
                        document.getElementById('actionTaken5').disabled = false;
                        break;
                    case 5:
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('actionTaken3').disabled = false;
                        document.getElementById('actionTaken4').disabled = false;
                        document.getElementById('actionTaken5').disabled = false;
                        break;
                    case 6:
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('actionTaken3').disabled = false;
                        document.getElementById('actionTaken4').disabled = false;
                        document.getElementById('actionTaken5').disabled = false;
                        break;
                    case 7:
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('actionTaken3').disabled = false;
                        document.getElementById('actionTaken4').disabled = false;
                        document.getElementById('actionTaken5').disabled = false;
                        break;
                    case 8:
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('actionTaken3').disabled = true;
                        document.getElementById('actionTaken4').disabled = true;
                        document.getElementById('actionTaken5').disabled = true;
                        break;
                    case 9:
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('actionTaken3').disabled = false;
                        document.getElementById('actionTaken4').disabled = false;
                        document.getElementById('actionTaken5').disabled = false;
                        break;
                    case 10:
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('actionTaken3').disabled = false;
                        document.getElementById('actionTaken4').disabled = false;
                        document.getElementById('actionTaken5').disabled = false;
                        break;
                    case 11:
                        document.getElementById('actionTaken1').disabled = false;
                        document.getElementById('actionTaken2').disabled = false;
                        document.getElementById('actionTaken3').disabled = false;
                        document.getElementById('actionTaken4').disabled = false;
                        document.getElementById('actionTaken5').disabled = false;
                        break;
                    default:
                        document.getElementById('actionTaken1').disabled = true;
                        document.getElementById('actionTaken2').disabled = true;
                        document.getElementById('actionTaken3').disabled = true;
                        document.getElementById('actionTaken4').disabled = true;
                        document.getElementById('actionTaken5').disabled = true;
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
    </script>
</body>

</html>