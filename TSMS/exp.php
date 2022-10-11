<?php
session_start();
require('templates/iconn.php');
 
if (!$result = mysqli_query($dbc, "select * from A".$_SESSION['o'].$_SESSION['exp']
                            ." order by loc_id, sample_date, m_time;")) {
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
//$u="SELECT e.ele_id,e.unt FROM element e WHERE e.ele_id in (".$r.")";
$u="SELECT e.name,e.unt FROM element e WHERE e.ele_id in (".$r.")";

//print "u = ".$u."</p>";

if (!$resultunt = mysqli_query($dbc, $u)) {
    exit(mysqli_error($dbc));
}else{
    $r_unt = array();
    $no=0;

    //$s_param="Location,Sample Date,Time,";
    $s_param="Location@@!Sample Date@@!Time@@!";

    $i=1;
    while ($row = mysqli_fetch_assoc($resultunt)) {
        //$r_unt[] = $row;
       // echo $r_unt[$no]["ele_id"]."-".$r_unt[$no]["unt"]."</p>";
        //$no++;


/*
        if ($resultunt->num_rows > $i ){
            $s_param .= $row['ele_id'].' ('.$row['unt'].'),';
        }else{
            $s_param .= $row['ele_id'].' ('.$row['unt'].')';
        }
*/

        if ($resultunt->num_rows > $i ){
            //$s_param .= $row['name'].' ('.$row['unt'].'),';
            $s_param .= $row['name'].' ('.$row['unt'].')@@!';

        }else{
            $s_param .= $row['name'].' ('.$row['unt'].')';
        }



    }
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

//$s_param="Location,Sample Date,Time,";

$cols=array_column($r_unt, 'ele_id');
//echo $cols[0]["ele_id"]."-".$r_unt[$no]["unt"]."</p>";
//exit;

/* 
for($i = 0; $i < count($_SESSION['param']); $i++) {		

    
    $key = array_search($_SESSION['param'][$i], array_column($r_unt, 'ele_id'));
    //PRINT "_SESSION[param][".$i."] = ".$_SESSION['param'][$i]."</p>";
    //$key = array_search($_SESSION['param'][$i], $cols);
    //print "Key = ".$key."</p>";

    mysqli_data_seek($resultunt,$key);
    $rw=mysqli_fetch_row($resultunt);	

    if ($i==count($_SESSION['param'])-1){
        $s_param .= $_SESSION['param'][$i].' ('.$rw[1].')';
    }else{
        $s_param .= $_SESSION['param'][$i].' ('.$rw[1].'),';
    }
} */
//exit;




//$hdr = explode(',', $s_param);
$hdr = explode('@@!', $s_param);

//$hdr = explode(',', iconv("UTF-8", "Windows-1252", $s_param));
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
