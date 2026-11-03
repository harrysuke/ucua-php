<?php
$title = 'UC Form';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function funRunningNo()
{
    global $db;
    $sql1 = "SELECT MAX(Id) as 'runningno' FROM ucuaform";
    $result1 = mysqli_query($db, $sql1);
    $row1 = mysqli_fetch_array($result1);
    return $row1['runningno'];
}

function funLocation()
{
    $option = '';
    global $db;
    $sql1 = "SELECT LocationId, LocationName FROM location WHERE isdelete=0 ORDER BY LocationName";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $LocationId = $row1['LocationId'];
        $LocationName = $row1['LocationName'];
        $option .= "<option value='$LocationId'>$LocationName</option>";
    }
    return $option;
}

function getAllowedFileExtensions()
{
    global $db;
    $query = "SELECT prefix FROM prefix";
    $result = mysqli_query($db, $query);
    $allowTypes = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $allowTypes[] = $row['prefix'];
    }
    return $allowTypes;
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

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="mb-sm-0 font-size-18">Submit new unsafe condition
                                            <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                                        </h4>
                                        <p class="text-secondary">Every user with an account can create new unsafe report.</p>
                                        <!--a href="docs/UCUA_2.0_Guide_V2.pdf" target="_blank" class="me-3"><i class="fa-solid fa-book-open"></i> How this works</a>
                                        <a href="default.php?page=svideos&s=" target="_blank"><i class="fa-brands fa-youtube"></i> See them in action</a-->
                                    </div>
                                </div>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">Home</li>
                                        <li class="breadcrumb-item active">Unsafe Condition</li>
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
                                    <form id="register_form" action="default.php?page=UCcode_user" method="post" enctype="multipart/form-data">
                                        <table width="95%" cellpadding="5" cellspacing="5" class="uitable shadow mb-4">
                                            <tr>
                                                <td align="center" valign="center"><img src="img/JPB-Logo.png" /></td>
                                                <td align="center" valign="center">
                                                    <h3 class="form">FORM</h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Department Name : PSSD (HSE)</b></td>
                                                <td><b>Document No.: FM-PSSD-<?php echo funRunningNo(); ?></b></td>
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
                                                    <b>UNSAFE CONDITION (KONDISI TIDAK SELAMAT)</b><br />
                                                    <div class="mb-4 row">
                                                        <label for="location" class="col-sm-4 col-form-label">
                                                            Location:<br />
                                                            <a href="default.php?page=manage_location" class="btn btn-secondary btn-sm waves-effect waves-light"><i class="fa-solid fa-plus"></i> New location</a>
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-sm">
                                                                <select
                                                                    id="location"
                                                                    name="location"
                                                                    class="form-control js-select"
                                                                    required>
                                                                    <option selected="selected"></option>
                                                                    <?php echo funLocation(); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4 row">
                                                        <label for="offenceCode"
                                                            class="col-sm-4 col-form-label">Condition Details :</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-sm">
                                                                <textarea
                                                                    class="form-control"
                                                                    id="details"
                                                                    name="details"
                                                                    aria-label="Sizing example input"
                                                                    aria-describedby="inputGroup-sizing-sm"
                                                                    required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4 row">
                                                        <label for="offenceCode"
                                                            class="col-sm-4 col-form-label">Evidence :<br />
                                                            <small>Upload one or more photo Evidence.</small>
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input
                                                                class="form-control"
                                                                type="file"
                                                                name="files[]"
                                                                id="files"
                                                                accept=".<?php echo implode(',.', getAllowedFileExtensions()); ?>"
                                                                multiple="multiple"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4 row">
                                                        <label for="offenceCode" class="col-sm-4 "></label>
                                                        <div class="col-sm-8" id="preview"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>


                                        <p></p>

                                        <center>
                                            <button type="reset" class="btn btn-secondary btn-icon-split btn-sm">
                                                <span class="text text-white">
                                                    <i class="fa-solid fa-eraser"></i> Reset
                                                </span>
                                            </button>

                                            <button type="submit" name="draft_btn"
                                                class="btn btn-secondary btn-icon-split btn-sm">
                                                <span class="text text-white">
                                                    <i class="fa-solid fa-database"></i> Save as Draft
                                                </span>
                                            </button>

                                            <button type="submit" name="submit_btn"
                                                class="btn btn-primary btn-icon-split btn-sm">
                                                <span class="text text-white">
                                                    <i class="fa-solid fa-circle-check"></i> Submit
                                                </span>
                                            </button>

                                        </center>
                                        <input type="hidden" name="url" value="<?php echo base64_encode($fullurl); ?>" />
                                    </form>
                                </div><!--card-body-->

                            </div><!--Card-->
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

    <?php
    if (!empty($_SESSION['status'])) {
        echo '<script type="text/javascript">toastr.success("' . $_SESSION['status'] . '")</script>';
        unset($_SESSION['status']);
    }
    ?>

</body>

</html>