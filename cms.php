<?php
$title = 'Content Management';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function showLinks()
{
    global $db;
    $a = '';
    $sql = "SELECT id, title, content FROM pages ORDER BY title";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $a .= '<a href="default.php?page=pages&id=' . $row['id'] . '">' . $row['title'] . '</a>';
    }
    return $a;
}

function funPublish($p)
{
    if ($p == 0) {
        return 'Draft';
    } else {
        return 'Published';
    }
}

function funApprove($p)
{
    if ($p == 0) {
        return 'Pending';
    } else {
        return 'Approved';
    }
}


//ADD DATA
if (isset($_POST['create'])) {
    try {
        $t = $_POST['title'];
        $c = trim($_POST['content']);
        $sid = $_SESSION['user']['staffId'];
        $d = date('Y-m-d h:i:s');
        $query = "INSERT INTO pages(title, content, createby, createdate) VALUES (?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssss", $t, $c, $sid, $d);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $_SESSION['status'] = "New record added successfully ";
            $_SESSION['status_code'] = "success";
            header('Location: default.php?page=cms&id=1');
            exit();
        } else {
            $_SESSION['status'] = "Create record failed";
            $_SESSION['status_code'] = "error";
            header('Location: default.php?page=cms&id=1');
            exit();
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

//UPDATE DATA
if (isset($_POST['btnUpdate'])) {
    $t = $_POST['title'];
    $c = $_POST['content'];
    $sid = $_SESSION['user']['staffId'];
    $d = date('Y-m-d h:i:s');
    $id = $_POST['id'];
    $sql = "UPDATE pages SET title = ?, content = ?, editby = ?, editdate = ? WHERE id = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssssi", $t, $c, $sid, $d, $id);
    if ($stmt->execute()) {
        $_SESSION['status'] = "New record added successfully ";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Update record failed";
        $_SESSION['status_code'] = "error";
    }
    header('Location: default.php?page=cms&id=' . $_POST['id']);
    exit();
}

if (isset($_GET['approve'])) {
    $a = $_GET['approve'];
    $sid = $_SESSION['user']['staffId'];
    $d = date('Y-m-d h:i:s');
    $sql = "UPDATE pages SET isapprove = 1, approveby=?, approvedate=? WHERE id = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssi", $sid, $d, $a);
    if ($stmt->execute()) {
        $_SESSION['status'] = "Record updated successfully";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Update record failed";
        $_SESSION['status_code'] = "error";
    }
    //header('Location: default.php?page=cms&id=' . $_GET['approve']);
    echo '<script>alert("Content has been approved");window.location.replace("default.php?page=cms");</script>';
    exit();
}

if (isset($_GET['unapprove'])) {
    $a = $_GET['unapprove'];
    $sid = $_SESSION['user']['staffId'];
    $d = date('Y-m-d h:i:s');
    $sql = "UPDATE pages SET isapprove = 0, approveby=?, approvedate=? WHERE id = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssi", $sid, $d, $a);
    if ($stmt->execute()) {
        $_SESSION['status'] = "Record updated successfully";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Update record failed";
        $_SESSION['status_code'] = "error";
    }
    //header('Location: default.php?page=cms&id=' . $_GET['approve']);
    echo '<script>alert("Content has been unapproved");window.location.replace("default.php?page=cms");</script>';
    exit();
}

//DELETE
if (isset($_GET['del'])) {
    $sid = $_SESSION['user']['staffId'];
    $d = date("Y-m-d H:is");
    $id = $_GET['del'];
    $sql = "UPDATE pages SET isdelete=1, deleteby=?, deletedate=? WHERE id=? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssi", $sid, $d, $id);
    if ($stmt->execute()) {
        $_SESSION['status'] = "Record is deleted succefully";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Delete record failed";
        $_SESSION['status_code'] = "error";
    }
    header('Location: default.php?page=cms&id=1');
    exit();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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
                                        <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                                    </h4>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">
                                        <button type="button" class="btn btn-sm btn-light waves-effect waves-light"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <i class="bi bi-pencil-square"></i> Compose
                                        </button>
                                    </div>
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
                                        <th width="5%">No</th>
                                        <th>Title</th>
                                        <th>Create by</th>
                                        <th>Publish</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $i = 1;
                                    $sql = "SELECT id, title, createby, ispublish, isapprove FROM pages WHERE isdelete=0 ORDER BY title ";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row["title"]; ?></td>
                                            <td><?php echo $row["createby"]; ?></td>
                                            <td><?php echo funPublish($row["ispublish"]); ?></td>
                                            <td><?php echo funApprove($row["isapprove"]); ?></td>
                                            <td width="13%">
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    href="default.php?page=cms_edit&id=<?php echo $row['id']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a class="btn btn-soft-success waves-effect waves-light"
                                                    href="default.php?page=page&t=<?php echo strtolower($row["title"]); ?>" target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <div class="dropdown float-end">
                                                    <button class="btn btn-link text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="default.php?page=cms_edit&id=<?php echo $row['id']; ?>">Edit</a></li>
                                                        <li><a class="dropdown-item" href="default.php?page=page&t=<?php echo strtolower($row["title"]); ?>" target="_blank">Preview</a></li>
                                                        <?php if ($row["isapprove"] == 0) { ?>
                                                            <li><a class="dropdown-item" href="default.php?page=cms&approve=<?php echo $row['id']; ?>">Approve</a></li>
                                                        <?php } else { ?>
                                                            <li><a class="dropdown-item" href="default.php?page=cms&unapprove=<?php echo $row['id']; ?>">Unapprove</a></li>
                                                        <?php } ?>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li><a class="dropdown-item" href="default.php?page=cms&del=<?php echo $row['id']; ?>">Delete</a></li>
                                                    </ul>
                                                </div>

                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                    ?>
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

    <!-- right offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Create New</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="cms.php" method="POST">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label" id="exampleModalLabel">Title<span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Title" required>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label class="form-label" id="exampleModalLabel">Content<span class="text-danger"> *</span></label>
                        <textarea name="content" class="form-control" placeholder="Content" required></textarea>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                        <button type="submit" name="create" class="btn btn-primary">Create</button>
                    </div>

                </div>
            </form>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="composemodal" tabindex="-1" role="dialog" aria-labelledby="composemodalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-size-16" id="composemodalTitle">New Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="To">
                            </div>

                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Subject">
                            </div>
                            <div class="mb-3 email-editor">
                                <div id="email-editor"></div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Send <i class="fab fa-telegram-plane ms-1"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal -->


        <!-- right offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasRightLabel">Create New Email</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="default.php?page=admin_email" method="POST">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" name="HSEname" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Email<span class="text-danger"> *</span></label>
                            <input type="text" name="Email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Department<span class="text-danger"> *</span></label>
                            <select name="DeptName" class="form-control " required>
                                <option value="" disabled selected>Please Select Department</option>
                                <?php //echo funDeptAll(); 
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Access Level<span class="text-danger"> *</span></label>
                            <select id="accessLevel" name="AccessLevel" class="form-control " required>
                                <option value="" disabled selected>Please Select AccessLevel</option>
                                <?php //echo funRoleAll(); 
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                            <button type="submit" name="addbtn" class="btn btn-primary">Create</button>
                        </div>

                    </div>
                </form>
            </div>


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