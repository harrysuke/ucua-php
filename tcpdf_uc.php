<?php
set_time_limit(20);
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include('testFunctions.php');

class MYPDF extends TCPDF {

	function Header(){
		$this->SetFont('dejavusans','',6);
		$this->SetTextColor(0,0,0);
		$this->Ln(3);
		$head='';
		$this->writeHTML($head, true, false, true, false,'');
		$this->Ln(10);
	}
	function Footer(){
		//$this->SetY(-70);
		$this->SetFont('dejavusans','',7);
		//$this->SetTextColor(128);
		//$kaki='';
		$kaki='';
		$this->writeHTML($kaki, true, false, true, false,'');
		// Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//Hide Header Footer
//$pdf->SetPrintHeader(false); $pdf->SetPrintFooter(false); 

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


// set default monospaced font 
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks 
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); 

//set image scale factor 
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);  

//set some language-dependent strings 
$pdf->setLanguageArray($l);  

// --------------------------------------------------------- 

// set font 
$pdf->SetFont('dejavusans', '', 7); 

// add a page 
$pdf->AddPage(); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Kairi Juri');
$pdf->SetTitle('DGC Invoice');
$pdf->SetSubject('DGC Invoice');
$pdf->SetKeywords('TCPDF, PDF, DGC Invoice');

$dimensions = $pdf->getPageDimensions();
$hasBorder = false; //flag for fringe case

function photoEvidence($id){
    $img = '';
    global $db;
    $sql = "SELECT evidenceImage FROM ucuaphoto WHERE ucuaform_Id = '".$id."' AND type='Photo Evidence'";
    $result = mysqli_query($db, $sql);
    while($row = mysqli_fetch_array($result)){
		$img .= '<a href="'.$row['evidenceImage'].'" class="image-popup-desc" data-title="Photo Evidence" data-description="Photo evidence description.">';
		$img .= '<img src="'.$row['evidenceImage'].'" alt="Photo Evidence" class="img-thumbnail w-25">';
		$img .= '</a>&nbsp;';
    }
    return $img;
  }

  function closedEvidence($id){
    $img = '';
    global $db;
    $sql = "SELECT evidenceImage FROM ucuaphoto WHERE ucuaform_Id = '".$id."' AND type='Close Evidence'";
    $result = mysqli_query($db, $sql);
    while($row = mysqli_fetch_array($result)){
		$img .= '<a href="'.$row['evidenceImage'].'" class="image-popup-desc" data-title="Photo Evidence" data-description="Photo evidence description.">';
		$img .= '<img id="myImg" src="'.$row['evidenceImage'].'" alt="Photo Evidence" width="200">';
		$img .= '</a>&nbsp;';
    }
    return $img;
  }

function funLocation(){
    $option = '';
    global $db;
    $sql1 = "SELECT LocationId, LocationName FROM location";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $option .= '<option value="'.$row1['LocationId'].'">'.$row1['LocationName'].'</option>';
    }
    return $option;
}

function funDepartment(){
	$option = '';
    global $db;
	$sql = "SELECT dept AS deptCode, deptName FROM dept ORDER BY deptName";
	$result = mysqli_query($db, $sql);
	while ($row = mysqli_fetch_array($result)) {        
		$option .= '<option value="'.$row['deptCode'].'">'.$row['deptName'].' ('.$row['deptCode'].')</option>';
	}
	return $option;
}

//ISI
$reportid = base64_decode($_GET['id']);
    $sql = "SELECT ucuaform.Id, ucuaform.conditionDetails, ucuaform.ActionTaken, ucuaform.evidenceImage, 
	ucuaform.observerName, ucuaform.observerDepartment, ucuaform.observerEmail, ucuaform.observerConditionDetails,
	ucuaform.closedEvidence,location.LocationName, ucuaform.isclosed, ucuaform.closedby, ucuaform.closeddate, ucuaform.closedEvidence FROM ucuaform INNER JOIN location ON ucuaform.LocationId=location.LocationId WHERE `Id` = '$reportid'";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result);
    $LocationName  = $row['LocationName'];
    $conditionDetails = $row['conditionDetails'];
    $test = $row["evidenceImage"];
    $ActionTaken = $row['ActionTaken'];
    $observerName = $row['observerName'];
    $observerDepartment = $row['observerDepartment'];
    $observerEmail = $row['observerEmail'];
    $observerConditionDetails = $row['observerConditionDetails'];
    $isclosed = $row['isclosed'];
    $closedby = $row['closedby'];
    $closeddate = $row['closeddate'];
    $closedEvidence = $row['closedEvidence'];
	
	$pdf->Ln(5);
	$pdf->SetFont('dejavusans','',7);
	
	$isi = '<table border="1" width="95%" cellpadding="5" cellspacing="5" class="uitable shadow mb-4">
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
						<input type="text" class="form-control" id="location" name="location" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?php echo $LocationName; ?>" />
					</div>
				</div>
			</div>
			<div class="mb-3 row">
				<label for="offenceCode" class="col-sm-4 col-form-label">Condition Details :</label>
				<div class="col-sm-8">
					<div class="input-group input-group-sm">
						<textarea class="form-control" id="conditionDetails" name="conditionDetails" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"><?php echo strval($conditionDetails); ?></textarea>
					</div>
				</div>
			</div>
			<div class="mb-3 row">
				<label for="offenceCode" class="col-sm-4 col-form-label">Evidence :</label>
				<div class="col-sm-8">'.photoEvidence(base64_decode($_GET['id'])).'</div>
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
					<table width="100%" class="noborder">';
						$action = explode(', ',$ActionTaken);
						if($action[0] != 'Others'){
							$isi .= '<tr>
							<td><input class="" type="radio" name="actionTaken" id="actionTaken" value="Notify Related Department" checked  /></td>
							<td align="left">NOTIFY RELATED DEPARTMENT: (ICT) MOHAMAD TAUFIK ABDUL RAZAK (mtaufik@johorport.com.my))</td>
						</tr>
						<tr>
							<td><input class="" type="radio" name="actionTaken" id="actionTaken" value="Others" /></td>
							<td align="left">
								OTHERS: <input type="text" name="others" class="w-75 blank" />
							</td>
						</tr>';
						}else{
							$isi .= '<tr>
							<td><input class="" type="radio" name="actionTaken" id="actionTaken" value="Notify Related Department" aria-label="..."  /></td>
							<td align="left">NOTIFY RELATED DEPARTMENT: (ICT) MOHAMAD TAUFIK ABDUL RAZAK (mtaufik@johorport.com.my))</td>
						</tr>
						<tr>
							<td><input class="" type="radio" name="actionTaken" id="actionTaken" value="Others" checked  /></td>
							<td align="left">
								OTHERS: <input type="text" name="others" class="w-75 blank" value="'.$action[1].'" />
							</td>
						</tr>';
						}
					$isi .= '</table>
					 
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
				<input type="text" class="form-control" id="observerName" name="observerName" placeholder="Name" value="<?php echo $observerName;?>">
			</div>
			<div class="mb-3">
				<label for="observerDepartment" class="form-label">Department:</label>
				<select class="form-control js-select bg-light" id="observerDepartment" name="observerDepartment">
				<?php echo funDepartment();?>
				</select>
			</div>
			<div class="mb-3">
				<label for="observerEmail" class="form-label">Email:</label>
				<input type="email" class="form-control" id="observerEmail" name="observerEmail" placeholder="Email" value="<?php echo $observerEmail;?>">
			</div>
			<div class="mb-3">
				<label for="observerConditionDetails" class="form-label">Condition Details:</label>
				<textarea class="form-control" id="observerConditionDetails" name="observerConditionDetails" placeholder="Condition Details" ><?php echo $observerConditionDetails;?>
				</textarea>
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
			STATUS: <span class="badge text-bg-warning">PENDING</span>
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
			STATUS: <span class="badge text-bg-warning">PENDING</span>
			<p>&nbsp;</p>
			<center>
			
				<button type="submit" name="btnReasign" class="btn btn-success" id="btnNotify"><span class="text text-white"><i class="fa-solid fa-circle-exclamation"></i> Reasign</span></button>
			
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
					STATUS: <span class="badge text-bg-warning">PENDING</span><br/>
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
					STATUS: <span class="badge text-bg-warning">PENDING</span><br/>
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
					STATUS: <span class="badge text-bg-warning">PENDING</span>
				</div>
			</div>
		</td>
	</tr>
	<tr align="left">
		<td colspan="2">
			<div class="row ms-5">
				<div class="col-11">
					Remark:<br/>
					<textarea name="remark" class="form-control"></textarea><br/>
					
					<table width="100%" class="noborder">
						<tr>
							<td width="20%" align="left">
								<label for="formFile" class="form-label">Take Action Upload : </label>
							</td>
							<td>
								<div class="custom-file">
									<input class="form-control" type="file" name="files[]" id="formFile" multiple>
								</div>
							</td>
						</tr>
					</table>
					<p></p>
				</div>
			</div>
			
		</td>
	</tr>
</table>';
	$pdf->writeHTML($isi, true, false, true, false,'');
	
	$pdf->SetFont('dejavusans','B',7);
//$pdf->Cell(10,4,'Item(s)');
//mysql_free_result($result);
mysqli_close($db);
$pdf->Output();
?>