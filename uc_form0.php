<?php
include('testfunctions.php') ;

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

//hse reps/hos/hod <> u see/u act (ucua)
//public user after approved can see activities else cannot see (ucua)
?>
<?php include('includes/header.php');?>
<style>
            table{
                align: left;
            }
            td{
                padding:8px;
            }
            .uitable {
                margin-top: -20px;
                margin-left: auto;
                margin-right: auto;
            }
            .uitable td {
                border: 1px solid #CCCCCC;
            }
            .form {
                font-weight: bold;
            }
            img.evidence {
                width:100px;
                height:100px;
                background: url(http://goo.gl/vyAs27) no-repeat scroll 0 0;
            }
            .blank{
                border-top: 0;
                border-left: 0;
                border-right: 0;
                border-bottom: 1px solid #000000;
                width: 100px;
            }
            .noborder td{
                border: 0;
                padding: 0;
                margin: 0;
            }
            h5, h6{
                color: darkblue;
                font-weight: bold;
            }
            #overlay {
                position: absolute; /* Sit on top of the page content */
                display: none; /* Hidden by default */
                background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
                opacity: .6;
                z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
                cursor: pointer; /* Add a pointer on hover */
            }
            #overlay:before {
                display: none; /* Hidden by default */
                width: 100%; /* Full width (cover the whole page) */
                height: 100%; /* Full height (cover the whole page) */
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
                opacity: .6;
                z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
                cursor: pointer; /* Add a pointer on hover */
            }
            #overlay:after {
                display: none; /* Hidden by default */
                width: 100%; /* Full width (cover the whole page) */
                height: 100%; /* Full height (cover the whole page) */
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
                opacity: .6;
                z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
                cursor: pointer; /* Add a pointer on hover */
            }
        </style>
        
<?php include('includes/navbar-Tester.php');?>
<!-- End of Topbar -->

    <div class="container-fluid">
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

        <!--div class="card shadow">
            <div class="card-body">
                <h5 class="card-title">Activities</h5>
                <p class="card-text">This is some text within a card body.</p>
            </div>
        </div-->


    </div><!--container-fluid-->

        

    </div>
    <!-- End of Main Content -->

            
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form method="post" action="default.php?page=testLogout">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="logout_btn" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
        </form>
    </div>
    
<input type="hidden" id="userid" value="<?php echo $_SESSION['user']['AccessLevel'];?>">   

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>

<script>
function myOnLoad() {
    try{
        var userid = document.getElementById("userid").value;
        var msg = '';
        console.log(userid);
        switch (parseInt(userid)){
            case 1://public user
                document.getElementById('location').disabled = false;
                document.getElementById('conditionDetails').disabled = false;
                document.getElementById('actionTaken').disabled = false;
                document.getElementById('observerName').disabled = false;
                document.getElementById('observerDepartment').disabled = false;
                document.getElementById('observerEmail').disabled = false;
                document.getElementById('observerConditionDetails').disabled = false;

                document.getElementById('HSEreviewName').disabled = true;
                document.getElementById('PICreviewName').disabled = true;
                document.getElementById('HOSreviewName').disabled = true;
                document.getElementById('HODreviewName').disabled = true;
                document.getElementById('btnNotify').disabled = true;
                break;
            case 2://public selected user
                msg = 'required';
                break;
            case 3://PSSD Admin
                msg = 'disabled';
                break;
            case 4://PSSD HSE Officer
                msg = 'disabled';
                break;
            case 5://PSSD Personnel
                msg = 'disabled';
                break;
            case 6://PSSD HOS
                msg = 'disabled';
                break;
            case 7://PSSD HOD
                msg = 'disabled';
                break;
            case 8://HSE Reps
                document.getElementById('location').disabled = true;
                document.getElementById('conditionDetails').disabled = true;
                document.getElementById('actionTaken').disabled = true;
                document.getElementById('observerName').disabled = true;
                document.getElementById('observerDepartment').disabled = true;
                document.getElementById('observerEmail').disabled = true;
                document.getElementById('observerConditionDetails').disabled = true;

                document.getElementById('HSEreviewName').disabled = false;
                document.getElementById('PICreviewName').disabled = false;
                document.getElementById('HOSreviewName').disabled = false;
                document.getElementById('HODreviewName').disabled = false;
                document.getElementById('btnNotify').disabled = false;
                break;
            case 9://ICT Admin
                msg = 'required';
                break;
            case 10://HSE PCU
                msg = 'disabled';
                break;
            case 11://Tester
                msg = 'required';
                break;
            default:
                msg = 'disabled';
                break;
        }
        console.log(msg);
    }catch(err){
        console.error(err.message);
    }
}

window.onload = myOnLoad;
</script>