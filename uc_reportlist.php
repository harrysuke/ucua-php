<?php
$title = 'UC Form';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
  }

  
  
  function funHosArray($a){
	$aa = explode(', ',$a);
	return $aa[1];
  }

if(isset($_GET['del'])){
	$query = "UPDATE ucuaform SET isdelete=1, deleteby='".$_SESSION['user']['staffId']."', deletedate='".date('Y-m-d h:i:s')."' WHERE Id  = '".$_GET['id']."'";
	$query_run = mysqli_query($db,$query);
	if($query_run)
	{
		$_SESSION['status'] = "Record has been deleted succefully";
		$_SESSION['status_code'] = "success";
		//echo '<meta http-equiv="refresh" content="0; url=default.php?page=uc_reportlist">';
	}else {
		$_SESSION['status'] = "Detele failed";
		$_SESSION['status_code'] = "error";
		//echo '<meta http-equiv="refresh" content="0; url=default.php?page=uc_reportlist">';
	}
}

include('inc_header.php');
?>

    </head>
    <body class="pace-done sidebar-enable" data-new-gr-c-s-check-loaded="8.906.0" data-gr-ext-installed="" data-sidebar="brand" data-sidebar-size="sm">
	<div class="pace pace-inactive"><div class="pace-progress" style="transform: translate3d(100%, 0px, 0px);" data-progress-text="100%" data-progress="99">
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
                                    <h4 class="mb-sm-0 font-size-18">
									<a href="index.php?page=report-uc" class="btn btn-light waves-effect">
									<i class="fa-solid fa-arrow-left"></i>
									</a>&nbsp;
									Unsafe Condition Report
									</h4>

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
						<?php if($_SESSION['user']['AccessLevel']==8){?>
							<ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
									<a class="nav-link active" data-bs-toggle="" href="uc_reportlist" role="tab">
										<span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">Assigned</span> 
                                    </a>
                                </li>
                                <!--li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="" href="uc_reportlist_all" role="tab">
										<span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">All</span> 
                                    </a>
                                </li-->
                            </ul>
						<?php } ?>
                                        

                        <div class="row">
                            <div class="col-lg-12">
                            
					<table id="datatable" class="table dt-responsive w-100">
					<thead>
                    <tr>
                        <th>No.</th>
						<th nowrap>Document No.</th>
                        <th>Report Date</th>
                        <th>Observer Name</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Log</th>
                        <th>action</th>
                    </tr>
					</thead>
					<tbody>
					<?php
                    $staffid=$_SESSION['user']['staffId'];
                    //AND uf.isrejected=0
                    if($_SESSION['user']['AccessLevel'] == 4){//PSSD HSE OFFICER
                        $sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isverified, uf.isrejected, uf.isnotifysherep, uf.status, uf.remark, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.form_type='Unsafe Condition' AND uf.isdelete=0 AND uf.submitdate BETWEEN '".$_POST['dateStart']."' AND '".$_POST['dateEnd']."' ORDER BY uf.Id DESC";
					}elseif($_SESSION['user']['AccessLevel'] == 5){//PSSD PERSONEL
						$sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isapproved, uf.isupload, uf.remark, uf.isverified, u.Department, uf.isrejected, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.HSEReps = '".trim($staffid)."' OR uf.HSEReps0 = '".trim($staffid)."' AND uf.form_type='Unsafe Condition' AND uf.isdelete=0 AND uf.submitdate BETWEEN '".$_POST['dateStart']."' AND '".$_POST['dateEnd']."' ORDER BY uf.Id DESC";
                    }elseif($_SESSION['user']['AccessLevel'] == 8){//HSE REPS
						$sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isapproved, uf.isupload, uf.remark, uf.isverified, u.Department, uf.isrejected, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.HSEReps = '".trim($staffid)."' OR uf.HSEReps0 = '".trim($staffid)."' AND uf.form_type='Unsafe Condition'  AND uf.isdelete=0 AND uf.submitdate BETWEEN '".$_POST['dateStart']."' AND '".$_POST['dateEnd']."' ORDER BY uf.Id DESC";
                    }elseif($_SESSION['user']['AccessLevel'] == 6){//HOS
                        //$sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isHosApproved, uf.status, uf.remark, uf.isverified, uf.isrejected FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.form_type='Unsafe Condition' AND uf.isNotifyHos=1  ORDER BY uf.Id DESC";
						$sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isapproved, uf.isupload, uf.remark, uf.isverified, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf LEFT JOIN location AS l ON  l.LocationId = uf.LocationId LEFT JOIN user AS u ON u.staffId = uf.staffId WHERE uf.HOSreviewName LIKE '%".$_SESSION['user']['Email']."%' AND uf.form_type='Unsafe Condition' AND uf.isdelete=0 AND uf.submitdate BETWEEN '".$_POST['dateStart']."' AND '".$_POST['dateEnd']."'
						GROUP BY uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isapproved, uf.isupload, uf.remark, uf.isverified, uf.HOSreviewName, uf.HODreviewName";
						//v.Department='".$_SESSION['user']['Department']."'
                    }elseif($_SESSION['user']['AccessLevel'] == 7){//HOD
                        $sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.status, uf.isHODapproved, uf.remark, uf.isverified, uf.isrejected, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.HODreviewName LIKE '%".$_SESSION['user']['Email']."%' AND uf.form_type='Unsafe Condition' AND uf.isHosApproved=1 AND uf.isdelete=0 AND uf.submitdate BETWEEN '".$_POST['dateStart']."' AND '".$_POST['dateEnd']."' ORDER BY uf.Id DESC";
					}elseif($_SESSION['user']['AccessLevel'] == 9){//ADMIN
						$sql = "SELECT ucuaform.Id, ucuaform.submitdate, u.Name, ucuaform.form_type, location.LocationName, ucuaform.isverified, ucuaform.remark, ucuaform.isrejected, ucuaform.HOSreviewName, ucuaform.HODreviewName FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId INNER JOIN user AS u ON u.staffId = ucuaform.staffId WHERE  ucuaform.form_type='Unsafe Condition' AND ucuaform.isdelete=0 AND ucuaform.submitdate BETWEEN '".$_POST['dateStart']."' AND '".$_POST['dateEnd']."' ORDER BY id DESC";
                    }else{
                        $sql = "SELECT ucuaform.Id, ucuaform.submitdate, u.Name, ucuaform.form_type, location.LocationName, ucuaform.isverified, ucuaform.remark, ucuaform.isrejected, ucuaform.HOSreviewName, ucuaform.HODreviewName  FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId INNER JOIN user AS u ON u.staffId = ucuaform.staffId WHERE ucuaform.staffid='".$staffid."' AND ucuaform.form_type='Unsafe Condition' AND ucuaform.isdelete=0 AND ucuaform.submitdate BETWEEN '".$_POST['dateStart']."' AND '".$_POST['dateEnd']."' ORDER BY id DESC";
                    }
                    $result = mysqli_query($db, $sql);
                    $tmpCount = 1; 
                    while($row = mysqli_fetch_array($result)) {
					?>
					<tr id="row<?php echo $row['Id'];?>">
                    <td width="5%" align="center"><?php echo $tmpCount; ?></td>
					<td align="center" nowrap>FM-PSSD-<?php echo $row['Id']; ?></td>
                    <td align="center"><?php echo $row['submitdate']; ?></td>
                    <td class="text-capitalize"><?php echo strtolower($row['Name']); ?></td>
                    <td><?php echo $row['LocationName']; ?></td>
                    <td align="center">
                    <?php echo reportStatus($_SESSION['user']['AccessLevel'], $row['Id']);?>
                    </td>
					<?php if($_SESSION['user']['AccessLevel'] == 6){//HOS
						echo '<td>'.funHosArray($row['HOSreviewName']).'</td>';
					}elseif($_SESSION['user']['AccessLevel'] == 7){//HOD
						echo '<td>'.$row['HODreviewName'].'</td>';
					}else{
						echo '<td>'.ucfirst(strtolower($row['remark'] ?? '')).'</td>';
					}?>
                    <td width="5%">
                        <ul class="d-flex justify-content-center">
                        <a class="btn btn-success" href="default.php?page=history&ucuaid=<?php echo $row['Id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="This is the history icon. Click to view the report history log."><i class="fas fa-network-wired"></i></a>&nbsp;
                        <a class="btn btn-success" href="default.php?page=newuiuc_form&id=<?php echo base64_encode($row['Id']); ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="This is the preview icon. Click to preview report."><i class="fas fa-eye"></i></a>&nbsp;
						<?php if ($row['isverified'] == 0) {; ?>
						<!--a class="btn btn-success" href="default.php?page=uc_reportlist&del=<?php echo $row['Id'];?>" onClick="return confirm(\'Delete this item? This action cannot be undone.\')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="This is the trash icon. Click to delete report."><i class="fa-regular fa-trash-can"></i></a-->
                        <a class="btn btn-danger delete" href="javascript:void(0)" data-id="<?php echo $row['Id'];?>"><i class="fa-regular fa-trash-can"></i></a>
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
<script>
$(document).ready(function(){
    $('body').on('click', '.delete', function() {
        if (confirm("Delete Record?") == true) {
		    var id = $(this).data('id');
		    $.ajax({
			    url: 'uc_reportlist.php',
			    type: 'GET',
			    data: {
				    'del': 1,
				    'id': id,
			    },
			    success: function (response) {
				    $("#row"+id).slideUp('slow',function(){$(this).remove()});
			    }
		    });
        }
	});
});
</script>
</body>
</html>