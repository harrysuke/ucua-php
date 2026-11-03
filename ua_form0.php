<?php
include('testFunctions.php') ;

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
        let userid = document.getElementById("userid");
        let msg = '';
        switch (parseInt(userid)){
            case 1://public user
                msg = 'required';
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
                msg = 'disabled';
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
                <h5>Unsafe Action Form</h5>
            </div>
            <div class="float-right">
                <!--button class="btn btn-primary">Print</button>
                <button class="btn btn-danger">Save as PDF</button-->
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
                        <label for="location" class="col-sm-2 col-form-label">Location :</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="location" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-2 col-form-label">Offence Code :</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="offenceCode" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="offenceCode" class="col-sm-2 col-form-label">Evidence :</label>
                        <div class="col-sm-10">
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
                        <div class="col-4 themed-grid-col">
                            <table width="100%" class="noborder">
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="stopWork" aria-label="..."  /></td>
                                    <td align="left">STOP WORK</td>
                                </tr>
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="verbalWarning" aria-label="..."  /></td>
                                    <td align="left">VERBAL WARNING</td>
                                </tr>
                            </table>
                             
                        </div>
                        <div class="col-4 themed-grid-col">
                            <table width="100%" class="noborder">
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="revokeEntryPermit" aria-label="..."  /></td>
                                    <td align="left">REVOKE ENTRY PERMIT</td>
                                </tr>
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="notifyRelatedDepartment" aria-label="..."  /></td>
                                    <td align="left">NOTIFY RELATED DEPARTMENT</td>
                                </tr>
                            </table>
                             
                        </div>
                        <div class="col-4 themed-grid-col">
                            <table width="100%" class="noborder">
                                <tr>
                                    <td><input class="" type="radio" name="actionTaken" id="actionTaken" value="others" aria-label="..."  /></td>
                                    <td align="left">OTHERS</td>
                                </tr>
                            </table>
                             
                        </div>
                      </div>
                </td>
            </tr>
            <tr bgcolor="#EFEFEF" align="center">
                <td width="50%"><b>OBSERVER</b></td>
                <td width="50%"><b>VIOLATOR</b></td>
            </tr>
            <tr align="left">
                <td width="50%" valign="top">
                    
                    <div id="">
                    <div class="mb-3">
                        <label for="observerName" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="observerName" placeholder="Name">
                    </div>
                    <div class="mb-3">
                        <label for="observerDepartment" class="form-label">Department:</label>
                        <input type="text" class="form-control" id="observerDepartment" placeholder="Department">
                    </div>
                    <div class="mb-3">
                        <label for="observerEmail" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="observerEmail" placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <label for="observerDatetime" class="form-label">Date & Time:</label>
                        <input type="datetime-local" class="form-control" id="observerDatetime" placeholder="Date & Time">
                    </div>
                    </div><!--Overlay-->
                </td>
                <td width="50%" valign="top">
                    <div class="mb-3">
                        <label for="violatorName" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="violatorName" placeholder="Name">
                    </div>
                    <div class="mb-3">
                        <label for="violatorDepartment" class="form-label">Department</label>
                        <input type="text" class="form-control" id="violatorDepartment" placeholder="Department">
                    </div>
                    <div class="mb-3">
                        <label for="violatorCompanyName" class="form-label">Company's Name</label>
                        <input type="text" class="form-control" id="violatorCompanyName" placeholder="Company's Name">
                    </div>
                    <div class="mb-3">
                        <label for="violatorICPassport" class="form-label">IC/Passport</label>
                        <input type="text" class="form-control" id="violatorICPassport" placeholder="IC/Passport">
                    </div>
                    <div class="mb-3">
                        <label for="violatorDatetime" class="form-label">Date & Time</label>
                        <input type="datetime-local" class="form-control" id="violatorDatetime" placeholder="Date & Time">
                    </div>
                    <p align="center">
                        <img src="https://via.placeholder.com/100/FFFFFF?text=4" alt="" class="img-thumbnail evidence">
                    </p>
                </td>
            </tr>
            <tr bgcolor="#EFEFEF">
                <td colspan="2" align="left"><b>3. FOLLOW-UP ACTION (To be filled by PSSD only)</b></td>
            </tr>
            <tr align="left">
                <td colspan="2">
                    <b>ACTION BY HSE PSSD</b><br/>
                    <div class="row mb-3 ms-5">
                        <div class="col-6 themed-grid-col">

                            <table cellpadding="0" cellspacing="0" class="noborder">
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Verbal Warning" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">VEBAL WARNING</label>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Domestic Inquiry (DI)" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">DOMESTIC INQUIRY</label>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Blacklisted" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">BLACKLISTED</label>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Notice/ Memo of safety violation" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">NOTICE/ MEMO OF SAFETY VIOLATION</label>
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td valign="top">
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Report to related parties/ Department" aria-label="...">
                                    </td>
                                    <td valign="top">
                                        <label class="form-check-label" for="actionTakenByHsePssd">REPORT TO RELATED PARTIES/ DEPARTMENT</label>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                    
                        <div class="col-6 themed-grid-col">
                            <table class="noborder">
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Fine RM " aria-label="...">
                                    </td>
                                    <td>
                                        FINE RM <input type="text" name="fine" class="blank" />
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Attend Induction (PEP)" aria-label="...">
                                    </td>
                                    <td>
                                        ATTEND INDUCTION (PEP)
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Attend briefing (VEP) - FROM " aria-label="...">
                                    </td>
                                    <td>
                                        ATTEND BRIEFING (VEP) - FROM 
                                        <input type="text" name="from" class="blank"/> UNTIL
                                        <input type="text" name="until" class="blank" />
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Banned " aria-label="...">
                                    </td>
                                    <td>
                                        BANNED <input type="text" name="until" class="blank" /> MONTH(S)
                                    </td>
                                </tr>
                                <tr align="left">
                                    <td>
                                        <input type="radio" name="actionTakenByHsePssd" id="actionTakenByHsePssd" value="Others " aria-label="...">
                                    </td>
                                    <td>
                                        OTHERS <input type="text" name="others" class="blank" />
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>

                    <div class="row ms-5">
                        <div class="col-4">
                            DATE OF ACTION TAKEN:<br/>
                            <?php echo date("Y-m-d h:i:s");?>
                        </div>
                        <div class="col-4">
                            ACTION TAKEN BY:<br/>
                            PSSD HSE OFFICER
                        </div>
                    </div>
                </td>
            </tr>
            <tr bgcolor="#EFEFEF">
                <td colspan="2" align="left"><b>4. STATUS</b></td>
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
                                            <select name="reviewName" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
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
                                            <select name="reviewName" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
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
                            PSSD Remark:<br/>
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
                        <strong>Note:</strong> Once you press the 'Save' button, you cannot update the information anytime you want
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
            <span class="text text-white">
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