<?php
namespace Phppot;
use Phppot\DataSource;

ini_set('max_execution_time', 0);
set_time_limit(1800);
ini_set('memory_limit', '-1');
require_once 'sqlHelper.php';
session_start();
require_once "connection.php"; 
include('header2.php');
include('iconn.php');
include('fn.php');

if (isset($_SESSION['previous'])) {
	if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
		 //session_destroy();
		 unset($_SESSION['site_id']);
		 unset($_SESSION['dateFrom']);
		 unset($_SESSION['dateTo']);
		 ### or alternatively, you can use this for specific variables:
		 ### unset($_SESSION['varname']);
	}
}

if (isset($_SESSION['prev_incid'])) {
	if (basename($_SERVER['PHP_SELF']) != $_SESSION['prev_incid']) {
		 //session_destroy();
		 unset($_SESSION['site_code_inc']);
		 unset($_SESSION['dateFrom_inc']);
		 unset($_SESSION['dateTo_inc']);
		 ### or alternatively, you can use this for specific variables:
		 ### unset($_SESSION['varname']);
	}
}

if (isset($_POST["import"])) {
    $fileName = $_FILES["file"]["tmp_name"];	
    if ($_FILES["file"]["size"] > 0) {		
        $file = fopen($fileName, "r");
        $r_in=0;
		$count_duplicate=0;

        mysqli_autocommit($dbc, FALSE);
        mysqli_begin_transaction($dbc, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
		$header = null;
		
		while (($row = fgetcsv($file, 50000)) !== false){
			if(!$header){
				$header = $row;
			}else{
				$data[] = array_combine($header, $row);
			}
		}
		fclose($file);

		$sampleId = "";
		$samplingDate = "";
		$siteId = "";
		$compound = "";
		$compoundGrp = "";
		$concSample= "";
		$concGM3= "";
		$samplingTime= "";
		$flowRate= "";
		$volume= "";
		$testCount = 0;

		$DioxinCollection = array();
		//$DioxinCollection = array((object)['sample_id' => 'CWSDFS220709', 'compound_grp' =>'DF']);

		foreach($data as $key => $result) {		
			$testCount++;
			foreach($result  as $key => $value){
				//echo $key;
				if (strpos ($key,'sample_id') !== false){
					$sampleId = $value;
					//$compoundGrp = substr($sampleId,3,2);
				}
				
				if (strpos ($key,'sampling_date') !== false){
					$samplingDate = $value; 
				}
				
				if (strpos ($key,'compound_grp') !== false){
					$compoundGrp = $value;
				}

				if (strpos ($key,'site_id') !== false){
					$siteId = $value;
				}

				if (strpos ($key,'compound') !== false){
					$compound = $value;
					//echo "Compound: ".$compound;
				}
				
				if (strpos ($key,'conc_sample') !== false){
					$concSample = $value;
				}

				if (strpos ($key,'sampling_time') !== false){
					$samplingTime = $value;
				}

				if (strpos ($key,'flow_rate') !== false){
					$flowRate = $value;
				}

				if (strpos ($key,'volume') !== false){
					$volume = $value;
				}	
			}

			$concGM3 = ($flowRate * $samplingTime) / 1000;
			//echo '$conc='.$concGM3;
			//echo 'group = '.$compoundGrp;
			if($compoundGrp == 'DF' or $compoundGrp == 'Dl-PB'){
				$select_factor_qry = "SELECT * FROM factor WHERE compound = '".$compound."';";
				$factorRst=mysqli_query($dbc, $select_factor_qry);
				$factorRow = mysqli_fetch_row($factorRst);  
				$iTef = $concGM3 * $factorRow[4];
				$whoTef2005 = $concGM3 * $factorRow[3];
				$whoTef1998 = $concGM3 * $factorRow[2];
			}

			//echo '$iTef'.$iTef;
			//echo '$whoTef2005'.$whoTef2005;
			//echo '$whoTef1998'.$whoTef1998;

			$select_qry = "SELECT * FROM contractor_sample WHERE sample_id = '".$sampleId."'
							AND compound = '".$compound."'
							AND compound_grp = '".$compoundGrp."'
							AND CURRENT_TIMESTAMP > create_date";

			$checkDupRes=mysqli_query($dbc, $select_qry);
			$rowcount=mysqli_num_rows($checkDupRes); 
			if ($rowcount == 0) {
				if (!empty($sampleId)){
					if($compoundGrp == 'DF' or $compoundGrp == 'Dl-PB'){
						//Check if sample id is not existed in dixon array
						if(!in_array((object)['sample_id' => $sampleId,'compound_grp' => $compoundGrp], $DioxinCollection)) {
							//echo "The key is not existed in the array";
							array_push($DioxinCollection, (object)['sample_id' => $sampleId,'compound_grp' => $compoundGrp]);
						}

						$updateQuery = "UPDATE glab_sample SET conc_g_m3='".$concGM3."', i_tef='".$iTef."', who_tef_2005='".$whoTef2005."', who_tef_1998 ='".$whoTef1998."', last_upd_date = CURRENT_DATE, last_upd_by ='".$_SESSION['vuserid']."' WHERE sample_id = '".$sampleId."' and compound = '".$compound."' and compound_grp = '".$compoundGrp."';";
					}else{
						$updateQuery = "UPDATE glab_sample SET conc_g_m3='".$concGM3."', last_upd_date = CURRENT_DATE, last_upd_by ='".$_SESSION['vuserid']."' WHERE sample_id = '".$sampleId."' and compound = '".$compound."' and compound_grp = '".$compoundGrp."';";
					}
					//echo $updateQuery;
					$resUpd=mysqli_query($dbc, $updateQuery);

					$in1 = "INSERT INTO `contractor_sample` (`sample_id`, `sampling_date`, `site_id`, `compound`, `compound_grp`, `conc_sample`, `sampling_time`,`flow_rate`, `volume`, `create_date`, `create_by`, `last_upd_date`, `last_upd_by`) 
					VALUES ('".$sampleId."','".$samplingDate."','".$siteId."','".$compound."','".$compoundGrp."','".$concSample."','".$samplingTime."','".$flowRate."','".$volume."', current_timestamp, '".$_SESSION['vuserid']."', current_timestamp, '".$_SESSION['vuserid']."');";
					//echo $in1;

					$res=mysqli_query($dbc, $in1); 
					/* or trigger_error("Query Failed! SQL: $in1 - Error: ".mysqli_error($dbc), E_USER_ERROR);*/
								
					if (!empty($res)) {
						$r_in++;
						$type = "success";
						$message = "*No. of contractor records have been imported : ".$r_in;
					} else {
						$type = "error";
						print "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in1."</p>";
						exit();
					}

					if ($type == "success"){
						mysqli_commit($dbc);        
					}else{
						mysqli_rollback($dbc);
					}
				}
			}else{
				$count_duplicate++;
				$type = "error";
				$message = "*No. of contractor records are duplicated : ".$count_duplicate;
			}
		}
		mysqli_autocommit($dbc, TRUE);

		foreach($DioxinCollection as $key=>$value){
			//echo $value->sample_id;
			//echo ' ';
			//echo $value->compound_grp;
			//echo '<br>';

			$updQueryTtl = "UPDATE glab_sample 
						set i_tef = (SELECT SUM(g.i_tef) AS total_tef FROM glab_sample g WHERE g.sample_id = '".$value->sample_id."' AND g.compound NOT LIKE 'Total%' AND g.compound_grp = '".$value->compound_grp."'), 
						who_tef_1998 = (SELECT SUM(g.who_tef_1998) AS total_who_tef_1998 FROM glab_sample g WHERE g.sample_id = '".$value->sample_id."' AND g.compound NOT LIKE 'Total%' AND g.compound_grp = '".$value->compound_grp."'),
						who_tef_2005 = (SELECT SUM(g.who_tef_2005) AS total_who_tef_2005 FROM glab_sample g WHERE g.sample_id = '".$value->sample_id."' AND g.compound NOT LIKE 'Total%' AND g.compound_grp = '".$value->compound_grp."') 
						WHERE sample_id = '".$value->sample_id."' AND compound LIKE 'Total%' AND compound_grp = '".$value->compound_grp."';";

			$resUpdTtl=mysqli_query($dbc, $updQueryTtl);
		}
		mysqli_autocommit($dbc, TRUE);
    }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<style>
			.outer-scontainer {
				background: #F0F0F0;
				border: #e0dfdf 1px solid;
				padding: 20px;
				border-radius: 2px;
			}

			.input-row {
				margin-top: 0px;
				margin-bottom: 20px;
			}

			.outer-scontainer table {
				border-collapse: collapse;
				width: 100%;
			}

			.outer-scontainer th {
				border: 1px solid #dddddd;
				padding: 8px;
				text-align: left;
			}

			.outer-scontainer td {
				border: 1px solid #dddddd;
				padding: 8px;
				text-align: left;
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

			#submit {
				background-color: #87ceeb;
				color: white;
				padding: 12px 20px;
				border: none;
				border-radius: 4px;
				cursor: pointer;
				width:100
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#frmCSVImport").on("submit", function () {
					$("#response").attr("class", "");
					$("#response").html("");
					var fileType = ".csv";
					var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
					if (!regex.test($("#file").val().toLowerCase())) {
							$("#response").addClass("error");
							$("#response").addClass("display-block");
						$("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
						return false;
					}
					return true;
				});
			});
		</script>
	</head>

	<body>
		<h2 style="margin-left:10px">Import Contractor CSV File</h2><hr>
		<div id="response"
			class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
			<?php if(!empty($message)) { echo $message; } ?>
		</div>
		<div>
			<form  action="" method="post" name="frmCSVImport" id="frmCSVImport"
				enctype="multipart/form-data">
				<table style="margin-left:10px">
					<tr>
						<td><label>(Choose Data File (.csv) </label></td>
						<td><input type="file" name="file" id="file" accept=".csv"></td>
					</tr>	
				</table>
				<hr>
				<button type="submit" style="margin-left:10px" id="submit" name="import" class="btn-submit">Import</button>							
			</form>
		</div>
	</body>
</html>
<?php include('footer.html');?>