<?php
$title = 'UC Form';
include('testFunctions.php');

//Authentication
if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

//Pagination
$_SESSION['records-limit'] = 10;
$limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
$p = (isset($_GET['p']) && is_numeric($_GET['p'])) ? $_GET['p'] : 1;
$paginationStart = ($p - 1) * $limit;

function funURL($type, $id)
{
  if ($type == 'Unsafe Condition') {
    $url = 'default.php?page=newuiuc_form&id=' . base64_encode($id);
  } else {
    $url = 'default.php?page=newuiua_form&id=' . base64_encode($id);
  }
  return $url;
}

//LIMIT $paginationStart, $limit
function funSearch($s)
{
  try {
    global $db;
    $http2 = empty($_SERVER['HTTPS']) ? 'http' : 'https';
    $option = '';
    $sql1 = "SELECT f.Id, f.evidenceImage, f.conditionDetails, f.form_type FROM ucuaform AS f WHERE f.conditionDetails LIKE '%" . $s . "%' ORDER BY f.conditionDetails ";
    $result1 = $db->query($sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
      if (!empty($row1['conditionDetails'])) {
        $option .= '<div class="col-12 results">
          <div class="pt-4 border-bottom">
            <a class="d-block h4 mb-0" href="' . funURL($row1['form_type'], $row1['Id']) . '">' . substr(trim($row1['conditionDetails']),0,100) . '&hellip;</a>
            <a class="page-url text-primary" href="#">' . $http2 . '://' . $_SERVER['SERVER_NAME'] . '/ucua300/uploads/' . $row1['evidenceImage'] . '</a>
            <p class="page-description mt-1 w-75 text-muted">' . $row1['conditionDetails'] . '</p>
          </div>
        </div>';
      }
    }
  } catch (mysqli_sql_exception $e) {
    $option .= '<div class="col-12 results">
    <div class="pt-4 border-bottom">
      <a class="d-block h4 mb-0" href="#">' . $e . '</a>
      <a class="page-url text-primary" href="#">' . $http2 . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '</a>
      <p class="page-description mt-1 w-75 text-muted">' . $e . '</p>
    </div>
  </div>';
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

$allRecrods = funTotalRecord($_GET['s']);
$totoalPages = ceil($allRecrods / $limit);
$prev = $p - 1;
$next = $p + 1;

include('inc_header.php');
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

          <div class="row mb-3 text-center">
            <div class="col-lg-12">


              <div class="card">
                <div class="card-body text-start">
                  <div class="row">

                    <div class="col-12 mb-5">
                      <h2>Search Result For<u class="ml-2">"
                          <?php echo $_GET['s']; ?>"
                        </u></h2>
                      <p class="text-muted">About <?php echo $allRecrods;?> results (0.52 seconds)</p>
                    </div>
                    <div id="content">
                    <?php echo funSearch($_GET['s']); ?>
                  </div>
                  </div>
                </div>
              </div>

              <!-- Pagination -->
              <div id="page-selection">Pagination goes here</div>
              <!--nav class="col-12 " aria-label="Page navigation">
                <ul class="pagination mt-5 float-end">
                  <li class="page-item <?php if ($p <= 1) { echo 'disabled';} ?>">
                    <a class="page-link"
                      href="index.php?page=search&s=<?php echo $_GET['s'];?>&p=<?php if ($p <= 1) { echo '#';} else { echo "?p=" . $prev;} ?>">
                      <i class="fa-solid fa-chevron-left"></i>
                    </a>
                  </li>

                  <?php for ($i = 1; $i <= $totoalPages; $i++): ?>

                    <li class="page-item <?php if ($p == $i) { echo 'active';} ?>">
                      <a class="page-link" href="index.php?page=search&s=<?php echo $_GET['s'];?>&p=<?= $i; ?>"> <?= $i; ?> </a>
                    </li>

                  <?php endfor; ?>

                  <li class="page-item <?php if ($p >= $totoalPages) { echo 'disabled';} ?>">
                    <a class="page-link"
                      href="index.php?page=search&s=<?php echo $_GET['s'];?>&p=<?php if ($p >= $totoalPages) { echo '#';} else { echo "?p=" . $next;} ?>">
                      <i class="fa-solid fa-chevron-right"></i>
                    </a>
                  </li>
                </ul>
              </nav-->



            </div>
            <!-- container-fluid -->
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
        // init bootpag
        $('#page-selection').bootpag({
            total: 10
        }).on("page", function(event, /* page number here */ num){
             $("#content").html("Insert content"); // some ajax content loading...
        });
    </script>
</body>
</html>