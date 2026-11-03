<?php
include('testFunctions.php');
$title = 'Wizard';

if (!isLoggedIn()) {
  //$_SESSION['msg'] = "You must log in first";
  //header('location: login');
}

function showName($n)
{
  global $db;
  $sql = "SELECT Name, Email FROM user WHERE staffId = '" . $n . "'";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
    return $row['Name'];
  } else {
    return $n;
  }
}

function showDept($n)
{
  global $db;
  $sql = "SELECT Department FROM user WHERE staffId = '" . $n . "'";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
    return $row['Department'];
  } else {
    return $n;
  }
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
                </h4>
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"></li>
                    <li class="breadcrumb-item active"></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- end page title -->

          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title mb-0">Basic pills Wizard</h4>
                </div>
                <div class="card-body">
                  <div id="basic-pills-wizard" class="twitter-bs-wizard">
                    <ul class="twitter-bs-wizard-nav">
                      <li class="nav-item">
                        <a href="#seller-details" class="nav-link" data-toggle="tab">
                          <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Seller Details">
                            <i class="bx bx-list-ul"></i>
                          </div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#company-document" class="nav-link" data-toggle="tab">
                          <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Company Document">
                            <i class="bx bx-book-bookmark"></i>
                          </div>
                        </a>
                      </li>
          
                      <li class="nav-item">
                        <a href="#bank-detail" class="nav-link" data-toggle="tab">
                          <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Bank Details">
                            <i class="bx bxs-bank"></i>
                          </div>
                        </a>
                      </li>
                    </ul>
                    <!-- wizard-nav -->
          
                    <div class="tab-content twitter-bs-wizard-tab-content">
                      <div class="tab-pane" id="seller-details">
                        <div class="text-center mb-4">
                          <h5>Seller Details</h5>
                          <p class="card-title-desc">Fill all information below</p>
                        </div>
                        <form>
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="mb-3">
                                <label for="basicpill-firstname-input" class="form-label">First name</label>
                                <input type="text" class="form-control" id="basicpill-firstname-input"
                                  placeholder="Enter Your First Name">
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="mb-3">
                                <label for="basicpill-lastname-input" class="form-label">Last name</label>
                                <input type="text" class="form-control" id="basicpill-lastname-input"
                                  placeholder="Enter Your Last Name">
                              </div>
                            </div>
                          </div>
          
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="mb-3">
                                <label for="basicpill-phoneno-input" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="basicpill-phoneno-input"
                                  placeholder="Enter your Phone No.">
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="mb-3">
                                <label for="basicpill-email-input" class="form-label">Email</label>
                                <input type="email" class="form-control" id="basicpill-email-input"
                                  placeholder="Enter your email">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="mb-3">
                                <label for="basicpill-address-input" class="form-label">Address</label>
                                <textarea id="basicpill-address-input" class="form-control" rows="2"
                                  placeholder="Enter your Address"></textarea>
                              </div>
                            </div>
                          </div>
                        </form>
                        <ul class="pager wizard twitter-bs-wizard-pager-link">
                          <li class="next"><a href="javascript: void(0);" class="btn btn-primary">Next <i class="fa-solid fa-angle-right"></i></a></li>
                        </ul>
                      </div>
                      <!-- tab pane -->
                      <div class="tab-pane" id="company-document">
                        <div>
                          <div class="text-center mb-4">
                            <h5>Company Document</h5>
                            <p class="card-title-desc">Fill all information below</p>
                          </div>
                          <form>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-pancard-input" class="form-label">PAN Card</label>
                                  <input type="text" class="form-control" id="basicpill-pancard-input"
                                    placeholder="Enter your PAN No.">
                                </div>
                              </div>
          
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-vatno-input" class="form-label">VAT/TIN No.</label>
                                  <input type="text" class="form-control" id="basicpill-vatno-input"
                                    placeholder="Enter your VAT/TIN No.">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-cstno-input" class="form-label">GST No.</label>
                                  <input type="text" class="form-control" id="basicpill-cstno-input"
                                    placeholder="Enter your GST No.">
                                </div>
                              </div>
          
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-servicetax-input" class="form-label">Service Tax No.</label>
                                  <input type="text" class="form-control" id="basicpill-servicetax-input"
                                    placeholder="Enter your Service Tex No.">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-companyuin-input" class="form-label">Company UIN</label>
                                  <input type="text" class="form-control" id="basicpill-companyuin-input"
                                    placeholder="Enter your Company UIN.">
                                </div>
                              </div>
          
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-declaration-input" class="form-label">Declaration</label>
                                  <input type="text" class="form-control" id="basicpill-declaration-input"
                                    placeholder="Enter your Declaration">
                                </div>
                              </div>
                            </div>
                          </form>
                          <ul class="pager wizard twitter-bs-wizard-pager-link">
                            <li class="previous"><a href="javascript: void(0);" class="btn btn-primary"><i class="fa-solid fa-angle-left"></i> Previous</a></li>
                            <li class="next"><a href="javascript: void(0);" class="btn btn-primary">Next <i class="fa-solid fa-angle-right"></i></a></li>
                          </ul>
                        </div>
                      </div>
                      <!-- tab pane -->
                      <div class="tab-pane" id="bank-detail">
                        <div>
                          <div class="text-center mb-4">
                            <h5>Bank Details</h5>
                            <p class="card-title-desc">Fill all information below</p>
                          </div>
                          <form>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-namecard-input" class="form-label">Name on Card</label>
                                  <input type="text" class="form-control" id="basicpill-namecard-input"
                                    placeholder="Enter your Name on Card">
                                </div>
                              </div>
          
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label class="form-label">Credit Card Type</label>
                                  <select class="form-select">
                                    <option selected>Select Card Type</option>
                                    <option value="AE">American Express</option>
                                    <option value="VI">Visa</option>
                                    <option value="MC">MasterCard</option>
                                    <option value="DI">Discover</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-cardno-input" class="form-label">Credit Card Number</label>
                                  <input type="text" class="form-control" id="basicpill-cardno-input"
                                    placeholder="Enter your Credit Card Number">
                                </div>
                              </div>
          
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-card-verification-input" class="form-label">Card Verification
                                    Number</label>
                                  <input type="text" class="form-control" id="basicpill-card-verification-input"
                                    placeholder="Enter your Card Verification Number">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="basicpill-expiration-input" class="form-label">Expiration Date</label>
                                  <input type="date" class="form-control" id="basicpill-expiration-input">
                                </div>
                              </div>
                            </div>
                          </form>
                          <ul class="pager wizard twitter-bs-wizard-pager-link">
                            <li class="previous"><a href="javascript: void(0);" class="btn btn-primary"><i class="fa-solid fa-angle-left"></i> Previous</a></li>
                            <li class="float-end"><a href="javascript: void(0);" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target=".confirmModal">Save
                                Changes</a></li>
                          </ul>
                        </div>
                      </div>
                      <!-- tab pane -->
                    </div>
                    <!-- end tab content -->
                  </div>
                </div>
                <!-- end card body -->
              </div>
              <!-- end card -->
            </div>
            <!-- end col -->
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
  <script>
    $(function () {
      var areaChartData = {
        labels: ['<?php echo implode('\',\'', $label) ?>'],
        datasets: [
          {
            label: 'Frequent Asks',
            backgroundColor: 'rgba(81,86,190,0.9)',
            borderColor: 'rgba(81,86,190,0.8)',
            pointRadius: false,
            pointColor: '#5156be',
            pointStrokeColor: 'rgba(81,86,190,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(81,86,190,1)',
            data: [<?php echo implode(',', $data) ?>]
          }
        ]
      }

      var areaChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false,
            }
          }],
          yAxes: [{
            gridLines: {
              display: false,
            }
          }]
        }
      }
      //-------------
      //- BAR CHART -
      //-------------
      var barChartCanvas = $('#barChart').get(0).getContext('2d')
      var barChartData = $.extend(true, {}, areaChartData)
      var temp0 = areaChartData.datasets[0]
      barChartData.datasets[0] = temp0
      console.log()

      var barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
      })

    })
  </script>
</body>

</html>