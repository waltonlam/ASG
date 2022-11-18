<?php
namespace Phppot;
use Phppot\DataSource;
require_once "iconn.php";
require_once "header2.php";
require_once __DIR__ . '/lib/CompoundModel.php';
$compoundModel = new CompoundModel();

/*if (isset($_POST["submit"])) {
    $result = $imageModel->uploadImage();
    $id = $imageModel->updateImage($result, $_GET["id"], $_POST['site_code'], $_POST['remark']);
}*/

$result = $compoundModel->selectCompoundById($_GET["id"]);
$yearFieldBlankAvg = 0;
$yearFieldBlankAvgSameLoc = 0;
$percentile = 0;
$last3YrsConcList;
$avgFrmThreeYrs = 0;
$coLocateSample;
$percentageDiff = 0;

if(substr($result[0]["sample_id"],5,1) == 'F'){
	$yearFieldBlankAvg = $compoundModel->calAvgFieldBlank($result[0]["compound"], $result[0]["compound_grp"], substr($result[0]["sample_id"],6,2));
	$yearFieldBlankAvgSameLoc = $compoundModel->calAvgFieldBlankSameLoc($result[0]["site_id"], $result[0]["compound"], $result[0]["compound_grp"], substr($result[0]["sample_id"],6,2));
	
}else if(substr($result[0]["sample_id"],5,1) == 'S'){
	if(substr($result[0]["sample_id"],-2) != 'A2'){
		$last3YrsConcList = $compoundModel->getConcFrmLast3Yrs($result[0]["site_id"], $result[0]["compound"], $result[0]["compound_grp"], $result[0]["strt_date"]);
		$percentile = $compoundModel->calPercentile((array)$last3YrsConcList, 99);
		$avgFrmThreeYrs = $compoundModel->calAvgFrmLast3Yrs($result[0]["site_id"], $result[0]["compound"], $result[0]["compound_grp"], $result[0]["strt_date"]);
	}

	if(strlen($result[0]["sample_id"]) > 12){
		$coLocateSample = $compoundModel->getCoLocatedSample($result[0]["sample_id"], $result[0]["site_id"], $result[0]["compound"], $result[0]["compound_grp"]);
		$percentageDiff = $compoundModel->calPercentageDiff($result[0]["conc_g_m3"], $coLocateSample[0]["conc_g_m3"]);
	}
}

?>
<html>
	<head>
		<link href="assets/style.css" rel="stylesheet" type="text/css" />
		<style>
			input[type=button], input[type=submit], input[type=reset] {
				background-color: #4D9BF3;
				border: none;
				color: white;
				padding: 16px 32px;
				text-decoration: none;
				margin: 4px 2px;
				cursor: pointer;
			}
		</style>
	</head>
	<body>
		<div>
			<h2>Compound Detail</h2>
			<hr>
			<!--span id="message" style="color:red"></span-->
			<!--form action="?id=<?php //echo $result[0]['id']; ?>" method="post" name="frm-edit" enctype="multipart/form-data"	onsubmit="return imageValidation()"-->
			<form action="?id=<?php echo $result[0]['id']; ?>" method="post" name="frm-edit" enctype="multipart/form-data">
				<br>
				<table>
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
							<input type="text" name="compound" value ="<?php echo $result[0]["compound"]?>" />
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">Compound Group: </td>
						<td>
							<input type="text" name="compound_grp" value="<?php echo $result[0]["compound_grp"]?>" />
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">CONC (µg/m3): </td>
						<td>
							<input type="text" name="conc_g_m3" value="<?php echo $result[0]["conc_g_m3"]?>" />
						</td>
					</tr>	

					<?php if(substr($result[0]["sample_id"],5,1) == 'S' and substr($result[0]["sample_id"],-2) != 'A2'){ ?>
					<tr>	
						<td style="vertical-align: top;"> 99th Percentile of Last 3 Years: </td>
						<td>
							<input type="text" name="percentile" value="<?php echo number_format((float)$percentile, 2, '.', '')?>" readonly/>
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">CONC (µg/m3) Average of Last 3 Years: </td>
						<td>
							<input type="text" name="avg_conc_g_m3" value="<?php echo number_format((float)$avgFrmThreeYrs[0]["avg_conc_g_m3"], 2, '.', '')?>" readonly/>
						</td>
					</tr>
					<?php } ?>

					<?php if(strlen($result[0]["sample_id"]) > 12){ ?>
					<tr>	
						<td style="vertical-align: top;">Co-located Sample ID: </td>
						<td>
							<input type="text" name="coLocatedSampleId" value="<?php echo $coLocateSample[0]["sample_id"]?>" readonly/>
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">CONC (µg/m3)  of Co-located Sample: </td>
						<td>
							<input type="text" name="concCoLocated" value="<?php echo number_format((float)$coLocateSample[0]["conc_g_m3"], 2, '.', '')?>" readonly/>
						</td>
					</tr>

					<tr>	
						<td style="vertical-align: top;"> Percentage Difference of Co-located Sample: </td>
						<td>
							<input type="text" name="percentageDiff" value="<?php echo number_format((float)$percentageDiff, 2, '.', '').'%'?>" readonly/>
						</td>
					</tr>
					<?php } ?>

					<?php if(substr($result[0]["sample_id"],5,1) == 'F'){ ?>
					<tr>	
						<td style="vertical-align: top;">[<?php echo $result[0]["site_id"]?>] Yearly Field Blank Average of 20<?php echo substr($result[0]["sample_id"],6,2) ?>: </td>
						<td>
							<input type="text" name="avg_conc_g_m3_sameLoc" value="<?php echo number_format((float)$yearFieldBlankAvgSameLoc[0]["avg_conc_g_m3"], 2, '.', '')?>" disabled/>
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">Yearly Field Blank Average of 20<?php echo substr($result[0]["sample_id"],6,2) ?>: </td>
						<td>
							<input type="text" name="avg_conc_g_m3" value="<?php echo number_format((float)$yearFieldBlankAvg[0]["avg_conc_g_m3"], 2, '.', '')?>" disabled/>
						</td>
					</tr>
					<?php } ?>
				</table>
				<br>
				<hr>
				<!--input type="submit" name="submit" value="Submit"--> 
				<input type="button" name="cancel" value="Cancel" onClick="document.location.href='showGlabSample.php'"/>	
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>