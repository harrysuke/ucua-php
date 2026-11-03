<?php
$title = 'User Roles / Set User Permissions';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function checkRoleAccessRights($moduleid, $roleid)
{
    global $db;
    $query = "SELECT moduleid FROM userright WHERE roleid = " . $roleid . " AND moduleid = " . $moduleid;
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $check = 'checked';
    } else {
        $check = '';
    }

    return $check;
}

function userRole($id)
{
    global $db;
    $query = "SELECT rolename FROM userroles WHERE id = " . $id;
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result) or die(mysqli_error($db));
    return $row['rolename'];
}

function insertAccessLevel($roleid, $moduleid, $module)
{
    global $db;

    if ($moduleid > 0) {
        $accesslevel = 1;
    } else {
        $accesslevel = 0;
    }

    $sql = "INSERT INTO userright (roleid, moduleid, accesslevel, createby, createdate) VALUES ('" . $roleid . "', '" . $moduleid . "', '" . $accesslevel . "', '" . $_SESSION['user']['staffId'] . "', '" . date('Y-m-d h:i:s') . "')";
    mysqli_query($db, $sql);
    //echo $sql;
}

function check($id)
{
    if (!empty($id)) {
        return $id;
    } else {
        return 0;
    }
}

function checkAccessLevel($roleid)
{
    global $db;
    $sql0 = "DELETE FROM userright WHERE roleid = '" . $roleid . "'";
    mysqli_query($db, $sql0);
}

if (isset($_POST['btnAccessRight'])) {
    $counter = $_POST['counter'];

    checkAccessLevel($_POST['roleid']);

    for ($j = 0; $j <= $counter; $j++) {
        if (isset($_POST['moduleid' . $j])) {
            insertAccessLevel($_POST['roleid'], $_POST['moduleid' . $j], $_POST['module' . $j]);
            //echo '('.$_POST['roleid'].' '.$counter.' '.$_POST['moduleid'.$j].')<br/>';
        }
    }
    //echo '<meta http-equiv="refresh" content="0;URL=' . $_POST['fullurl'] . '" />';
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
        <?php include('inc_sidebar_admin.php'); ?>
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
                                </h4>

                                <div class="page-title-right">
                                    <a href="#" onclick="history.back()"><i class="fa-solid fa-xmark"></i></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body p-4">
                                    <form method="post" action="default.php?page=admin_user_rights&id=<?php echo $_GET['id']; ?>">
                                        <table class="table" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>#</td>
                                                    <th>Module Name</th>
                                                    <th>Module Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $i = 0;
                                                $query = "SELECT id AS moduleid, modulename, moduledescription FROM usermodule ";
                                                $query_run = mysqli_query($db, $query);
                                                if (mysqli_num_rows($query_run) > 0) {
                                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="moduleid<?php echo $i; ?>" class="form-check-label" value="<?php echo $row['moduleid']; ?>" <?php echo checkRoleAccessRights($row['moduleid'], $_GET['id']); ?> />
                                                            </td>
                                                            <td class="text-capitalize">
                                                                <?php echo strtolower($row["modulename"]) ?> (<?php echo $row["moduleid"] ?>)
                                                                <input type="hidden" name="module<?php echo $i; ?>" value="<?php echo $row['moduleid']; ?>" />
                                                            </td>
                                                            <td class="text-capitalize">
                                                                <?php echo strtolower($row["moduledescription"]) ?>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        $i++;
                                                    }
                                                } else {
                                                    echo "No Record Found";
                                                }

                                                ?>

                                            </tbody>
                                        </table>
                                        <div class="float-right">
                                            <a href="default.php?page=admin_user_roles" class="btn btn-secondary">Cancel</a>
                                            <input type="submit" class="btn btn-success" name="btnAccessRight" value="Update Permission" />
                                            <input type="hidden" name="roleid" value="<?php echo $_GET['id']; ?>" />
                                            <input type="hidden" name="counter" value="<?php echo $i; ?>" />
                                            <input type="hidden" name="staffId" value="<?php echo $_SESSION['user']['staffId']; ?>" />
                                            <input type="hidden" name="fullurl" value="<?php echo $fullurl; ?>" />
                                        </div>
                                    </form>

                                </div>
                            </div>
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