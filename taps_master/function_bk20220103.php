<?php include ('iconn.php');
	include 'header2.html';
	require_once 'sqlHelper.php';
	global $reviewSql ;
	global $reviewSqlWithDate ;
	global $filterValue ;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		// Functions --------------------------------------
		function display()
		{
			echo "hello ".$_POST["studentname"]. "<br><br>";

			print "last_sql = ".$_POST["last_sql"];
			exit;
		}

		if(isset($_POST['exp']))
		{
			display();
		}					

		// ------------------------------------------------



		if (isset($_POST['review'])) {
			//header('Cache-Control: no cache'); //no cache
			//session_cache_limiter('private_no_expire');

			print '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		
			<script src="https://code.jquery.com/jquery-3.5.0.js"></script>

			<style type="text/css">
						

						#popup { display:none; border-radius: 10px; position:absolute;top:0px; left:0px;  width:100%; height:100%; z-index:10; } 
						#popupOverlay { position:absolute;top:0px;  left:0px;  width:100%; height:100%;background:black;opacity:0.5; z-index:11; }
						#popupContent { position:relative; border-radius: 10px; width:450px; margin:10 auto;  background:white; margin-top:150px; border:0.5px solid; z-index:12; padding: 20px;}
						.popupTitle {margin-left: 20px; margin-right: 20px; }

						#sampleId{visibility: visible;}

						.button {
						  background-color: #4CAF50; 
						  border: none;
						  color: white;
						  padding: 10px 30px;
						  text-align: center;
						  text-decoration: none;
						  display: inline-block;
						  font-size: 13px;
						  border-radius: 10px;
						  margin: 4px 2px;
						  cursor: pointer;
						}

						.button1 {background-color: #008CBA;}
						.button2 {background-color: #f44336;}

						.row {
						  width: 100%;
						  display: flex;
						  flex-direction: row;
						  justify-content: center;
						}

					</style>';



			print '<h2>Filter Result</h2>';

			$sql_value = "";

			$val_list ="";
			$compounds = $_POST["compound_id"];
			consol.log(json_encode($_POST["compound_id"]));
	
			$sComp='';
			for($i = 0; $i <= count($_POST["compound_id"])-1; $i++) {	
				if ($i==count($_POST["compound_id"])-1){
					$sComp.= "'".$_POST["compound_id"][$i]."'";
				}
				else{
					$sComp.= "'".$_POST["compound_id"][$i]."',";
				}
			}
			if ($_POST["review"]=="CURRENT"){
				$sql_value.=" glab_curr.compound_code in (".$sComp.")";
			}
			if ($_POST["review"]=="RAW"){
				$sql_value.=" glab_template.compound_code in (".$sComp.")";
			}
			if ($_POST["review"]=="HISTORY"){
				$sql_value.=" glab_rev_h.compound_code in (".$sComp.")";
			}


			$site_list = "";

			$sSt='';
			for($i = 0; $i <= count($_POST["site_id"])-1; $i++) {	
				if ($i==count($_POST["site_id"])-1){
					$sSt.= "'".$_POST["site_id"][$i]."'";
				}
				else{
					$sSt.= "'".$_POST["site_id"][$i]."',";
				}
			}


			if ($_POST["review"]=="CURRENT"){
				$sql_value.=" and glab_curr.site IN (".$sSt.") and 
				glab_curr.start_date between '".$_POST['start']."' and '".$_POST['end']."'";
			}
			if ($_POST["review"]=="RAW"){
				$sql_value.=" and glab_template.site IN (".$sSt.") and 
				glab_template.start_date between '".$_POST['start']."' and '".$_POST['end']."'";
			}
			if ($_POST["review"]=="HISTORY"){
				$sql_value.=" and glab_rev_h.site IN (".$sSt.") and 
				glab_rev_h.start_date between '".$_POST['start']."' and '".$_POST['end']."'";
			}
				


			$haveSite = false;
			$haveCompound = false;

			$sql_value_final = "";
			   		$sql_value_final = "WHERE ".$sql_value;
/*			 
			$l = "SELECT glab_curr.site as 'Site', glab_curr.start_date AS 'Start Date' , 
				glab_curr.sample_id AS 'Sample ID',glab_curr.compound_group as 'Group',
				glab_curr.compound_code as 'Compound',glab_curr.conc_ppbv as 'Conc.(ppbv)/SAMPLE',
				contractor_curr.flow_rate as 'Flow(LPM)',contractor_curr.duration as 'Time(min)',
				(contractor_curr.flow_rate/1000)*contractor_curr.duration AS 'Volume(m3)',
				IF (glab_curr.conc_ppbv < 0,
					(glab_curr.conc_ppbv/-2/((contractor_curr.flow_rate/1000)*contractor_curr.duration)),
					(glab_curr.conc_ppbv/((contractor_curr.flow_rate/1000)*contractor_curr.duration)) ) AS 'Conc/M3',
					contractor_curr.who_tef AS 'WHO TEF Ratio' ,
				ROUND( (IF (glab_curr.conc_ppbv < 0,
					(glab_curr.conc_ppbv/-2/((contractor_curr.flow_rate/1000)*contractor_curr.duration)),
					(glab_curr.conc_ppbv/((contractor_curr.flow_rate/1000)*contractor_curr.duration)) ))*contractor_curr.who_tef,6)  AS 'WHO TEQ conc/m3',
				glab_curr.casno1 
				FROM glab_curr JOIN contractor_curr 
				ON contractor_curr.sample_id = glab_curr.sample_id 
				AND glab_curr.compound_group = contractor_curr.compound_group ".$sql_value_final." 
				ORDER BY glab_curr.start_date , glab_curr.compound_code ;";
*/		   
			if ($_POST["review"]=="CURRENT"){
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
				
				FROM glab_curr 
				JOIN contractor_curr ON glab_curr.site = contractor_curr.site AND 
				contractor_curr.sample_id = glab_curr.sample_id AND glab_curr.start_date = contractor_curr.sample_date 
				AND glab_curr.compound_group = contractor_curr.compound_group 
				AND glab_curr.compound_code = contractor_curr.compound_code ".$sql_value_final." 
				ORDER BY glab_curr.site, glab_curr.start_date , glab_curr.compound_code ;";
			}


			if ($_POST["review"]=="RAW"){
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
				
				FROM glab_template 
				JOIN contractor_template ON glab_template.site = contractor_template.site AND 
				contractor_template.sample_id = glab_template.sample_id AND glab_template.start_date = contractor_template.sample_date 
				AND glab_template.compound_group = contractor_template.compound_group 
				AND glab_template.compound_code = contractor_template.compound_code ".$sql_value_final." 
				ORDER BY glab_template.site, glab_template.start_date , glab_template.compound_code ;";
			}


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
				
				FROM glab_rev_h 
				".$sql_value_final." 
				ORDER BY modi_dt desc, glab_rev_h.site, glab_rev_h.start_date , glab_rev_h.compound_code ;";
			}



			$reviewSqlWithDate = $l;
				print "<p>l = ".$l;
			//	 exit();  

			$filterValue = $sql_value_final;

			$reviewSql = "SELECT glab_curr.site as 'Site', glab_curr.sample_id AS 'Sample ID',glab_curr.compound_code as 'Compound',
							glab_curr.conc_ppbv as RawResult, glab_curr.conc_mcg_m3 as GLabResult, 
							glab_curr.conc as 'Conc./SAMPLE',contractor_curr.flow_rate as 'Flow(LPM)',
							contractor_curr.duration as 'Time(min)',
							(contractor_curr.flow_rate/1000)*contractor_curr.duration AS 'Volume(m3)',
							IF (glab_curr.conc < 0,
								(glab_curr.conc/-2/((contractor_curr.flow_rate/1000)*contractor_curr.duration)),
							(glab_curr.conc/((contractor_curr.flow_rate/1000)*contractor_curr.duration)) ) AS 'Conc/M3',
							contractor_curr.who_tef AS 'WHO TEF Ratio' ,
							ROUND( (IF (glab_curr.conc < 0,
								(glab_curr.conc/-2/((contractor_curr.flow_rate/1000)*contractor_curr.duration)),
								(glab_curr.conc/((contractor_curr.flow_rate/1000)*contractor_curr.duration)) ))*contractor_curr.who_tef,6)  AS 'WHO TEQ conc/m3'
							FROM glab_curr JOIN contractor_curr 
							ON contractor_curr.sample_id = glab_curr.sample_id 
							AND glab_curr.compound_group = contractor_curr.compound_group ".$sql_value_final." 
							ORDER BY glab_curr.start_date , glab_curr.compound_code ;";

			//$glab_rs = $dbc->query($l);
			$abc = $l;
			if (!$resultunt = mysqli_query($dbc, $l)) {
				exit(mysqli_error($dbc));
			}else{
				
				print '
				<form id="ts" action="n_glab_h.php" method="post">  			
				<table id="dataset" class="table" cellspacing="0" width="100%" table-layout:fixed>
					<tr>
						<th>Site</th>
						<th>Start Date</th>
						<th>Sample ID</th>
						<th>Group</th>
						<th>Compound</th>
						<th>Raw Result(ppbv)</th>
						<th>GLab Result(mg/m3)</th>
						<th>Conc.(ppbv)/SAMPLE</th>
						<th>Conc/M3</th>
						<th>Flow(LPM)</th>
						<th>Time(min)</th>
						<th>Volume(m3)</th>
						<th>WHO TEF Ratio</th>
						<th>WHO TEQ conc/m3</th>
						<th>Casno1</th>';
						if ($_POST["review"]=="HISTORY"){
							print '<th>Remark</th>
							<th>Modify Timestamp</th>';
						}
					print '</tr>';


				if ($resultunt->num_rows){ 
	//stp				while ($r_rev = $glab_rs->fetch_array(MYSQLI_BOTH)) {
					$i=0;
					while ($r_rev = mysqli_fetch_assoc($resultunt)) {
				
						if ($_POST["review"]=="CURRENT"){
							print '<tr id='.$i.' class='.$i.'>
							<td style="width:30px"><input type="text" id="site.'.$i.'" name="site[]" readonly value='.$r_rev['Site'].' style="width:50px">
							<input type="hidden" id="rwstatus.'.$i.'" name="rwstatus.'.$i.'" value="NNNNNNNNNNNNNNN"></td>

							<td style="width:100px"><input type="text" id="s_date.'.$i.'" name="s_date[]" readonly value='.$r_rev['Start Date'].' style="width:120px"></td>
							<td style="width:130px"><input type="text" id="sample_id.'.$i.'" name="sample_id[]" readonly value='.$r_rev['Sample ID'].' style="width:150px"></td>				
							<td style="width:50px"><input type="text" id="group.'.$i.'" name="group[]" readonly value='.$r_rev['Group'].' style="width:70px"></td>
							<td style="width:50px"><input type="text" id="compound.'.$i.'" name="compound[]" readonly value='.$r_rev['Compound'].' style="width:180px"></td>
							<td style="width:20px"><input type="text" id="rawresult.'.$i.'" name="rawresult[]" readonly value="'.$r_rev['RawResult'].'" style="width:100px"></td>
							<td style="width:20px"><input type="text" id="glabresult.'.$i.'" name="glabresult[]" readonly value='.$r_rev['GLabResult'].' style="width:100px"></td>
			
							<td style="width:80px"><input type="number" id="sample_conc.'.$i.'" name="sample_conc[]" value='.$r_rev["Conc.(ppbv)/SAMPLE"].' style="width:100px">
							<input type="hidden" id="Original_sample_conc.'.$i.'" name="Original_sample_conc.'.$i.'" value='.$r_rev["Conc.(ppbv)/SAMPLE"].'>

							<td style="width:80px"><input type="number" id="conc_m3.'.$i.'" name="conc_m3[]" readonly value='.round($r_rev['Conc/M3'],5).' style="width:100px"></td>							
							
							<td style="width:80px"><input type="number" id="flow.'.$i.'" name="flow[]" value='.$r_rev["Flow(LPM)"].' style="width:170px">					
							<input type="hidden" id="Original_flow.'.$i.'" name="Original_flow.'.$i.'" value='.$r_rev["Flow(LPM)"].'>

							<td style="width:80px"><input type="number" id="mtime.'.$i.'" name="mtime[]" value='.$r_rev["Time(min)"].' style="width:100px">
							<input type="hidden" id="Original_mtime.'.$i.'" name="Original_mtime.'.$i.'" value='.$r_rev["Time(min)"].'>

							<td style="width:80px"><input type="number" id="vol.'.$i.'" name="vol[]" readonly value='.round($r_rev["Volume(m3)"],5).' style="width:100px">

							<td style="width:80px"><input type="number" id="who_tef.'.$i.'" name="who_tef[]" value='.$r_rev["WHO TEF Ratio"].' style="width:100px">
							<input type="hidden" id="Original_who_tef.'.$i.'" name="Original_who_tef.'.$i.'" value='.$r_rev["WHO TEF Ratio"].'>

							<td style="width:80px"><input type="number" id="who_teq.'.$i.'" name="who_teq[]" value='.round($r_rev["WHO TEQ Conc/m3"],5).' style="width:100px">
							<input type="hidden" id="Original_who_teq.'.$i.'" name="Original_who_teq.'.$i.'" value='.round($r_rev["WHO TEQ Conc/m3"],5).'>

							<td style="width:80px"><input type="text"  id="casno1.'.$i.'" name="casno1[]" value='.$r_rev['casno1'].' style="width:100px"></td>						
							<input type="hidden" id="Original_casno1.'.$i.'" name="Original_casno1.'.$i.'" value='.round($r_rev["casno1"],5).'>

							</tr>';
						}

						if ($_POST["review"]=="RAW"){
							print '<tr id='.$i.' class='.$i.'>
							<td style="width:30px"><input type="text" id="site.'.$i.'" name="site[]" readonly value='.$r_rev['Site'].' style="width:50px">
							<td style="width:100px"><input type="text" id="s_date.'.$i.'" name="s_date[]" readonly value='.$r_rev['Start Date'].' style="width:120px"></td>
							<td style="width:130px"><input type="text" id="sample_id.'.$i.'" name="sample_id[]" readonly value='.$r_rev['Sample ID'].' style="width:150px"></td>				
							<td style="width:50px"><input type="text" id="group.'.$i.'" name="group[]" readonly value='.$r_rev['Group'].' style="width:70px"></td>
							<td style="width:50px"><input type="text" id="compound.'.$i.'" name="compound[]" readonly value='.$r_rev['Compound'].' style="width:180px"></td>
							<td style="width:20px"><input type="text" id="rawresult.'.$i.'" name="rawresult[]" readonly value='.$r_rev['RawResult'].' style="width:100px"></td>
							<td style="width:20px"><input type="text" id="glabresult.'.$i.'" name="glabresult[]" readonly value='.$r_rev['GLabResult'].' style="width:100px"></td>
							<td style="width:80px"><input type="number" id="sample_conc.'.$i.'" name="sample_conc[]" readonly value='.$r_rev["Conc.(ppbv)/SAMPLE"].' style="width:100px">
							<td style="width:80px"><input type="number" id="conc_m3.'.$i.'" name="conc_m3[]" readonly value='.round($r_rev['Conc/M3'],5).' style="width:100px"></td>							
							<td style="width:80px"><input type="number" id="flow.'.$i.'" name="flow[]" readonly value='.$r_rev["Flow(LPM)"].' style="width:170px">					
							<td style="width:80px"><input type="number" id="mtime.'.$i.'" name="mtime[]" readonly value='.$r_rev["Time(min)"].' style="width:100px">
							<td style="width:80px"><input type="number" id="vol.'.$i.'" name="vol[]" readonly value='.round($r_rev["Volume(m3)"],5).' style="width:100px">
							<td style="width:80px"><input type="number" id="who_tef.'.$i.'" name="who_tef[]" readonly value='.$r_rev["WHO TEF Ratio"].' style="width:100px">
							<td style="width:80px"><input type="number" id="who_teq.'.$i.'" name="who_teq[]" readonly value='.round($r_rev["WHO TEQ Conc/m3"],5).' style="width:100px">
							<td style="width:80px"><input type="text"  id="casno1.'.$i.'" name="casno1[]" readonly value='.$r_rev['casno1'].' style="width:100px"></td>						
							</tr>';
						}

						if ($_POST["review"]=="HISTORY"){
							$mdt = date("Y-m-d\TH:i:s", strtotime($r_rev['Modify Timestamp']));
							print '<tr id='.$i.' class='.$i.'>
							<td style="width:30px"><input type="text" id="site.'.$i.'" name="site[]" readonly value='.$r_rev['Site'].' style="width:50px">
							<td style="width:100px"><input type="text" id="s_date.'.$i.'" name="s_date[]" readonly value='.$r_rev['Start Date'].' style="width:120px"></td>
							<td style="width:130px"><input type="text" id="sample_id.'.$i.'" name="sample_id[]" readonly value='.$r_rev['Sample ID'].' style="width:150px"></td>				
							<td style="width:50px"><input type="text" id="group.'.$i.'" name="group[]" readonly value='.$r_rev['Group'].' style="width:70px"></td>
							<td style="width:50px"><input type="text" id="compound.'.$i.'" name="compound[]" readonly value='.$r_rev['Compound'].' style="width:180px"></td>
							<td style="width:20px"><input type="text" id="rawresult.'.$i.'" name="rawresult[]" readonly value='.($r_rev['RawResult']=="" ? 'NULL':$r_rev['RawResult']).' style="width:100px"></td>
							<td style="width:20px"><input type="text" id="glabresult.'.$i.'" name="glabresult[]" readonly value='.($r_rev['GLabResult']=="" ? 'NULL':$r_rev['GLabResult']).' style="width:100px"></td>
							<td style="width:80px"><input type="number" id="sample_conc.'.$i.'" name="sample_conc[]" readonly value='.$r_rev["Conc.(ppbv)/SAMPLE"].' style="width:100px">
							<td style="width:80px"><input type="number" id="conc_m3.'.$i.'" name="conc_m3[]" readonly value='.round($r_rev['Conc/M3'],5).' style="width:100px"></td>							
							<td style="width:80px"><input type="number" id="flow.'.$i.'" name="flow[]" readonly value='.$r_rev["Flow(LPM)"].' style="width:170px">					
							<td style="width:80px"><input type="number" id="mtime.'.$i.'" name="mtime[]" readonly value='.$r_rev["Time(min)"].' style="width:100px">
							<td style="width:80px"><input type="number" id="vol.'.$i.'" name="vol[]" readonly value='.round($r_rev["Volume(m3)"],5).' style="width:100px">
							<td style="width:80px"><input type="number" id="who_tef.'.$i.'" name="who_tef[]" readonly value='.$r_rev["WHO TEF Ratio"].' style="width:100px">
							<td style="width:80px"><input type="number" id="who_teq.'.$i.'" name="who_teq[]" readonly value='.round($r_rev["WHO TEQ Conc/m3"],5).' style="width:100px">
							<td style="width:80px"><input type="text"  id="casno1.'.$i.'" name="casno1[]" readonly value='.$r_rev['casno1'].' style="width:100px"></td>						
							<td style="width:80px"><input type="text"  id="rmk.'.$i.'" name="rmk[]" readonly value='.($r_rev['Remark']=="" ? 'NULL':$r_rev['Remark']).' style="width:100px"></td>						
							<td style="width:80px"><input type="text"  id="modi_dt.'.$i.'" name="modi_dt[]" readonly value='.$mdt.' style="width:150px"></td>
							
							</tr>';
						}

							//($_POST['sample_conc'][$i]=="" ? 'NULL':$_POST['sample_conc'][$i])




						$i+=1;
					}		
				

				print '<input type="hidden" id="last_sql" name="last_sql" value="'.$abc.'">
				<input type="hidden" id="rev_cat" name="rev_cat" value="'.$_POST["review"].'">';
	
				if ($_POST["review"]=="CURRENT"){
//					print '</table><input type="submit" value="Submit"></form>';			
					print '</table><input type="submit" value="Submit">';			

				}else{
//					print '</table></form>';
					print '</table>';

				}

				print '<span>  </span><input type="submit" value="Export" name="exp" formaction="exp.php"></form>';

/*				
				print '<form id="rev" method="post" action="exp.php">
				<input type="hidden" id="last_sql" name="last_sql" value="'.$abc.'">
				<input type="hidden" id="rev_cat" name="rev_cat" value="'.$_POST["review"].'">

				<input type="submit" value="Export" name="exp"> 
						</form>';
*/

/*
				<td style="width:30px"><input type="text" id="site'.$i.'" name="site[]" readonly value='.$r_rev['Site'].' style="width:50px"></td>
				<td style="width:100px"><input type="text" name="s_date[]" readonly value='.$r_rev['Start Date'].' style="width:120px"></td>
				<td style="width:130px"><input type="text" name="sample_id[]" readonly value='.$r_rev['Sample ID'].' style="width:150px"></td>				
				<td style="width:50px"><input type="text" name="group[]" readonly value='.$r_rev['Group'].' style="width:70px"></td>
				<td style="width:50px"><input type="text" name="compound[]" readonly value='.$r_rev['Compound'].' style="width:180px"></td>
				<td style="width:20px"><input type="text" name="rawresult[]" readonly value='.$r_rev['RawResult'].' style="width:100px"></td>
				<td style="width:20px"><input type="text" name="glabresult[]" readonly value='.$r_rev['GLabResult'].' style="width:100px"></td>

				<td style="width:80px"><input type="number" name="sample_conc[]" value='.$r_rev["Conc./SAMPLE"].' style="width:100px"></td>
				<td style="width:80px"><input type="number" name="conc_m3[]" readonly value='.round($r_rev['Conc/M3'],5).' style="width:100px"></td>							
				<td style="width:80px"><input type="number" name="flow[]" value='.$r_rev['Flow(LPM)'].' style="width:100px"></td>					
				<td style="width:80px"><input type="number" name="mtime[]" value='.$r_rev['Time(min)'].' style="width:100px"></td>
				<td style="width:80px"><input type="number" name="vol[]" value='.round($r_rev['Volume(m3)'],5).' style="width:100px"></td>
				<td style="width:80px"><input type="number" name="who_tef[]" value='.$r_rev['WHO TEF Ratio'].' style="width:100px"></td>
				<td style="width:80px"><input type="number" name="who_teq[]" value='.round($r_rev['WHO TEQ Conc/m3'],5).' style="width:100px"></td>
				<td style="width:80px"><input type="text" name="casno1[]" value='.$r_rev['casno1'].' style="width:100px"></td>
*/		

				}
			
			}
		}



		print "<script>
		$(document).ready(function(){		  
		  $(\"input\").change(function (e) {

				var abc = $(this).attr('id').indexOf('.')+1;
				alert('. position = ' + abc);
			  var rowId = $(this).attr('id').substring($(this).attr('id').indexOf('.')+1);
				alert('rowId = ' + rowId);

			  let modi_input = $(this).attr('id');
			  alert('Being modified input : ' + modi_input );
			  $(this).val($(\"input[id='\"+modi_input+\"']\").first().val());

			  let result = \"input[id='rwstatus.\" + rowId + \"']\";
			  alert('Id : ' + $(this).attr('id') );
			  alert('Row status id - rwresult is : ' + result );

			  
			$(result).val(function(index, oldValue)
			{
				let status_val = '';

			  alert('Inside val function - result is : ' + result );
			  alert('status oldValue is : ' + oldValue);
			  
			  alert ('New value : ' + \"input[id='\"+modi_input+\"']\ = \" + $(\"input[id='\"+modi_input+\"']\").first().val()); 
			  alert ('Original value : ' + \"input[id='Original_\"+modi_input+\"']\ = \" + $(\"input[id='Original_\"+modi_input+\"']\").first().val()); 

			  if ( $(\"input[id='Original_\"+modi_input+\"']\").first().val() === $(\"input[id='\"+modi_input+\"']\").first().val() ) {
				  alert('They are the same');
				  
				  //In case user change the value back from modified value to original, DIR must be cleared in order to prevent post

				  switch (modi_input.replace(rowId,'')) { 
					  case 'sample_conc.':
						  status_val = oldValue.substring(0, 7) + 'N' + oldValue.substring(8);
						  alert('status_val = ' + status_val);
						  return status_val;
						  break;
					  case 'conc_m3.':
						  status_val = oldValue.substring(0, 8) + 'N' + oldValue.substring(9);
						  alert('status_val = ' + status_val);
						  return status_val;
						  break;
					  case 'flow.':
						  status_flow = oldValue.substring(0, 9) + 'N' + oldValue.substring(10);
						  alert('status_val = ' + status_val);
						  return status_val;
						  break;						
					  case 'mtime.': 
						  status_val = oldValue.substring(0, 10) + 'N' + oldValue.substring(11);
						  alert('status_val = ' + status_val);
						  return status_val;
						  break;
					  case 'vol.': 
						  status_val = oldValue.substring(0, 11) + 'N' + oldValue.substring(12);
						  alert('status_val = ' + status_val);
						  return status_val;
						  break;
					  case 'who_tef.':
						  status_val = oldValue.substring(0, 12) + 'N' + oldValue.substring(13);
						  alert('status_val = ' + status_val);
						  return status_val;
						  break;
					  case 'who_teq.':
						  status_val = oldValue.substring(0, 13) + 'N' + oldValue.substring(14);
						  alert('status_val = ' + status_val);
						  return status_val;
						  break;
					  case 'casno1.':
						  status_val = oldValue.substring(0, 14) + 'N' + oldValue.substring(15);
						  alert('status_val = ' + status_val);
						  return status_val;
						  break;
					  default:
						alert('default');
				}


			  }
			  else{
				alert('They are NOT the same - return DIR');
				alert ('modi_input.replace : ' + modi_input.replace(rowId,''))

				switch (modi_input.replace(rowId,'')) { 
					case 'sample_conc.':
						status_val = oldValue.substring(0, 7) + 'Y' + oldValue.substring(8);
						alert('status_val = ' + status_val);
						return status_val;
						break;
					case 'conc_m3.':
						status_val = oldValue.substring(0, 8) + 'Y' + oldValue.substring(9);
						alert('status_val = ' + status_val);
						return status_val;
						break;
					case 'flow.':
						status_flow = oldValue.substring(0, 9) + 'Y' + oldValue.substring(10);
						alert('status_val = ' + status_val);
						return status_val;
						break;						
					case 'mtime.': 
						status_val = oldValue.substring(0, 10) + 'Y' + oldValue.substring(11);
						alert('status_val = ' + status_val);
						return status_val;
						break;
					case 'vol.': 
						status_val = oldValue.substring(0, 11) + 'Y' + oldValue.substring(12);
						alert('status_val = ' + status_val);
						return status_val;
						break;
					case 'who_tef.':
						status_val = oldValue.substring(0, 12) + 'Y' + oldValue.substring(13);
						alert('status_val = ' + status_val);
						return status_val;
						break;
					case 'who_teq.':
						status_val = oldValue.substring(0, 13) + 'Y' + oldValue.substring(14);
						alert('status_val = ' + status_val);
						return status_val;
						break;
					case 'casno1.':
						status_val = oldValue.substring(0, 14) + 'Y' + oldValue.substring(15);
						alert('status_val = ' + status_val);
						return status_val;
						break;
					default:
						break;
				}

			  }
			  
			  });  
		 
		});
	  });
				
	  </script>";
	  
	  
	 print "<script>
	  $( \"form\" ).submit(function( event ) {
		
	  for (let line = 0; line <= $('#dataset tr:last').attr(\"id\"); line++) {
		if ( $(\"input[id='rwstatus.\" + line + \"']\").first().val() === 'NNNNNNNNNNNNNNN' ) {
		  
			  <!-- let con = \"tr.\" + line + \" input, tr.\" + line + \" select, tr.\" + line + \" textarea\"; -->
			  let con = \"tr.\" + line + \" input\";
			  $(con).prop('disabled', true);    
	  
		  }
	   	  
	  }
	  
	  return;
	  event.preventDefault();
	  
	  });
	  </script>";




	}


?>













