<?php
session_start();
require("iconn.php");
 
if (!$result = mysqli_query($dbc, "select * from A".$_SESSION['o'].$_SESSION['exp'])) {
    exit(mysqli_error($dbc));
}
 
$r='';
for($i = 0; $i < count($_SESSION['param']); $i++) {			
    if ($i==count($_SESSION['param'])-1){
        $r .= "'".$_SESSION['param'][$i]."'";
    }else{
        $r .= "'".$_SESSION['param'][$i]."',";
    }
}
$u="SELECT e.ele_id,e.unt FROM element e WHERE e.ele_id in (".$r.")";
if (!$resultunt = mysqli_query($dbc, $u)) {
    exit(mysqli_error($dbc));
}else{
    $r_unt = $resultunt->fetch_array(MYSQLI_BOTH);
}



$ts = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $ts[] = $row;
    }
}
 
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=result.csv');
$output = fopen('php://output', 'w');
//$hdr="'Location', 'Sample Date', 'Time','".implode("','", $_SESSION['param'])."',";
//implode(', ', $_SESSION['param'])
$s_param="Location,Sample Date,Time,";
for($i = 0; $i < count($_SESSION['param']); $i++) {		

    $key = array_search($_SESSION['param'][$i], array_column($r_unt, 'ele_id'));
    mysqli_data_seek($resultunt,$key);
    $rw=mysqli_fetch_row($resultunt);	

    if ($i==count($_SESSION['param'])-1){
        $s_param .= $_SESSION['param'][$i].' ('.$rw[1].')';
    }else{
        $s_param .= $_SESSION['param'][$i].' ('.$rw[1].'),';
    }
}
$hdr = explode(',', $s_param);
//$hdr=array('Location', 'Sample Date', 'Time', $_SESSION['param']);

//print $hdr;
//fputcsv($output, array('Location', 'Sample Date', 'Time', implode(',', $_SESSION['param'])));
//fputcsv($output, array($hdr));
//$hdr=array('Location', 'Sample Date', 'Time');
fputcsv($output, $hdr);


if (count($ts) > 0) {
    foreach ($ts as $row) {
        fputcsv($output, $row);
    }
}



$q="";
for($i = 0; $i < count($_SESSION['param']); $i++) {			
    if ($i==count($_SESSION['param'])-1){
        $q .= "drop table T".$_SESSION['o'].$i;
    }else{
        $q .= "drop table T".$_SESSION['o'].$i.";";
    }
}

if ($_SESSION['exp']>0){
    $q .= ";";
    for($i = 0; $i <= $_SESSION['exp']; $i++) {			
        if ($i==count($_SESSION['param'])-1){
            $q .= "drop table A".$_SESSION['o'].$i;
        }else{
            $q .= "drop table A".$_SESSION['o'].$i.";";
        }
    }
}

if (!$dbc->multi_query($q)) 
{
    //handle error
    echo mysqli_error ( $dbc ); 
}
