<?php
$title = 'UC Form';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
  }

function funLocation(){
    $option = '';
    global $db;
    $sql1 = "SELECT * FROM location";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $LocationId = $row1['LocationId'];
        $LocationName = $row1['LocationName'];
        $option .= "<option value='$LocationId'>$LocationName</option>";
    }
    return $option;
}

include('inc_header.php');
?>

    </head>
    <body class="pace-done" data-new-gr-c-s-check-loaded="8.904.0" data-gr-ext-installed="" data-sidebar="brand"><div class="pace pace-inactive"><div class="pace-progress" style="transform: translate3d(100%, 0px, 0px);" data-progress-text="100%" data-progress="99">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            <!-- ========== Left Navbar Start ========== -->
            <?php include('inc_navbar.php');?>
            <!-- Left Navbar End -->
            <!-- ========== Left Sidebar Start ========== -->
            <?php include('inc_sidebar.php');?>
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
                                    <h4 class="mb-sm-0 font-size-18"></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                                            <li class="breadcrumb-item active"></li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-12">
                            <div class="card shadow mb-4">
        <div class="card-header">
            <div class="float-left">
                <h5>Unsafe Condition Form</h5>
            </div>
            <div class="float-right">
                <!--button class="btn btn-primary">Print</button>
                <button class="btn btn-danger">Save as PDF</button-->
            </div>
            <div class="clearfix"></div>
        </div>
        <p></p>
        <div class="card-body">
        <form id="register_form" action="UCcode_user.php" method="post" enctype="multipart/form-data">
        <table width="95%" cellpadding="5" cellspacing="5" class="uitable shadow mb-4">
            <tr>
                <td align="center" valign="center"><img src="img/JPB-Logo.png"/></td>
                <td align="center" valign="center"><h3 class="form">FORM</h3></td>
            </tr>
            <tr>
                <td><b>Department Name : PSSD (HSE)</b></td>
                <td><b>Document No.: FM-PSSD-115</b></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><b>U-SEE U-ACT (UCUA)</b></td>
            </tr>
            <tr>
                <td colspan="2" align="center"></td>
            </tr>
            <tr bgcolor="#EFEFEF">
                <td colspan="2" align="left"><b>1. U-SEE</b></td>
            </tr>
            <tr align="left">
                <td colspan="2">
                    <b>UNSAFE ACT (PERBUATAN TIDAK SELAMAT)</b><br/>
                    <div class="mb-3 row">
                        <label for="location" class="col-sm-4 col-form-label">Location :</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                            <select id="location" name="location" class="form-control js-example-placeholder-multiple">
                                <option selected="selected"></option>
                                <?php echo funLocation();?>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-4 col-form-label">Condition Details :</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <textarea class="form-control" id="details" name="details" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-4 col-form-label">Evidence :</label>
                        <div class="col-sm-8">
                            <input type="file" name="files[]" id="fileToUpload" multiple="multiple">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-4 col-form-label"></label>
                        <div class="col-sm-8">
                            <img src="https://via.placeholder.com/100/FFFFFF?text=1" alt="" class="img-thumbnail evidence">
                            <img src="https://via.placeholder.com/100/FFFFFF?text=2" alt="" class="img-thumbnail evidence">
                            <img src="https://via.placeholder.com/100/FFFFFF?text=3" alt="" class="img-thumbnail evidence">
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        </form>

        <p></p>
        
        <center>
        <button type="reset"class="btn btn-secondary btn-icon-split btn-sm">
            <span class="text text-white">
                <i class="fa-solid fa-eraser"></i> Reset
            </span>
        </button>
        <button type="submit" name="submit_btn" class="btn btn-primary btn-icon-split btn-sm">
            <span class="text text-white">
                <i class="fa-solid fa-circle-check"></i> Submit
            </span>
        </button>
        </center>
        </div><!--card-body-->

    </div><!--Card-->
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php include('inc_footer.php');?>

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        
        <!-- Right Sidebar -->
        <?php include('inc_rightbar.php');?>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <?php include('inc_scripts.php');?>

</body>
</html>