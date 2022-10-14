<?php
session_start();
require('iconn.php');


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit;
}

if (!$result = mysqli_query($dbc, $_POST['last_sql'])) {
    exit(mysqli_error($dbc));
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

//$cols=array_column($r_unt, 'ele_id');

//$hdr = explode('@@!', $s_param);
//fputcsv($output, $hdr);

$hdr="";

if ($_POST['rev_cat'] == "RAW"){
    $hdr="Site,Start Date,Sample ID,Group,Compound,Conc.(ppbv)/SAMPLE,Flow(LPM),Time(min),Volume(m3),Conc/M3,WHO TEF Ratio,WHO TEQ Conc/m3,Casno1";
}
/*
if ($_POST['rev_cat'] == "RAW"){
    $hdr="Site,Start Date,Sample ID,Group,Compound,Conc.(ppbv)/SAMPLE,
        GLab Result(mg/m3),Flow(LPM),Time(min),Volume(m3),Conc/M3,WHO TEF Ratio,WHO TEQ Conc/m3,Casno1";
}
*/


/*
$l = "SELECT glab_template.site as 'Site', glab_template.start_date AS 'Start Date' , 
glab_template.sample_id AS 'Sample ID',glab_template.compound_group as 'Group',
glab_template.compound_code as 'Compound',glab_template.conc_ppbv as 'Conc.(ppbv)/SAMPLE',

glab_template.conc_ppbv as RawResult, glab_template.conc_mcg_m3 as GLabResult, 
contractor_template.flow_rate as 'Flow(LPM)',contractor_template.duration as 'Time(min)',
(contractor_template.flow_rate/1000)*contractor_template.duration AS 'Volume(m3)',
IF (glab_template.conc_ppbv < 0,
    (glab_template.conc_ppbv/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)),
    (glab_template.conc_ppbv/((contractor_template.flow_rate/1000)*contractor_template.duration)) ) AS 'Conc/M3',
    contractor_template.who_tef AS 'WHO TEF Ratio' ,

ROUND( (IF (glab_template.conc_ppbv < 0,
    (glab_template.conc_ppbv/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)),
    (glab_template.conc_ppbv/((contractor_template.flow_rate/1000)*contractor_template.duration)) )  )*contractor_template.who_tef,6)  AS 'WHO TEQ conc/m3',
glab_template.casno1 
*/

/*
if ($_POST['rev_cat'] == "CURRENT"){
    $hdr="Site,Start Date,Sample ID,Group,Compound,Conc.(ppbv)/SAMPLE,
        GLab Result(mg/m3),Flow(LPM),Time(min),Volume(m3),Conc/M3,WHO TEF Ratio,WHO TEQ Conc/m3,Casno1";
}
*/
if ($_POST['rev_cat'] == "CURRENT"){
    $hdr="Site,Start Date,Sample ID,Group,Compound,Conc.(ppbv)/SAMPLE,Flow(LPM),Time(min),Volume(m3),Conc/M3,WHO TEF Ratio,WHO TEQ Conc/m3,Casno1";
}


/*
$l = "SELECT glab_curr.site as 'Site', glab_curr.start_date AS 'Start Date' , 
				glab_curr.sample_id AS 'Sample ID',glab_curr.compound_group as 'Group',
				glab_curr.compound_code as 'Compound',glab_curr.conc_ppbv as 'Conc.(ppbv)/SAMPLE',
				glab_curr.conc_ppbv as RawResult, glab_curr.conc_mcg_m3 as GLabResult, 
				contractor_curr.flow_rate as 'Flow(LPM)',contractor_curr.duration as 'Time(min)',
				(contractor_curr.flow_rate/1000)*contractor_curr.duration AS 'Volume(m3)',
				IF (glab_curr.conc_ppbv < 0,
					(glab_curr.conc_ppbv/-2/((contractor_curr.flow_rate/1000)*contractor_curr.duration)),
					(glab_curr.conc_ppbv/((contractor_curr.flow_rate/1000)*contractor_curr.duration)) ) AS 'Conc/M3',
					contractor_curr.who_tef AS 'WHO TEF Ratio' ,
				
				ROUND( (IF (glab_curr.conc_ppbv < 0,
					(glab_curr.conc_ppbv/-2/((contractor_curr.flow_rate/1000)*contractor_curr.duration)),
					(glab_curr.conc_ppbv/((contractor_curr.flow_rate/1000)*contractor_curr.duration)) )  )*contractor_curr.who_tef,6)  AS 'WHO TEQ conc/m3',
				glab_curr.casno1 
*/				


/*
if ($_POST['rev_cat'] == "HISTORY"){
    $hdr="Site,Start Date,Sample ID,Group,Compound,Conc.(ppbv)/SAMPLE,
        GLab Result(mg/m3),Flow(LPM),Time(min),Volume(m3),Conc/M3,WHO TEF Ratio,WHO TEQ Conc/m3,Casno1,Reamrk,Modify Timestamp";
}
*/
if ($_POST['rev_cat'] == "HISTORY"){
    $hdr="Site,Start Date,Sample ID,Group,Compound,Conc.(ppbv)/SAMPLE,Flow(LPM),Time(min),Volume(m3),Conc/M3,WHO TEF Ratio,WHO TEQ Conc/m3,Casno1,Reamrk,Modify Timestamp";
}



/*
if ($_POST["review"]=="HISTORY"){
    $l = "SELECT glab_rev_h.site as 'Site', glab_rev_h.start_date AS 'Start Date' , 
    glab_rev_h.sample_id AS 'Sample ID',glab_rev_h.compound_group as 'Group',
    glab_rev_h.compound_code as 'Compound',glab_rev_h.conc_ppbv as 'Conc.(ppbv)/SAMPLE',
    glab_rev_h.conc as 'Conc',
    glab_rev_h.conc_m3 as 'Conc m3',
    glab_rev_h.vol as 'Vol',
    glab_rev_h.glab_result as 'GLab Result',
    glab_rev_h.raw_result as 'RAW Result',
    glab_rev_h.flow_rate as 'Flow(LPM)',glab_rev_h.duration as 'Time(min)',
    (glab_rev_h.flow_rate/1000)*glab_rev_h.duration AS 'Volume(m3)',
    IF (glab_rev_h.conc_ppbv < 0,
        (glab_rev_h.conc_ppbv/-2/((glab_rev_h.flow_rate/1000)*glab_rev_h.duration)),
        (glab_rev_h.conc_ppbv/((glab_rev_h.flow_rate/1000)*glab_rev_h.duration)) ) AS 'Conc/M3',
        glab_rev_h.who_tef AS 'WHO TEF Ratio' ,
    
    ROUND( (IF (glab_rev_h.conc_ppbv < 0,
        (glab_rev_h.conc_ppbv/-2/((glab_rev_h.flow_rate/1000)*glab_rev_h.duration)),
        (glab_rev_h.conc_ppbv/((glab_rev_h.flow_rate/1000)*glab_rev_h.duration)) )  )*glab_rev_h.who_tef,6)  AS 'WHO TEQ conc/m3',
    glab_rev_h.casno1 as 'Casno1', rmk as 'Remark', modi_dt as 'Modify Timestamp' 
*/ 


//if ($_POST["review"]=="HISTORY"){
//    print '<th>Remark</th>
//    <th>Modify Timestamp</th>';
//}

$hdr = explode(',', $hdr);
fputcsv($output, $hdr);


if (count($ts) > 0) {
    foreach ($ts as $row) {
        fputcsv($output, $row);
    }
}



?>

