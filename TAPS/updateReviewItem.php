<?php
	require_once 'sqlHelper.php';
    $con = new MyDB();


    $xDateSql=$_POST['xDateSql'];

	$sid=$_POST['sampleId'];
	$compound=$_POST['compound'];
	$conc=$_POST['conc'];
	$flow=$_POST['flow'];
	$time=$_POST['time'];

	$orignalCompound=$_POST['orignalCompound'];
	$orignalConc=$_POST['orignalConc'];
	$orignalFlow=$_POST['orignalFlow'];
	$orignalTime=$_POST['orignalTime'];

	$sql = "update glab_template , contractor_template set glab_template.compound_code = '".$compound."' , glab_template.conc = '".$conc."' , glab_template.conc_string = '".$conc."', contractor_template.duration = '".$time."',contractor_template.compound_group = '".$compound."', contractor_template.flow_rate = '".$flow."' where glab_template.sample_id = contractor_template.sample_id AND glab_template.sample_id = '".$sid."'AND contractor_template.sample_id = '".$sid."' and compound_code = '".$orignalCompound."' and conc = '".$orignalConc."' and contractor_template.flow_rate = '".$orignalFlow."' and contractor_template.duration = '".$orignalTime."'";

	$updateArray = $con -> updateReview($sql);

	$updatedTable = $con -> selectData($xDateSql);



?>


