﻿<?php
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
	$sql="select o.outlet_id oid, o.name oname, op.prg_id op_id, pc.name pc_name from out_prg op, prg_cat pc, outlet o where op.outlet_id=o.outlet_id and op.prg_id=pc.prg_id and pc.prg_id='".$q."'";
	$result_o = mysqli_query($dbc,$sql);
//	if ($result_op->num_rows){
		 	while ($row = mysqli_fetch_array($result_o))
		 {
		  echo "<option value='".$row['oid']."'>(".$row['oid'].") ".$row['oname']."</option>";
		 }
			 
//	}		

//mysqli_close($dbc);
?>


