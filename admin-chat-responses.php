<?php
$title = 'Chatbot';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if(isset($_GET['delq'])){
	$sql="DELETE FROM chatbot_db.questions WHERE id='".$_GET['delq']."' ";
	mysqli_query($db, $sql);
	
	$sql="DELETE FROM chatbot_db.responses WHERE id='".$_GET['delr']."' ";
	mysqli_query($db, $sql);
	
	$_SESSION['status'] = "Record deleted successfully";
	$_SESSION['status_code'] = "success";
	echo '<meta http-equiv="refresh" content="0; URL=default.php?page=admin-chat-responses" />';
	exit;
}

if(isset($_POST['btnSave'])){
	$sql="INSERT INTO responses (response_message) VALUES ('".$_POST['response_message']."')";
	mysqli_query($db, $sql);
	$last_id = mysqli_insert_id($db);
	
	$sql2="INSERT INTO questions (question, response_id) VALUES ('".$_POST['question']."', '".$last_id."')";
	mysqli_query($db, $sql2);
	
	$_SESSION['status'] = "Record has been added";
	$_SESSION['status_code'] = "success";
	
	echo 'Record inserted successfully';
	echo '<meta http-equiv="refresh" content="0; URL=default.php?page=admin-chat-responses" />';
	exit;
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
                                <h4 class="mb-sm-0 font-size-18">Chatbot</h4>

									<div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Chatbot</a></li>
                                            <li class="breadcrumb-item active">Response</li>
                                        </ol>
                                    </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <div class="float-start">
                                        <h3 class="card-title">List of Questions Responses</h3>
                                    </div>

                                    <div class="float-end">
                                        <button class="btn btn-outline-primary waves-effect waves-light"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <span class="fas fa-plus"></span> Create New
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="container-fluid">
                                        <table id="datatable" class="table dt-responsive w-100">
                                            <colgroup>
                                                <col width="5%">
                                                <col width="20%">
                                                <col width="">
                                                <col width="5%">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Question</th>
                                                    <th>Response</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                $sql = "SELECT q.id, q.response_id, r.response_message, q.question FROM chatbot_db.questions q inner join chatbot_db.responses r on q.response_id = r.id order by q.question asc ";
                                                $qry = mysqli_query($db, $sql);
                                                while ($row = mysqli_fetch_array($qry)):
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $i++; ?></td>
                                                        <td><?php echo $row['question'] ?></td>
                                                        <td class="truncate"><?php echo $row['response_message'] ?></td>
                                                        <td align="center">
                                                            <a class="btn btn-outline-secondary btn-sm" href="default.php?page=admin-chat-responses-edit&id=<?php echo $row['response_id'];?>&q=<?php echo $row['id'] ?>">
                                                                <span class="fa fa-edit"></span>
                                                            </a>
                                                                        
                                                            <a class="btn btn-outline-secondary btn-sm delete_data" href="default.php?page=admin-chat-responses&delr=<?php echo $row['response_id'];?>&delq=<?php echo $row['id'];?>" data-id="<?php echo $row['id'] ?>">
                                                                <span class="fa fa-trash"></span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- right offcanvas -->
			
            <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasRight"
                aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasRightLabel">Create New Question Response</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="form-group mb-3">
				        <label for="question_field" class="control-label">Question</label>
                        <textarea type="text" name="question" id="question" class="form-control form-control-sm" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="response_message" class="control-label">Response Message</label>
				        <textarea name="response_message" id="response_message" cols="30" rows="3" class="form-control" required></textarea>
				    </div>
                    <div class="form-group mb-3">
                        <button type="button" name="btnSubmit" id="btnSubmit" class="btn btn-flat btn-primary" form="response-form">Save</button>
		                <a class="btn btn-flat btn-default" data-bs-dismiss="offcanvas" href="#">Cancel</a>
                    </div>
                </div>
            </div>
			
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
	$("#btnSubmit").click(function(){
		var question = $("#question").val();
		var response_message = $("#response_message").val();
		var table = $("#datatable").DataTable();
        $.ajax({
			type: "POST",
			url:    "admin_chat_responses_edit.php",
			data: {
				btnSave: 1,
				question: question,
				response_message: response_message,
			},
			success: function(data){
				console.log(data);
				alert(data);
				$("#question").val("");
				$("#response_message").val("");
				$(location).prop('href', 'default.php?page=admin-chat-responses');
			},
			error: function(data){
				console.error(data);
				alert(data);
			}
		});
	});
	</script>

</body>

</html>