<?php
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

function showDashboard()
{
  global $db;
  $d = '<li class="dropdown-header">Available dashboards</li>';
  try {
    $sid = trim($_SESSION['user']['staffId']);
    $sql = "SELECT id, dashboard_name, dashboard_desc FROM dashboard WHERE createby = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $sid);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $d .= '<li><a class="dropdown-item" href="default.php?page=dashboards&id=' . $row['id'] . '">' . $row['dashboard_name'] . '</a></li>';
    }
    $stmt->close();
    return $d;
  } catch (Exception $e) {
    die($e->getMessage());
  }
}

function funActivity($id)
{
  global $db;
  $a = '';
  $y = date('Y');
  $m = date('m');
  $sql = "SELECT h.id, h.ucuaid, h.action, h.activity, h.status, h.datetimestamp, u.Name FROM history AS h
  LEFT JOIN user AS u ON u.staffid=h.staffId 
  WHERE h.staffId=? AND YEAR(h.datetimestamp)=? AND h.action NOT IN('LOGIN','LOGOUT')
  ORDER BY h.datetimestamp DESC LIMIT 3";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ss", $id, $y);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $a .= '<li class="activity-list activity-border">
                        <div class="activity-icon avatar-md">
                          <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            ' . substr(trim($row['Name']), 0, 1) . '
                          </span>
                        </div>
                        <div class="timeline-list-item">
                          <div class="d-flex">
                            <div class="flex-grow-1 overflow-hidden me-4">
                              <h5 class="font-size-14 mb-1">' . $row['Name'] . '</h5>
                              <p class="text-truncate text-muted font-size-13">' . $row['activity'] . '</p>
                            </div>
                            <div class="flex-shrink-0 text-end me-3">
                              <h6 class="mb-1">' . $row['status'] . '</h6>
                              <div class="font-size-13">' . date('M d, Y', strtotime($row['datetimestamp'])) . '</div>
                            </div>
                          </div>
                        </div>
                      </li>';
  }
  $stmt->close();
  return $a;
}
function showFavorite()
{
  global $db;
  $f = '';
  try {
    $sid = trim($_SESSION['user']['staffId']);
    $sql = "SELECT id, label, url FROM  favorites WHERE createby = ? ORDER BY RAND() LIMIT 3";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $sid);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $f .= '<div class="col-xl-4 col-md-6">
                  <a href="' . base64_decode($row['url']) . '">
                    <div class="card border border-light bg-light text-dark">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col-2">
                            <i class="bi bi-link-45deg fa-2x"></i>
                          </div>
                          <div class="col-10 mr-2">
                            <div class="h5 mb-0 font-weight-bold text-truncate text-dark">
                              ' . $row['label'] . '
                            </div>
                            <div class="text-xs font-weight-bold text-truncate mb-0">
                              <span title="' . $row['label'] . '" class="text-truncate">' . $row['url'] . '</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>';
    }
    $f .= '<div class="col-xl-4 col-md-6">
                  <a href="default.php?page=favorite">
                    <div class="card border border-light bg-light text-dark">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col-2">
                            <i class="bi bi-star fa-2x"></i>
                          </div>
                          <div class="col-10 mr-2">
                            <div class="h5 mb-0 font-weight-bold text-dark">
                              Favorites
                            </div>
                            <div class="text-xs font-weight-bold text-truncate mb-0">
                              <span title="Show all your favorite shortcuts." class="text-truncate">Show all your favorite shortcuts.</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>';
    $stmt->close();
    return $f;
  } catch (Exception $e) {
    die($e->getMessage());
  }
}
//THIS WIDGET IS FOR USER
function showVideo($t)
{
  global $db;
  $option = '';
  $sql1 = "SELECT id, title, url FROM tutorials WHERE type = '" . trim($t) . "' ORDER BY id LIMIT 3";
  $result1 = $db->query($sql1);
  while ($row1 = mysqli_fetch_array($result1)) {
    $option .= '<div class="col-xl-4 col-sm-6">
      <div class="card bg-dark border-dark text-light">
        <div class="embed-responsive ratio ratio-1x1">
          <iframe class="embed-responsive-item" src="' . $row1['url'] . '"
            title="YouTube video player" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen></iframe>
        </div>
        <div class="card-body">
          <h5 class="card-text text-light">' . $row1['title'] . '</h5>
        </div>
      </div>
    </div>';
  }
  return $option;
}

try {
  $sid = trim($_SESSION['user']['staffId']);
  $sql = "SELECT id, dashboard_name, dashboard_desc FROM dashboard WHERE createby = ? ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("s", $sid);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  if ($row) {
    $dashboard_id = $row['id'];
    $dashboard_name = $row['dashboard_name'];
    $dashboard_desc = $row['dashboard_desc'];
  } else {
    $dashboard_id = 0;
    $dashboard_name = 'Dashboard';
    $dashboard_desc = '';
  }
  $stmt->close();
} catch (Exception $e) {
  die($e->getMessage());
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$title = $dashboard_name;
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$_SESSION['page'] = $_SERVER['HTTP_REFERER'];
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
          <div class="row mb-0">
            <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="float-start">
                  <h4 class="mb-sm-0 font-size-18">
                    <?php echo $dashboard_name; ?>
                    <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($dashboard_name); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                  </h4>
                  <p>Welcome to your <?php echo $_SESSION['company']['brandName']; ?> <?php echo $_SESSION['user']['rolename']; ?> account</p>
                </div>
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- end page title -->

          <!-- start page title -->
          <div class="row mt-0">
            <div class="col-8">
              <div class="page-title-box d-sm-flex justify-content-between">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="" href="default.php?page=dashboard" role="tab">
                      <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                      <span class="d-none d-sm-block">Quick Start</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard_overview" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                      <span class="d-none d-sm-block">Overview</span>
                    </a>
                  </li>
                  <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
                    <li class="nav-item">
                      <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard-chart" role="tab">
                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                        <span class="d-none d-sm-block">KPI</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard-kpi" role="tab">
                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                        <span class="d-none d-sm-block">Summary</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link " data-bs-toggle="" href="default.php?page=dashboard-usage" role="tab">
                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                        <span class="d-none d-sm-block">Usage</span>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">More&hellip;</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="default.php?page=feeds">Feeds</a></li>
                        <li><a class="dropdown-item" href="default.php?page=leaderboard">Leaderboard</a></li>
                        <li><a class="dropdown-item" href="default.php?page=favorite">Favorites</a></li>
                        <li>
                          <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="default.php?page=workspaces">Workspaces</a></li>
                      </ul>
                    </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <div class="col-md-4">
              <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">
                  <div>
                    <a href="default.php?page=dashboard_new" class="btn btn-soft-light waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Create new dashboard"><i class="bi bi-plus-lg"></i></a>
                  </div>
                  <?php if (!empty($dashboard_id)) { ?>
                    <div>
                      <a href="default.php?page=dashboard_edit&id=<?php echo $dashboard_id; ?>" class="btn btn-soft-light waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit dashboard"><i class="bi bi-pencil"></i></a>
                    </div>
                    <div>
                      <a href="default.php?page=dashboard&del=<?php echo $dashboard_id; ?>" class="btn btn-soft-light waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Delete dashboard"><i class="bi bi-trash"></i></a>
                    </div>
                  <?php } ?>
                  <div class="dropdown">
                    <a class="btn btn-link text-muted py-1 font-size-16 shadow-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <?php echo showDashboard(); ?>
                      <li><a class="dropdown-item" href="default.php?page=dashboard_new">New Dashboard</a></li>
                      <?php if (!empty($dashboard_id)) { ?>
                        <li><a class="dropdown-item" href="#">Edit Dashboard</a></li>
                      <?php } ?>
                      <li class="dropdown-divider"></li>
                      <li><a class="dropdown-item" href="default.php?page=dashboard_manager">Manage Dashboards</a></li>
                    </ul>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
          <!-- end page title -->

          <p class="text-secondary">Use the quick actions below to start right away.</p>

          <div class="row">
            <div class="col-md-8 themed-grid-col">
              <div class="row">
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-4 col-md-6">
                  <a href="default.php?page=uc_form">
                    <div class="card border border-primary bg-primary text-light">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col-2">
                            <i class="fas fa-building fa-2x"></i>
                          </div>
                          <div class="col-10 mr-2">
                            <div class="h5 mb-0 font-weight-bold text-light">
                              Unsafe Condition
                            </div>
                            <div class="text-xs font-weight-bold text-truncate mb-0">
                              <span title="Start submitting new unsafe condition" class="text-truncate">Start submitting new unsafe condition.</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-4 col-md-6">
                  <a href="default.php?page=ua_form">
                    <div class="card border border-success bg-success text-white">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col-2">
                            <i class="fas fa-running fa-2x text-white"></i>
                          </div>
                          <div class="col-10 mr-2">
                            <div class="h5 mb-0 font-weight-bold text-white">
                              Unsafe Action
                            </div>
                            <div class="text-xs font-weight-bold text-white text-truncate mb-0">
                              <span title="Start submitting new unsafe action" class="text-truncate">Start submitting new unsafe action.</span>
                            </div>

                          </div>

                        </div>
                      </div>
                    </div>
                  </a>
                </div>
                <!-- Earnings (Monthly) Card Example -->
                <!--div class="col-xl-4 col-md-6">
                  <a href="default.php?page=learn">
                    <div class="card border border-light bg-light waves-effect waves-light">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col-2">
                            <i class="fa-solid fa-book fa-2x text-dark"></i>
                          </div>
                          <div class="col-10 mr-2">
                            <div class="h5 mb-0 font-weight-bold text-dark">
                              Knowledge Base
                            </div>
                            <div class="text-xs font-weight-bold text-secondary text-truncate mb-0">
                              <span title="A comprehensive collection of articles, tutorials, and guides to help you get started" class="text-truncate">A collection of tutorials, & guides.</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div-->
                <!-- end col -->
                <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                  <!--div class="col-xl-4 col-md-6">
                    <a href="default.php?page=admin_user_management">
                      <div class="card border border-light bg-primary-subtle">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col-2">
                              <i class="fa-solid fa-user fa-2x text-dark"></i>
                            </div>
                            <div class="col-10 mr-2">
                              <div class="h5 mb-0 font-weight-bold text-dark">
                                Users
                              </div>
                              <div class="text-xs font-weight-bold text-dark text-truncate mb-0">
                                <span title="Add/update users, manage their details, or change roles." class="text-truncate">Add/update users, manage their details, or change roles.</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-xl-4 col-md-6">
                    <a href="default.php?page=admin_user_roles">
                      <div class="card border border-light bg-success-subtle">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col-2">
                              <i class="fa-solid fa-user-group fa-2x text-dark"></i>
                            </div>
                            <div class="col-10 mr-2">
                              <div class="h5 mb-0 font-weight-bold text-dark">
                                Roles
                              </div>
                              <div class="text-xs font-weight-bold text-dark text-truncate mb-0">
                                <span title="Create/update roles, manage their details, or change roles. Add/remove permissions, or assign roles." class="text-truncate">Create/update roles, manage their details, or change roles. Add/remove permissions, or assign roles.</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                </div>
                  <div class="col-xl-4 col-md-6">
                    <a href="default.php?page=settings_overview">
                      <div class="card border border-light bg-light">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col-2">
                              <i class="fa-solid fa-screwdriver-wrench fa-2x text-dark"></i>
                            </div>
                            <div class="col-10 mr-2">
                              <div class="h5 mb-0 font-weight-bold text-dark">
                                Settings
                              </div>
                              <div class="text-xs font-weight-bold text-secondary text-truncate mb-0">
                                <span title="Manage configuration settings." class="text-truncate">Manage configuration settings.</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div-->
                <?php } ?>
                <?php echo showFavorite(); ?>
              </div>
              <!-- end row -->



              <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                <!-- card -->
                <!--div class="card">
                <div class="card-header text-start">
                  <h5 class="card-title">Top 5 Frequent Questions</h5>
                </div>
                <div class="card-body">
                  <?php
                  $sql = "SELECT * FROM chatbot_db.questions where id in (SELECT question_id from chatbot_db.frequent_asks) ";
                  $questions = mysqli_query($db, $sql);
                  $list = array();
                  while ($row = mysqli_fetch_array($questions)) {
                    $count = mysqli_query($db, "SELECT * FROM chatbot_db.frequent_asks where question_id = {$row['id']} ")->num_rows;
                    $list[$count] = array("count" => $count, "question" => $row['question']);
                  }
                  krsort($list);
                  $label = array();
                  $data = array();
                  $i = 5;
                  foreach ($list as $k => $v) {
                    $i--;
                    $label[] = $list[$k]['question'];
                    $data[] = $list[$k]['count'];
                    if ($i == 0)
                      break;
                  }
                  ?>
                  <div class="chart">
                    <canvas id="barChart"
                      style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                  <input type="hidden" name="label" id="label" value="<?php echo implode('\',\'', $label) ?>"/>
                  <input type="hidden" name="data" id="data" value="<?php echo implode(',', $data) ?>"/>
                </div>
              </div-->
              <?php } ?>

              <div class="row">
                <div class="col-6">
                  <div class="card">
                    <div class="card-body overflow-hidden position-relative">
                      <div>
                        <i class="fa-solid fa-link widget-box-1-icon"></i>
                      </div>
                      <h5 class="card-title text-start">Quick Links</h5>
                      <p>Links to common user functions.</p>
                      <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                          <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                        </div>
                        <div class="flex-grow-1">
                          <a href="default.php?page=admin_company" class="fw-bold">Manage Company</a>
                          <p class="card-text text-secondary text-truncate">For accessing company management screen.</p>
                        </div>
                      </div>
                      <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                          <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                        </div>
                        <div class="flex-grow-1">
                          <a href="default.php?page=admin_location" class="fw-bold">Manage Location</a>
                          <p class="card-text text-secondary text-truncate">For accessing location management screen.
                          </p>
                        </div>
                      </div>
                      <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
                        <div class="d-flex mb-3">
                          <div class="flex-shrink-0 me-3">
                            <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                          </div>
                          <div class="flex-grow-1">
                            <a href="default.php?page=admin_department" class="fw-bold">Manage Department</a>
                            <p class="card-text text-secondary text-truncate">For accessing department management screen.
                            </p>
                          </div>
                        </div>
                        <div class="d-flex mb-3">
                          <div class="flex-shrink-0 me-3">
                            <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                          </div>
                          <div class="flex-grow-1">
                            <a href="default.php?page=admin_offence" class="fw-bold">Manage Offence</a>
                            <p class="card-text text-secondary text-truncate">For accessing offence management screen.</p>
                          </div>
                        </div>
                      <?php } ?>
                      <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                        <div class="d-flex mb-3">
                          <div class="flex-shrink-0 me-3">
                            <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                          </div>
                          <div class="flex-grow-1">
                            <a href="default.php?page=admin_user_management" class="fw-bold">Manage User</a>
                            <p class="card-text text-secondary text-truncate">For accessing user management screen.</p>
                          </div>
                        </div>
                        <!--div class="d-flex mb-3">
                          <div class="flex-shrink-0 me-3">
                          <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                          </div>
                          <div class="flex-grow-1">
                            <a href="default.php?page=admin_user_roles" class="fw-bold">Manage Role</a>
                            <p class="card-text text-secondary text-truncate">For accessing role management screen.</p>
                          </div>
                        </div-->
                      <?php } ?>
                      <div class="d-grid bm-4 ps-2 pe-2">
                        <a href="default.php?page=page&t=sitemap"
                          class="btn btn-sm btn-light font-size-14 text-center">
                          <span>View all</span>
                        </a>
                      </div>
                    </div>
                    <!-- end card body -->
                  </div>
                  <!-- end card -->
                </div><!--end col-->
                <div class="col-6">
                  <div class="card">
                    <div class="card-body overflow-hidden position-relative">
                      <div>
                        <i class="fa-solid fa-print widget-box-1-icon"></i>
                      </div>
                      <h5 class="card-title text-start">Quick Reports</h5>
                      <p>Reports on all current processes.</p>
                      <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                          <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                        </div>
                        <div class="flex-grow-1">
                          <a href="default.php?page=uc_reportlist-individual" class="fw-bold">Individual unsafe
                            condition</a>
                          <p class="card-text text-secondary text-truncate">For accessing individual UC report screen.
                          </p>
                        </div>
                      </div>
                      <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                          <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                        </div>
                        <div class="flex-grow-1">
                          <a href="default.php?page=ua_reportlist-individual" class="fw-bold">Individual unsafe
                            action</a>
                          <p class="card-text text-secondary text-truncate">For accessing individual UA report screen.
                          </p>
                        </div>
                      </div>
                      <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                          <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                        </div>
                        <div class="flex-grow-1">
                          <a href="default.php?page=report-uc" class="fw-bold">Unsafe condition</a>
                          <p class="card-text text-secondary text-truncate">For accessing UC report screen.</p>
                        </div>
                      </div>
                      <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                          <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                        </div>
                        <div class="flex-grow-1">
                          <a href="default.php?page=report-ua" class="fw-bold">Unsafe action</a>
                          <p class="card-text text-secondary text-truncate">For accessing UA report screen.</p>
                        </div>
                      </div>
                      <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                          <i class="fa-solid fa-arrow-up-right-from-square text-primary me-1"></i>
                        </div>
                        <div class="flex-grow-1">
                          <a href="default.php?page=report-department" class="fw-bold">Department report</a>
                          <p class="card-text text-secondary text-truncate">For accessing department report screen.</p>
                        </div>
                      </div>
                      <div class="d-grid bm-4 ps-2 pe-2">
                        <a href="default.php?page=reports_all"
                          class="btn btn-sm btn-light font-size-14 text-center">
                          <span>View all</span>
                        </a>
                      </div>
                    </div>
                    <!-- end card body -->
                  </div>
                  <!-- end card -->



                </div>
              </div><!--end row-->
              <?php if ($_SESSION['user']['AccessLevel'] < 3) { ?>
                <!--div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body text-start">
                        <div class="card-header align-items-center d-flex">
                          <h4 class="card-title mb-0 flex-grow-1">Watch Our Product Demo</h4>
                          <div class="flex-shrink-0">
                            <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#transactions-all-tab" role="tab">
                                  Videos
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#transactions-more-tab" role="tab">
                                  More
                                </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#transactions-pdf-tab" role="tab">
                                  Manual
                                </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                        <div class="tab-content">
                          <div class="tab-pane active" id="transactions-all-tab" role="tabpanel">
                            <div class="row">
                              <?php echo showVideo('vimeo'); ?>
                              <div class="d-grid">
                                <a class="btn btn-sm btn-link font-size-14 text-center"
                                  href="default.php?page=svideos&s=">
                                  <span>View more..</span>
                                </a>
                              </div>
                            </div>
                          </div>
                          <div class="tab-pane" id="transactions-more-tab" role="tabpanel">
                            <div class="row">
                              <?php echo showVideo('youtube'); ?>
                            </div>
                          </div>
                          <div class="tab-pane" id="transactions-pdf-tab" role="tabpanel">
                            <div class="table-responsive px-3" data-simplebar>
                              <div class="row">
                                <div class="col-xl-4 col-sm-6">
                                  <div class="card bg-light-subtle border-light text-white-50">
                                    <div class="embed-responsive ratio ratio-1x1">
                                      <img src="img/pdf.png" width="64" width="64" />
                                    </div>
                                    <div class="card-body">
                                      <h5 class="card-text">
                                        <a href="docs/UCUA_2.0_Guide_V2.pdf" target="_blank" class="text-secondary">Read the latest documentation</a>
                                      </h5>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-xl-4 col-sm-6">
                                  <div class="card bg-light-subtle border-light text-white-50">
                                    <div class="embed-responsive ratio ratio-1x1">
                                      <img src="img/pdf.png" width="64" width="64" />
                                    </div>
                                    <div class="card-body">
                                      <h5 class="card-text">
                                        <a href="docs/docs.pdf" target="_blank" class="text-secondary">Read the documentation</a>
                                      </h5>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div-->
              <?php } ?>
            </div><!--end col-->

            <div class="col-md-4 themed-grid-col">
              <div class="card">
                <div class="card-body overflow-hidden position-relative">
                  <div>
                    <i class="fa-solid fa-link widget-box-1-icon"></i>
                  </div>
                  <h4 class="card-title mb-3">Useful Links</h4>
                  <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                      <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6><a href="docs/UCUA_2.5_Guide_v3.pdf" target="_blank"
                          class="fw-bolder text-dark">Getting Started</a></h6>
                      <p class="card-text">Check out our user guide for detailed
                        instructions, tips, and how-tos.</p>
                    </div>
                  </div>
                  <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                      <i class="fa-brands fa-youtube"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6><a href="default.php?page=svideos&s="
                          class="fw-bolder text-dark">Product Demo</a></h6>
                      <p class="card-text">Watch our short demo to discover the amazing
                        features that we offers!
                      </p>
                    </div>
                  </div>
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <i class="fa-solid fa-car-side"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6><a href="../ucua300/index.php?page=login"
                          class="fw-bolder text-dark">Test Drive</a></h6>
                      <p class="card-text">Take a spin and test drive this app to explore and
                        experience it firsthand.
                      </p>
                    </div>
                  </div>


                </div>
              </div>
              <!-- end card -->

              <div class="card mb-3">
                <div class="card-body overflow-hidden position-relative">
                  <div>
                    <i class="fa-solid fa-headset widget-box-1-icon"></i>
                  </div>
                  <h4 class="card-title mb-3">Support</h4>
                  <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                      <i class="fa-solid fa-circle-question"></i>
                    </div>
                    <div class="flex-grow-1">
                      <p class="card-text">Support is available from 8:30am to 5:30pm GMT+8 every workday.</p>
                    </div>
                  </div>
                  <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                      <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="flex-grow-1">
                      <p class="card-text"><a href="mailto=icthelpdesk@johorport.com.my" class="text-dark">icthelpdesk@johorport.com.my</a></p>
                    </div>
                  </div>
                  <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                      <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="flex-grow-1">
                      <p class="card-text">+607 - 253 5888 ext.314</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!--end row-->

          <!--div class="card mb-3">
                <div class="row g-0">
                  <div class="col-md-4 bg-light text-center">
                    <img src="img/email.png" class="img-fluid rounded-start mt-4" alt="Contact" width="64" height="64">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">Contact Us</h5>
                      <p class="m-0 card-text text-secondary">Don't hesitate to reach out if you need any help.</p>
                      <p class="m-0"><a href="mailto:icthelpdesk@johorport.com.my?subject=Hello&body=Hello"
                          class="text-secondary">Email us <i class="fa-solid fa-arrow-right"></i></a></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mb-3">
                <div class="row g-0">
                  <div class="col-md-4 bg-light text-center">
                    <img src="img/user2.png" class="img-fluid rounded-start mt-4" alt="profile" width="64" height="64">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">Profile Fill</h5>
                      <p class="m-0 card-text text-secondary">Update your details, password and email settings.</p>
                      <p class="m-0"><a href="default.php?page=profile&sid=<?php echo base64_encode($_SESSION['user']['staffId']); ?>"
                          class="text-secondary">Go to profile <i class="fa-solid fa-arrow-right"></i></a></p>
                    </div>
                  </div>
                </div>
              </div-->

        </div>
      </div><!--end row-->



    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->

  <!-- right offcanvas -->
  <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasRight"
    aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasRightLabel">Learning center</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <h6 class="text-dark">Video tour</h6>
      <p class="text-secondary">Click the view more link below for more video tour.</p>
      <div class="ratio ratio-16x9">
        <iframe
          src="https://player.vimeo.com/video/875812985?badge=0&amp;autopause=0&amp;quality_selector=1&amp;progress_bar=1&amp;player_id=0&amp;app_id=58479"
          title="YouTube video" allowfullscreen></iframe>
      </div>

      <div class="d-grid mt-2">
        <a class="btn btn-sm btn-link font-size-14 text-center" href="default.php?page=learn">
          <span>View more..</span>
        </a>
      </div>
      <hr />

      <h6 class="text-dark mt-4">Help articles</h6>
      <div class="d-flex mb-2">
        <div class="flex-shrink-0 me-3">
          <i class="fa-solid fa-file-pdf fa-2xl pt-4"></i>
        </div>
        <div class="flex-grow-1">
          <a href="docs/UCUA_2.0_Guide_V2.pdf" target="_blank" class="fw-bold text-dark">User guide</a>
          <p class="text-secondary">Check out our User Guide for tips, how-tos, and if you're looking for help.</p>
        </div>
      </div>
      <div class="d-flex mb-2">
        <div class="flex-shrink-0 me-3">
          <i class="fa-regular fa-file-pdf fa-2xl pt-4"></i>
        </div>
        <div class="flex-grow-1">
          <a href="docs/docs.pdf" target="_blank" class="fw-bold text-dark">Extra guide</a>
          <p class="text-secondary">Check out our user guide for tips, how-tos, and if you're looking for help.</p>
        </div>
      </div>
      <hr />

      <h6 class="text-dark mt-4">Support</h6>
      <div class="d-flex mb-2">
        <div class="flex-shrink-0 me-3">
          <i class="fa-regular fa-paper-plane fa-2xl pt-4"></i>
        </div>
        <div class="flex-grow-1">
          <a href="mailto:icthelpdesk@johorport.com.my?subject=Help&body=Feedback" class="fw-bold text-dark">Get
            help</a>
          <p class="text-secondary">Send us comments, feedback or report issues to helpdesk.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- end offcanvas -->

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