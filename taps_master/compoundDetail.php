<?php
	namespace Phppot;
	use Phppot\DataSource;
	require_once "iconn.php";
	require_once "header2.php";
	require_once __DIR__ . '/lib/CompoundModel.php';
	$compoundModel = new CompoundModel();

	$recordId = $_GET["id"];
	$result = $compoundModel->selectCompoundById($recordId);
	$yearFieldBlankAvg = 0;
	$yearFieldBlankAvgSameLoc = 0;
	$percentile = 0;
	$last3YrsConcList;
	$avgFrmThreeYrs = 0;
	$coLocateSample;
	$percentageDiff = 0;
	$totalTEF = 0;
	$ttlTefUnit = "";

	//Get the QC criteria parameters for calculation
	$qcCriteria = $compoundModel->getDeviationByCompoundGrp($result[0]["compound_grp"]);

	if(substr($result[0]["sample_id"],5,1) == 'F'){
		$yearFieldBlankAvg = $compoundModel->calAvgFieldBlank($result[0]["compound"], $result[0]["compound_grp"], substr($result[0]["sample_id"],6,2));
		$yearFieldBlankAvgSameLoc = $compoundModel->calAvgFieldBlankSameLoc($result[0]["site_id"], $result[0]["compound"], $result[0]["compound_grp"], substr($result[0]["sample_id"],6,2));
		
	}else if(substr($result[0]["sample_id"],5,1) == 'S'){
		if(substr($result[0]["sample_id"],-2) != 'A2'){
			$last3YrsConcList = $compoundModel->getConcFrmLast3Yrs($result[0]["site_id"], $result[0]["compound"], $result[0]["compound_grp"], $result[0]["strt_date"], $qcCriteria[0]["year_avg"]);
			$percentile = $compoundModel->calPercentile((array)$last3YrsConcList, $qcCriteria[0]["percentile"]);
			$avgFrmThreeYrs = $compoundModel->calAvgFrmLast3Yrs($result[0]["site_id"], $result[0]["compound"], $result[0]["compound_grp"], $result[0]["strt_date"], $qcCriteria[0]["year_avg"]);
		}

		if(strlen($result[0]["sample_id"]) > 12){
			$coLocateSample = $compoundModel->getCoLocatedSample($result[0]["sample_id"], $result[0]["site_id"], $result[0]["compound"], $result[0]["compound_grp"]);
			/*if(empty($coLocateSample[0]["conc_g_m3"])){
				echo "1234";
				$type = "error";
				$message = "The conc g/m3 for co-located sample is not ready.";
			}*/
			if(!empty($coLocateSample[0]["conc_g_m3"])){
				$percentageDiff = $compoundModel->calPercentageDiff($result[0]["conc_g_m3"], $coLocateSample[0]["conc_g_m3"]);
			}
		}
	}

	if(isset($_POST['calTef'])){
		if($_POST['tef_ratio'] != "Please select") {
			$selected = $_POST['tef_ratio'];
			if($selected == "I-TEF"){
				$ttlTefUnit = " pg i-TEQ/m3";
			}else{
				$ttlTefUnit = " pg WHO-TEQ/m3";
			}
			$totalTEF = $compoundModel->calTEF($selected, $result[0]["sample_id"], $result[0]["site_id"]);
		}else{
			//$err_msg = "Please select a TEF ratio.";
			$type = "error";
			$message = "Please select a TEF ratio.";
		}
	}

	if (isset($_POST["submit"])) {
		if(strlen($_POST['sampleId']) > 14){
			$type = "error";
			$message = "Sample ID should not exceed 14 characters.";
		}else if(preg_match('/[\'^??$%&*()}{@#~?><>,|=_+??-]/', $_POST['sampleId'])){
			$type = "error";
			$message = "Sample ID should not contains special characters.";
		}else if(strlen($_POST['site_id']) > 3){
			$type = "error";
			$message = "Site should not exceed 3 characters.";
		}else if(preg_match('/[\'^??$%&*()}{@#~?><>,|=_+??-]/', $_POST['site_id'])){
			$type = "error";
			$message = "Site should not contains special characters.";
		}else{
			$id = $compoundModel->updateSiteIdSampleId($recordId, $_POST['sampleId'], $_POST['site_id'], $_POST['status']);
			echo "<meta http-equiv='refresh' content='0'>";
		}
	}
?>
<html>
	<head>
		<link href="assets/style.css" rel="stylesheet" type="text/css" />
		<style>
			input[type=button], input[type=submit], input[type=reset] {
				background-color: #87ceeb;
				color: white;
				padding: 12px 10px;
				border: none;
				border-radius: 4px;
				cursor: pointer;
				width:100
			}

			#response {
				padding: 10px;
				margin-bottom: 10px;
				border-radius: 5px;
				display: none;
			}

			.success {
				background: #c7efd9;
				border: #bbe2cd 1px solid;
			}

			.error {
				background: #fbcfcf;
				border: #f3c6c7 1px solid;
			}

			div#response.display-block {
				display: block;
			}
		</style>
	</head>
	<body>
		<div>
			<h2 style="margin-left:10px">Compound Detail</h2>
			<hr>
			<!--form action="?id=<?php //echo $result[0]['id']; ?>" method="post" name="frm-edit" enctype="multipart/form-data"	onsubmit="return imageValidation()"-->
			<form action="?id=<?php echo $result[0]['id']; ?>" method="post" name="frm-edit" enctype="multipart/form-data">
				<div id="response"
					class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
					<?php if(!empty($message)) { echo $message; } ?>
				</div>
				<table style="margin-left:10px">
					<tr>	
						<td style="width: 330px; vertical-align: top;">Sample ID: </td>
						<td>
							<input type="text" name="sampleId" value ="<?php echo $result[0]["sample_id"]?>" />
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">Site: </td>
						<td>
							<input type="text" name="site_id" value="<?php echo $result[0]["site_id"]?>" />
						</td>
					</tr>	
					<tr>	
						<td style="vertical-align: top;">Compound: </td>
						<td>
							<label><?php echo $result[0]["compound"]?></label>
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">Compound Group: </td>
						<td>
							<label><?php echo $result[0]["compound_grp"]?></label>
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">CONC (??g/m3): </td>
						<?php if(!empty ($result[0]["conc_g_m3"])){?>
							<td>
								<label><?php echo $result[0]["conc_g_m3"]?></label>
							</td>
						<?php } else { ?>
							<td>
								<label><span style="color:red">The CONC (??g/m3) is not ready.</span></label>
							</td>
						<?php } ?>
					</tr>	

					<?php if(substr($result[0]["sample_id"],5,1) == 'S' and substr($result[0]["sample_id"],-2) != 'A2'){ ?>
					<tr>	
						<td style="vertical-align: top;"> 99th Percentile of Last 3 Years: </td>
						<td>
							<label><?php echo number_format((float)$percentile, 2, '.', '')?></label>
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">CONC (??g/m3) Average of Last 3 Years: </td>
						<td>
							<label><?php echo number_format((float)$avgFrmThreeYrs[0]["avg_conc_g_m3"], 2, '.', '')?></label>
						</td>
					</tr>
					<?php } ?>

					<?php if(strlen($result[0]["sample_id"]) > 12){ ?>
					<tr>	
						<td style="vertical-align: top;">Co-located Sample ID: </td>
						<td>
							<label><?php echo $coLocateSample[0]["sample_id"]?></label>
						</td>
					</tr>
						<?php if(!empty($coLocateSample[0]["conc_g_m3"])){ ?>
							<tr>	
								<td style="vertical-align: top;">CONC (??g/m3)  of Co-located Sample: </td>
								<td>
									<label><?php echo number_format((float)$coLocateSample[0]["conc_g_m3"], 2, '.', '')?></label>
								</td>
							</tr>
						<?php } else { ?>
							<tr>	
								<td style="vertical-align: top;">CONC (??g/m3)  of Co-located Sample: </td>
								<td>
									<label><span style="color:red">The CONC (??g/m3) of Co-located Sample is not ready.</span></label>
								</td>
							</tr>
						<?php } ?>
					<tr>	
						<td style="vertical-align: top;"> Percentage Difference of Co-located Sample: </td>
						<td>
							<label><?php echo number_format((float)$percentageDiff, 2, '.', '').'%'?></label>
						</td>
					</tr>
					<?php } ?>

					<?php if(substr($result[0]["sample_id"],5,1) == 'F'){ ?>
					<tr>	
						<td style="vertical-align: top;">[<?php echo $result[0]["site_id"]?>] Yearly Field Blank Average of 20<?php echo substr($result[0]["sample_id"],6,2) ?>: </td>
						<td>
							<label><?php echo number_format((float)$yearFieldBlankAvgSameLoc[0]["avg_conc_g_m3"], 2, '.', '')?></label>
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">Yearly Field Blank Average of 20<?php echo substr($result[0]["sample_id"],6,2) ?>: </td>
						<td>
							<label><?php echo number_format((float)$yearFieldBlankAvg[0]["avg_conc_g_m3"], 2, '.', '')?></label>
						</td>
					</tr>
					<?php } ?>

					<?php if($result[0]["compound_grp"] == 'DF' or $result[0]["compound_grp"] == 'DI_PB'){ ?>
					<tr>	
						<td style="vertical-align: top;">TEF Ratio: </td>
						<td style="vertical-align: top;" >
							<select name="tef_ratio" id="tef_ratio">
								<option>Please select</option>
								<option <?php if (isset($selected) && $selected=="I-TEF") echo "selected";?>>I-TEF</option>
								<option <?php if (isset($selected) && $selected=="WHO-TEF-1998") echo "selected";?>>WHO-TEF-1998</option>
								<option <?php if (isset($selected) && $selected=="WHO-TEF-2005") echo "selected";?>>WHO-TEF-2005</option>
							</select>
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">Total Toxicity Equivalence: </td>
						<td>
							<label><?php echo number_format((float)$totalTEF[0]["total_tef"], 1, '.', '').$ttlTefUnit?></label>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td style="vertical-align: top;">Status: </td>
						<td>
							<input type="radio" name="status" <?php if ($result[0]["status"]=="Y") echo "checked";?> value="Y">Valid
							&nbsp;&nbsp;
							<input type="radio" name="status" <?php if ($result[0]["status"]=="N") echo "checked";?> value="N">Invalid
						</td>
					</tr>
				</table>
				<hr>
				<?php if($result[0]["compound_grp"] == 'DF' or $result[0]["compound_grp"] == 'DI_PB'){ ?>
					<input type="submit" style="margin-left:10px" name="calTef" value="Cal. TEF"/>
				<?php } ?>
				<?php if($_SESSION['utp']==0){ ?>
					<input type="submit" style="margin-left:10px" name="submit" value="Save"> 
				<?php } ?>
				<input type="button" style="margin-left:10px" name="cancel" value="Cancel" onClick="document.location.href='showGlabSample.php'"/>	
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>