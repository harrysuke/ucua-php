<?php
$title = 'User Activity';
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
                                <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);"
                                                onclick="history.back()">Security</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></h4>
                                        </li>
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
                                        <th>ucuaid</th>
                                        <th>action</th>
                                        <th>activity</th>
                                        <th>status</th>
                                        <th>datetimestamp</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>ucuaid</th>
                                        <th>action</th>
                                        <th>activity</th>
                                        <th>status</th>
                                        <th>datetimestamp</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    $staffid = $_SESSION['user']['staffId'];
                                    $y = date('Y');
                                    $m = date('m');
                                    $sql = "SELECT id, ucuaid, action, activity, status, datetimestamp FROM history WHERE staffId=? AND action NOT IN('LOGIN','LOGOUT')
                                    ORDER BY datetimestamp DESC ";
                                    $stmt = $db->prepare($sql);
                                    $stmt->bind_param("s", $staffid);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $tmpCount = 1;
                                    while ($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td>
                                                <?php echo $tmpCount; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['ucuaid']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['action']; ?>
                                            </td>
                                            <td>
                                                <?php echo ucfirst(strtolower(trim($row['activity']))); ?>
                                            </td>
                                            <td>
                                                <?php echo $row['status']; ?>
                                            </td>
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