<?php
$title = 'UC Form';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
  }

  function reportStatus($accessLevel, $id){
    global $db;
    $status = '';
    $sql = "SELECT isverified, isapproved, isrejected, isconfirm, isNotifyHos, isHosApproved, isHodApproved, isclosed, isreopen, isupload,
        CASE 
            WHEN isverified = 0 THEN 0
            WHEN isverified = 1 THEN 1
            WHEN isverified = 2 THEN 2
        END AS status
    FROM ucuaform WHERE id = '".$id."' ";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    if($accessLevel == 6){//HOS
        if ($row['isHosApproved'] == 0 && $row['isclosed'] != 1) {
            $status = '<span class="badge rounded-pill bg-warning">Pending</span>';
        }elseif($row['isHosApproved'] == 1) {
            $status = '<span class="badge rounded-pill bg-success">Approved</span>';
        }elseif ($row['isclosed'] == 1) {
            $status = '<span class="badge rounded-pill bg-secondary">Closed</span>';
        }
    }elseif($accessLevel == 7){//HOD
        if ($row['isHodApproved'] == 0 && $row['isclosed'] != 1) {
            $status = '<span class="badge rounded-pill bg-warning">Pending</span>';
        }elseif ($row['isHodApproved'] == 1) {
            $status = '<span class="badge rounded-pill bg-success">Approved</span>';
        }elseif ($row['isclosed'] == 1) {
            $status = '<span class="badge rounded-pill bg-secondary">Closed</span>';
        }
    }else{
        if ($row['status'] == 0) {
            $status = '<span class="badge rounded-pill bg-warning">Pending</span>';
        }elseif ($row['status'] == 1) {
            $status = '<span class="badge rounded-pill bg-success">Approved</span>';
        }elseif ($row['status'] == 2 && $row['isclosed'] != 1) {
            $status = '<span class="badge rounded-pill bg-danger">Notified</span>';
        }elseif ($row['isupload'] == 1 && $row['isclosed'] != 1) {
            $status = '<span class="badge rounded-pill bg-success">Uploaded</span>';
        }elseif ($row['isclosed'] == 1) {
            $status = '<span class="badge rounded-pill bg-secondary">Closed</span>';
        }
    }    
    return $status;
  }

if(isset($_GET['del'])){
	$query = "DELETE FROM ucuaform WHERE Id  = '".$_GET['del']."'";
	$query_run = mysqli_query($db,$query);
	if($query_run)
	{
		$_SESSION['status'] = "Record has been deleted succefully";
		$_SESSION['status_code'] = "success";
		echo '<meta http-equiv="refresh" content="0; url=default.php?page=uc_reportlist">';
	}else {
		$_SESSION['status'] = "Detele failed";
		$_SESSION['status_code'] = "error";
		echo '<meta http-equiv="refresh" content="0; url=default.php?page=uc_reportlist">';
	}
}

include('inc_header.php');
?>

    </head>
    <body class="pace-done" data-new-gr-c-s-check-loaded="8.904.0" data-gr-ext-installed="" data-sidebar="brand"><div class="pace pace-inactive"><div class="pace-progress" style="transform: translate3d(100%, 0px, 0px);" data-progress-text="100%" data-progress="99">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            <!-- ========== Left Navbar Start ========== -->
            <?php include('inc_navbar.php');?>
            <!-- Left Navbar End -->
            <!-- ========== Left Sidebar Start ========== -->
            <?php include('inc_sidebar.php');?>
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
                                    <h4 class="mb-sm-0 font-size-18">Unsafe Condition Report</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Home</li>
                                            <li class="breadcrumb-item active">Report</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
						
						<!-- Nav tabs -->
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link " data-bs-toggle="" href="uc_reportlist" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                    <span class="d-none d-sm-block">Assigned</span> 
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="" href="uc_reportlist_all" role="tab">
                                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                    <span class="d-none d-sm-block">All</span> 
                                                </a>
                                            </li>
                                        </ul>

                        <div class="row">
                            <div class="col-lg-12">
                            
					<table id="datatable" class="table dt-responsive w-100">
					<thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Report Date</th>
                        <th scope="col">Observer Name</th>
                        <th scope="col">Location</th>
                        <th scope="col">Status</th>
                        <th scope="col">Log</th>
                        <th scope="col">action</th>
                    </tr>
					</thead>
					<tbody>
					<?php
                    $staffid=$_SESSION['user']['staffId'];
                    
                    if($_SESSION['user']['AccessLevel'] == 4){//PSSD HSE OFFICER
                        $sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isverified, uf.isrejected, uf.isnotifysherep, uf.status, uf.remark FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.form_type='Unsafe Condition' AND uf.isrejected=0 ORDER BY uf.Id DESC";
                    }elseif($_SESSION['user']['AccessLevel'] == 8){//HSE REPS
						$sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isapproved, uf.isupload, uf.remark, uf.isverified, u.Department FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE u.Department = '".$_SESSION['user']['Department']."' AND uf.form_type='Unsafe Condition' ORDER BY uf.Id DESC";
                    }elseif($_SESSION['user']['AccessLevel'] == 6){//HOS
                        //$sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isHosApproved, uf.status, uf.remark, uf.isverified FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.form_type='Unsafe Condition' AND uf.isNotifyHos=1 AND uf.isrejected=0 ORDER BY uf.Id DESC";
						$sql = "SELECT DISTINCT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isapproved, uf.isupload, uf.remark, uf.isverified, v.Department FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId LEFT JOIN viewucuaformbydept AS v ON v.id=uf.Id WHERE v.Department='".$_SESSION['user']['Department']."' AND uf.form_type='Unsafe Condition' ORDER BY uf.Id DESC";
                    }elseif($_SESSION['user']['AccessLevel'] == 7){//HOD
                        $sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.status, uf.isHODapproved, uf.remark, uf.isverified FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.form_type='Unsafe Condition' AND uf.isHosApproved=1 ORDER BY uf.Id DESC";
					}elseif($_SESSION['user']['AccessLevel'] == 9){//ADMIN
						$sql = "SELECT ucuaform.Id, ucuaform.submitdate, u.Name, ucuaform.form_type, location.LocationName, ucuaform.isverified, ucuaform.remark FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId INNER JOIN user AS u ON u.staffId = ucuaform.staffId WHERE  ucuaform.form_type='Unsafe Condition' AND ucuaform.isrejected=0 ORDER BY id DESC";
                    }else{
                        $sql = "SELECT ucuaform.Id, ucuaform.submitdate, u.Name, ucuaform.form_type, location.LocationName, ucuaform.isverified, ucuaform.remark FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId INNER JOIN user AS u ON u.staffId = ucuaform.staffId WHERE ucuaform.staffid='".$staffid."' AND ucuaform.form_type='Unsafe Condition' AND ucuaform.isrejected=0 ORDER BY id DESC";
                    }
                    $result = mysqli_query($db, $sql);
                    $tmpCount = 1; 
                    while($row = mysqli_fetch_array($result)) {
					?>
					<tr>
                    <td width="5%" align="center"><?php echo $tmpCount; ?></td>
                    <td width="" align="center"><?php echo $row['submitdate']; ?></td>
                    <td width="" class="text-capitalize"><?php echo strtolower($row['Name']); ?></td>
                    <td width=""><?php echo $row['LocationName']; ?></td>
                    <td width="" align="center">
                    <?php echo reportStatus($_SESSION['user']['AccessLevel'], $row['Id']);?>
                    </td>
                    <td class=""><?php echo ucfirst($row['remark']);?></td>
                    <td width="5%">
                        <ul class="d-flex justify-content-center">
                        <a class="btn btn-success" href="default.php?page=history&ucuaid=<?php echo $row['Id']; ?>"><i class="fas fa-network-wired"></i></a>&nbsp;
                        <a class="btn btn-success" href="default.php?page=newuiuc_form&id=<?php echo base64_encode($row['Id']); ?>"><i class="fas fa-eye"></i></a>&nbsp;
						<?php if ($row['isverified'] == 0) {; ?>
						<a class="btn btn-success" href="default.php?page=uc_reportlist&del=<?php echo $row['Id'];?>" onClick="return confirm(\'Delete this item? This action cannot be undone.\')"><i class="fa-regular fa-trash-can"></i></a>
						<?php }else{?>
						<a class="btn btn-secondary" href="#"><i class="fa-regular fa-trash-can"></i></a>
						<?php }?>
                        </ul>
                    </td>
					</tr>
					<?php $tmpCount++; } ?>
					</tbody>
					</table><?php //echo $sql;?>
							
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php include('inc_footer.php');?>

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        
        <!-- Right Sidebar -->
        <?php include('inc_rightbar.php');?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <?php include('inc_scripts.php');?>

</body>
</html>