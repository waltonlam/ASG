﻿<?php

include('templates/iconn.php');

//if(isset($_POST['get_option']))
//{
/*
 $host = 'localhost';
 $user = 'root';
 $pass = '';
 mysql_connect($host, $user, $pass);
 mysql_select_db('demo');
*/
 $state = $_POST['get_option'];
// $find=mysql_query("select city from places where state='$state'");

	$q="select op.prg_id op_id, op.name op_name from out_prg op, outlet o where op.outlet_id=o.outlet_id and o.outlet_id='".$state."'";
	print $q;
	$result_op=$dbc->query($q);
	if ($result_op->num_rows){
		 	while ($r_op=$result_op->fetch_object())
		 {
		  echo "<option>(".$r_o->op_id.") ".$r_op->op_name."</option>";
		 }
			 
	}			
/*	
 $find=mysql_query();
 print $find;
 while($row=mysql_fetch_array($find))
 {
  echo "<option>(".$row['op_id'].") ".$row['op_name']."</option>";
 }
 */
 
 
 exit;
//}
?>