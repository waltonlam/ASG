<?php 
session_start();

include('header2.html');
include('iconn.php');
include('fn.php');


//$conn = $db->getConnection();
/*if (empty($_SESSION['vuserid'])) {
	print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
	exit();
} else{
    if ($_SESSION['utp']=='R'){
		print '<p class="text--error">Access Deny</p>';		
		exit();
	}


}*/

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
			
			$site = "";
			$compound_group = "";
			$sample_id = "";
			$flow_rate = "";
			$duration = "";
			$remarks = "";
			
			foreach($returnVal as $key => $result) {
				 //echo "{$key}";
				// echo '<br>';
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
					
					if (strpos ($key,'Flow Rate')!== false){
						$flow_rate = $value;
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
				
				
				//echo $sub_year;
				//echo '<br>';
				//echo $sub_month;
				//echo '<br>';
			  $in= "update contractor_template set "
					."flow_rate='".$flow_rate."' , remarks = '".$remarks."' , duration = '".$duration.
					"' where sample_id ='".$sample_id."'";
				//echo $in;
				//echo '<br>';
				//echo '<br>';
				
				$res=mysqli_query($dbc, $in);
			   
				if (! empty($res)) {
					$r_in++;
					$type = "success";
					$message = "*No. of records have been imported : ".$r_in;
				} else {
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
        
    <h2>Update Constractor file</h2>

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
                        class="btn-submit">Update</button>
				<div>
				
				
				</div>
				

            </form>

        </div>


    </div>
</body>

</html>

<?php include('footer.html'); 


?>