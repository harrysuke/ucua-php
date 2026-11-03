<?php
include('testFunctions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login');
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$title = 'Sitemap';
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
                    <?php echo $title; ?>
                    <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                  </h4>
                  <p></p>
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

          <!-- start page title -->
          <div class="row mt-0">
            <section id="sec1">

    <h5>Main Menu</h5>
    <div class="row">
      <div class="col-md-3">
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">Feeds</a></li>
          <li><a href="#">Leaderboard</a></li>
          <li><a href="#">Workspaces</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <ul>
          <li><a href="#">Submit</a></li>
          <li><a href="#">Unsafe Condition</a></li>
          <li><a href="#">Unsafe Action</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <ul>
          <li><a href="#">Reports</a></li>
          <li><a href="#">All Reports</a></li>
        </ul>
      </div>
      
    </div>
  </section>
  <section id="sec2">

    <h5>Manage</h5>
    <div class="row">
      <div class="col-md-3">
        <ul>
          <li><a href="#">User Management</a></li>
          <li><a href="#">User Roles</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <ul>
          <li><a href="#">Management</a></li>
          <li><a href="#">Companies</a></li>
          <li><a href="#">Locations</a></li>
          <li><a href="#">Departments</a></li>
          <li><a href="#">Sections</a></li>
          <li><a href="#">Offences</a></li>
          <li><a href="#">Prefix</a></li>
          <li><a href="#">Emails</a></li>
          <li><a href="#">Email Template</a></li>
          <li><a href="#">Modules</a></li>
          <li><a href="#">Contents</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <ul>
          <li><a href="#">App</a></li>
          <li><a href="#">Search</a></li>
          <li><a href="#">Settings</a></li>

        </ul>
      </div>
      <div class="col-md-3">
        <ul>

        </ul>
      </div>
    </div>
  </section>
  <section id="sec3">

    <h5>Resources</h5>
    <div class="row">
      <div class="col-md-3">
        <ul>
          <li><a href="#">Knowleddge Base</a></li>
          <li><a href="#">Support</a></li>
          <li><a href="#">Log Out</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <ul>
        </ul>
      </div>
      <div class="col-md-3">
        <ul>
        </ul>
      </div>
      <div class="col-md-3">
        <ul>
        </ul>
      </div>
    </div>
  </section>
  <section id="sec4">

    
  </section>

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