<?php
ini_set('max_execution_time', 0);
set_time_limit(1800);
ini_set('memory_limit', '-1');
require_once 'sqlHelper.php';
session_start();
include('header2.php');
include('iconn.php');
include('fn.php');

if (isset($_POST["import"])) {
    $fileName = $_FILES["file"]["tmp_name"];	
    if ($_FILES["file"]["size"] > 0) {		
        $file = fopen($fileName, "r");
        $r_in=0;
        mysqli_autocommit($dbc, FALSE);
        mysqli_begin_transaction($dbc, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
		$header = null;
		
		while (($row = fgetcsv($file, 50000)) !== false){
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);
		}
		fclose($file);

		$sampleId = "";
		$strtDate = "";
		$strtTime = "";
		$duration = "";
		$siteId = "";
		$cpdcat = "";
		$sampType = "";
		$location = "";
		$casno1 = "";
		$casno2 = "";
		$casno3 = "";
		$compound = "";
		$mdl_ppbv = "";
		$mdl_g_m3 = "";
		$pql_ppbv = "";
		$pql_g_m3 = "";
		$conc_ppbv= "";
		$conc_g_m3="";
		$sampMthd = "";
		$sampler = "" ;
		$detector = "";
		$remflg1 = "";
		$remflg2 = "";
		$remflg3 = "";
		$sampleBy = "";
		$analyseBy = "";

		$testCount = 0;

		foreach($data as $key => $result) {		
			$testCount++;
			foreach($result  as $key => $value){
				if (strpos ($key,'SAMPID') !== false){
					$sampleId = $value;
				}
				
				if (strpos ($key,'STRTDATE') !== false){
					$strtDate = $value;
				}

				if (strpos ($key,'STRTTIME') !== false){
					$strtTime = $value;
				}
				if (strpos ($key,'DURATION') !== false){
					$duration = $value;
				}
				if (strpos ($key,'SITEID') !== false){
					$siteId = $value;
				}
				if (strpos ($key,'CPDCAT') !== false){
					$cpdcat = $value;
				}
				if (strpos ($key,'SAMPTYPE') !== false){
					$sampType = $value;
				}
				if (strpos ($key,'LOCATION') !== false){
					$location = $value;
				}
				if (strpos ($key,'CASNO1') !== false){
					$casno1 = $value;
				}
				if (strpos ($key,'CASNO2') !== false){
					$casno2 = $value;
				}
				if (strpos ($key,'CASNO3') !== false){
					$casno3 = $value;
				}
				if (strpos ($key,'COMPOUND') !== false){
					$compound = $value;
				}
				if (strpos ($key,'MDL (ppbv)') !== false){
					$mdl_ppbv = $value;
				}
				if (strpos ($key,'MDL (?g/m3)') !== false){
					$mdl_g_m3 = $value;
				}
				if (strpos ($key,'PQL (ppbv)') !== false){
					$pql_ppbv = $value;
				}
				if (strpos ($key,'PQL (?g/m3)') !== false){
					$pql_g_m3 = $value;
				}
				if (strpos ($key,'CONC (ppbv)') !== false){
					$conc_ppbv = $value;
				}
				if (strpos ($key,'CONC (?g/m3)') !== false){
					$conc_g_m3 = $value;
				}
				if (strpos ($key,'SAMPMTHD') !== false){
					$sampMthd = $value;
				}
				if (strpos ($key,'SAMPLER') !== false){
					$sampler = $value;
				}
				if (strpos ($key,'DETECTOR') !== false){
					$detector = $value;
				}
				if (strpos ($key,'REMFLG1') !== false){
					$remflg1 = $value;
				}
				if (strpos ($key,'REMFLG2') !== false){
					$remflg2 = $value;
				}
				if (strpos ($key,'REMFLG3') !== false){
					$remflg3 = $value;
				}
				if (strpos ($key,'SAMPLEBY') !== false){
					$sampleBy = $value;
				}
				if (strpos ($key,'ANALYSEBY') !== false){
					$analyseBy = $value;
				}





				/*if (strpos ($key,'CASNO1')!== false){
					// echo "{$key} => {$value} ";
					$casno1 = $value;
				}
				
				if (strpos ($key,'SITE') !== false){
					// echo "{$key} => {$value} ";
					$site = $value;
				}

				if (strpos ($key,'COMPOUND CODE')!== false){
					// echo "{$key} => {$value} ";
					$finalVal = str_replace("'","\'",$value);
					$compoundCode = $finalVal;
				}
				
				if (strpos ($key,'GROUP')!== false){
					// echo "{$key} => {$value} ";
					$compoundGpd = $value;
				}

				if (strpos ($key,'STRTDATE')!== false){
					// echo "{$key} => {$value} ";
					$strtdate = $value;
					//$strtdate = mysqli_real_escape_string($dbc, $value);
				}
				
				if (strpos ($key,'CONC (ppbv)')!== false){
					$conc_ppbv_raw = $value;
					if (substr($conc_ppbv_raw, 0,1) == "<"){						
						$conc_ppbv = (float)substr_replace($conc_ppbv_raw,"-".substr($conc_ppbv_raw, 1),0); // 0 will start replacing at the first character in the string
					} else{
						$conc_ppbv = (float)$conc_ppbv_raw;
					}
				}

				if (strpos ($key,'CONC (mcg/m3)')!== false){
					$conc_mcg_m3_raw = $value;
					if (substr($conc_mcg_m3_raw, 0,1) == "<"){
						$conc_mcg_m3 = (float)substr_replace($conc_mcg_m3_raw,"-".substr($conc_mcg_m3_raw, 1),0); // 0 will start replacing at the first character in the string
					}else{
						$conc_mcg_m3 = (float)$conc_mcg_m3_raw;
					}
				}*/
			}

			if (!empty($sampleID)){
				$sampleId = "";
		$strtDate = "";
		$strtTime = "";
		$duration = "";
		$siteId = "";
		$cpdcat = "";
		$sampType = "";
		$location = "";
		$casno1 = "";
		$casno2 = "";
		$casno3 = "";
		$compound = "";
		$mdl_ppbv = "";
		$mdl_g_m3 = "";
		$pql_ppbv = "";
		$pql_g_m3 = "";
		$conc_ppbv= "";
		$conc_g_m3="";
		$sampMthd = "";
		$sampler = "" ;
		$detector = "";
		$remflg1 = "";
		$remflg2 = "";
		$remflg3 = "";
		$sampleBy = "";
		$analyseBy = "";

				$in1 = "INSERT INTO `glab_sample` (`sample_id`, `strt_date`, `strt_time`, `duration`, `site_id`, `cpdcat`, `samp_type`, `location`, `casno1`, 
				`casno2`, `casno3`, `compund`, `mdl_ppbv`, `mdl_g_m3`, `pql_ppbv`, `pql_g_m3`, `conc_ppbv`, `conc_g_m3`, `samp_mthd`, `sampler`, `detector`, `remflg1`, `remflg2`, `remflg3`, `sample_by`, `analyse_by`)
				 VALUES ('".$sampleId."',"."STR_TO_DATE('".$strtdate."','%Y/%m/%d')".",'".$strtTime."',


				$in1 = "INSERT INTO `glab_sample` (`compound_group`,`site`,`sample_id`, `start_date`, `casno1`, `compound_code`, `conc_ppbv_raw`, `conc_mcg_m3_raw`, `conc_ppbv`, `conc_mcg_m3`) 
						VALUES ('".$compoundGpd."','".$site."','".$sampleID."', "."STR_TO_DATE('".$strtdate."','%Y/%m/%d')".",'".$casno1."', '".$compoundCode."', '".$conc_ppbv_raw."', '"
								.$conc_mcg_m3_raw."', $conc_ppbv,$conc_mcg_m3 );";

				$in2 = "INSERT INTO `glab_curr` (`compound_group`,`site`,`sample_id`, `start_date`, `casno1`, `compound_code`, `conc_ppbv_raw`, `conc_mcg_m3_raw`, `conc_ppbv`, `conc_mcg_m3`) 
				VALUES ('".$compoundGpd."','".$site."','".$sampleID."', "."STR_TO_DATE('".$strtdate."','%Y/%m/%d')".",'".$casno1."', '".$compoundCode."', '".$conc_ppbv_raw."', '"
						.$conc_mcg_m3_raw."', $conc_ppbv,$conc_mcg_m3 );";

				$res=mysqli_query($dbc, $in1); /* or trigger_error("Query Failed! SQL: $in1 - Error: ".mysqli_error($dbc), E_USER_ERROR);*/
//				print "in1 = ".$in1;
							
				if (! empty($res)) {
					//$r_in++;
					$type = "success";
					$message = "*No. of records have been imported : ".$r_in;
				} else {
					$type = "error";
					//$message = "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in1."</p>";
					print "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in1."</p>";

					//$message = "Problem in loading CSV Data -> ".$loc_id." | ".$sample_date." | ".$m_time." | ".$ele_id." | ".$sample_v."</p>";
					exit();
				}

				$res=mysqli_query($dbc, $in2); /* or trigger_error("Query Failed! SQL: $in2 - Error: ".mysqli_error($dbc), E_USER_ERROR); */	
				if (! empty($res)) {
					$r_in++;
					$type = "success";
					$message = "*No. of records have been imported : ".$r_in;
				} else {
					$type = "error";
					//$message = "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in2."</p>";
					print "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in2."</p>";
					//$message = "Problem in loading CSV Data -> ".$loc_id." | ".$sample_date." | ".$m_time." | ".$ele_id." | ".$sample_v."</p>";
					exit();
				}

				if ($type == "success"){
					mysqli_commit($dbc);        
				}else{
					mysqli_rollback($dbc);
				}
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
			width:100%;
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
		<h2>Import Glab CSV File</h2>
		<div id="response"
			class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
			<?php if(!empty($message)) { echo $message; } ?>
			</div>
		<div class="outer-scontainer">
			<div class="row">
				<form class="form-horizontal" action="" method="post"
					name="frmCSVImport" id="frmCSVImport"
					enctype="multipart/form-data">
					<div class="input-row">
						<h4><label class="col-md-4 control-label">(Choose Data File (.csv)</label></h4> 
						<br/>
					</div>			
					<div class="input-row">
						<input type="file" name="file" id="file" accept=".csv">
						<br/>

					</div>
					<button type="submit" id="submit" name="import" class="btn-submit">Import</button>
					<div></div>			
				</form>
			</div>
		</div>
	</body>
</html>
<?php include('footer.html');?>