<?php
$title = 'UC Report';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

include('inc_header.php');
?>

</head>

<body class="pace-done sidebar-enable" data-new-gr-c-s-check-loaded="8.906.0" data-gr-ext-installed=""
  data-sidebar="brand" data-sidebar-size="sm">
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
                <h4 class="mb-sm-0 font-size-18">
                  <?php echo $title; ?>
                  <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                </h4>

                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Report</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- end page title -->
          <div class="row">

            <div class="alert alert-info alert-border-left alert-dismissible fade show mb-0" role="alert">
              <i class="mdi mdi-alert-circle-outline align-middle me-3"></i><strong>How can we improve reporting for you?</strong>
              We're working on improvements to reporting and would love your feedback. <a href="mailto:icthelpdesk@johorport.com.my?subject=feedback&body=feedback">Share feedback here.</a>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="col-lg-6">
              <div class="card border-0">
                <div class="card-header">
                  <h4 class="card-title">Search report by date</h4>
                  <p class="card-title-desc"></p>
                </div>
                <div class="card-body">
                  <form method="post" action="default.php?page=uc_reportlist">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="mb-3">
                          <label for="example-date-input" class="form-label">Date start</label>
                          <input class="form-control" type="date" value="" name="dateStart">
                        </div>

                        <div class="mb-3">
                          <label for="example-date-input" class="form-label">Date end</label>
                          <input class="form-control" type="date" value="" name="dateEnd">
                        </div>

                        <div class="mb-3">
                          <button type="submit" name="submit" class="btn btn-primary btn-lg w-md">Generate Report</button>
                          <a href="default.php?page=dashboard" class="btn btn-link">Cancel</a>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div> <!-- end row -->
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