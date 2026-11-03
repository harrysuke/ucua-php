<?php
$title = 'Manage Offences';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function funCategory($c)
{
    $msg = '';
    if ($c == 'Major') {
        $msg = "<h6 style='color:red'>Major";
    } else {
        $msg = "<h6 style='color:green'>Minor";
    }
    return $msg;
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
                                <h4 class="mb-sm-0 font-size-18">
                                    <?php echo $title; ?>
                                    <a href="default.php?page=favorite&add=1&label=<?php echo base64_encode($title); ?>&url=<?php echo base64_encode($fullurl); ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-title="Add to favorite"><i class="fa-regular fa-star fa-2xs"></i></a>
                                </h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage</a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                    <div class="float-end mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <i class="fa-solid fa-plus"></i> New Offense
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">

                            <table id="datatable" class="table dt-responsive w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Offense Code</th>
                                        <th>Offense Keyword</th>
                                        <th>Offense Details</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $sql = "SELECT * FROM offence ORDER BY OffenceCode";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td>
                                                <?php echo $i; ?>
                                            </td>
                                            <td width="15%">
                                                <?php echo $row["OffenceCode"] ?>
                                            </td>
                                            <td>
                                                <?php echo $row["offKeyword"] ?>
                                            </td>
                                            <td>
                                                <?php echo $row["Offence"] ?>
                                            </td>
                                            <td>
                                                <?php echo funCategory($row['Category']); ?>
                                            </td>
                                            <td width="10%">
                                                <a class="btn btn-soft-primary waves-effect waves-light"
                                                    href="default.php?page=manage_offence_edit&id=<?php echo $row['OffenceCode']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>&nbsp;
                                                <a class="btn btn-soft-danger waves-effect waves-light"
                                                    href="default.php?page=manage_offence_edit&del=<?php echo $row['OffenceCode']; ?>"
                                                    name="delete_btn">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>

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

    <!-- right offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel"
        data-bs-scroll="false" data-bs-backdrop="true">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Create Offence Code</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">

            <form action="default.php?page=manage_offence_edit" method="POST">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Offence Code<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="OffenceCode" class="form-control" placeholder=" Offence Code"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Category<span class="text-danger">
                                    *</span></label>
                            <select id="Category" name="Category" class="form-control" required>
                                <option selected="selected">Please choose</option>
                                <option Value="Major">Major</option>
                                <option Value="Minor">Minor</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Offence Keyword<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="offKeyword" class="form-control" placeholder=" Offence Keywords"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" id="exampleModalLabel">Offence Description<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="Offence" class="form-control" placeholder=" Offence Description"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                            <button type="submit" name="addbtn" class="btn btn-primary">Create</button>
                        </div>


                    </div>
                </div>
            </form>
        </div>


    </div>
    </div>



    <!-- Right Sidebar -->
    <?php include('inc_rightbar.php'); ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <?php include('inc_scripts.php'); ?>

</body>

</html>