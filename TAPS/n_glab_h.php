<?php include ('iconn.php');
	include 'header2.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		print '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

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
 

		print '<h2>Review & Changes</h2>';


/*		
		SELECT glab_template.start_date AS 'Start Date' , 
			glab_template.sample_id AS 'Sample ID',
		 glab_template.compound_code as 'Compound',
		 glab_template.conc as 'Conc./SAMPLE', 
		 contractor_template.flow_rate as 'Flow(LPM)',
		 contractor_template.duration as 'Time(min)', 
		 (contractor_template.flow_rate/1000)*contractor_template.duration AS 'Volume(m3)', 
		 IF (glab_template.conc < 0, (glab_template.conc/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)), 
		 (glab_template.conc/((contractor_template.flow_rate/1000)*contractor_template.duration)) ) AS 'Conc/M3', 
		 contractor_template.who_tef AS 'WHO TEF Ratio' , 
		 ROUND( (IF (glab_template.conc < 0, (glab_template.conc/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)), 
		 (glab_template.conc/((contractor_template.flow_rate/1000)*contractor_template.duration)) ))*contractor_template.who_tef,6) 
		 AS 'WHO TEQ conc/m3', 
		 glab_template.casno1 FROM glab_template 

*/

/* K
		INSERT INTO promotions ( promotion_name, discount, start_date, expired_date ) 
		VALUES ( '2019 Summer Promotion', 0.15, '20190601', '20190901' ), 
				( '2019 Fall Promotion', 0.20, '20191001', '20191101' ), 
				( '2019 Winter Promotion', 0.25, '20191201', '20200101' );
K */ 

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

		FROM glab_template 
		JOIN contractor_template ON glab_template.site = contractor_template.site AND 
		contractor_template.sample_id = glab_template.sample_id AND glab_template.start_date = contractor_template.sample_date 
		AND glab_template.compound_group = contractor_template.compound_group 
		AND glab_template.compound_code = contractor_template.compound_code ".$sql_value_final." 
		ORDER BY glab_template.site, glab_template.start_date , glab_template.compound_code ;";
*/


		mysqli_autocommit($dbc, FALSE);
		mysqli_begin_transaction($dbc, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);



		//$h= "insert into glab_rev_h (start_date,site,sample_id,compound_group,compound_code,conc,
		//		flow_rate,duration,vol,conc_m3,who_tef,who_teq_conc_m3,casno1) values ";

		for($i = 0; $i <= count($_POST["sample_id"])-1; $i++) {	
			//($var > 2 ? true : false)
			
			//"',STR_TO_DATE('".$sample_date."','%d/%m/%Y')"

			//$s_who_tef = ($_POST['who_tef'][$i]=="" ? 'NULL':$_POST['who_tef'][$i]);
			//$s_who_teq = ($_POST['who_teq'][$i]=="" ? 'NULL':$_POST['who_teq'][$i]);
			// $s_vol = ($_POST['vol'][$i]=="" ? 'NULL':$_POST['vol'][$i]);

			$z1 = "update glab_curr set conc_ppbv = ".($_POST['sample_conc'][$i]=="" ? 'NULL':$_POST['sample_conc'][$i])
					."	where start_date=STR_TO_DATE('".$_POST['s_date'][$i]."','%Y-%m-%d')"
					." and site='".$_POST['site'][$i]."' and sample_id='".$_POST["sample_id"][$i]."'"
					." and compound_group='".$_POST['group'][$i]."' and compound_code='".$_POST['compound'][$i]."';";

					if (!$resultunt = mysqli_query($dbc, $z1)) {
						exit(mysqli_error($dbc));
					}	

			$z2 = "update contractor_curr set flow_rate=".($_POST['flow'][$i]=="" ? 'NULL':$_POST['flow'][$i])
					.",duration=".$_POST['mtime'][$i].",who_tef=".($_POST['who_tef'][$i]=="" ? 'NULL':$_POST['who_tef'][$i])
					."	where sample_date=STR_TO_DATE('".$_POST['s_date'][$i]."','%Y-%m-%d')"
					." and site='".$_POST['site'][$i]."' and sample_id='".$_POST["sample_id"][$i]."'"
					." and compound_group='".$_POST['group'][$i]."' and compound_code='".$_POST['compound'][$i]."';";

					if (!$resultunt = mysqli_query($dbc, $z2)) {
						exit(mysqli_error($dbc));
					}	
/*
			$h= "insert into glab_rev_h (start_date,site,sample_id,compound_group,compound_code,conc_ppbv,
				flow_rate,glab_result,duration,vol,conc_m3,who_tef,who_teq_conc_m3,casno1) values (STR_TO_DATE('".$_POST['s_date'][$i]."','%Y-%m-%d'),'"
				.$_POST['site'][$i]."','".$_POST["sample_id"][$i]."','".$_POST['group'][$i]."','".$_POST['compound'][$i]
				."',".($_POST['sample_conc'][$i]=="" ? 'NULL':$_POST['sample_conc'][$i])
				.",".($_POST['flow'][$i]=="" ? 'NULL':$_POST['flow'][$i]).",".$_POST['glabresult'][$i].",".$_POST['mtime'][$i].","
				.($_POST['vol'][$i]=="" ? 'NULL':$_POST['vol'][$i]).",".($_POST['conc_m3'][$i]=="" ? 'NULL':$_POST['conc_m3'][$i]).","
				.($_POST['who_tef'][$i]=="" ? 'NULL':$_POST['who_tef'][$i]).",".($_POST['who_teq'][$i]=="" ? 'NULL':$_POST['who_teq'][$i])
				.",'".$_POST['casno1'][$i]."');";
					//."','".$_POST['rmk'][i]."'),";
*/
			$h= "insert into glab_rev_h (start_date,site,sample_id,compound_group,compound_code,conc_ppbv,
			flow_rate,duration,vol,conc_m3,who_tef,who_teq_conc_m3,casno1) values (STR_TO_DATE('".$_POST['s_date'][$i]."','%Y-%m-%d'),'"
			.$_POST['site'][$i]."','".$_POST["sample_id"][$i]."','".$_POST['group'][$i]."','".$_POST['compound'][$i]
			."',".($_POST['sample_conc'][$i]=="" ? 'NULL':$_POST['sample_conc'][$i])
			.",".($_POST['flow'][$i]=="" ? 'NULL':$_POST['flow'][$i]).",".$_POST['mtime'][$i].","
			.($_POST['vol'][$i]=="" ? 'NULL':$_POST['vol'][$i]).",".($_POST['conc_m3'][$i]=="" ? 'NULL':$_POST['conc_m3'][$i]).","
			.($_POST['who_tef'][$i]=="" ? 'NULL':$_POST['who_tef'][$i]).",".($_POST['who_teq'][$i]=="" ? 'NULL':$_POST['who_teq'][$i])
			.",'".$_POST['casno1'][$i]."');";
				//."','".$_POST['rmk'][i]."'),";
	
					




			//$h.=$s;
			//$h.=$z;
			//print $h."<br>";			
			//print "after sql";
			
//			$res=mysqli_query($dbc, $h) or trigger_error("Query Failed! SQL: $h - Error: ".mysqli_error($dbc), E_USER_ERROR);
			//$res=mysqli_query($dbc, $h); 
			if (!$resultunt = mysqli_query($dbc, $h)) {
				exit(mysqli_error($dbc));
			}	

			if (! empty($resultunt)) {

				$r_in++;
				$type = "success";
				$message = "*No. of records have been modified : ".$r_in;
			} else {
				$type = "error";
				$message = "Problem in adding history data. Please Correct and Reload the related records ".$h."</p>";
				//$message = "Problem in loading CSV Data -> ".$loc_id." | ".$sample_date." | ".$m_time." | ".$ele_id." | ".$sample_v."</p>";
			}					



		}

		//exit;




/*K
		foreach($_POST['s_date'] as $sdate_list) { 
			$d .= "'".$sdate_list."',";
		}

		foreach($_POST['compound'] as $comp_cde_list) { 
			$cp .= "'".$comp_cde_list."',";
		}

		foreach($_POST['sample_conc'] as $sample_conc_list) { 
			$sc .= "'".$sample_conc_list."',";
		}

		foreach($_POST['flow'] as $flow_rate_list) { 
			$fr .= "'".$flow_rate_list."',";
		}

		foreach($_POST['mtime'] as $duration_list) { 
			$t .= "'".$duration_list."',";
		}
		
		foreach($_POST['vol'] as $vol_list) { 
			$v .= "'".$vol_list."',";
		}

		foreach($_POST['conc_m3'] as $conc_m3_list) { 
			$cm3 .= "'".$conc_m3_list."',";
		}

		foreach($_POST['who_tef'] as $who_tef_list) { 
			$tef .= "'".$who_tef_list."',";
		}

		foreach($_POST['who_teq'] as $who_teq_list) { 
			$teq .= "'".$who_teq_list."',";
		}

K */

		//$teq=substr($teq,0,-1);
		//$h.=$s.$d.$cp.$sc.$fr.$t.$v.$cm3.$tef.$teq.")";

		//$s=substr($s,0,-1).";";


		if ($type == "success"){
            mysqli_commit($dbc);        
			//Print "Total number of records have been modified : ".$i."<br>";
		}else{
            mysqli_rollback($dbc);
        }
        mysqli_autocommit($dbc, TRUE);




/*-------------------------------------------------


		for($i = 0; $i <= count($_POST["sample_id"])-1; $i++) {	
			if ($i==count($_POST["sample_id"])-1){
				$h.= "'".$_POST["sample_id[$i]"]."'";
			}
			else{
				$h.= "'".$_POST["sample_id"][$i]."',";
			}
		}


		$dbc->autocommit(FALSE);
		if ($dbc->query($q) === TRUE) {

			foreach($_POST['did'] as $did_list) { 
				$q = "insert into user_district (trans_date,userid,district_id) 
							values ('".date('Y/m/d')."','".$_POST['userid']
							."','".$did_list."');";
			if ($dbc->query($q) === FALSE) {
					echo "Error: " . $q . "<br>" . $dbc->error;
					exit();
							}
			}

			$dbc->autocommit(TRUE);
			$created=TRUE;
																													
		} else {
			$comp="false";				
			echo "Error: " . $q . "<br>" . $dbc->error;
			exit();
			}

-------------------------------------------------	*/		
		print '<div id="response" class="'; if(!empty($type)) { echo $type . " display-block"; }; 
		print '">';
		if(!empty($message)) { echo $message; }; 
		print '</div>';

	}


?>













