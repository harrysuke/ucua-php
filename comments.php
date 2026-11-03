<?php
$title = 'Comments';
include('testFunctions.php');

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function showComments()
{
    global $db;
    $activity = '';
    try {
        $sid = trim($_SESSION['user']['staffId']);
        $sanitizedSid = "%" . mysqli_real_escape_string($db, $sid) . "%";
        $sql = "SELECT a.id, a.staffid, a.datetimestamp, a.activity, a.uploadfilename, u.Name 
        FROM activity AS a 
        LEFT JOIN user AS u ON (a.staffid=u.staffId) 
        LEFT JOIN ucuaform AS f ON f.Id=a.ucuaid
        WHERE f.staffId LIKE ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $sanitizedSid);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $activity .= '<div class="d-flex py-2 border-bottom">
							<div class="flex-shrink-0 me-3 ms-3">
								<img class="rounded-circle me-2" width="45" height="45" avatar="' . $row['Name'] . '">
							</div>
							<div class="flex-grow-1">
								<h5 class="font-size-14 mb-0">' . $row['Name'] . ' ' . showUserRole($row['staffid']) . '</h5>
								<span class="font-size-13 text-black-50 time">' . date('d M, Y', strtotime($row['datetimestamp'])) . '</span>
								<p class="text-muted">' . ucfirst($row['activity']) . 'l</p>
							</div>
						</div>';
            $activity .= '<div class="feed-image p-2 px-3 mt-0">';
            $activity .= showPhoto($row['id']);
            $activity .= '</div>';
        }
        $stmt->close();
        return $activity;
    } catch (Exception $e) {
        die($e->getMessage());
    }
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
                                <div class="float-start">
                                    <h4 class="mb-sm-0 font-size-18">
                                        <?php echo $title; ?>
                                    </h4>
                                    <p class="card-title-desc">Explore the conversations in our community.</p>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-6">
                            <?php echo showComments(); ?>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                    <div aria-hidden="true">
                        <div class="placeholder-glow col-6">
                            <table class="table table-nowrap align-middle justify-content-center mb-0"></table>
                        </div>
                    </div>

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include('inc_footer.php'); ?>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- right offcanvas -->
    <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Create User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">


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