<?php
ini_set('max_execution_time', 0);
set_time_limit(1800);
ini_set('memory_limit', '-1');
require_once 'sqlHelper.php';
session_start();
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
		$strtDate = "";
		$siteId = "";
		$compound = "";
		$compound_grp = "PH";
		$conc_ppbv= "";
		$conc_ppbv_str= "";
		$fieldBlank = "N";
		$testCount = 0;

		foreach($data as $key => $result) {		
			$testCount++;
			foreach($result  as $key => $value){
				if (strpos ($key,'Sample I.D.') !== false){
					$sampleId = $value;
					if(substr($sampleId,5,1) == 'F'){
						$fieldBlank = "Y";
					}
					$siteId = substr($sampleId, 0, 3);						
					//$strtDate = "20".substr($sampleId,6, -4)."/".substr($sampleId,8, -2)."/".substr($sampleId,10);
					$strtDate = "20".substr($sampleId,6, 2)."/".substr($sampleId,10, 2)."/".substr($sampleId,8,2);
				}
				
				if (strpos ($key,'Compounds') !== false){
					$compound = $value;
				}
				
				if (strpos ($key,'ng/sample') !== false){
					if(empty($value)){
						$conc_ppbv = '0.00';
					}else{
						$conc_ppbv_str = $value;
						if(substr($value,0,1) == "<"){
							$conc_ppbv = str_replace('<', '', $value);
							$conc_ppbv = $conc_ppbv/2;
						}else{
							$conc_ppbv = $value;
						}
					}
				}
			}

			$select_qry = "SELECT * FROM glab_sample WHERE sample_id = '".$sampleId."'
							AND compound = '".$compound."'
							AND CURRENT_TIMESTAMP > create_date";

			$checkDupRes=mysqli_query($dbc, $select_qry);
			$rowcount=mysqli_num_rows($checkDupRes); 
			if ($rowcount == 0) {
				if (!empty($sampleId)){
					$in1 = "INSERT INTO `glab_sample` (`sample_id`, `strt_date`, `site_id`, `compound`, `compound_grp`, `conc_ppbv`, `conc_ppbv_str`, `field_blank`, `create_date`, `create_by`, `last_upd_date`, `last_upd_by`) 
					VALUES ('".$sampleId."',"."STR_TO_DATE('".$strtDate."','%Y/%m/%d'),'".$siteId."','".$compound."','".$compound_grp."','".$conc_ppbv."','".$conc_ppbv_str."','".$fieldBlank."', current_timestamp, '".$_SESSION['vuserid']."', current_timestamp, '".$_SESSION['vuserid']."');";
					//echo $in1;

					$res=mysqli_query($dbc, $in1); 
					/* or trigger_error("Query Failed! SQL: $in1 - Error: ".mysqli_error($dbc), E_USER_ERROR);*/
								
					if (!empty($res)) {
						$r_in++;
						$type = "success";
						$message = "*No. of records have been imported : ".$r_in;
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
				$message = "*No. of records are duplicated : ".$count_duplicate;
			}
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
		<h2 style="margin-left:10px">Import PAH CSV File</h2><hr>
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