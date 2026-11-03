<?php
$title = 'Email Template';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}



//ADD DATA
if (isset($_POST['submit'])) {
	$query = "UPDATE emailtemplate SET eto = '".$_POST['eto']."', ecc = '".$_POST['ecc']."', ebcc = '".$_POST['ebcc']."', esubject = '".$_POST['esubject']."', ebody = '".$_POST['ebody']."' WHERE id = '".$_POST['eid']."' ";
	$query_run= mysqli_query($db, $query);

	if ($query_run)
	{
	//echo " saved";
	$_SESSION['status'] = "New record added successfully ";
	$_SESSION['status_code'] = "success";
	} 
	else
	{
	//echo "not saved";
	$_SESSION['status'] = "Create record failed";
	$_SESSION['status_code'] = "error";
	}
	
	header('Location: default.php?page=admin_email_template');
}

if(isset($_GET['del']))
{
	$id = $_GET['del'];

	$query = "UPDATE emailtemplate SET isdelete=1, deleteby='".$_SESSION['user']['staffId']."', deletedate='".date("Y-m-d H:is")."' WHERE EmailId = '".$id."' ";
	$query_run = mysqli_query($db,$query);

	if($query_run)
	{
		$_SESSION['status'] = "Record is deleted succefully";
		$_SESSION['status_code'] = "success";
	}else {
		$_SESSION['status'] = "Delete record failed";
		$_SESSION['status_code'] = "error";
	}
	
	header('Location: default.php?page=admin_email_template');
}

$id = trim($_GET['id']);

if(empty($id)){
	$id = 1;
}

try{
	$sql = "SELECT id, eto, ecc, ebcc, esubject, ebody FROM emailtemplate WHERE id = '".$id."' ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
    if($row){
        $eid = $row['id'];
	    $eto = $row['eto'];
	    $ecc = $row['ecc'];
	    $ebcc = $row['ebcc'];
	    $esubject = $row['esubject'];
	    $ebody = $row['ebody'];
    }else{
        $eid = '';
	    $eto = '';
	    $ecc = '';
	    $ebcc = '';
	    $esubject = '';
	    $ebody = '';
    }
	
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
					<!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Email</a></li>
                                            <li class="breadcrumb-item active">Template</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
							<!-- Left sidebar -->
                                <div class="email-leftbar card">
                                    <!--button type="button" class="btn btn-danger btn-block waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#composemodal">
                                        Compose
                                    </button-->
                                    <div class="mail-list mt-0">
                                        <a href="default.php?page=admin_email_template&id=1"><i class="mdi mdi-email-outline me-2"></i> HOS</a>
                                        <a href="default.php?page=admin_email_template&id=2"><i class="mdi mdi-email-outline me-2"></i>HOD</a>
                                        <a href="javascript: void(0);"><i class="mdi mdi-trash-can-outline me-2"></i>Trash</a>
                                    </div>

                                    
                                </div>
                                <!-- End Left sidebar -->

                                <!-- Right Sidebar -->
                                <div class="email-rightbar mb-0">

                                    <div class="card">
                                        <div class="card-body">
											<form method="post" action="default.php?page=admin_email_template">
												<div class="mb-3">
													<input type="text" name="eto" class="form-control" placeholder="To" value="<?php echo $eto;?>">
												</div>
												<div class="mb-3">
													<input type="text" name="ecc" class="form-control" placeholder="CC" value="<?php echo $ecc;?>">
												</div>
												<div class="mb-3">
													<input type="text" name="ebcc" class="form-control" placeholder="BCC" value="<?php echo $ebcc;?>">
												</div>
												<div class="mb-3">
													<input type="text" name="esubject" class="form-control" placeholder="Subject" value="<?php echo $esubject;?>">
												</div>
												<div class="mb-3 email-editor">
													<textarea name="ebody" id="email-editor" class="form-control"><?php echo $ebody;?></textarea>
												</div>
												<div class="mb-3">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" name="submit" class="btn btn-primary">Save <i class="fab fa-telegram-plane ms-1"></i></button>
												</div>
												<input type="hidden" name="eid" value="<?php echo $eid;?>"/>
											</form>
											
                                        </div>
                                    </div>
                                </div>
                                <!-- card -->


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
	
	
	<!-- Modal -->
                <div class="modal fade" id="composemodal" tabindex="-1" role="dialog" aria-labelledby="composemodalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title font-size-16" id="composemodalTitle">New Message</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" placeholder="To">
                                    </div>

                                    <div class="mb-3">
                                        <input type="text" class="form-control" placeholder="Subject">
                                    </div>
                                    <div class="mb-3 email-editor">
                                        <div id="email-editor"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Send <i class="fab fa-telegram-plane ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end modal -->
	

    <!-- right offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Create New Email</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="default.php?page=admin_email" method="POST">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" name="HSEname" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Email<span class="text-danger"> *</span></label>
                            <input type="text" name="Email" class="form-control" placeholder="Email" required>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Department<span class="text-danger"> *</span></label>
                            <select name="DeptName" class="form-control " required>
                                <option value="" disabled selected>Please Select Department</option>
                                <?php //echo funDeptAll(); ?>
                            </select>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Access Level<span class="text-danger"> *</span></label>
                            <select id="accessLevel" name="AccessLevel" class="form-control " required>
								<option value="" disabled selected>Please Select AccessLevel</option>
								<?php //echo funRoleAll(); ?>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                            <button type="submit" name="addbtn" class="btn btn-primary">Create</button>
                        </div>

                    </div>
            </form>
        </div>
        

    </div>
    </div>


    <!-- Right Sidebar -->
    <?php include('inc_rightbar.php'); ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <?php include('inc_scripts.php'); ?>

</body>

</html>