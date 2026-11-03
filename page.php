<?php
$title = 'Pages';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function showClouds()
{
    global $db;
    $a = '';
    $sql = "SELECT id, title, content FROM pages ORDER BY title";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $a .= '<a href="default.php?page=page&t=' . strtolower($row['title']) . '" class="font-size-17"><span class="btn btn-rounded btn-outline-secondary waves-effect waves-light mb-1">' . ucwords(strtolower($row['title'])) . '</span></a>&nbsp;';
    }
    return $a;
}

function funRelatedPost()
{
    global $db;
    $a = '';
    $sql = "SELECT p.id, p.title, p.content, p.createby, p.createdate, u.Name FROM pages AS p 
    LEFT JOIN user AS u ON u.staffId=p.createby
    WHERE p.isdelete=0
    ORDER BY RAND() LIMIT 3";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $a .= '<div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title"><a href="default.php?page=page&t=' . strtolower($row['title']) . '" class="text-dark">' . $row['title'] . '</a></h2>
                <p class="card-text">' . substr(strip_tags($row['content']), 0, 100) . '&hellip;</p>
                <p class="card-text">
                <span class="text-secondary">By ' . $row['Name'] . '</span>
                </p>
            </div>
        </div>
    </div>';
    }
    return $a;
}

function funRelatedPost2()
{
    global $db;
    $a = '';
    $sql = "SELECT p.id, p.title, p.content, p.createby, p.createdate, u.Name FROM pages AS p 
    LEFT JOIN user AS u ON u.staffId=p.createby
    WHERE p.isdelete=0
    ORDER BY RAND() LIMIT 3";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $a .= '<div class="row">
        <div class="col-xl-12">
            <div class="card border-0">
                <div class="card-body">
                <h2 class="card-title"><a href="default.php?page=page&t=' . strtolower($row['title']) . '" class="text-dark">' . $row['title'] . '</a></h2>
                <p class="card-text">' . substr(strip_tags($row['content']), 0, 500) . '&hellip;</p>
                <p class="card-text">
                <span class="text-secondary">By ' . $row['Name'] . '</span>
                </p>
            </div>
        </div>
    </div>
    </div>';
    }
    return $a;
}

try {
    if (!empty($_GET['t'])) {
        $t = trim($_GET['t']);
    } else {
        $t = 1;
    }
    $sql = "SELECT p.id, p.title, p.content, p.createby, p.createdate, u.Name FROM pages AS p 
    LEFT JOIN user AS u ON u.staffId=p.createby
    WHERE p.title = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $t);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $title = $row['title'];
        $content = $row['content'];
        $createby = $row['Name'];
        $createdate = $row['createdate'];
    } else {
        $id = '';
        $title = 'Page not found';
        $content = 'The page you\'re looking for doesn\'t exist.';
        $createby = 'BOTS';
        $createdate = date('Y-m-d');
    }
    $stmt->close();
} catch (Exception $e) {
    die($e->getMessage());
}

include('inc_header.php');
?>

</head>

<body class="pace-done sidebar-enable" data-new-gr-c-s-check-loaded="8.906.0" data-gr-ext-installed="" data-sidebar="brand" data-sidebar-size="sm">
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
            <div class="page-content mt-0">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8 border-end">
                            <div class="card border-0">
                                <div class="position-relative">
                                    <!--img src="img/undraw_Reading_time_re_phf7.png" alt="" class="card-img-top img-fluid" style="width: 100%; height: 250px; object-fit: cover;"-->
                                    <h1 class="text-dark fw-bold">
                                        <?php echo ucwords(strtolower($title)); ?>
                                        <span class="fs-4"><a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a></span>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis fa-2xs"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                                <li><a class="dropdown-item" href="default.php?page=cms">Manage posts</a></li>
                                                <li><a class="dropdown-item" href="default.php?page=cms_edit&id=<?php echo $id; ?>">Edit post</a></li>
                                                <li><a class="dropdown-item" href="default.php?page=cms&del=<?php echo $id; ?>">Delete post</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item" href="#">Report post</a></li>
                                            </ul>
                                        </div>
                                    </h1>
                                    <div class="d-flex py-3">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-md align-self-center">
                                                <span class="avatar-title rounded-circle  bg-light text-dark">
                                                    <?php echo substr(trim($createby), 0, 1); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="font-size-14 mb-1 text-dark">
                                                <?php echo $createby; ?>
                                            </h5>
                                            <p class="text-secondary">
                                                Published <?php echo date('M d, Y', strtotime($createdate)); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="mt-0">
                                            <p class="text-muted font-size-14">
                                                <?php echo $content; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="d-flex py-3 mt-5">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-md align-self-center">
                                                <span class="avatar-title rounded-circle  bg-light text-dark">
                                                    <?php echo substr(trim($createby), 0, 1); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="font-size-14 mb-1 text-dark">
                                                Written by
                                                <?php echo $createby; ?>
                                            </h5>
                                            <p class="text-secondary">
                                                Editor for pages
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->

                        <div class="col-lg-4 ">
                            <div class="row">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <div class="search-box">
                                            <h5 class="card-title mb-3">Search</h5>
                                            <div class="position-relative px-2">
                                                <form method="get" action="default.php">
                                                    <input type="text" name="t"
                                                        class="form-control rounded bg-light border-light"
                                                        placeholder="Search...">
                                                    <i class="mdi mdi-magnify search-icon"></i>
                                                    <input type="submit" id="submitbtn" style="display:none;" />
                                                    <input type="hidden" name="page" value="page" />
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card -->

                                <?php echo funRelatedPost2(); ?>

                                <div class="mt-5">
                                    <h5 class="card-title ms-4 mb-3">More to explore</h5>
                                    <div class="px-2 ms-3">
                                        <?php echo showClouds(); ?>
                                    </div>
                                </div>
                                <div class="d-grid bm-4 mt-4">
                                    <a href="default.php?page=page-list"
                                        class="btn btn-sm btn-light font-size-14 text-center">
                                        <span>See more..</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div> <!-- end row -->

                    <div class="row mt-2">
                        <div class="col-8">
                            <div class="row">
                                <h6>Related Articles</h6>
                                <?php echo funRelatedPost();
                                ?>
                            </div>
                        </div>
                    </div>



                </div>
                <!-- End container-fluid -->
                <?php include('inc_footer.php'); ?>
            </div>
            <!-- end page-content-->

        </div>
        <!-- END main-content -->


        <!-- Right Sidebar -->
        <?php include('inc_rightbar.php'); ?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <?php include('inc_scripts.php'); ?>
    </div><!--End layout-wrapper-->
</body>

</html>