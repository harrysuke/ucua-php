<?php
$title = 'Login Sessions';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

include('inc_header.php');
?>

</head>

<body class="pace-done sidebar-enable" data-new-gr-c-s-check-loaded="8.906.0" data-gr-ext-installed=""
    data-sidebar="brand" data-sidebar-size="sm">
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
                                <!--h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4-->
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link " data-bs-toggle="" href="default.php?page=audit-log"
                                            role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span class="d-none d-sm-block">Audit logs</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="" href="default.php?page=audit-login"
                                            role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">User sessions</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- end nav tabs -->

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);" onclick="history.back()">Settings</a></li>
                                        <li class="breadcrumb-item active">Logs</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">



                            <table id="datatable" class="table dt-responsive w-100">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>#</th>
                                        <th>action</th>
                                        <th>activity</th>
                                        <th>user agent</th>
                                        <th nowrap>IP address</th>
                                        <th>status</th>
                                        <th>datetimestamp</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>action</th>
                                        <th>activity</th>
                                        <th>user agent</th>
                                        <th>IP address</th>
                                        <th>status</th>
                                        <th>datetimestamp</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    $staffid = $_SESSION['user']['staffId'];
                                    $sql = "SELECT id, staffid, action, activity, status, datetimestamp, ipaddress, useragent FROM history WHERE action IN('LOGIN','LOGOUT') AND YEAR(datetimestamp)='" . date('Y') . "' AND MONTH(datetimestamp)='" . date('m') . "' ORDER BY datetimestamp DESC ";
                                    $result = mysqli_query($db, $sql);
                                    $tmpCount = 1;
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $tmpCount; ?></td>
                                            <td><code><?php echo $row['action']; ?></code></td>
                                            <td><?php echo ucfirst(strtolower($row['staffid'])); ?>&nbsp;
                                                <?php echo ucfirst($row['activity']); ?>
                                            </td>
                                            <td><?php echo $row['useragent']; ?></td>
                                            <td><?php echo $row['ipaddress']; ?></td>
                                            <td><code><?php echo $row['status']; ?></code></td>
                                            <td nowrap>
                                                <?php echo $row['datetimestamp']; ?>
                                            </td>
                                        </tr>
                                    <?php $tmpCount++;
                                    } ?>
                            </table>





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