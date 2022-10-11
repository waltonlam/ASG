<?php
include('templates/iconn.php');
//$q = intval($_GET['q']);
$q = $_GET['q'];
/*
$con = mysqli_connect('localhost','peter','abc123','my_db');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql="SELECT * FROM user WHERE id = '".$q."'";
$result = mysqli_query($con,$sql);

echo "<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
<th>Age</th>
<th>Hometown</th>
<th>Job</th>
</tr>";
*/
	$sql="select op.prg_id op_id, op.name op_name from out_prg op, outlet o where op.outlet_id=o.outlet_id and o.outlet_id='".$q."'";
	$result_op = mysqli_query($dbc,$sql);
//	if ($result_op->num_rows){
		 	while ($row = mysqli_fetch_array($result_op))
		 {
		  echo "<option>(".$row['op_id'].") ".$row['op_name']."</option>";
		 }
			 
//	}		

//mysqli_close($dbc);
?>