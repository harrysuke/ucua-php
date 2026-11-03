<?php
$title = 'Open‑case backlog report';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function funOffence($id){
	global $db;
	$sql = "SELECT Offence FROM offence WHERE OffenceCode = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if($row){
		return $row['Offence'];
	}
	$stmt->close();
}

include('inc_header.php');
?>

</head>

<body class="pace-done sidebar-enable" data-new-gr-c-s-check-loaded="8.906.0" data-gr-ext-installed="" data-sidebar="brand" data-sidebar-size="sm">
    <div class="pace pace-inactive">
        <div class="pace-progress" style="transform: translate3d(100%, 0px, 0px);" data-progress-text="100%" data-progress="99">
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
                                <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?>
                                    <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
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

                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Report list</h4>
                                    <div class="flex-shrink-0">
                                        <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="default.php?page=report_trend_analysis_dept" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                    <span class="d-none d-sm-block">Report</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="" href="default.php?page=dashboard-usage" role="tab">
                                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                    <span class="d-none d-sm-block">Visual</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-body">

                                    <table id="datatable" class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Report Details</th>
                                                <th scope="col">Report Type</th>
                                                <th scope="col">Report Date</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tmpCount = 1;
                                            $staffid = $_SESSION['user']['staffId'];
                                            $y = date('Y');
                                            $sql = "SELECT Id, OffenceCode, ConditionDetails, form_type, status, submitdate,isUserClosed, userClosedDate, isrejected, isapproved, isHosApproved, isHODapproved FROM `ucuaform` WHERE isrejected=0 AND isapproved=0 AND isUserClosed=0 AND isHosApproved=0 AND isHODapproved=0";
                                            $stmt = $db->prepare($sql);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($row = $result->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td width="5%"><?php echo $tmpCount; ?></td>
                                                    <td width="40%"><?php echo funOffence($row['OffenceCode']).' '.$row['ConditionDetails']; ?></td>
                                                    <td><?php echo $row['form_type']; ?></td>
                                                    <td><?php echo $row['submitdate']; ?></td>
                                                    <td><?php echo $row['status']; ?></td>
                                                    <td width="5%">
                                                        <?php
                                                        if ($row['form_type'] == 'Unsafe Action') {
                                                            echo '<a href="default.php?page=newuiua_form&id=' . base64_encode($row['Id']) . '" target="_blank" class="btn btn-light"><i class="bi bi-binoculars"></i></a>';
                                                        } elseif ($row['form_type'] == 'Unsafe Condition') {
                                                            echo '<a href="default.php?page=newuiuc_form&id=' . base64_encode($row['Id']) . '" target="_blank" class="btn btn-light"><i class="bi bi-binoculars"></i></a>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php $tmpCount++;
                                            }
                                            $stmt->close();
                                            ?>
                                        </tbody>
                                    </table>

                                </div><!-- end card-body -->
                            </div><!-- end card -->

                            <?php //echo $_SESSION['user']['AccessLevel'];
                            ?>
                            <?php //echo $sql;
                            ?>

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