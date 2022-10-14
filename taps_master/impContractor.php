<?php 

ini_set('max_execution_time', 0);
set_time_limit(1800);
ini_set('memory_limit', '-1');

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

			while(($row = fgetcsv($file)) !== false){
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

			while (($row = fgetcsv($file, 50000)) !== false)
			{
				if(!$header)
					$header = $row;
				else
					$data[] = array_combine($header, $row);
	
			}


			fclose($file);


			//  print "<pre>";
		 // var_dump($returnVal);
		 // print "</pre>";

			
			$site = "";
			$compound_group = "";
			$compound_code = "";
			$sample_id = "";
			$flow_rate = "";
//			$duration = "";
			$duration = 0;

			$remark = "";

			$testCount = 0;
			
			foreach($data as $key => $result) {
				// echo "{$key}";
				 //echo '<br>';
 				$testCount++;
				foreach($result  as $key => $value){
					//echo "{$key} => {$value} ";
					//echo '<br>';
					
					//print "going through<br>";
					if (strpos ($key,'Sample ID') !== false){
						$sample_id = $value;
						
					}
					
					if (strpos ($key,'Site')!== false){
						$site = $value;
					}
					
					if (strpos ($key,'Compound Group')!== false){
						$compound_group = $value;
					}
					
					if (strpos ($key,'Compound Code')!== false){
						$compound_code = $value;
					}


					if (strpos ($key,'Flow Rate')!== false){					
						$flow_rate = $value;
 
							// echo $flow_rate;
				// echo '<br>';
					}


					if (strpos ($key,'Duration')!== false){
						$duration = $value;
					}
					
					if (strpos ($key,'Remark')!== false){
						$remark = $value;
					}
					
			
/* Stp===================						
				$sub_year = substr($sample_id,6,4);
				$sub_month = substr($sample_id,12,2);
				$sub_day = substr($sample_id,10,2);
				$m = $sub_year  ."-".$sub_month."-".$sub_day;
======================*/

				//print "Before Sample Date"."<br>";
				//echo "{$key} => {$value} "."<br>";
				if (strpos ($key,'STRTDATE')!== false){
					// echo "$key => Value is {$value} "."<br>";
					$strtdate = $value;
					//$strtdate = mysqli_real_escape_string($dbc, $value);
				}

				$flow_rateDec = (float)$flow_rate;

			}	
				


			if (!empty($sample_id) ){
			
				//echo $sub_year;
				//echo '<br>';
				//echo $sub_month;
				//echo '<br>';

				// SELECT who_tef FROM factor WHERE compound = '".$compound_group."'


//stp				$in = "SELECT * FROM `contractor_template` WHERE `site` = '".$site."' AND `compound_group` = '".$compound_group."' AND `sample_id` = '".$sample_id."' AND `flow_rate` = '".$flow_rateDec."' AND `duration` = '".$duration."' AND `remarks` = '".$remarks."' AND `who_tef` = '".$compound_group."';";

			  //$in2 = "INSERT into `contractor_template`(`site`, `compound_group`, `compound_code`,`sample_id`, `duration`, `remark`, `sample_date`) VALUES 
			  //('".$site."','".$compound_group."','".$compound_code."','".$sample_id."',".$duration.",'".$remark."',"."STR_TO_DATE('".$strtdate."','%Y/%m/%d')".");"; 
			  
			  $in1 = "INSERT into `contractor_curr`(`site`, `compound_group`, `compound_code`,`sample_id`,`flow_rate`,`duration`, `remark`, `sample_date`, who_tef) VALUES 
			  ('".$site."','".$compound_group."','".$compound_code."','".$sample_id."',".$flow_rate.",".$duration.",'".$remark."',"."STR_TO_DATE('".$strtdate."','%Y/%m/%d'),(SELECT who_tef from compound where id='".$compound_code."'));"; 

			  
				$in2 = "INSERT into `contractor_template`(`site`, `compound_group`, `compound_code`,`sample_id`,`flow_rate`,`duration`, `remark`, `sample_date`, who_tef) VALUES 
			  ('".$site."','".$compound_group."','".$compound_code."','".$sample_id."',".$flow_rate.",".$duration.",'".$remark."',"."STR_TO_DATE('".$strtdate."','%Y/%m/%d'),(SELECT who_tef from compound where id='".$compound_code."'));"; 

				  //print "in2 = {$in2}";
				// echo '<br>';
				// echo '<br>';
				// echo $in2;
				// echo '<br>';
				// echo '<br>';

				
//stp				$res=mysqli_query($dbc, $in);
			

			   
//stp				if (mysqli_num_rows($res) == 0) {
/*
				//$res2=mysqli_query($dbc, $in);
				$res=mysqli_multi_query($dbc, $in);	
				print "After mysqli_query<br>";
				if (! empty($res2)) {
					// code...
					$r_in++;
					print "r_in = ".$r_in;
					$type = "success";
					$message = "*No. of records have been imported : ".$r_in."<br>";
					print $message;
				}else{
					$type = "error";
					$message = "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in."</p>";

				}
*/


				$res=mysqli_query($dbc, $in1) or trigger_error("Query Failed! SQL: $in1 - Error: ".mysqli_error($dbc), E_USER_ERROR);
							
				if (! empty($res)) {
					//$r_in++;
					$type = "success";
					$message = "*No. of records have been imported : ".$r_in;
				} else {
					$type = "error";
					$message = "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in1."</p>";
					//$message = "Problem in loading CSV Data -> ".$loc_id." | ".$sample_date." | ".$m_time." | ".$ele_id." | ".$sample_v."</p>";
					exit();
				}


				$res=mysqli_query($dbc, $in2) or trigger_error("Query Failed! SQL: $in2 - Error: ".mysqli_error($dbc), E_USER_ERROR);
							
				if (! empty($res)) {
					$r_in++;
					$type = "success";
					$message = "*No. of records have been imported : ".$r_in;
				} else {
					$type = "error";
					$message = "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in2."</p>";
					//$message = "Problem in loading CSV Data -> ".$loc_id." | ".$sample_date." | ".$m_time." | ".$ele_id." | ".$sample_v."</p>";
					exit();
				}
				

//stp				} 
			
				//$message .= "</p>No. of records have been imported : ".$r_in;
			
			
				if ($type == "success"){
					mysqli_commit($dbc);        
				}else{
					mysqli_rollback($dbc);
					print "Loading...".$r_in;
					//exit();
				}
			}
			
		}
		// print "The total num of insert = ".$testCount;
		mysqli_autocommit($dbc, TRUE);

	}
		
	
	
}

?>


<!DOCTYPE html>
<html>

<head>
<script src="jquery-3.2.1.min.js"></script>

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
        
    <h2>Import Contractor file</h2>

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
                    <h4><label class="col-md-4 control-label">Choose Data File (.csv)</label></h4> 
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