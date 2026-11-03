<?php
include('testfunctions.php') ;

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: testLogin');
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
<?php include('includes/navbar-Tester.php');?>
<!-- End of Topbar -->

    <div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header">
            <div class="float-left">
                <h5>Unsafe Condition Form</h5>
            </div>
            <div class="float-right">
                <button class="btn btn-primary">Print</button>
                <button class="btn btn-danger">Save as PDF</button>
            </div>
            <div class="clearfix"></div>
        </div>
        <p></p>
        <div class="card-body">
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
                                <input type="text" class="form-control" id="location" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-4 col-form-label">Condition Details :</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <textarea class="form-control" id="conditionDetails" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-4 col-form-label">Evidence :</label>
                        <div class="col-sm-8">
                            <img src="https://via.placeholder.com/100/FFFFFF?text=1" alt="" class="img-thumbnail evidence">
                            <img src="https://via.placeholder.com/100/FFFFFF?text=2" alt="" class="img-thumbnail evidence">
                            <img src="https://via.placeholder.com/100/FFFFFF?text=3" alt="" class="img-thumbnail evidence">
                        </div>
                    </div>
                </td>
            </tr>
            <tr bgcolor="#EFEFEF">
                <td colspan="2" align="left"><b>2. U-ACT</b></td>
            </tr>
            <tr align="left">
                <td colspan="2"><b>IMMEDIATE CORRECTIVE ACTION TAKEN (TINDAKAN SEGERA YANG DIAMBIL)</b><br/>
                    <div class="row mb-3 ms-5">
                        <div class="col-12 themed-grid-col">
                            <table width="100%" class="noborder">
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="Notify Related Department" aria-label="..."  /></td>
                                    <td align="left">NOTIFY RELATED DEPARTMENT: (ICT) MOHAMAD TAUFIK ABDUL RAZAK (mtaufik@johorport.com.my))</td>
                                </tr>
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="Others" aria-label="..."  /></td>
                                    <td align="left">
                                        OTHERS: <input type="text" name="others" class="blank" />
                                    </td>
                                </tr>
                            </table>
                             
                        </div>
                        
                      </div>
                </td>
            </tr>
            <tr bgcolor="#EFEFEF" align="center">
                <td width="50%"><b>OBSERVER</b></td>
                <td width="50%"><b>HSE REPS</b></td>
            </tr>
            <tr align="left">
                <td width="50%" valign="top">
                    
                    <div class="mb-3">
                        <label for="observerName" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="observerName" placeholder="Name" <?php //echo ObserverUser($_SESSION['user']['AccessLevel']);?>>
                    </div>
                    <div class="mb-3">
                        <label for="observerDepartment" class="form-label">Department:</label>
                        <input type="text" class="form-control" id="observerDepartment" placeholder="Department" <?php //echo ObserverUser($_SESSION['user']['AccessLevel']);?>>
                    </div>
                    <div class="mb-3">
                        <label for="observerEmail" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="observerEmail" placeholder="Email" <?php //echo ObserverUser($_SESSION['user']['AccessLevel']);?>>
                    </div>
                    <div class="mb-3">
                        <label for="observerConditionDetails" class="form-label">Condition Details:</label>
                        <textarea class="form-control" id="observerConditionDetails" placeholder="Condition Details" <?php //echo ObserverUser($_SESSION['user']['AccessLevel']);?>></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Case status:</label>
                        <span class="badge badge-success">CLOSED</span>
                    </div>
                    
                </td>
                <td width="50%" valign="top">
                    <b>REVIEWED BY (HSE REPS)</b><br/>
                    <table width="100%" class="noborder">
                        <tr>
                            <td>Name:</td>
                            <td>
                            <div class="input-group input-group-sm">
                                <select name="HSEreviewName" id="HSEreviewName" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" >
                                    <option value="">MOHAMAD TAUFIK ABDUL RAZAK (mtaufik@johorport.com.my)</option>
                                </select>
                            </div>
                            </td>
                        </tr>
                    </table>
                    <br/>
                    DESIGNATION: HSE REPS<br/>
                    DATE: <?php echo date("Y-m-d h:i:s");?><br/>
                    STATUS: <span class="badge badge-warning">PENDING</span>
                    <p>&nbsp;</p>
                    <b>REVIEWED BY (PIC/RELATED DEPARTMENT)</b><br/>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="noborder">
                        <tr>
                            <td width="10%">Name:</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <select name="PICreviewName" id="PICreviewName" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" >
                                        <option value="">MASLINDA YUSOF (maslinda@johorport.com.my)</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <br/>
                    DESIGNATION: HSE REPS<br/>
                    DATE: <?php echo date("Y-m-d h:i:s");?><br/>
                    STATUS: <span class="badge badge-warning">PENDING</span>
                    <p>&nbsp;</p>
                    <center>
                    <button class="btn btn-success" id="btnNotify"><span class="text text-white"><i class="fa-solid fa-circle-exclamation"></i> Reasiggn</span></button>
                    </center>
                </td>
            </tr>
            <tr bgcolor="#EFEFEF">
                <td colspan="2" align="left"><b>3. FOLLOW-UP ACTION & STATUS</b></td>
            </tr>
            
            <tr align="left">
                <td colspan="2">
                    <div class="row ms-5">
                        <div class="col-6">
                            <b>APPROVED BY (PSSD HSE OFFICER)</b><br/>
                            NAME: PSSD HSE OFFICER<br/>
                            DESIGNATION: PSSD HSE OFFICER<br/>
                            DATE: <?php echo date("Y-m-d h:i:s");?><br/>
                            STATUS: <span class="badge badge-warning">PENDING</span><br/>
                            <p>&nbsp;</p>
                            <b>REVIEWED BY (HEAD OF SECTION/PSSD)</b><br/>
                            <div class="clearfix"></div>

                            <table width="100%" class="noborder">
                                <tr>
                                    <td width="10%">NAME:</td>
                                    <td>
                                        <div class="input-group input-group-sm">    
                                            <select name="HOSreviewName" id="HOSreviewName" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                                <option>Nor Zaini (norzaini@johorport.com.my)</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="clearfix"></div>
                            DESIGNATION: PSSD HOS<br/>
                            DATE: <?php echo date("Y-m-d h:i:s");?><br/>
                            STATUS: <span class="badge badge-warning">PENDING</span><br/>
                        </div>
                        <div class="col-6">
                            <b>REVIEWED BY (HEAD OF DEPARTMENT/PSSD)</b><br/>
                            <div class="clearfix"></div>

                            <table width="100%" class="noborder">
                                <tr>
                                    <td width="10%">NAME:</td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <select name="HODreviewName" id="HODreviewName" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                                <option>Jamaludin (jml@johorport.com.my)</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <div class="clearfix"></div>
                            DESIGNATION: PSSD HOD<br/>
                            DATE: <?php echo date("Y-m-d h:i:s");?><br/>
                            STATUS: <span class="badge badge-warning">PENDING</span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr align="left">
                <td colspan="2">
                    <div class="row ms-5">
                        <div class="col-11">
                            Remark:<br/>
                            <textarea name="pssdRemark" class="form-control"></textarea><br/>
                            
                            <table width="100%" class="noborder">
                                <tr>
                                    <td width="20%" align="left">
                                        <label for="formFile" class="form-label">Take Action Upload : </label>
                                    </td>
                                    <td>
                                        <div class="custom-file">
                                            <input class="" type="file" id="formFile" multiple>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <p></p>
                        </div>
                    </div>
                    
                </td>
            </tr>
        </table>
        <p></p>
        
        <table border="0" width="95%" cellpadding="5" cellspacing="5" align="center" style="margin-left: auto; margin-right: auto;">
            <tr>
                <td>
                    <div class="alert alert-danger" role="alert">
                        <strong>Note:</strong> Once you press the 'Save' button, you can update the information anytime you want
                    </div>
                </td>
            </tr>
        </table>
        <p></p>
        <center>
        <button type="button"class="btn btn-secondary btn-icon-split btn-sm">
            <span class="text text-white">
                <i class="fa-solid fa-floppy-disk"></i> Save
            </span>
        </button>
        <button type="button"class="btn btn-primary btn-icon-split btn-sm">
            <span class="text text-white">
                <i class="fa-solid fa-circle-check"></i> Approved
            </span>
        </button>
        <button type="button" class="btn btn-danger btn-icon-split btn-sm">
            <span class="text">   
                <i class="fa-solid fa-circle-xmark"></i> Reject
            </span>
        </button>
        </center>
        </div><!--card-body-->

    </div><!--Card-->

    <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title">Activities</h5>
                <p class="card-text">This is some text within a card body.</p>
            </div>
        </div>


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