<?php
$title = 'Chat';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function funShowQ($q)
{
    global $db;
    try {
        $sql = "SELECT question FROM chatbot_db.unanswered WHERE id = '" . $q . "' ";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);
        return $row['question'];
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

if(isset($_POST['btnsave'])){
    //Add answer
    $sql = "INSERT INTO chatbot_db.responses (response_message) VALUES ('".$_POST['response_message']."') ";
    mysqli_query($db, $sql);
    $last_id = mysqli_insert_id($db);
    //Add question
    $sql = "INSERT INTO chatbot_db.questions (question, response_id) VALUES ('".$_POST['question']."', '".$last_id."') ";
    mysqli_query($db, $sql);
    //Delete unanswered
    $sql = "DELETE FROM unanswered WHERE id = '".$_POST['uid']."' ";
    mysqli_query($db,$sql);

    //echo $sql;
    echo '<meta http-equiv="refresh" content="0; url=default.php?page=admin-chat-unanswered" />';
    exit;
}

try {
    $sql = "SELECT * FROM chatbot_db.unanswered WHERE id = '" . $_GET['id'] . "' ";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    $question = $row['question'];
    $id = $row['id'];
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
                                <h4 class="mb-sm-0 font-size-18">Responses</h4>

                                <div class="page-title-right">
									<a href="#" onclick="history.back()"><i class="fa-solid fa-xmark"></i></a>
								</div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="card">
						<div class="card-body p-4">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Question</label>
                                    <textarea type="text" name="question" id="question" class="form-control form-control-sm" required><?php echo funShowQ($_GET['q']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="html-editor" class="form-label">Response</label>
                                    <textarea name="response_message" id="response_message" class="form-control ace-editor"><?php echo isset($response_message)?$response_message:0; ?></textarea>
                                </div>
                                <div class="mt-4">
                                    <button type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary w-md">Save</button>
                                    <input type="hidden" name="idq" id="idq" value="<?php echo $_GET['q'];?>"/>
                                    <input type="hidden" name="idr" id="idr" value="<?php echo $_GET['id'];?>"/>
                                    <input type="hidden" name="furl" id="furl" value="<?php echo $fullurl;?>"/>
                                </div>
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
	$("#btnSubmit").click(function(){
		var question = $("#question").val();
		var response_message = $("#response_message").val();
		var idq = $("#idq").val();
		var idr = $("#idr").val();
		var furl = $("#furl").val();
        $.ajax({
			type: "POST",
			url:    "admin_chat_responses_edit.php",
			data: {
				btnUnanswered: 1,
				question: question,
				response_message: response_message,
				idq: idq,
				idr: idr,
			},
			success: function(data){
				console.log(data);
				alert(data);
				//$(location).prop('href', furl);
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