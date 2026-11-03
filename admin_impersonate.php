<?php
include('testFunctions.php');
$title = 'User Impersonation';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function dept()
{
    global $db;
    $dept = '';
    $sql = "SELECT deptId, deptName, dept FROM dept WHERE isDelete = 0 ORDER BY deptName";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $dept .= '<option value="' . $row['dept'] . '">' . strtoupper($row['dept']) . ' - ' . strtoupper($row['deptName']) . '</option>';
    }
    $stmt->close();
    return $dept;
}

function sect()
{
    global $db;
    $d = '';
    $sql = "SELECT id, sect, section FROM sect WHERE isDelete = 0 ORDER BY section";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $d .= '<option value="' . strtoupper($row['sect']) . '">' . strtoupper($row['sect']) . ' - ' . strtoupper($row['section']) . '</option>';
    }
    $stmt->close();
    return $d;
}

function user()
{
    global $db;
    $d = '';
    $sql = "SELECT u.staffId, u.Name, u.PhoneNo, u.Department, u.Designation, u.AccessLevel, u.Email, u.CompanyID, r.rolename 
    FROM user AS u 
    LEFT JOIN userroles AS r ON r.id = u.AccessLevel 
    ORDER BY u.Name";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $d .= '<option value="' . $row['staffId'] . '">' . strtoupper($row['staffId']) . ' - ' . strtoupper($row['Name']) . '</option>';
    }
    $stmt->close();
    return $d;
}

function role()
{
    global $db;
    $d = '';
    $sql = "SELECT id, rolename FROM  userroles ORDER BY rolename";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $d .= '<option value="' . $row['id'] . '|' . $row['rolename'] . '">' . strtoupper($row['rolename']) . '</option>';
    }
    $stmt->close();
    return $d;
}

if (isset($_POST['btnChangeDept'])) {
    unset($_SESSION['user']['Department']);
    $_SESSION['user']['Department'] = trim($_POST['dept']);
    header("Location: default.php?page=admin_impersonate");
    exit;
}

if (isset($_POST['btnChangeSect'])) {
    header("Location: default.php?page=admin_impersonate");
    exit;
}

if (isset($_POST['btnChangeUser'])) {
    try {
        unset($_SESSION['user']);
        $sid = trim($_POST['user']);
        $sql = "SELECT u.staffId, u.Name, u.PhoneNo, u.Department, u.Designation, u.AccessLevel, u.Email, u.CompanyID, r.rolename 
        FROM user AS u 
        LEFT JOIN userroles AS r ON r.id = u.AccessLevel 
        WHERE u.staffId = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $sid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $_SESSION['user'] = $row;
        header("Location: default.php?page=admin_impersonate");
        exit;
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

if (isset($_POST['btnChangeRole'])) {
    unset($_SESSION['user']['AccessLevel'], $_SESSION['user']['rolename']);
    $role = explode("|", $_POST['role']);
    $_SESSION['user']['AccessLevel'] = trim($role[0]);
    $_SESSION['user']['rolename'] = trim($role[1]);
    header("Location: default.php?page=admin_impersonate");
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
                                <div class="float-start">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>
                                    <p>Impersonation allows you to access this system with the same privileges as this user. This allows you to diagnose problems with their account.</p>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tools</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Session info</h5>
                                    <code>
                                        {&quot;Department&quot;: &quot;<?php echo $_SESSION['user']['Department']; ?>&quot;,
                                        &quot;Section&quot;:&quot;&quot;,
                                        &quot;User&quot;: &quot;<?php echo $_SESSION['user']['Name']; ?>&quot;,
                                        &quot;Role&quot;: &quot;<?php echo $_SESSION['user']['rolename']; ?>&quot;}
                                    </code>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="admin_impersonate.php">
                                        <h4 class="card-title">Change Company</h4>
                                        <h6 class="card-subtitle"></h6>
                                        <div class="form-group mt-5">
                                            <label>Company</label>
                                            <select class="form-control" data-trigger name="dept"
                                                id="choices-single-default"
                                                placeholder="This is a search placeholder">
                                                <option value="<?php echo $_SESSION['company']['companyCode']; ?>"><?php echo $_SESSION['company']['companyName']; ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input type="hidden" name="url" value="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" />
                                            <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
                                            <input type="hidden" name="useragent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                                            <button type="submit" name="btnChangeCompany" class="btn btn-dark waves-effect waves-light" disabled><i class="bi bi-arrow-left-right"></i> Apply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="admin_impersonate.php">
                                        <h4 class="card-title">Change Branch</h4>
                                        <h6 class="card-subtitle"></h6>
                                        <div class="form-group mt-5">
                                            <label>Branch</label>
                                            <select class="form-control" data-trigger name="dept"
                                                id="choices-single-default"
                                                placeholder="This is a search placeholder">
                                                <option value="<?php echo $_SESSION['company']['branchCode']; ?>"><?php echo strtoupper($_SESSION['company']['branchName']); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input type="hidden" name="url" value="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" />
                                            <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
                                            <input type="hidden" name="useragent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                                            <button type="submit" name="btnChangeBranch" class="btn btn-dark waves-effect waves-light" disabled><i class="bi bi-arrow-left-right"></i> Apply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="admin_impersonate.php">
                                        <h4 class="card-title">Change Department</h4>
                                        <h6 class="card-subtitle"></h6>
                                        <div class="form-group mt-5">
                                            <label>Departments</label>
                                            <select class="form-control" data-trigger name="dept"
                                                id="choices-single-default"
                                                placeholder="This is a search placeholder">
                                                <option value="<?php echo $_SESSION['user']['Department']; ?>"><?php echo $_SESSION['user']['Department']; ?></option>
                                                <?php echo dept(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input type="hidden" name="url" value="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" />
                                            <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
                                            <input type="hidden" name="useragent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                                            <button type="submit" name="btnChangeDept" class="btn btn-dark waves-effect waves-light"><i class="bi bi-arrow-left-right"></i> Apply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="admin_impersonate.php">
                                        <h4 class="card-title">Change section</h4>
                                        <h6 class="card-subtitle"></h6>
                                        <div class="form-group mt-5">
                                            <label>Sections</label>
                                            <select class="form-control" data-trigger name="sect"
                                                id="choices-single-default"
                                                placeholder="This is a search placeholder">
                                                <?php echo sect(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input type="hidden" name="url" value="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" />
                                            <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
                                            <input type="hidden" name="useragent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                                            <button type="submit" name="btnChangeSect" class="btn btn-dark waves-effect waves-light"><i class="bi bi-arrow-left-right"></i> Apply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="admin_impersonate.php">
                                        <h4 class="card-title">Change user</h4>
                                        <h6 class="card-subtitle"></h6>
                                        <div class="form-group mt-5">
                                            <label>Users</label>
                                            <select class="form-control" data-trigger name="user"
                                                id="choices-single-default"
                                                placeholder="This is a search placeholder">
                                                <?php echo user(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input type="hidden" name="url" value="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" />
                                            <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
                                            <input type="hidden" name="useragent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                                            <button type="submit" name="btnChangeUser" class="btn btn-dark waves-effect waves-light"><i class="bi bi-arrow-left-right"></i> Apply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="admin_impersonate.php">
                                        <h4 class="card-title">Change role</h4>
                                        <h6 class="card-subtitle"></h6>
                                        <div class="form-group mt-5">
                                            <label>Roles</label>
                                            <select class="form-control" data-trigger name="role"
                                                id="choices-single-default"
                                                placeholder="This is a search placeholder">
                                                <?php echo role(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input type="hidden" name="url" value="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" />
                                            <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
                                            <input type="hidden" name="useragent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                                            <button type="submit" name="btnChangeRole" class="btn btn-dark waves-effect waves-light"><i class="bi bi-arrow-left-right"></i> Apply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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