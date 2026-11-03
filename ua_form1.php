<?php
$title = 'UA Form';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function funNotifyRelatedDepartment($dept){
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

function funLocation(){
    global $db;
    $option = '';
    try{
        $sql1 = "SELECT * FROM location";
	    $result1 = $db->query($sql1);                 
	    while ($row1 = mysqli_fetch_array($result1)) {
		    $option .= '<option value="'.$row1['LocationId'].'">'.$row1['LocationName'].'</option>';
	    }
    }catch(Exception $e){
        $option .= $e->getMessage();
    }
    return $option;
}

function funOffence(){
    global $db;
    $option = '';
    try{
        $sql1 =  "SELECT * FROM offence";
		$result1 = $db->query($sql1);             
		while ($row1 = mysqli_fetch_array($result1)) {
            $option .= '<option value="'.$row1['OffenceCode'].'">'.$row1['OffenceCode'].'-'.$row1['offKeyword'].'</option>';
		}
    }catch(Exception $e){
        $option .= $e->getMessage();
    }
    return $option;
}

function funDepartment(){
    global $db;
    $option = '';
    try{
        $sql1 = "SELECT * FROM dept ORDER BY deptName";
		$result1 = $db->query($sql1);    
		while ($row1 = mysqli_fetch_array($result1)) {
		    $option .= '<option value="'.$row1['dept'].'">'.$row1['deptName'].' ('.$row1['dept'].')</option>';
		}
    }catch(Exception $e){
        $option.= $e->getMessage();
    }
}

function funCompany(){
    global $db;
    $option = '';
    $query = "SELECT companyName FROM companies WHERE deptInCharge = 'PSSD' ";
    $result = mysqli_query($db, $query);
    while ($row = mysqli_fetch_array($result)) {
        $option .= '<option value="' . strtoupper($row['companyName']) . '">' . strtoupper($row['companyName']) . '</option>';
    }
    return $option;
}

if (isset($_POST['submit'])) {
    $sql = "INSERT INTO companies (companyName, companyRegNo, groupType, description, deptInCharge, createBy, createDate) VALUES ('" . $_POST['companyName'] . "', '" . $_POST['companyRegNo'] . "', '" . $_POST['groupType'] . "', '" . $_POST['description'] . "', '" . $_POST['deptInCharge'] . "', '" . $_POST['createBy'] . "', '" . $_POST['createDate'] . "') ";
    mysqli_query($db, $sql);
    mysqli_close($db);
    header('location: default.php?page=UA_user');
}

include('inc_header.php');
?>
<style>
            table{
                align: left;
            }
            td{
                padding:8px;
            }
            .uitable {
                margin-top: -20px;
                margin-left: auto;
                margin-right: auto;
            }
            .uitable td {
                border: 1px solid #CCCCCC;
            }
            .form {
                font-weight: bold;
            }
            img.evidence {
                width:100px;
                height:100px;
                background: url(http://goo.gl/vyAs27) no-repeat scroll 0 0;
            }
            .blank{
                border-top: 0;
                border-left: 0;
                border-right: 0;
                border-bottom: 1px solid #000000;
                width: 100px;
            }
            .noborder td{
                border: 0;
                padding: 0;
                margin: 0;
            }
            h5, h6{
                color: darkblue;
                font-weight: bold;
            }
            #overlay {
                position: absolute; /* Sit on top of the page content */
                display: none; /* Hidden by default */
                background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
                opacity: .6;
                z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
                cursor: pointer; /* Add a pointer on hover */
            }
            #overlay:before {
                display: none; /* Hidden by default */
                width: 100%; /* Full width (cover the whole page) */
                height: 100%; /* Full height (cover the whole page) */
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
                opacity: .6;
                z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
                cursor: pointer; /* Add a pointer on hover */
            }
            #overlay:after {
                display: none; /* Hidden by default */
                width: 100%; /* Full width (cover the whole page) */
                height: 100%; /* Full height (cover the whole page) */
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
                opacity: .6;
                z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
                cursor: pointer; /* Add a pointer on hover */
            }
        </style>
    </head>
    <body class="pace-done" data-new-gr-c-s-check-loaded="8.904.0" data-gr-ext-installed="" data-sidebar="brand"><div class="pace pace-inactive"><div class="pace-progress" style="transform: translate3d(100%, 0px, 0px);" data-progress-text="100%" data-progress="99">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

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
                                    <h4 class="mb-sm-0 font-size-18"></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                                            <li class="breadcrumb-item active"></li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-12">
                            
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
        <table width="95%" cellpadding="5" cellspacing="5" class="uitable shadow mb-4">
            <tr>
                <td align="center" valign="center"><img src="img/JPB-Logo.png"/></td>
                <td align="center" valign="center"><h3 class="form">FORM</h3></td>
            </tr>
            <tr>
                <td><b>Department Name : PSSD (HSE)</b></td>
                <td><b>Document No.: FM-PSSD-115</b></td>
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
                    <b>UNSAFE ACT (PERBUATAN TIDAK SELAMAT)</b><br/>
                    <div class="mb-3 row">
                        <label for="location" class="col-sm-2 col-form-label">Location :</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-sm">
                                <select name="location" class="form-select js-select">
                                    <?php echo funLocation();?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-2 col-form-label">Offence Code :</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-sm">
                                <select name="offenceCode" class="form-select js-select">
                                    <?php echo funOffence();?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-2 col-form-label">Evidence :</label>
                        <div class="col-sm-10">
                            <input 
                            class="form-control" 
                            type="file" 
                            name="files[]" 
                            id="files" 
                            accept="image/png, image/gif, image/jpeg"
                            multiple="multiple"
                            required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-2 "></label>
                        <div class="col-sm-10" id="preview"></div>
                    </div>
                </td>
            </tr>
            <tr bgcolor="#EFEFEF">
                <td colspan="2" align="left"><b>2. U-ACT</b></td>
            </tr>
            <tr align="left">
                <td colspan="2"><b>IMMEDIATE CORRECTIVE ACTION TAKEN (TINDAKAN SEGERA YANG DIAMBIL)</b><br/>
                    <div class="row mb-3 ms-5">
                        <div class="col-4 themed-grid-col">

                        <?php //echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';?>

                            <table width="100%" class="noborder">
                                <?php if(!empty($_SESSION['accesslevel6']['accesslevel']) && $_SESSION['accesslevel6']['accesslevel']==1){?>
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="stopWork" aria-label="..."  /></td>
                                    <td align="left">STOP WORK</td>
                                </tr>
                                <?php } ?>
                                <?php if(!empty($_SESSION['accesslevel5']['accesslevel']) && $_SESSION['accesslevel5']['accesslevel']==1){?>
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="verbalWarning" aria-label="..."  /></td>
                                    <td align="left">VERBAL WARNING</td>
                                </tr>
                                <?php } ?>
                            </table>
                             
                        </div>
                        <div class="col-4 themed-grid-col">
                            <table width="100%" class="noborder" border="0">
                                <?php if(!empty($_SESSION['accesslevel7']['accesslevel']) && $_SESSION['accesslevel7']['accesslevel']==1){?>
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="revokeEntryPermit" aria-label="..."  /></td>
                                    <td align="left">REVOKE ENTRY PERMIT</td>
                                </tr>
                                <?php } ?>
                                <?php if(!empty($_SESSION['accesslevel4']['accesslevel']) && $_SESSION['accesslevel4']['accesslevel']==1){?>
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="notifyRelatedDepartment" aria-label="..."  /></td>
                                    <td align="left">NOTIFY RELATED DEPARTMENT</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <select name="notifyRelatedDepartment" class="form-select js-select">
                                            <?php echo funNotifyRelatedDepartment($_SESSION['user']['Department']); ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                             
                        </div>
                        <div class="col-4 themed-grid-col">
                            <table width="100%" class="noborder">
                                <tr>
                                    <td width="5%"><input class="" type="radio" name="actionTaken" id="actionTaken" value="others" aria-label="..."  /></td>
                                    <td align="left">OTHERS</td>
                                </tr>
                            </table>
                             
                        </div>
                      </div>
                </td>
            </tr>
            <tr bgcolor="#EFEFEF" align="center">
                <td colspan="2"><b>VIOLATOR</b></td>
            </tr>
            <tr align="left">
                <td width="50%" valign="top">
                <div class="mb-3">
                        <label for="violatorName" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="violatorName" placeholder="Name">
                    </div>
                    <div class="mb-3">
                        <label for="violatorDepartment" class="form-label">Department</label>
                        <select name="violatorDepartment" class="form-select js-select">
                            <?php echo funDepartment();?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="violatorCompanyName" class="form-label">Company's Name</label>
                        <input type="text" class="form-control" id="violatorCompanyName" placeholder="Company's Name">
                    </div>
                    <div class="mb-3">
                        <label for="violatorICPassport" class="form-label">IC/Passport</label>
                        <input type="text" class="form-control" id="violatorICPassport" placeholder="IC/Passport">
                    </div>
                    
                </td>
                <td width="50%" valign="top">
                    
                    <div class="mb-3">
                        <label for="violatorDatetime" class="form-label">Date & Time</label>
                        <input type="datetime-local" class="form-control" id="violatorDatetime" placeholder="Date & Time">
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Take Action Upload : </label>
                        <input 
                        class="form-control" 
                        type="file" 
                        name="files[]" 
                        id="files2" 
                        accept="image/png, image/gif, image/jpeg"
                        multiple="multiple"
                        required>
                    </div>
                    <div class="mb-3" id="preview2"></div>
                    
                </td>
            </tr>
            <tr bgcolor="#EFEFEF">
                <td colspan="2" align="left"><b>3. FOLLOW-UP ACTION (To be filled by PSSD only)</b></td>
            </tr>
            <tr align="left">
                <td colspan="2">
                    <b>ACTION BY HSE PSSD</b><br/>
                    <div class="row mb-3 ms-5">
                        <div class="col-6 themed-grid-col">

                            <table cellpadding="0" cellspacing="0" class="noborder">
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Verbal Warning" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">VEBAL WARNING</label>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Domestic Inquiry (DI)" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">DOMESTIC INQUIRY</label>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Blacklisted" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">BLACKLISTED</label>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Notice/ Memo of safety violation" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">NOTICE/ MEMO OF SAFETY VIOLATION</label>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Report to related parties/ Department" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">REPORT TO RELATED PARTIES/ DEPARTMENT</label>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                    
                        <div class="col-6 themed-grid-col">
                            <table class="noborder">
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Fine RM " aria-label="...">
                                    </td>
                                    <td>
                                        FINE RM <input type="text" name="fine" class="blank" />
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Attend Induction (PEP)" aria-label="...">
                                    </td>
                                    <td>
                                        ATTEND INDUCTION (PEP)
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Attend briefing (VEP) - FROM " aria-label="...">
                                    </td>
                                    <td>
                                        ATTEND BRIEFING (VEP) - FROM 
                                        <input type="text" name="from" class="blank"/> UNTIL
                                        <input type="text" name="until" class="blank" />
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Banned " aria-label="...">
                                    </td>
                                    <td>
                                        BANNED <input type="text" name="until" class="blank" /> MONTH(S)
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Others " aria-label="...">
                                    </td>
                                    <td>
                                        OTHERS <input type="text" name="others" class="blank" />
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>

                    <div class="row ms-5">
                        <div class="col-4">
                            DATE OF ACTION TAKEN:<br/>
                            <?php echo date("Y-m-d h:i:s"); ?>
                        </div>
                        <div class="col-4">
                            ACTION TAKEN BY:<br/>
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
                            <b>APPROVED BY (PSSD HSE OFFICER)</b><br/>
                            NAME: PSSD HSE OFFICER<br/>
                            DESIGNATION: PSSD HSE OFFICER<br/>
                            DATE: <?php echo date("Y-m-d h:i:s"); ?><br/>
                            STATUS: <span class="badge badge-warning">PENDING</span><br/>
                            <p>&nbsp;</p>
                            <b>REVIEWED BY (HEAD OF SECTION/PSSD)</b><br/>
                            <div class="clearfix"></div>

                            <table width="100%" class="noborder">
                                <tr>
                                    <td width="10%">NAME:</td>
                                    <td>
                                        <div class="input-group input-group-sm">    
                                            <select name="reviewName" class="form-control js-select" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                            <option>Nor Zaini (norzaini@johorport.com.my)</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="clearfix"></div>
                            DESIGNATION: PSSD HOS<br/>
                            DATE: <?php echo date("Y-m-d h:i:s"); ?><br/>
                            STATUS: <span class="badge badge-warning">PENDING</span><br/>
                        </div>
                        <div class="col-6">
                            <b>REVIEWED BY (HEAD OF DEPARTMENT/PSSD)</b><br/>
                            <div class="clearfix"></div>

                            <table width="100%" class="noborder">
                                <tr>
                                    <td width="10%">NAME:</td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <select name="reviewName" class="form-control js-select" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                            <option>Jamaludin (jml@johorport.com.my)</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <div class="clearfix"></div>
                            DESIGNATION: PSSD HOD<br/>
                            DATE: <?php echo date("Y-m-d h:i:s"); ?><br/>
                            STATUS: <span class="badge badge-warning">PENDING</span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr align="left">
                <td colspan="2">
                    <div class="row ms-5">
                        <div class="col-11">
                            PSSD Remark:<br/>
                            <textarea name="pssdRemark" class="form-control"></textarea><br/>
                            
                            <table width="100%" class="noborder">
                                <tr>
                                    <td width="20%" align="left">
                                        <label for="formFile" class="form-label">Take Action Upload : </label>
                                    </td>
                                    <td>
                                        <div class="custom-file">
                                            <input class="" type="file" id="formFile" multiple>
                                        </div>
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
        
        <table border="0" width="95%" cellpadding="5" cellspacing="5" align="center" style="margin-left: auto; margin-right: auto;">
            <tr>
                <td>
                    <div class="alert alert-danger" role="alert">
                        <strong>Note:</strong> Once you press the 'Save' button, you cannot update the information anytime you want
                    </div>
                </td>
            </tr>
        </table>
        <p></p>
        <center>
        <button type="button"class="btn btn-secondary btn-icon-split btn-sm">
            <span class="text text-white">
                <i class="fa-solid fa-floppy-disk"></i> Save
            </span>
        </button>
        <button type="button"class="btn btn-primary btn-icon-split btn-sm">
            <span class="text text-white">
                <i class="fa-solid fa-circle-check"></i> Approved
            </span>
        </button>
        <button type="button" class="btn btn-danger btn-icon-split btn-sm">
            <span class="text text-white">
                <i class="fa-solid fa-circle-xmark"></i> Reject
            </span>
        </button>
        </center>
        </div><!--card-body-->

    </div><!--Card-->

        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title">Activities</h5>
                <p class="card-text">This is some text within a card body.</p>
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

</body>
</html>