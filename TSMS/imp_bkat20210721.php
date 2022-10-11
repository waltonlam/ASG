<?php 
session_start();
define('TITLE', 'Monthly Time-Series AQ Sampling');
include('templates/header.html');
include('templates/iconn.php');
include('templates/fn.php');


//$conn = $db->getConnection();
if (empty($_SESSION['vuserid'])) {
	print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
	exit();
} else{
    if ($_SESSION['utp']=='R'){
		print '<p class="text--error">Access Deny</p>';		
		exit();
	}

		/*
		$q = "select d.district_id did, d.name dname, s.station_id sid, s.name sname
				from user_district ud, district d, station s 
				where s.district_id = ud.district_id and ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";
		$result=$dbc->query($q);
		if (!$result->num_rows){
				print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
				exit();
		}				
			//$q = "select * from user_acc where userid = ? and pwd= ?";
			//$stmt = $db->prepare($q);
			//$stmt->bind_param('ss',$_POST['userid'], $_POST['pwd']);
			//$stmt->execute();
			//mysqli_query($dbc, $q);			
		*/			
}

if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        $r_in=0;

        mysqli_autocommit($dbc, FALSE);
        mysqli_begin_transaction($dbc, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
        
        

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {

            $loc_id = "";
            if (isset($column[0])) {
                $loc_id = mysqli_real_escape_string($dbc, $column[0]);
            }
            $sample_date = "";
            if (isset($column[1])) {
                $sample_date = mysqli_real_escape_string($dbc, $column[1]);
            }
            $m_time = "";
            if (isset($column[2])) {
                $m_time = mysqli_real_escape_string($dbc, $column[2]);
            }
            $ele_id = "";
            if (isset($column[3])) {
                $ele_id = mysqli_real_escape_string($dbc, $column[3]);
            }
            $sample_v = "";
            if (isset($column[4])) {
                $sample_v = mysqli_real_escape_string($dbc, $column[4]);
                if ($sample_v=="") $sample_v="null";
            }
            
            $in = "INSERT into sample_ele (loc_id,sample_date,m_time,ele_id,sample_v)
                   values ('".$loc_id."',STR_TO_DATE('".$sample_date."','%d/%m/%Y')".",STR_TO_DATE('".$m_time."','%H:%i'),'".$ele_id."',".$sample_v.")";
                   
                   //print $in;

            $res=mysqli_query($dbc, $in);
             /* $paramType = "issss";
                $paramArray = array(
                $loc_id,
                $sample_date,
                $m_time,
                $ele_id,
                $sample_v
                ); 
              $insertId = $dbc->insert($sqlInsert, $paramType, $paramArray); */
           
            if (! empty($res)) {
                $r_in++;
                $type = "success";
                $message = "*No. of records have been imported : ".$r_in;
            } else {
                $type = "error";
                $message = "Problem in loading CSV Data. Please Correct and Reload Whole Batch-> ".$in."</p>";
                //$message = "Problem in loading CSV Data -> ".$loc_id." | ".$sample_date." | ".$m_time." | ".$ele_id." | ".$sample_v."</p>";
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
body {
   /* font-family: Arial;*/
    width: 100%;
}

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

.btn-submit {
    background: #333;
    border: #1d1d1d 1px solid;
    color: #f0f0f0;
    font-size: 0.9em;
    width: 100px;
    border-radius: 2px;
    cursor: pointer;
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
    border-radius: 2px;
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
    <hr>
	<div class="topnav">
    <a href="imp.php">Data Template Input</a>
		<a href="loc_new.php">Add Loc</a>
		<a href="update_loc_m.php">Edit Loc</a>
		<a href="del_loc.php">Delete Loc</a>
		<a href="egp_new.php">Add Param Group</a>
		<a href="update_gp_m.php">Edit Param Group</a>
		<a href="del_gp.php">Delete Param Group</a>
		<a href="ele_new.php">Add Param</a>
		<a href="update_ele_m.php">Edit Param</a>
		<a href="del_ele.php">Delete Param</a>
		<a href="ua_new.php">Add UAC</a>
		<a href="update_ua_m.php">Edit UAC</a>
        <a href="del_ua.php">Delete UAC</a>
        <a href="logout.php">Logout</a>;
    </div><hr>
        
    <h2>Import data file</h2>

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
                    <label class="col-md-4 control-label">Choose Data
                        File</label> <input type="file" name="file"
                        id="file" accept=".csv">
                    <button type="submit" id="submit" name="import"
                        class="btn-submit">Import</button>
                    <br />

                </div>

            </form>

        </div>


    </div>
    <p><hr><a href="sampletx.php">Home</a></p><hr>
</body>

</html>

<?php include('templates/footer.html'); 


?>