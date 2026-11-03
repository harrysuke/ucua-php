<?php
$title = 'UA Individual Report';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

if (isset($_GET['del'])) {
    $query = "UPDATE ucuaform SET isdelete=1, deleteby='" . $_SESSION['user']['staffId'] . "', deletedate='" . date('Y-m-d h:i:s') . "' WHERE Id  = '" . $_GET['del'] . "'";
    $query_run = mysqli_query($db, $query);
    if ($query_run) {
        $_SESSION['status'] = "Record has been deleted succefully";
        $_SESSION['status_code'] = "success";
        echo '<meta http-equiv="refresh" content="0; url=default.php?page=ua_reportlist-individual">';
    } else {
        $_SESSION['status'] = "Detele failed";
        $_SESSION['status_code'] = "error";
        echo '<meta http-equiv="refresh" content="0; url=default.php?page=ua_reportlist-individual">';
    }
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
                                <h4 class="mb-sm-0 font-size-18">Unsafe Action Report
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
                                        <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs"
                                            role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle=""
                                                    href="default.php?page=ua_reportlist-individual" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                    <span class="d-none d-sm-block">Publish</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle=""
                                                    href="default.php?page=ua_reportlist-individual_draft" role="tab">
                                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                    <span class="d-none d-sm-block">Draft</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-body">

                                    <table id="datatable" class="table dt-responsive  nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Report Date</th>
                                                <th>Violator Company</th>
                                                <th>Observer Name</th>
                                                <th>Status</th>
                                                <th>Offence Code</th>
                                                <th>Create by</th>
                                                <th>Create date</th>
                                                <th>action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $staffid = $_SESSION['user']['staffId'];

                                            if ($_SESSION['user']['AccessLevel'] == 4) { //PSSD HSE OFFICER
                                                $sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isverified, uf.isrejected, uf.isnotifysherep, uf.status, uf.remark, uf.createby, uf.createdate,uf.ViolatorName, uf.ViolatorCompany, uf.observerName, uf.OffenceCode FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.staffId='" . trim($staffid) . "' AND uf.form_type='Unsafe Action' AND uf.isrejected=0 AND uf.isdelete=0 ORDER BY uf.Id DESC";
                                            } elseif ($_SESSION['user']['AccessLevel'] == 8) { //HSE REPS
                                                $sql = "SELECT ucuaform.Id, ucuaform.submitdate, u.Name, ucuaform.form_type, location.LocationName, ucuaform.isverified, ucuaform.remark, ucuaform.createby, ucuaform.createdate,ucuaform.ViolatorName, ucuaform.ViolatorCompany, ucuaform.observerName, ucuaform.OffenceCode FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId INNER JOIN user AS u ON u.staffId = ucuaform.staffId WHERE ucuaform.staffId='" . trim($staffid) . "' AND ucuaform.form_type='Unsafe Action' AND ucuaform.isrejected=0 AND ucuaform.isdelete=0  ORDER BY id DESC";
                                            } elseif ($_SESSION['user']['AccessLevel'] == 6) { //HOS
                                                $sql = "SELECT ucuaform.Id, ucuaform.submitdate, u.Name, ucuaform.form_type, location.LocationName, ucuaform.isverified, ucuaform.remark, ucuaform.createby, ucuaform.createdate,ucuaform.ViolatorName, ucuaform.ViolatorCompany, ucuaform.observerName, ucuaform.OffenceCode FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId INNER JOIN user AS u ON u.staffId = ucuaform.staffId WHERE ucuaform.staffId='" . trim($staffid) . "' AND ucuaform.form_type='Unsafe Action' AND ucuaform.isrejected=0 AND ucuaform.isdelete=0  ORDER BY id DESC";
                                            } elseif ($_SESSION['user']['AccessLevel'] == 7) { //HOD
                                                $sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.status, uf.isHODapproved, uf.remark, uf.isverified, uf.createby, uf.createdate, uf.ViolatorName, uf.ViolatorCompany, uf.observerName, uf.OffenceCode FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.staffId='" . trim($staffid) . "' AND uf.form_type='Unsafe Action' AND uf.isdelete=0  ORDER BY uf.Id DESC";
                                            } elseif ($_SESSION['user']['AccessLevel'] == 9) { //ADMIN
                                                $sql = "SELECT ucuaform.Id, ucuaform.submitdate, u.Name, ucuaform.form_type, location.LocationName, ucuaform.isverified, ucuaform.remark, ucuaform.createby, ucuaform.createdate, ucuaform.ViolatorName, ucuaform.ViolatorCompany, ucuaform.observerName, ucuaform.OffenceCode FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId INNER JOIN user AS u ON u.staffId = ucuaform.staffId WHERE ucuaform.staffId='" . trim($staffid) . "' AND ucuaform.form_type='Unsafe Action' AND ucuaform.isrejected=0 AND ucuaform.isdelete=0  ORDER BY id DESC";
                                            } else {
                                                $sql = "SELECT ucuaform.Id, ucuaform.submitdate, u.Name, ucuaform.form_type, location.LocationName, ucuaform.isverified, ucuaform.remark,ucuaform.createby, ucuaform.createdate, ucuaform.ViolatorName, ucuaform.ViolatorCompany, ucuaform.observerName, ucuaform.OffenceCode FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId INNER JOIN user AS u ON u.staffId = ucuaform.staffId WHERE ucuaform.staffid='" . $staffid . "' AND ucuaform.form_type='Unsafe Action' AND ucuaform.isrejected=0 AND ucuaform.isdelete=0  ORDER BY id DESC";
                                            }
                                            $result = mysqli_query($db, $sql);
                                            //echo $sql;

                                            $tmpCount = 1;
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <tr>
                                                    <td width='5%' align='center'>
                                                        <?php echo $tmpCount; ?>
                                                    </td>
                                                    <td width='15%' align='center'>
                                                        <?php echo $row['submitdate']; ?>
                                                    </td>
                                                    <td width="15%" align="center" class="text-capitalize">
                                                        <?php echo strtolower($row['ViolatorCompany']); ?>
                                                    </td>
                                                    <td width="15%" align="center" class="text-capitalize">
                                                        <?php echo strtolower($row['observerName']); ?>
                                                    </td>
                                                    <td width='20%' align='center'>
                                                        <?php echo reportStatus($_SESSION['user']['AccessLevel'], $row['Id']); ?>
                                                    </td>
                                                    <td width='15%' align='center'>
                                                        <?php echo $row['OffenceCode']; ?>
                                                    </td>
                                                    <td class="">
                                                        <?php echo ucfirst($row['createby']); ?>
                                                    </td>
                                                    <td class="">
                                                        <?php echo ucfirst($row['createdate']); ?>
                                                    </td>
                                                    <td width="5%">
                                                        <ul class="d-flex justify-content-center">
                                                            <a class="btn btn-success"
                                                                href="default.php?page=history&ucuaid=<?php echo $row['Id']; ?>"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="This is the history icon. Click to view the report history log."><i
                                                                    class="fas fa-network-wired"></i></a>&nbsp;
                                                            <a class="btn btn-success"
                                                                href="default.php?page=newuiua_form&id=<?php echo base64_encode($row['Id']); ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="This is the preview icon. Click to preview the report you created."><i
                                                                    class="fas fa-eye"></i></a>&nbsp;
                                                            <?php if ($row['isverified'] == 0) {; ?>
                                                                <a class="btn btn-danger"
                                                                    href="default.php?page=ua_reportlist-individual&del=<?php echo $row['Id']; ?>"
                                                                    onClick="return confirm(\'Delete this item? This action cannot be undone.\')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="This is the trash icon. Click to delete the report you created."><i
                                                                        class="fa-regular fa-trash-can"></i></a>
                                                            <?php } else { ?>
                                                                <a class="btn btn-secondary" href="#"><i
                                                                        class="fa-regular fa-trash-can"></i></a>
                                                            <?php } ?>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            <?php $tmpCount++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div><!-- end card-body -->
                            </div><!-- end card -->

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