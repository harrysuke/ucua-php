<?php
$title = 'All Unsafe Action Report';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}



if (isset($_GET['del'])) {
    $query = "UPDATE ucuaform SET isdelete=1, deleteby='" . $_SESSION['user']['staffId'] . "', deletedate='" . date('Y-m-d h:i:s') . "' WHERE Id  = '" . $_GET['id'] . "'";
    $query_run = mysqli_query($db, $query);
    if ($query_run) {
        $_SESSION['status'] = "Record has been deleted succefully";
        $_SESSION['status_code'] = "success";
        //echo '<meta http-equiv="refresh" content="0; url=default.php?page=ua_reportlist">';
    } else {
        $_SESSION['status'] = "Detele failed";
        $_SESSION['status_code'] = "error";
        //echo '<meta http-equiv="refresh" content="0; url=default.php?page=ua_reportlist">';
    }
    echo $query;
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
                                <h4 class="mb-sm-0 font-size-18">
                                    <?php echo $title; ?>
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

                            <table id="datatable" class="table dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th nowrap>Document No.</th>
                                        <th>Report Date</th>
                                        <th>Violator Company</th>
                                        <th>Observer Name</th>
                                        <th>Status</th>
                                        <th>Offence Code</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $staffid = $_SESSION['user']['staffId'];
                                    //AND uf.isrejected=0
                                    if ($_SESSION['user']['AccessLevel'] == 4) { //PSSD HSE OFFICER
                                        $sql = "SELECT uf.Id, uf.OffenceCode, uf.isverified, uf.ViolatorName, uf.ViolatorCompany, uf.observerName, uf.submitdate, u.Name, uf.isapproved, uf.status, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf INNER JOIN user AS u ON uf.staffId = u.staffId WHERE uf.form_type='Unsafe Action' AND uf.isdelete=0 ORDER BY id DESC";
                                    } elseif ($_SESSION['user']['AccessLevel'] == 8) { //HSE REPS
                                        $sql = "SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.isapproved, uf.HOSreviewName, uf.HODreviewName, uf.ViolatorCompany FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.HSEReps='$staffid' AND uf.form_type='Unsafe Action' AND uf.isverified=1 AND uf.isapproved=1 AND uf.isdelete=0 ";
                                    } elseif ($_SESSION['user']['AccessLevel'] == 6) { //HOS
                                        $sql = "SELECT uf.Id, uf.OffenceCode, uf.isverified, uf.ViolatorName, uf.ViolatorCompany, uf.observerName, uf.submitdate, u.Name, uf.isHosApproved, uf.status, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf INNER JOIN user AS u ON uf.staffId = u.staffId WHERE uf.HOSreviewName LIKE '%" . $_SESSION['user']['Email'] . "%' AND uf.form_type='Unsafe Action' AND uf.isNotifyHos=1 AND uf.isdelete=0 ORDER BY id DESC";
                                    } elseif ($_SESSION['user']['AccessLevel'] == 7) { //HOD
                                        $sql = "SELECT uf.Id, uf.OffenceCode, uf.isverified,uf.ViolatorName, uf.observerName, uf.submitdate, uf.status, uf.ViolatorCompany, u.Name, uf.isHODapproved, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf INNER JOIN user AS u ON uf.staffId = u.staffId WHERE uf.HODreviewName LIKE '%" . $_SESSION['user']['Email'] . "%' AND uf.form_type='Unsafe Action' AND uf.isHosApproved=1 AND uf.isdelete=0 ORDER BY id DESC";
                                    } elseif ($_SESSION['user']['AccessLevel'] == 9) { //ADMIN
                                        $sql = "SELECT uf.Id, uf.OffenceCode, uf.isverified,uf.ViolatorName, uf.ViolatorCompany, uf.observerName, uf.submitdate, uf.status, u.Name, uf.isHODapproved, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf INNER JOIN user AS u ON uf.staffId = u.staffId WHERE uf.form_type='Unsafe Action' AND uf.isdelete=0 ORDER BY uf.Id DESC";
                                    } else {
                                        $sql = "SELECT uf.Id, uf.OffenceCode, uf.isverified, uf.ViolatorName, uf.ViolatorCompany, uf.observerName, uf.submitdate, uf.status, u.Name, uf.isHODapproved, uf.HOSreviewName, uf.HODreviewName FROM ucuaform AS uf INNER JOIN user AS u ON uf.staffId = u.staffId WHERE staffid='$staffid' AND uf.form_type='Unsafe Action' AND uf.isdelete=0 ORDER BY uf.Id DESC";
                                    }
                                    $result = mysqli_query($db, $sql);
                                    $tmpCount = 1;
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <tr id="row<?php echo $row['Id']; ?>">
                                            <td width='5%' align='center'><?php echo $tmpCount; ?></td>
                                            <td align="center" nowrap>FM-PSSD-<?php echo $row['Id']; ?></td>
                                            <td align='center'><?php echo $row['submitdate']; ?></td>
                                            <td class="text-capitalize"><?php echo strtolower($row['ViolatorCompany']); ?></td>
                                            <td class="text-capitalize"><?php echo strtolower($row['observerName']); ?></td>
                                            <td width='20%' align='center'>
                                                <?php echo reportStatus($_SESSION['user']['AccessLevel'], $row['Id']); ?>
                                            </td>
                                            <td align='center'><?php echo $row['OffenceCode']; ?></td>
                                            <td width="5%">
                                                <div class="dropdown">
                                                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="default.php?page=newuiua_form&id=<?php echo base64_encode($row['Id']); ?>">Preview</a></li>
                                                        <li><a class="dropdown-item" href="#">Export PDF</a></li>
                                                        <li><a class="dropdown-item" href="default.php?page=history&ucuaid=<?php echo $row['Id']; ?>">History</a></li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <?php if ($row['isverified'] == 0) {; ?>
                                                            <li><a class="dropdown-item" href="#" data-id="<?php echo $row['Id']; ?>">Delete</a></li>
                                                        <?php } else { ?>
                                                            <li><a class="dropdown-item" href="#">Delete</a></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $tmpCount++;
                                    } ?>
                                </tbody>
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
    <script>
        $(document).ready(function() {
            $('body').on('click', '.delete', function() {
                if (confirm("Delete Record?") == true) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: 'ua_reportlist.php',
                        type: 'GET',
                        data: {
                            'del': 1,
                            'id': id,
                        },
                        success: function(response) {
                            $("#row" + id).slideUp('slow', function() {
                                $(this).remove()
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>