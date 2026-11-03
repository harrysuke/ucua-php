<?php
include('testFunctions.php');
$title = 'Explore';
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
    ORDER BY RAND() LIMIT 3";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $a .= '<div class="col-md-6 col-xl-3">
        <div class="card border-0">
            <div class="card-body">
                <h2 class="card-title"><a href="default.php?page=page&t=' . strtolower($row['title']) . '" class="text-dark">' . $row['title'] . '</a></h2>
                <p class="card-text">' . substr(strip_tags($row['content']), 0, 500) . '&hellip;</p>
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

function randomHero()
{
    $names = array(
        '<img src="img/blog/1.png" width="100" height="100" />',
        '<img src="img/blog/2.png" width="100" height="100" />',
        '<img src="img/blog/3.png" width="100" height="100" />',
        '<img src="img/blog/4.png" width="100" height="100" />',
        '<img src="img/blog/5.png" width="100" height="100" />',
        '<img src="img/blog/6.png" width="100" height="100" />',
        '<img src="img/blog/7.png" width="100" height="100" />',
        '<img src="img/blog/8.png" width="100" height="100" />',
        '<img src="img/blog/9.png" width="100" height="100" />',
        '<img src="img/blog/10.png" width="100" height="100" />',
        '<img src="img/blog/11.png" width="100" height="100" />',
        '<img src="img/blog/12.png" width="100" height="100" />',
        '<img src="img/blog/13.png" width="100" height="100" />',
        '<img src="img/blog/14.png" width="100" height="100" />',
        '<img src="img/blog/15.png" width="100" height="100" />',
        '<img src="img/blog/16.png" width="100" height="100" />',
        '<img src="img/blog/17.png" width="100" height="100" />',
        '<img src="img/blog/18.png" width="100" height="100" />',
        '<img src="img/blog/19.png" width="100" height="100" />',
        '<img src="img/blog/20.png" width="100" height="100" />',
        '<img src="img/blog/21.png" width="100" height="100" />',
        '<img src="img/blog/22.png" width="100" height="100" />',
        '<img src="img/blog/23.png" width="100" height="100" />',
        '<img src="img/blog/24.png" width="100" height="100" />',
        '<img src="img/blog/25.png" width="100" height="100" />',
        '<img src="img/blog/26.png" width="100" height="100" />',
        '<img src="img/blog/27.png" width="100" height="100" />',
        '<img src="img/blog/28.png" width="100" height="100" />',
        '<img src="img/blog/29.png" width="100" height="100" />'
    );
    return $names[rand(0, count($names) - 1)];
}

function showContents1()
{
    $c = '';
    global $db;
    $sql = "SELECT p.id, p.title, p.content, p.createby, p.createdate, u.Name FROM pages AS p LEFT JOIN user AS u ON u.staffId=p.createby ORDER BY p.createdate DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $c .= '<li>
              <a href="default.php?page=page&t=' . $row['title'] . '" class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top">
                ' . randomHero() . '
                <div class="col-lg-8">
                <div class="float-start me-2">
											<div class="avatar-xs align-self-center">
												<small class="avatar-title rounded-circle bg-primary-subtle text-primary">
													' . substr(trim($row['Name']), 0, 1) . '
												</small>
											</div>
										</div>
                                        <div class="float-start overflow-hidden">
                <p class="text-dark mb-1">' . $row['Name'] . '</p>
                </div>
                <div class="clearfix"></div>
                  <h3 class="mb-0 fw-bolder">' . $row['title'] . '</h3>
                  <p class="text-body-secondary mb-0">' . strip_tags(substr($row['content'], 0, 100)) . '&hellip;</p>
                  <small class="text-secondary">' . date('M d', strtotime($row['createdate'])) . '</small>
                </div>
              </a>
            </li>';
    }
    $stmt->close();
    return $c;
}

function showContents()
{
    $c = '';
    global $db;
    $sql = "SELECT p.id, p.title, p.content, p.createby, p.createdate, u.Name FROM pages AS p LEFT JOIN user AS u ON u.staffId=p.createby ORDER BY p.createdate DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $c .= '<div class="card">
          <div class="card-body">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                                        <div class="avatar-sm align-self-center">
                                                                            <span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
                                                                            ' . substr(trim($row['Name']), 0, 1) . '
                                                                            </span>
                                                                        </div>
                                                                        <span class="user-status"></span>
                                                                    </div>
                                                                    
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h5 class="text-truncate font-size-14 mb-1">' . $row['Name'] . '</h5>
                                                                        <p class="text-truncate text-muted mb-0">' . date('M d, Y', strtotime($row['createdate'])) . '</p>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-start mt-4">
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h5 class="font-size-15 text-truncate">
                                                                        <a href="#" class="text-body">' . $row['title'] . '</a>
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                                <div class="pt-3">
                                                                    <p class="text-muted">' . strip_tags(substr($row['content'], 0, 400)) . '&hellip;</p>
                                                                    <div>
                                                                        <a href="default.php?page=page&t=' . $row['title'] . '" class="text-primary">Read more</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </div>';
    }
    $stmt->close();
    return $c;
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
            <div class="page-content mt-0">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">
                                    Explore
                                    <span class="fs-4"><a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a></span>
                                </h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Explore</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-8">
                            <?php echo showContents(); ?>
                        </div>
                        <!-- end col -->

                        <div class="col-lg-4 ">
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="">
                                            <h5 class="card-title mb-3">More to explore</h5>
                                            <div class="px-2">
                                                <?php echo showClouds(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card -->

                                <?php //echo funRelatedPost2(); 
                                ?>
                                <div class="d-grid bm-4">
                                    <a href="#"
                                        class="btn btn-sm btn-light font-size-14 text-center">
                                        <span>View more..</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div> <!-- end row -->

                    <div class="row border-top">
                        <?php //echo funRelatedPost(); 
                        ?>
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