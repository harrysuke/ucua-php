<?php
$title = 'Favorites';
include('testFunctions.php');

//Authentication
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function showFavorite()
{
    global $db;
    $d = '';
    try {
        $sid = trim($_SESSION['user']['staffId']);
        $sql = "SELECT id, label, url FROM  favorites WHERE createby = ? ";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $sid);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $d .= '<tr>
                                        <th scope="row">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <a href="' . base64_decode($row['url']) . '">
                                            ' . $row['label'] . '
                                            </a>
                                        </th>
                                        <td class="text-end">
                                            <a href="default.php?page=favorite&del=' . $row['id'] . '" class="btn btn-soft-danger waves-effect waves-light"><i class="fa-solid fa-trash-can"></i></a>
                                        </td>
                                    </tr>';
        }
        $stmt->close();
        return $d;
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

if (isset($_GET['add'])) {
    try {
        $label = base64_decode($_GET['label']);
        $url0 = $_GET['url'];
        $url = base64_decode($_GET['url']);
        $sid = trim($_SESSION['user']['staffId']);
        $date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO favorites (label, url, createby, createdate) VALUES (?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssss", $label, $url0, $sid, $date);
        $stmt->execute();
        $stmt->close();
        echo '<script>alert("' . $label . ' have been added to favorite");window.location.href = "' . $url . '";</script>';
        exit;
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

if (isset($_GET['del'])) {
    try {
        $sql = "DELETE FROM favorites WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $_GET['del']);
        $stmt->execute();
        $stmt->close();
        header("Location: default.php?page=favorite");
        exit;
    } catch (Exception $e) {
        die($e->getMessage());
    }
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
                                    <h4 class="mb-sm-0 font-size-18">
                                        <?php echo $title; ?>
                                    </h4>
                                    <p class="card-title-desc">Add boards, docs, or dashboards to your favorites for quick and easy access.</p>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table table-nowrap align-middle justify-content-center mb-4">
								<tr>
                                        <th scope="row">
										<i class="bi bi-pin-angle-fill text-primary"></i>
                                        <a href="default.php?page=get-started">
                                            Getting Started
                                            </a>
                                        </th>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-soft-primary waves-effect waves-light"><i class="bi bi-pin-angle-fill"></i></a>
                                        </td>
                                    </tr>
                                    <?php echo showFavorite(); ?>
                                </table>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                    <div aria-hidden="true">
                        <div class="placeholder-glow col-6">
                            <table class="table table-nowrap align-middle justify-content-center mb-0"></table>
                        </div>
                    </div>

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