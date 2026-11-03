<?php
$title = 'UC Form';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function funRunningNo(){
	global $db;
    $sql1 = "SELECT MAX(Id) as 'runningno' FROM ucuaform";
    $result1 = mysqli_query($db, $sql1);
    $row1 = mysqli_fetch_array($result1);
    return $row1['runningno'];
}

function funLocation()
{
    $option = '';
    global $db;
    $sql1 = "SELECT LocationId, LocationName FROM location WHERE isdelete=0 ORDER BY LocationName";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $LocationId = $row1['LocationId'];
        $LocationName = $row1['LocationName'];
        $option .= "<option value='$LocationId'>$LocationName</option>";
    }
    return $option;
}

function funLocationName($id)
{
    $option = '';
    global $db;
    $sql1 = "SELECT LocationId, LocationName FROM location WHERE isdelete=0 AND LocationId='".$id."' ";
    $result1 = mysqli_query($db, $sql1);
    $row1 = mysqli_fetch_array($result1);
	if($row1){
		return $row1['LocationName'];
	}else{
		return $id;
	}
}

function photoEvidence($id)
{
    $img = '';
    global $db;
    $sql = "SELECT id, evidenceImage FROM ucuaphoto WHERE ucuaform_Id = '" . $id . "' AND type='Photo Evidence'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
		$img .= '<div class="col-xl-12">
					<div class="card">
						<img class="card-img-top img-fluid" src="'.$row['evidenceImage'].'" alt="Card image cap">
                        <div class="card-body">
                        <h4 class="card-title"></h4>
                        <p class="card-text"></p>
                        <a href="'.$row['evidenceImage'].'" class="btn btn-light waves-effect waves-light image-popup-desc" data-title="Photo Evidence" data-description="Photo evidence description. metadata: '.$row['evidenceImage'].'"><i class="fa-solid fa-magnifying-glass"></i></a>
                        <a href="default.php?page=uc_form_edit&del='.$row['id'].'&id='.$id.'" class="btn btn-light waves-effect waves-light"><i class="fa-solid fa-trash-can"></i></a>
                    </div>
				</div><!-- end col -->';
    }
    return $img;
}

	function history($ucuaid, $staffid, $accessLevel, $action, $activity, $status, $datetimestamp){
		global $db;
		$sql = "INSERT INTO  history (ucuaid, staffid, accessLevel, action, activity, status, datetimestamp) VALUES (".$ucuaid.", '".$staffid."', ".$accessLevel.", '".$action."', '".$activity."', '".$status."', '".$datetimestamp."')";
		if(mysqli_query($db, $sql)){
			$_SESSION['statusType'] = "success";
			$_SESSION['status'] = "New record added successfully";
		}else{
			$_SESSION['statusType'] = "error";
			$_SESSION['status'] .= " Error ".mysqli_error($db);
		}
	}
	
	function insertUcuaphoto($ucuaform_Id, $evidenceImage, $createby, $createdate){
		global $db;
		$sql = "INSERT INTO ucuaphoto (ucuaform_Id, evidenceImage, createby, createdate, type) VALUES (".$ucuaform_Id.", '".$evidenceImage."', '".$createby."', '".$createdate."', 'Photo Evidence') ";
		if(mysqli_query($db, $sql)){
			$_SESSION['statusType'] = "success";
			$_SESSION['status'] .= " Photos uploaded successfully.";
		}else{
			$_SESSION['statusType'] = "error";
			$_SESSION['status'] .= " Error ".mysqli_error($db);
		}
	}
	
	if(isset($_GET['del'])){
		try{
			$sql = "DELETE FROM ucuaphoto WHERE id = '".trim($_GET['del'])."' ";
			mysqli_query($db,$sql);
			echo '<meta http-equiv="refresh" content="0; URL=default.php?page=uc_form_edit&ucuaid='.base64_encode($_GET['id']).'" />';
			exit;	
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

try{
	$sql = "SELECT Id, LocationId, conditionDetails FROM ucuaform WHERE Id = '".base64_decode($_GET['ucuaid'])."' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	if($row){
		$Id = $row['Id'];
		$LocationId = $row['LocationId'];
		$conditionDetails = $row['conditionDetails'];
	}else{
		$Id = base64_decode($_GET['ucuaid']);
		$LocationId = "";
		$conditionDetails = "";
	}
}catch(Exception $e){
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

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Unsafe Condition Form</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">Home</li>
                                        <li class="breadcrumb-item active">Edit</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Update Unsafe Condition</h4>
										<ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link " data-bs-toggle="" href="default.php?page=newuiuc_form&id=<?php echo $_GET['ucuaid'];?>" role="tab">
                                                        <span class="d-block d-sm-none"><i class="fa-solid fa-eye"></i></span>
                                                        <span class="d-none d-sm-block">Preview</span> 
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="" href="default.php?page=uc_form_edit&ucuaid=<?php echo $_GET['ucuaid'];?>" role="tab">
                                                        <span class="d-block d-sm-none"><i class="fa-solid fa-pencil"></i></span>
                                                        <span class="d-none d-sm-block">Edit</span>   
                                                    </a>
                                                </li>
                                            </ul>
                                    </div><!-- end card header -->
                                <div class="card-body">
								<p></p>
                                    <form id="register_form" action="default.php?page=UCcode_user" method="post" enctype="multipart/form-data">
                                        <table width="95%" cellpadding="5" cellspacing="5" class="uitable shadow mb-4">
                                            <tr>
                                                <td align="center" valign="center"><img src="img/JPB-Logo.png" /></td>
                                                <td align="center" valign="center">
                                                    <h3 class="form">FORM</h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Department Name : PSSD (HSE)</b></td>
                                                <td><b>Document No.: FM-PSSD-<?php echo base64_decode($_GET['ucuaid']);?></b></td>
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
                                                    <div class="mb-4 row">
                                                        <label for="location" class="col-sm-4 col-form-label">
															Location:<br/>
															<a href="default.php?page=admin_location" class="btn btn-secondary btn-sm waves-effect waves-light"><i class="fa-solid fa-plus"></i> New location</a>
														</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-sm">
                                                                <select 
                                                                id="LocationId" 
                                                                name="LocationId"
                                                                class="form-control js-select"
                                                                required>
                                                                <option value="<?php echo $LocationId;?>" selected="selected"><?php echo funLocationName($LocationId);?></option>
                                                                <?php echo funLocation(); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4 row">
                                                        <label for="offenceCode"
                                                            class="col-sm-4 col-form-label">Condition Details :</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-sm">
                                                                <textarea 
                                                                class="form-control" 
                                                                id="conditionDetails"
                                                                name="conditionDetails" 
                                                                aria-label="Sizing example input"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                required><?php echo $conditionDetails;?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4 row">
                                                        <label for="offenceCode"
                                                            class="col-sm-4 col-form-label">Evidence :<br/>
															<small>Upload one or more photo Evidence.</small>
															</label>
                                                        <div class="col-sm-8">
                                                            <input 
                                                            class="form-control" 
                                                            type="file" 
                                                            name="files[]" 
                                                            id="files" 
                                                            accept="image/png, image/gif, image/jpeg"
                                                            multiple="multiple">
                                                        </div>
                                                    </div>
													
													<div class="mb-4 row">
                                                        <label for="offenceCode" class="col-sm-4 "></label>
														<div class="col-sm-8">
															<div class="row">
																<?php echo photoEvidence(base64_decode($_GET['ucuaid'])); ?>
															</div><!-- end row -->
														</div>
                                                    </div>
													
                                                    <div class="mb-4 row">
                                                        <label for="offenceCode" class="col-sm-4 "></label>
														<div class="col-sm-8" id="preview"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>


                                        <p></p>

                                        <center>
                                            <button type="reset" class="btn btn-secondary btn-icon-split btn-sm">
                                                <span class="text text-white">
                                                    <i class="fa-solid fa-eraser"></i> Reset
                                                </span>
                                            </button>
                                            <button type="submit" name="update"
                                                class="btn btn-primary btn-icon-split btn-sm">
                                                <span class="text text-white">
                                                    <i class="fa-solid fa-circle-check"></i> Update
                                                </span>
                                            </button>
                                        </center>
										<input type="hidden" name="url" value="<?php echo base64_encode($fullurl); ?>" />
										<input type="hidden" name="Id" value="<?php echo $Id; ?>" />
                                    </form>
                                </div><!--card-body-->

                            </div><!--Card-->
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
    
<?php
if(!empty($_SESSION['status'])){
    echo '<script type="text/javascript">toastr.success("'.$_SESSION['status'].'")</script>';
    unset($_SESSION['status']);
}
?>

</body>
</html>