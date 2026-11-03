<?php
$title = 'Chatbot';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if(isset($_GET['del'])){
	$sql = "DELETE FROM chatbot_db.unanswered WHERE id = '".trim($_GET['del'])."' ";
	mysqli_query($db, $sql);
	echo '<meta http-equiv="refresh" content="0; URL=default.php?page=admin-chat-unanswered" />';
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
                                            <li class="breadcrumb-item active">Unanswered</li>
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
                                        <h3 class="card-title">List of Unanswered Questions</h3>
                                    </div>

                                    <div class="float-end">

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="container-fluid">
                                        <table id="datatable" class="table dt-responsive  nowrap w-100">
                                            <colgroup>
                                                <col width="5%">
                                                <col width="45%">
                                                <col width="45%">
                                                <col width="5%">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Question</th>
                                                    <th>Total Who Asks</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                $sql = "SELECT * FROM chatbot_db.unanswered order by question asc ";
                                                $qry = mysqli_query($db,$sql);
                                                while ($row = mysqli_fetch_array($qry)):
                                                    ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php echo $i++; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $row['question'] ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo number_format($row['no_asks']) ?>
                                                        </td>
                                                        <td align="center">
                                                            <a class="btn btn-outline-secondary btn-sm" href="default.php?page=admin-chat-responses-new&id=<?php echo $row['id'] ?>&q=<?php echo $row['id'];?>">
                                                                <span class="fa fa-edit"></span>
                                                            </a>
                                                            
                                                            <a class="btn btn-outline-secondary btn-sm delete_data" href="default.php?page=admin-chat-unanswered&del=<?php echo $row['id'];?>" data-id="<?php echo $row['id'];?>">
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
                        <input type="text" id="question" class="form-control form-control-sm" data-original-title=""
                            title="">
                    </div>
                    <div class="form-group mb-3">
                        <label for="response_message" class="control-label">Response Message</label>
                        <textarea name="response_message" id="response_message" cols="30" rows="3" class="form-control"
                            style="resize: none" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <button class="btn btn-flat btn-primary" form="response-form">Save</button>
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

</body>

</html>