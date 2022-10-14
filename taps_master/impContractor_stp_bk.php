<?php 

ini_set('max_execution_time', 0);
set_time_limit(1800);
ini_set('memory_limit', '-1');

session_start();
include('header2.html');
include('iconn.php');
include('fn.php');



if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
			$file = fopen($fileName, "r");
			$r_in=0;

			mysqli_autocommit($dbc, FALSE);
			mysqli_begin_transaction($dbc, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
			
			

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

			fclose($file);


			//  print "<pre>";
		 // var_dump($returnVal);
		 // print "</pre>";

			
			$site = "";
			$compound_group = "";
			$sample_id = "";
			$flow_rate = "";
//			$duration = "";
			$duration = 0;

			$remarks = "";

			$testCount = 0;
			
			foreach($returnVal as $key => $result) {
				// echo "{$key}";
				 //echo '<br>';

 				$testCount++;

				foreach($result  as $key => $value){
					//echo "{$key} => {$value} ";
					//echo '<br>';
					
					if (strpos ($key,'Sample ID') !== false){
						$sample_id = $value;
						
					}
					
					if (strpos ($key,'Site')!== false){
						$site = $value;
					}
					
					if (strpos ($key,'Compound group')!== false){
						$compound_group = $value;
					}
					
					if (strpos ($key,'Flow rate')!== false){
					
						$flow_rate = $value;
// 
							// echo $flow_rate;
				// echo '<br>';
					}
					
					if (strpos ($key,'Duration')!== false){
						$duration = $value;
					}
					
					if (strpos ($key,'Remarks')!== false){
						$remarks = $value;
					}
					
				}
			
				$sub_year = substr($sample_id,6,4);
				$sub_month = substr($sample_id,12,2);
				$sub_day = substr($sample_id,10,2);
				$m = $sub_year  ."-".$sub_month."-".$sub_day;

				$flow_rateDec = (float)$flow_rate;
				
			if (!empty($sample_id) ){
			
				//echo $sub_year;
				//echo '<br>';
				//echo $sub_month;
				//echo '<br>';

				// SELECT who_tef FROM factor WHERE compound = '".$compound_group."'


				$in = "SELECT * FROM `contractor_template` WHERE `site` = '".$site."' AND `compound_group` = '".$compound_group."' AND `sample_id` = '".$sample_id."' AND `flow_rate` = '".$flow_rateDec."' AND `duration` = '".$duration."' AND `remarks` = '".$remarks."' AND `who_tef` = '".$compound_group."';";

			  $in2 = "INSERT into `contractor_template`(`site`, `compound_group`, `sample_id`, `flow_rate`, `duration`, `remarks`, `sample_date`, `who_tef`) VALUES 
			  ('".$site."','".$compound_group."','".$sample_id."','".$flow_rateDec."','".$duration."','".$remarks."','".$m."','".$compound_group."');";
					   
				// 	   echo $in;
				// echo '<br>';
				// echo '<br>';
				// echo $in2;
				// echo '<br>';
				// echo '<br>';
				
				$res=mysqli_query($dbc, $in);
			   
				if (mysqli_num_rows($res) == 0) {

					$res2=mysqli_query($dbc, $in2);
					if (!empty($res2)) {
						// code...
						$r_in++;
						$type = "success";
						$message = "*No. of records have been imported : ".$r_in;
					}else{
						$type = "error";
					$message = "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in."</p>";
					}


				} 
			
				//$message .= "</p>No. of records have been imported : ".$r_in;
			
			
				if ($type == "success"){
					mysqli_commit($dbc);        
				}else{
					mysqli_rollback($dbc);
				}
				mysqli_autocommit($dbc, TRUE);
			}
			
		}
		// print "The total num of insert = ".$testCount;
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
        
    <h2>Import Constractor file</h2>

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