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

if(substr($result[0]["sample_id"],5,1) == 'F'){
	$yearFieldBlankAvg = $compoundModel->calAvgFieldBlank($result[0]["compound"], $result[0]["compound_grp"], substr($result[0]["sample_id"],6,2));
	$yearFieldBlankAvgSameLoc = $compoundModel->calAvgFieldBlankSameLoc($result[0]["site_id"], $result[0]["compound"], $result[0]["compound_grp"], substr($result[0]["sample_id"],6,2));
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
			<!--form action="?id=<?php echo $result[0]['id']; ?>" method="post" name="frm-edit" enctype="multipart/form-data"	onsubmit="return imageValidation()"-->
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
						<td style="vertical-align: top;">Site: </td>
						<td>
							<input type="text" name="site_id" value="<?php echo $result[0]["site_id"]?>" />
						</td>
					</tr>	
					<?php if(substr($result[0]["sample_id"],5,1) == 'F'){ ?>
					<tr>	
						<td style="vertical-align: top;">[<?php echo $result[0]["site_id"]?>] Yearly Field Blank Average of 20<?php echo substr($result[0]["sample_id"],6,2) ?>: </td>
						<td>
							<input type="text" name="avg_ppbv_sameLoc" value="<?php echo number_format((float)$yearFieldBlankAvgSameLoc[0]["avg_ppbv"], 2, '.', '')?>" disabled/>
						</td>
					</tr>
					<tr>	
						<td style="vertical-align: top;">Yearly Field Blank Average of 20<?php echo substr($result[0]["sample_id"],6,2) ?>: </td>
						<td>
							<input type="text" name="avg_ppbv" value="<?php echo number_format((float)$yearFieldBlankAvg[0]["avg_ppbv"], 2, '.', '')?>" disabled/>
						</td>
					</tr>
					<?php } ?>
					<!--tr>
						<td></td>
						<td>
							<input type="submit" name="submit" value="Submit"> 
							<input type="button" name="cancel" value="Cancel" onClick="document.location.href='showGlabSample.php'"/>
						</td>	
					</tr-->
				</table>
				<br>
				<hr>
				<input type="button" name="cancel" value="Cancel" onClick="document.location.href='showGlabSample.php'"/>	
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>
