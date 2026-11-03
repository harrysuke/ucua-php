<?php
$title = 'Content Management';
include('testFunctions.php');

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
        $a .= '<a href="default.php?page=cms_edit&id=' . $row['id'] . '">' . $row['title'] . '</a>';
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
            header('Location: default.php?page=cms_edit&id=1');
            exit();
        } else {
            $_SESSION['status'] = "Create record failed";
            $_SESSION['status_code'] = "error";
            header('Location: default.php?page=cms_edit&id=1');
            exit();
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

//UPDATE DATA
if (isset($_POST['btnUpdate'])) {
    $p = $_POST['publish'];
    $t = $_POST['title'];
    $c = $_POST['content'];
    $sid = $_SESSION['user']['staffId'];
    $d = date('Y-m-d h:i:s');
    $id = $_POST['id'];
    $query = "UPDATE pages SET title = ?, content = ?, editby = ?, editdate = ?, ispublish = ?, publishby = ?, publishdate = ? WHERE id = ? ";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssssissi", $t, $c, $sid, $d, $p, $sid, $d, $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $_SESSION['status'] = "Record updated successfully ";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Update record failed";
        $_SESSION['status_code'] = "error";
    }
    header('Location: default.php?page=cms_edit&id=' . $_POST['id']);
    exit();
}

//DELETE
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $query = "UPDATE pages SET isdelete=1, deleteby=?, deletedate=? WHERE id = ? ";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssi", $_SESSION['user']['staffId'], date("Y-m-d H:is"), $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $_SESSION['status'] = "Record is deleted succefully";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Delete record failed";
        $_SESSION['status_code'] = "error";
    }
    header('Location: default.php?page=cms_edit&id=1');
    exit();
}

if (empty(trim($_GET['id']))) {
    $id = 1;
} else {
    $id = trim($_GET['id']);
}

try {
    $sql = "SELECT * FROM pages WHERE id = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $title = $row['title'];
    $content = $row['content'];
    $createby = $row['createby'];
    $createdate = $row['createdate'];
    $publish = $row['ispublish'];
    $approve = $row['isapprove'];
} catch (Exception $e) {
    die($e->getMessage());
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
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <form method="post" action="cms_edit.php">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-0">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <input type="text" name="title" class="form-control" placeholder="Title" value="<?php echo $title; ?>">
                                            </div>
                                            <div class="mb-3 email-editor">
                                                <textarea name="content" class="form-control wysiwyg-editor" placeholder="Content"><?php echo $content; ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <a href="default.php?page=cms" class="btn btn-secondary">Close</a>
                                                <button type="submit" name="btnUpdate" class="btn btn-primary">
                                                    <i class="bi bi-floppy"></i> Update
                                                </button>
                                                <a href="default.php?page=page&t=<?php echo strtolower($title); ?>" target="_blank" class="btn btn-success">
                                                    <i class="bi bi-play"></i> Preview
                                                </a>
                                            </div>
                                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <!-- card -->
                            </div>
                            <!-- end col -->
                            <div class="col-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="createby" class="form-label">Author</label>
                                            <input class="form-control" type="text" value="<?php echo $createby; ?>" id="createby">
                                        </div>
                                        <div class="mb-3">
                                            <label for="createdate" class="form-label">Post date</label>
                                            <input class="form-control" type="text" value="<?php echo $createdate; ?>" id="createdate">
                                        </div>
                                        <div class="mb-3">
                                            <label for="publish" class="form-label">Published</label>
                                            <select class="form-control" name="publish" id="publish">
                                                <option value="<?php echo $publish; ?>" selected><?php echo funPublish($publish); ?></option>
                                                <option value="1">Publish</option>
                                                <option value="0">Draft</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="publish" class="form-label">Approved</label>
                                            <select class="form-control" name="approve" id="approve">
                                                <option value="<?php echo $publish; ?>" selected><?php echo funApprove($approve); ?></option>
                                                <option value="1">Approve</option>
                                                <option value="0">Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </form>
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
            <form action="pages.php" method="POST">
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