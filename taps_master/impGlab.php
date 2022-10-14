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
        
		
/* Stp===================		
		$returnVal = array();
		$header = null;
		while(($row = fgetcsv($file,50000)) !== false){
			if($header === null){
				$header = $row;
				continue;
			}

			$newRow = array();
			for($i = 0; $i<count($row); $i++){
				$newRow[$header[$i]] = $row[$i];
			}
			
			$returnVal[] = $newRow;

		}
==============================*/



	$header = null;
//  https://gist.github.com/jaywilliams/385876/e1e69b619e281ddfcf61ec76bf5ee0826d4730f9
		
		while (($row = fgetcsv($file, 50000)) !== false)
		{
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);

		}

		fclose($file);

		$site = "";
		$sampleID = "";
		$strtdate = "";
		$casno1 = "";
		$compoundCode = "";
		$conc_ppbv_raw = "";
		$conc_mcg_m3_raw="";
		$conc_ppbv = 0;
		$conc_mcg_m3 = 0 ;

		$conc = "";
		
		$compoundGpd = "";
		
		$raw_result = "";
		$glab_result = "";

		$testCount = 0;

/*
		foreach($data as $line_no => $line_set) {
			print $line_no."=".$line_set."<br>";
			foreach($line_set as $key => $value){
				print $key."=".$value." --- ";
			}
			print "<br>";
		}
		exit;

*/

		foreach($data as $key => $result) {		
			 $testCount++;
			foreach($result  as $key => $value){

				if (strpos ($key,'SAMPID') !== false){
					// echo "{$key} => {$value} ";
					$sampleID = $value;
				}
				

				if (strpos ($key,'CASNO1')!== false){
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
				
/*				
				if (strpos ($key,'EPD_CONC')!== false){
					// echo "{$key} => {$value} ";
					$conc = $value;
				}
*/

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
				}



/*
				if (strpos ($key,'Raw_Result')!== false){
					// echo "{$key} => {$value} ";
					$raw_result = $value;
				}
				
				if (strpos ($key,'Glab_Result')!== false){
					// echo "{$key} => {$value} ";
					$glab_result = $value;
				}
				// echo '<br>';
*/


/*
				$site=substr($sampleID,0,2);
				$sub_year = substr($sampleID,6,4);
				$sub_month = substr($sampleID,12,2);
				$sub_day = substr($sampleID,10,2);
				//$m = $sub_year  ."-".$sub_month."-".$sub_day;
				$m = $sub_day."/".$sub_month."/".$sub_year ; 

				// KCSVCS151612A2
*/


			}

			
//print "BEFORE empty sampleID<br>";

						//if (!empty($simpleID) and !empty($casno1) and !empty($compoundCode) and !empty($conc) and !empty($m)){
			if (!empty($sampleID)){

			    //STR_TO_DATE('31/12/2015','%d/%m/%Y');
				$in1 = "INSERT INTO `glab_template` (`compound_group`,`site`,`sample_id`, `start_date`, `casno1`, `compound_code`, `conc_ppbv_raw`, `conc_mcg_m3_raw`, `conc_ppbv`, `conc_mcg_m3`) 
						VALUES ('".$compoundGpd."','".$site."','".$sampleID."', "."STR_TO_DATE('".$strtdate."','%Y/%m/%d')".",'".$casno1."', '".$compoundCode."', '".$conc_ppbv_raw."', '"
								.$conc_mcg_m3_raw."', $conc_ppbv,$conc_mcg_m3 );";

				$in2 = "INSERT INTO `glab_curr` (`compound_group`,`site`,`sample_id`, `start_date`, `casno1`, `compound_code`, `conc_ppbv_raw`, `conc_mcg_m3_raw`, `conc_ppbv`, `conc_mcg_m3`) 
				VALUES ('".$compoundGpd."','".$site."','".$sampleID."', "."STR_TO_DATE('".$strtdate."','%Y/%m/%d')".",'".$casno1."', '".$compoundCode."', '".$conc_ppbv_raw."', '"
						.$conc_mcg_m3_raw."', $conc_ppbv,$conc_mcg_m3 );";
				



				//$res=mysqli_query($dbc, $in);
				/*
				$res=mysqli_multi_query($dbc, $in);				
			   
				if (! empty($res)) {
					$r_in++;
					$type = "success";
					$message = "*No. of records have been imported : ".$r_in;
				} else {
					$type = "error";
					$message = "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in."</p>";
				}
			*/
/*
				if (mysqli_multi_query($dbc, $in)) {
					$r_in++;
					$type = "success";
					$message = "*No. of records have been imported : ".$r_in;
				  } else {
					echo "Error: " . $in . "<br>" . mysqli_error($dbc);
					$type = "error";
					$message = "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in."</p>";
				}

*/


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

//print "in2 = ".$in2;


				//$message .= "</p>No. of records have been imported : ".$r_in;
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
        
    <h2>Import Glab file</h2>

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
                    <br />

                </div>
				
				
				 <div class="input-row">
                   <input type="file" name="file"
                        id="file" accept=".csv">
                   
                    <br />

                </div>
				 <button type="submit" id="submit" name="import"
                        class="btn-submit">Import</button>
				<div>
				
				
				</div>
				

            </form>

        </div>


    </div>
</body>

</html>

<?php include('footer.html'); 


?>