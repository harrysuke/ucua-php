<?php
set_time_limit(20);
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
require_once('tcpdf_include.php');
include('testFunctions.php');

class MYPDF extends TCPDF {

	public function Header(){
		$this->SetFont('dejavusans','',6);
		$this->SetTextColor(0,0,0);
		$this->Ln(3);
		$head='<table width="100%" border="1" cellpadding="5" cellspacing="0">
		<tr>
		<td width="65%"><img src="img/JPB-Logo.jpg" border="0" width="150" height="66" /></td>
		<td width="35%" align="center" valign="center"><h1>FORM</h1></td>
		</tr>
		<tr>
		<th><b>Department Name : PSSD (HSE)</b></th>
		<th><b>Document No.: FM-PSSD-'.base64_decode($_GET['id']).'</b></th>
		</tr>
		<tr>
		<th colspan="2" align="center"><b>U-SEE U-ACT (UCUA)</b></th>
		</tr>
		</table>';
		$this->writeHTML($head, true, false, true, false,'');
		$this->Ln(5);
	}
	public function Footer(){
		//$this->SetY(-70);
		$this->SetFont('dejavusans','',7);
		//$this->SetTextColor(128);
		//$kaki='';
		$kaki='Version no. : 3.0	    Unauthorised alteration on this template is strictly not allowed.<br/>
		Effective date : 01/02/2020';
		$this->writeHTML($kaki, true, false, true, false,'');
		// Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Kairi Juri');
$pdf->SetTitle('TCPDF UCUA');
$pdf->SetSubject('TCPDF REPORT');
$pdf->SetKeywords('TCPDF, PDF, UCUA, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'tcpdf/examples/lang/eng.php')) {
    require_once(dirname(__FILE__).'tcpdf/examples/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// --------------------------------------------------------- 

// set font 
$pdf->SetFont('dejavusans', '', 7); 

// add a page 
$pdf->AddPage(); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Kairi Juri');
$pdf->SetTitle('UCUA Report');
$pdf->SetSubject('UCUA Report');
$pdf->SetKeywords('TCPDF, PDF, UCUA Report');

$dimensions = $pdf->getPageDimensions();
$hasBorder = false; //flag for fringe case

function photoEvidence($id, $type)
{
    $img = '';
    global $db;
    $sql = "SELECT evidenceImage FROM ucuaphoto WHERE ucuaform_Id = '" . $id . "' AND type='" . $type . "' ";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $img .= '<img src="' . $row['evidenceImage'] . '" alt="' . $type . '" style="width:150px">';
    }
    return $img;
}


//ISI
try {
    $sql = "SELECT ucuaform.Id, ucuaform.OffenceCode,ucuaform.conditionDetails, ucuaform.ActionTaken, ucuaform.otherValue,ucuaform.evidenceImage, ucuaform.observerName, ucuaform.observerDepartment, ucuaform.observerEmail, ucuaform.observerConditionDetails, ucuaform.submitdate, ucuaform.ViolatorName, ucuaform.ViolatorDept, ucuaform.violatorCompany,ucuaform.ViolatorIc, ucuaform.ViolatorPass, ucuaform.violatorDatetime, ucuaform.closedEvidence,location.LocationName, ucuaform.isclosed, ucuaform.closedby, ucuaform.closeddate, ucuaform.closedEvidence, ucuaform.isnotifysherep, ucuaform.notifysherepdate, ucuaform.isapproved, ucuaform.approveddate, ucuaform.isHosApproved, ucuaform.HosApprovedDate, ucuaform.isHODapproved, ucuaform.HODapprovedDate, ucuaform.followup, ucuaform.fineamount, ucuaform.briefingFrom, ucuaform.briefingUntil, ucuaform.bannedMonths, ucuaform.bannedMonths2, ucuaform.otherAction, ucuaform.followupdate, ucuaform.approvedby, ucuaform.approveddate, ucuaform.isapproved, ucuaform.isreopen, ucuaform.HOSreviewName, ucuaform.HODreviewName, ucuaform.isHOSrejected, ucuaform.isHODrejected, ucuaform.violatorContactNo FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId WHERE ucuaform.Id = '".base64_decode($_GET['id'])."' ";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    if(!empty($row)){
        $LocationName = $row['LocationName'];
        $OffenceCode = $row['OffenceCode'];
        $conditionDetails = $row['conditionDetails'];
        $test = $row["evidenceImage"];
        $ActionTaken = $row['ActionTaken'];
        $otherValue = $row['otherValue'];
        $observerName = strtolower($row['observerName']);
        $observerDepartment = $row['observerDepartment'];
        $observerEmail = $row['observerEmail'];
        $observerConditionDetails = $row['observerConditionDetails'];
        $submitdate = $row['submitdate'];
        $ViolatorName = strtolower($row['ViolatorName']);
        $ViolatorDept = $row['ViolatorDept'];
        $violatorCompany = $row['violatorCompany'];
        $ViolatorIc = $row['ViolatorIc'];
        $ViolatorPass = $row['ViolatorPass'];
        $violatorDatetime = $row['violatorDatetime'];
        $violatorContactNo = $row['violatorContactNo'];
        $isnotifysherep = $row['isnotifysherep'];
        $notifysherepdate = $row['notifysherepdate'];
        $isapproved = $row['isapproved'];
        $approveddate = $row['approveddate'];
        $isHosApproved = $row['isHosApproved'];
        $HosApprovedDate = $row['HosApprovedDate'];
        $isHODapproved = $row['isHODapproved'];
        $HODapprovedDate = $row['HODapprovedDate'];
        $isclosed = $row['isclosed'];
        $closedby = $row['closedby'];
        $closeddate = $row['closeddate'];
        $closedEvidence = $row['closedEvidence'];
        $followup = $row['followup'];
        $fineamount = $row['fineamount'];
        $briefingFrom = $row['briefingFrom'];
        $briefingUntil = $row['briefingUntil'];
        $bannedMonths = $row['bannedMonths'];
        $bannedMonths2 = $row['bannedMonths2'];
        $otherAction = $row['otherAction'];
        $followupdate = $row['followupdate'];
        $approvedby = $row['approvedby'];
        $isapproved = $row['isapproved'];
        $isreopen = $row['isreopen'];
        $HOSreviewName = $row['HOSreviewName'];
        $HODreviewName = $row['HODreviewName'];
        $isHOSrejected = $row['isHOSrejected'];
        $isHODrejected = $row['isHODrejected'];
    } else {
        $LocationName = '';
        $OffenceCode = '';
        $conditionDetails = '';
        $test = '';
        $ActionTaken = '';
        $otherValue = '';
        $observerName = '';
        $observerDepartment = '';
        $observerEmail = '';
        $observerConditionDetails = '';
        $submitdate = '';
        $ViolatorName = '';
        $ViolatorDept = '';
        $violatorCompany = '';
        $ViolatorIc = '';
        $ViolatorPass = '';
        $violatorDatetime = '';
        $violatorContactNo = '';
        $isnotifysherep = '';
        $notifysherepdate = '';
        $isapproved = '';
        $approveddate = '';
        $isHosApproved = '';
        $HosApprovedDate = '';
        $isHODapproved = '';
        $HODapprovedDate = '';
        $isclosed = '';
        $closedby = '';
        $closeddate = '';
        $closedEvidence = '';
        $followup = '';
        $fineamount = '';
        $briefingFrom = '';
        $briefingUntil = '';
        $bannedMonths = '';
        $bannedMonths2 = '';
        $otherAction = '';
        $followupdate = '';
        $approvedby = '';
        $isapproved = '';
        $isreopen = '';
        $HOSreviewName = '';
        $HODreviewName = '';
        $isHOSrejected = '';
        $isHODrejected = '';
    }
} catch (Exception $e) {
    die($e->getMessage());
}
	
	$pdf->Ln(20);
	
	$a='<table border="1" cellpadding="5" cellspacing="0">
			<tr>
				<td bgcolor="#FFFFE0"><b>1. U-SEE</b></td>
				<td bgcolor="#FFFFE0"><b>EVIDANCE IMAGE</b></td>
			</tr>
			<tr>
				<td><b>UNSAFE ACT OBSERVED</b><br/>
                (PERBUATAN TIDAK SELAMAT)<br/>
				LOCATION: '.$LocationName.'<br/>
				CODE: '.$OffenceCode.'<br/>
				</td>
				<td>'.photoEvidence(base64_decode($_GET['id']), 'Photo Evidence').'</td>
			</tr>
            </table>
            <table border="1" cellpadding="5" cellspacing="0">
			<tr>
				<td bgcolor="#FFFFE0" colspan="3"><b>2. U-ACT</b></td>
			</tr>
			<tr>
				<td>
					
                        
                            <table border="0" width="100%">';
							if (strpos($ActionTaken, 'stopWork') !== false) {
								$a.='
                                                <tr>
                                                    <td width="5%">&#9745;</td>
                                                    <td width="95%" align="left">STOP WORK</td>
                                                </tr>';
                            } else {
                                $a.='			<tr>
                                                    <td width="5%">&#9744;</td>
                                                    <td width="95%" align="left">STOP WORK</td>
                                                </tr>';
                               	 	}
                                if (strpos($ActionTaken, 'verbalWarning') !== false) {
								$a.='         <tr>
                                                    <td width="5%">&#9745;</td>
                                                    <td width="95%" align="left">VERBAL WARNING</td>
                                                </tr>';
                                } else {
								$a.='			<tr>
                                                    <td width="5%">&#9744;</td>
                                                    <td width="95%" align="left">VERBAL WARNING</td>
                                                </tr>';
                                }
								$a.='
                            </table>
                        
                </td>
                <td>
                        
                            <table border="0" width="100%">';
                                if (strpos($ActionTaken, 'revokeEntryPermit') !== false) {
									$a.='		<tr>
                                                    <td width="5%">&#9745;</td>
                                                    <td width="95%" align="left">REVOKE ENTRY PERMIT</td>
                                                </tr>';
                                } else {
                                    $a.='		<tr>
                                                    <td width="5%">&#9744;</td>
                                                    <td width="95%" align="left">REVOKE ENTRY PERMIT</td>
                                                </tr>';
                                }
                                if (strpos($ActionTaken, 'notifyRelatedDepartment') !== false) {
									$a.='		<tr>
                                                    <td width="5%">&#9745;</td>
                                                    <td width="95%" align="left">NOTIFY RELATED DEPARTMENT</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                    <input type="hidden" name="notifyRelatedDepartment" class="form-control">
                                                    </td>
                                                </tr>';
                                } else {
                                    $a.='		<tr>
                                                    <td width="5%">&#9744;</td>
                                                    <td width="95%" align="left">NOTIFY RELATED DEPARTMENT</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                    <input type="hidden" name="notifyRelatedDepartment" class="form-control">
                                                    </td>
                                                </tr>';
                                }
								$a.='
                            </table>
                        
                </td>
                <td>
                        
                            <table border="0" width="100%">';
                                if (strpos($ActionTaken, 'others') !== false) {
									$a.='	<tr>
                                               	<td width="5%">&#9745;</td>
                                               	<td width="95%" align="left">OTHERS</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                <input type="hidden" id="otherValue" name="otherValue" class="w-75 blank" value="<?php echo $otherValue; ?>" />
                                                </td>
                                            </tr>';
                                } else {
									$a.='<tr>
                                                <td width="5%">&#9744;</td>
                                                <td width="95%" align="left">OTHERS</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                <input type="hidden" id="otherValue" name="otherValue" class="w-75 blank" />
                                                </td>
                                            </tr>';
                                }
								$a.='
                            </table>
                        
                    
				</td>
				</tr>
            </table>';
    $a.='<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th bgcolor="#FFFFE0" align="center"><b>OBSERVER</b></th>
        <th bgcolor="#FFFFE0" align="center"><b>VIOLATOR</b></th>
        <th bgcolor="#FFFFE0" align="center"><b>VIOLATOR PASS</b></th>
    </tr>
    <tr>
        <td valign="top">
        <table border="0" width="100%" cellpadding="1" cellspacing="0">
        <tr>
        <td width="35%">NAME</td>
        <td width="65%" style="border-bottom:1px solid black">'.ucwords(strtolower(trim($observerName))).'</td>
        </tr>
        <tr>
        <td>DEPARTMENT</td>
        <td style="border-bottom:1px solid black">'.$observerDepartment.'</td>
        </tr>
        <tr>
        <td>DATE & TIME</td>
        <td style="border-bottom:1px solid black">'.$submitdate.'</td>
        </tr>
        <tr>
        <td>EMAIL</td>
        <td style="border-bottom:1px solid black">'.$observerEmail.'</td>
        </tr>
        </table>
        </td>
        <td valign="top">
            <table border="0" width="100%" cellpadding="1" cellspacing="0">
                <tr>
                    <td width="35%">NAME</td>
                    <td width="65%" style="border-bottom:1px solid black">'.ucwords(strtolower(trim($ViolatorName))).'</td>
                </tr>
                <tr>
                    <td>CONTACT NO</td>
                    <td style="border-bottom:1px solid black">'.$violatorContactNo.'</td>
                </tr>
                <tr>
                    <td>DEPARTMENT</td>
                    <td style="border-bottom:1px solid black">'.$ViolatorDept.'</td>
                </tr>
                <tr>
                    <td>COMPANY</td>
                    <td style="border-bottom:1px solid black">'.$violatorCompany.'</td>
                </tr>
                <tr>
                    <td>IC/PASSPORT</td>
                    <td style="border-bottom:1px solid black">'.$ViolatorIc.'</td>
                </tr>
                <tr>
                    <td>DATE & TIME</td>
                    <td style="border-bottom:1px solid black">'.$violatorDatetime.'</td>
                </tr>
            </table>
        </td>
        <td>'.photoEvidence(base64_decode($_GET['id']), 'Violator Evidence').'</td>
    </tr>
    </table>';
    

    $a.='<table border="1" cellpadding="5" cellspacing="0">
    <tr>
    <td bgcolor="#FFFFE0"><b>3. FOLLOW-UP ACTION</b> (To be filled by PSSD only)</td>
    </tr>
    </table>';

    $a.='<table border="1" cellpadding="5" cellspacing="0">
    <tr>
    <td colspan="2">
    <b>ACTION BY HSE PSSD</b></td>
    </tr>
    <tr>
    <td>
    
        <table border="0" cellpadding="0" cellspacing="0">';
    if ($followup !== null && strpos($followup, 'Verbal Warning') !== false) {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9745;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">VERBAL WARNING</label>
            </td>
        </tr>';
    } else {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9744;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">VERBAL WARNING</label>
            </td>
        </tr>';
    }
    if ($followup !== null && strpos($followup, 'Domestic Inquiry (DI)') !== false) {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9745;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">DOMESTIC INQUIRY</label>
            </td>
        </tr>';
    } else {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9744;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">DOMESTIC INQUIRY</label>
            </td>
        </tr>';
    }
    if ($followup !== null && strpos($followup, 'Blacklisted') !== false) {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9745;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">BLACKLISTED</label>
            </td>
        </tr>';
    } else {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9744;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">BLACKLISTED</label>
            </td>
        </tr>';
    }
    if ($followup !== null && strpos($followup, 'Notice/ Memo of safety violation') !== false) {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9745;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">NOTICE/ MEMO OF SAFETY VIOLATION</label>
            </td>
        </tr>';
    } else {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9744;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">NOTICE/ MEMO OF SAFETY VIOLATION</label>
            </td>
        </tr>';
    }
    if ($followup !== null && strpos($followup, 'Report to related parties/ Department') !== false) {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9745;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">REPORT TO RELATED PARTIES/ DEPARTMENT</label>
            </td>
        </tr>';
    } else {
        $a.='<tr align="left">
            <td width="5%" valign="center">
            &#9744;
            </td>
            <td width="95%" valign="center">
                <label class="form-check-label" for="actionTakenByHsePssd">REPORT TO RELATED PARTIES/ DEPARTMENT</label>
            </td>
        </tr>';
    }

    $a.='</table>';

    $a.='</td>
    <td>
    <table border="0" cellpadding="0" cellspacing="0">';

    if ($followup !== null && strpos($followup, 'Fine RM ') !== false) {
        $a.='<tr align="left">
                <td width="5%" valign="center">
                &#9745;
                </td>
                <td width="95%" valign="center">
                    FINE RM '.$fineamount.'
                </td>
            </tr>';
    } else {
        $a.='<tr align="left">
                <td width="5%" valign="center">
                &#9744;
                </td>
                <td width="95%" valign="center">
                    FINE RM '.$fineamount.'
                </td>
            </tr>';
    }
    if ($followup !== null && strpos($followup, 'Attend Induction (PEP)') !== false) {
        $a.='<tr align="left">
                <td width="5%" valign="center">
                &#9745;
                </td>
                <td width="95%" valign="center">
                    ATTEND INDUCTION (PEP)
                </td>
            </tr>';
    } else {
        $a.='<tr align="left">
                <td width="5%" valign="center">
                &#9744;
                </td>
                <td width="95%" valign="center">
                    ATTEND INDUCTION (PEP)
                </td>
            </tr>';
    }
    if ($followup !== null && strpos($followup, 'Attend briefing (VEP) - FROM ') !== false) {
        $a.='';
    } else {
        $a.='';
    }
    if ($followup !== null && strpos($followup, 'Banned ') !== false) {
        $a.='<tr align="left">
                <td width="5%" valign="center">
                &#9745;
                </td>
                <td width="95%" valign="center">
                    BANNED '.$bannedMonths.' UNTIL '.$bannedMonths2.'
                </td>
            </tr>';
    } else {
        $a.='<tr align="left">
                <td width="5%" valign="center">
                &#9744;
                </td>
                <td width="95%" valign="center">
                    BANNED '.$bannedMonths.' UNTIL '.$bannedMonths2.'
                </td>
            </tr>';
    }
    if ($followup !== null && strpos($followup, 'Banned ') !== false) {
        $a.='<tr align="left">
                <td width="5%" valign="center">
                &#9745;
                </td>
                <td width="95%" valign="center">
                    OTHERS '.$otherAction.'
                </td>
            </tr>';
    } else {
        $a.='<tr align="left">
                <td width="5%" valign="center">
                &#9744;
                </td>
                <td width="95%" valign="center">
                    OTHERS '.$otherAction.'
                </td>
            </tr>';
    }
    
    $a.='</table>
    </td>
    </tr>
    </table>';
    
    $a.='<table border="1" cellpadding="5" cellspacing="0">
<tr>
<td>DATE OF ACTION TAKEN '.$followupdate.'</td>
<td>ACTION TAKEN BY PSSD HSE OFFICER</td>
</tr>
</table>';

$pdf->writeHTML($a, true, false, true, false,'');




//mysql_free_result($result);
mysqli_close($db);
$pdf->Output('ucua-report.pdf', 'I');
?>