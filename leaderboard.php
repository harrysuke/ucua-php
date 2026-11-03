<?php
$title = 'Leaderboard';
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

function funEmoji()
{
  $e = array(
    '<i class="fa-regular fa-face-smile me-3"></i>',
    '<i class="fa-regular fa-face-surprise me-3"></i>',
    '<i class="fa-regular fa-face-smile-wink me-3"></i>',
    '<i class="fa-regular fa-face-rolling-eyes me-3"></i>',
    '<i class="fa-regular fa-face-kiss-wink-heart me-3"></i>',
    '<i class="fa-regular fa-face-grin-tongue me-3"></i>',
    '<i class="fa-regular fa-face-grin-stars me-3"></i>',
    '<i class="fa-regular fa-face-grin-beam-sweat me-3"></i>'
  );
  return $e[rand(0, count($e) - 1)];
}

function funYear()
{
  global $db;
  $y = '';
  $sql = "SELECT YEAR(observerDatetime) as tahun FROM `ucuaform` WHERE YEAR(observerDatetime) <> 0 GROUP BY YEAR(observerDatetime)";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $y .= '<option value="default.php?page=Leaderboards&y=' . $row['tahun'] . '">' . $row['tahun'] . '</option>';
  }
  return $y;
}

function funLeader($r)
{
  global $db;
  $sql = "SELECT ranking, staffId, Name, points FROM ( SELECT u.staffId, u.Name, (SELECT COUNT(*) FROM ucuaform AS f WHERE f.staffId = u.staffId AND YEAR(f.observerDatetime)=YEAR(CURRENT_DATE)) AS points, @N := @N + 1 AS ranking FROM user AS u, (SELECT @N := 0) m ORDER BY points DESC ) AS ranked_data WHERE ranking = '" . $r . "' ";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return '<div class="card-body">
    <h5 class="mb-4 text-white"><i class="fa-solid fa-trophy fa-xl me-3"></i>' . ucwords(strtolower($row['Name'])) . '</h5>
    <p class="card-text fw-bold">Rank ' . $row['ranking'] . ' with <span class="counter-value" data-target="' . $row['points'] . '">0</span> points.</p>
  </div>';
}

function funLeadless($r, $t)
{
  global $db;
  $sql = "SELECT ranking, staffId, Name, points FROM ( SELECT u.staffId, u.Name, (SELECT COUNT(*) FROM ucuaform AS f WHERE f.staffId = u.staffId AND YEAR(f.observerDatetime)=YEAR(CURRENT_DATE)) AS points, @N := @N + 1 AS ranking FROM user AS u, (SELECT @N := 0) m ORDER BY points DESC ) AS ranked_data WHERE ranking = '" . $r . "' ";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  return '<div class="card-body">
    <h5 class="mb-4 ' . $t . '">' . funEmoji() . ' ' . ucwords(strtolower($row['Name'])) . '</h5>
    <p class="card-text">Rank ' . $row['ranking'] . ' with <span class="counter-value" data-target="' . $row['points'] . '">0</span> points.</p>
  </div>';
}

function funLeaderboard()
{
  global $db;
  $l = '';
  $sql = "SELECT *
FROM (
    SELECT
        ROW_NUMBER() OVER (ORDER BY points DESC) AS ranking,
        staffId,
        Name,
        points
    FROM (
        SELECT u.staffId, u.Name, COUNT(*) AS points
        FROM user AS u
        JOIN ucuaform AS f
              ON f.staffId = u.staffId
             AND YEAR(f.observerDatetime) = YEAR(CURDATE())
        GROUP BY u.staffId, u.Name
    ) AS inner_q
) AS ranked
WHERE ranking <= 10;";
  $result = mysqli_query($db, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $l .= '<div class="activity-list mb-0">
  <div class="activity-icon avatar-md">
    <div class="avatar-md align-self-center">
      <span class="avatar-title rounded-circle  bg-primary-subtle text-primary">
      ' . substr(trim($row['Name']), 0, 1) . '
      </span>
    </div>
  </div>
  <div class="timeline-list-item">
      <div class="d-flex">
          <div class="flex-grow-1 me-4">
              <h5 class="font-size-14 mb-1"><a href="default.php?page=profile&sid=' . base64_encode($row['staffId']) . '">' . ucwords(strtolower($row['Name'])) . '</a></h5>
              <p class="text-truncate text-muted font-size-13">Ranking ' . $row['ranking'] . '</p>
          </div>
          <div class="flex-shrink-0 text-end me-3">
              <h6 class="mb-1">Point</h6>
              <span class="counter-value" data-target="' . $row['points'] . '">0</span>
          </div>
      </div>
  </div> 
</div>';
  }
  return $l;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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
                <div class="float-start">
                  <h4 class="mb-sm-0 font-size-18">
                    <?php echo $title; ?>
                    <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                  </h4>
                  <p class="card-title-desc">This leaderboard ranks the current winners for the year.</p>
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
            <div class="col-xl-8">
              <!-- card -->
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-wrap align-items-center mb-4">
                    <h5 class="card-title text-secondary me-2">Ranks</h5>
                    <div class="ms-auto">
                      <select class="form-select form-select-sm" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                        <option value="" selected="">Filter</option>
                        <?php echo funYear(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="px-3" data-simplebar>
                    <div class="activity-wid mb-0">
                      <?php echo funLeaderboard(); ?>
                    </div>
                  </div>
                </div>
                <!-- end card body -->
              </div>
              <!-- end card -->
            </div>
            <div class="col-xl-4">
              <div class="card bg-warning border-warning text-white-50">
                <?php echo funLeader(1); ?>
              </div>
              <div class="card bg-warning-subtle text-warning-emphasis ">
                <?php echo funLeadless(2, 'text-warning-emphasis'); ?>
              </div>
              <div class="card bg-light-subtle">
                <?php echo funLeadless(3, 'text-primary'); ?>
              </div>
            </div>
            <!-- end col -->
          </div><!-- end row -->


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

</body>

</html>