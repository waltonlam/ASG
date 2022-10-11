<?php
session_start();
define('TITLE', 'Monthly Time-Series AQ Sampling');
include('templates/header.html');
include('templates/iconn.php');

//$result_t = mysqli_query($dbc,"SELECT * into table loc2 FROM loc");
$h= 'SELECT * into loc2 FROM loc';
$result_t = $dbc->query($h);
//$result1 = mysqli_query($dbc,"Create TEMPORARY table loc3 (Select * From loc);");
$result1 = mysqli_query($dbc,"Create table loc2 (Select * From loc);");
$result2 = mysqli_query($dbc,"SELECT * from loc2");
$rows_cnt = $result2->num_rows;

echo 'Loc2 Total count: '. $rows_cnt;

?>