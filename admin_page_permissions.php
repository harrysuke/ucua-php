<?php
include('testFunctions.php');
$title = 'Set Page Permissions';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function checkRoleAccessRights($pageid, $roleid)
{
    global $db;
    $sql = "SELECT pageId FROM pagepermissions WHERE roleId = ? AND pageId = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $roleid, $pageid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
        $check = 'checked';
    } else {
        $check = '';
    }

    return $check;
}

function userRole($id)
{
    global $db;
    $sql = "SELECT rolename FROM userroles WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
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

    $sid = $_SESSION['user']['staffId'];
    $d = date('Y-m-d h:i:s');

    $sql = "INSERT INTO  pagepermissions (pageId, roleid, createby, createdate) VALUES (?,?,?,?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iiss", $moduleid, $roleid, $sid, $d);
    $stmt->execute();
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
    $sql = "DELETE FROM userright WHERE roleid = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $roleid);
    $stmt->execute();
}

if (isset($_POST['btnAccessRight'])) {
    $counter = $_POST['counter'];

    checkAccessLevel($_POST['roleid']);

    for ($j = 0; $j <= $counter; $j++) {
        if (isset($_POST['pageid' . $j])) {
            insertAccessLevel($_POST['roleid'], $_POST['pageid' . $j], $_POST['page' . $j]);
            //echo '('.$_POST['roleid'].' '.$counter.' '.$_POST['moduleid'.$j].')<br/>';
        }
    }

    //echo '<meta http-equiv="refresh" content="0;URL=' . base64_decode($_POST['fullurl']) . '" />';
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
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">User Roles</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body p-4">
                                    <form method="post" action="default.php?page=admin_page_permissions&id=<?php echo $_GET['id']; ?>">
                                        <table class="table" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>#</td>
                                                    <th>Page Name</th>
                                                    <th>Page Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $i = 0;
                                                $sql = "SELECT id AS pageid, page, pagename FROM page ";
                                                $stmt = $db->prepare($sql);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="pageid<?php echo $i; ?>" class="form-check-label" value="<?php echo $row['pageid']; ?>" <?php echo checkRoleAccessRights($row['pageid'], $_GET['id']); ?> />
                                                            </td>
                                                            <td class="text-capitalize">
                                                                <?php echo strtolower($row["pagename"]) ?> (<?php echo $row["pageid"] ?>)
                                                                <input type="hidden" name="page<?php echo $i; ?>" value="<?php echo $row['pageid']; ?>" />
                                                            </td>
                                                            <td class="text-capitalize">
                                                                <?php echo strtolower($row["page"]) ?>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        $i++;
                                                    }
                                                    $stmt->close();
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
                                            <input type="hidden" name="fullurl" value="<?php echo base64_encode($fullurl); ?>" />
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