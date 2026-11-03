<?php
include('testFunctions.php');
$start_time = microtime(true);
$title = 'Image';

//Authentication
if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

//url ucua
function funURL($type, $id)
{
  if ($type == 'Unsafe Condition') {
    $url = 'default.php?page=newuiuc_form&id=' . base64_encode($id);
  } else {
    $url = 'default.php?page=newuiua_form&id=' . base64_encode($id);
  }
  return $url;
}

//Search
function funSearch($s, $paginationStart, $limit)
{
  try {
    global $db;
    $http2 = empty($_SERVER['HTTPS']) ? 'http' : 'https' . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $option = '';
    $sql1 = "SELECT f.Id, p.ucuaform_Id, p.id AS pid, p.evidenceImage, f.conditionDetails, f.form_type FROM ucuaform AS f 
    LEFT JOIN  ucuaphoto AS p ON p.ucuaform_Id=f.Id
    WHERE f.conditionDetails LIKE '%" . trim($s) . "%' AND p.type='Photo Evidence' ORDER BY f.conditionDetails LIMIT $paginationStart, $limit";
    $result1 = $db->query($sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
      if (file_exists($row1['evidenceImage'])) {
        $option .= '<div class="col-sm-6 col-lg-4">
        <div class="card">
            <a class="d-block h4 mb-0" href="' . funURL($row1['form_type'], $row1['ucuaform_Id']) . '">
              <img src="' . $row1['evidenceImage'] . '" class="card-img-top" alt="' . $row1['pid'] . '">
            </a>
            <div class="card-body">
              <h5 class="card-title">' . $row1['form_type'] . '</h5>
              <p class="card-text fs-sm text-muted">' . $row1['conditionDetails'] . '</p>
              <!--p>' . $_SERVER['DOCUMENT_ROOT'] . '/' . $row1['evidenceImage'] . '</p-->
            </div>
        </div>
      </div><!-- end col -->';
      }
    }
  } catch (mysqli_sql_exception $e) {
    $option .= '<div class="col-sm-6 col-lg-4">
    <div class="card">
        <img src="./cards_files/img-3.jpg" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">Card title that wraps to a new line</h5>
        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
      </div>
    </div>
</div><!-- end col -->';
  }

  return $option;
}

//Get total records
function funTotalRecord($s)
{
  global $db;
  $sql = "SELECT count(id) AS counter FROM ucuaform WHERE conditionDetails LIKE '%" . $s . "%' ";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return $row['counter'];
}

//Pagination
$_SESSION['records-limit'] = 10;
$limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
$p = (isset($_GET['p']) && is_numeric($_GET['p'])) ? $_GET['p'] : 1;
$paginationStart = ($p - 1) * $limit;
$allRecrods = funTotalRecord($_GET['s']);
$totalPages = ceil($allRecrods / $limit);
$prev = $p - 1;
$next = $p + 1;

include('inc_header.php');

$end_time = microtime(true);
$execution_time = ($end_time - $start_time);
?>

</head>

<body class="pace-done" data-new-gr-c-s-check-loaded="8.904.0" data-gr-ext-installed="" data-sidebar="brand">
  <div class="pace pace-inactive">
    <div class="pace-progress" style="transform: translate3d(100%, 0px, 0px)" data-progress-text="100%"
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
              <div class="page-title-box d-sm-flex justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Search</h4>
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);" onclick="history.back()">Home</a></li>
                    <li class="breadcrumb-item active">Search</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- end page title -->

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link " data-bs-toggle="" href="default.php?page=search&s=<?php echo $_GET['s']; ?>" role="tab">
                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                <span class="d-none d-sm-block"><i class="fa-solid fa-magnifying-glass"></i> Web</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="" href="default.php?page=simages&s=<?php echo $_GET['s']; ?>" role="tab">
                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                <span class="d-none d-sm-block"><i class="fa-regular fa-image"></i> Images</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="" href="default.php?page=svideos&s=<?php echo $_GET['s']; ?>" role="tab">
                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                <span class="d-none d-sm-block"><i class="fa-solid fa-play"></i> Videos</span>
              </a>
            </li>
          </ul>
          <!-- End Nav tabs -->
          <div class="row mt-2">
            <div class="col-lg-12">
              <h2>Search Result <u class="ml-2">"
                  <?php echo $_GET['s']; ?>"
                </u></h2>
              <p class="text-muted">About
                <?php echo $allRecrods; ?> results (<?php echo number_format($execution_time, 3); ?> seconds)
              </p>
              <div class="row" data-masonry="{&quot;percentPosition&quot;: true }">
                <?php echo funSearch($_GET['s'], $paginationStart, $limit); ?>
              </div>


              <!-- Pagination -->
              <nav aria-label="Page navigation">
                <ul class="pagination mt-5">
                  <li class="page-item <?php if ($p <= 1) {
                                          echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="index.php?page=simages&s=<?php echo $_GET['s']; ?>&p=
                      <?php if ($p <= 1) {
                        echo '#';
                      } else {
                        echo trim($prev);
                      } ?>">
                      <span aria-hidden="true">&laquo;</span> Previous
                    </a>
                  </li>

                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                    <!--li class="page-item <?php if ($p == $i) {
                                              echo 'active';
                                            } ?>">
                      <a class="page-link" href="index.php?page=simages&s=<?php echo $_GET['s']; ?>&p=<?= $i; ?>"> <?= $i; ?> </a>
                    </li-->

                  <?php endfor; ?>

                  <li class="page-item <?php if ($p >= $totalPages) {
                                          echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="index.php?page=simages&s=<?php echo $_GET['s']; ?>&p=
                      <?php if ($p >= $totalPages) {
                        echo '#';
                      } else {
                        echo trim($next);
                      } ?>">
                      Next <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
            <!-- End col -->


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