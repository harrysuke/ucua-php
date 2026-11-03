<?php
include('testfunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login.php');
}

include('includes/header.php'); 
include('includes/navbar-Tester.php');

$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if(isset($_GET['del'])){
	$query = "DELETE FROM ucuaform WHERE Id  = '".$_GET['del']."'";
	$query_run = mysqli_query($db,$query);
	if($query_run)
	{
		$_SESSION['status'] = "Record has been deleted succefully";
		$_SESSION['status_code'] = "success";
		echo '<meta http-equiv="refresh" content="0; url=default.php?page=reportUC_user">';
	}else {
		$_SESSION['status'] = "Detele failed";
		$_SESSION['status_code'] = "error";
		echo '<meta http-equiv="refresh" content="0; url=default.php?page=reportUC_user">';
	}
}
?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
 <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body">
	<?php
      if(!empty($_SESSION['error'])){
        echo '<div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
					<p><strong>Warning!</strong> '.$_SESSION['error'].'</p>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
			</div>';
        unset($_SESSION['error']);
      }
      if(!empty($_SESSION['success'])){
        echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
				<p><strong>Success!</strong> '.$_SESSION['success'].'</p>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>';
        unset($_SESSION['success']);
      }
    ?>

    <div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="userdashboard.php">Home</a></li>
            
            <li class="breadcrumb-item active"><a href="reportUC_user.php">My Report List</a></li>

            <li class="breadcrumb-item active"><a href="UC_user.php">Unsafe Condition</a></li>
        </ol>
    </div>
</div>
<!-- row -->


<div class="container-fluid">
 
 <!--**********************************
              Header
    ***********************************-->
<div class="card shadow mb-4">
  <div class="card-header py-3">
	<h4 class="card-title" style="font-size: 22px; color:#6f42c1;"> <b><i class="fas fa-history"></i> My Report (Unsafe Condition)</b> 
    <!--a href="default.php?page=reportUC_user_rejected" class="btn btn-danger float-right">Rejected</a-->
         
  </div>

  <!-- row -->

 <!--**********************************
                 Table 
    ***********************************-->
    <div class="card-body">


<div class="table-responsive">


<table class="table table-hover progress-table text-center" id="datatableid">
                <thead class="text-uppercase">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Report Date</th>
                        <th scope="col">Location</th>
                        <th scope="col">Status</th>
                        <th scope="col">Log</th>
                        <th scope="col">action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $staffid=$_SESSION['user']['staffId'];
                    $result = mysqli_query($db,"SELECT ucuaform.Id, ucuaform.submitdate, ucuaform.form_type, location.LocationName, ucuaform.status6, ucuaform.isverified, ucuaform.remark FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId WHERE ucuaform.staffid='$staffid' AND ucuaform.form_type='Unsafe Condition' AND ucuaform.isrejected=0 ORDER BY id DESC;" ) 
                    or die('Query failed. ' . $mysqli->error);
                    $tmpCount = 1; 
                    while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td width='15%' align='center'><?php echo $tmpCount; ?></td>
                    <td width='15%' align='center'><?php echo $row['submitdate']; ?></td>

                    <td width='15%' align='center'><?php echo $row['LocationName']; ?></td>

                    <td width='20%' align='center'>
                    <?php if ($row['isverified'] == 0) {; ?>
                        <span class="badge rounded-pill bg-warning">Pending</span>
                    <?php } if ($row['isverified'] == 1) { ?>
                        <span class="badge rounded-pill bg-success">Approved</span>
                    <?php }else{ ?>
                      <span class="badge rounded-pill bg-secondary">Closed</span>
                    <?php } ?>
                    </td>
                    <td><?php echo $row['remark'];?></td>
                    <td>
                        <ul class="d-flex justify-content-center">
                        <a class="btn btn-success" href="default.php?page=history&ucuaid=<?php echo $row['Id']; ?>"><i class="fas fa-network-wired"></i></a>&nbsp;
                        <a class="btn btn-success" href="default.php?page=newuiuc_form&id=<?php echo base64_encode($row['Id']); ?>"><i class="fas fa-eye"></i></a>&nbsp;
						<?php if ($row['isverified'] == 0) {; ?>
						<a class="btn btn-success" href="default.php?page=reportUC_user&del=<?php echo $row['Id'];?>" onClick="return confirm(\'Delete this item? This action cannot be undone.\')"><i class="fa-regular fa-trash-can"></i></a>
						<?php }else{?>
						<a class="btn btn-secondary" href="#"><i class="fa-regular fa-trash-can"></i></a>
						<?php }?>
                        </ul>
                    </td>
                </tr>
                <?php $tmpCount++; } ?>
            </table>

    </div>
  </div>
</div>



<?php
include('includes/scripts.php');
include('includes/footer.php');
?>