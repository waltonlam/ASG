<?php
	$con = mysqli_connect('localhost', 'root', '', 'taps');
	$output = '';
	session_start();
	$compound = "";
	$compoundGroup = "";
	$subclause = false;

	if(isset($_POST["export"])){
		if(!empty($_POST['dateFrom']) and empty($_POST['dateTo'])){
			$_SESSION['type'] = "error";
			$_SESSION['message'] = "Please select Date To.";
			header("Location: exportGlabReport.php");
			exit();
		}
		if(empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
			$_SESSION['type'] = "error";
			$_SESSION['message'] = "Please select Date From.";
			header("Location: exportGlabReport.php");
			exit();
		}

		if (!empty($_POST['compoundGrp'])) {
			foreach ($_POST['compoundGrp'] as $selectedCompoundGrp) {
				$compoundGroup .= "'".$selectedCompoundGrp."',";
			}
			$compoundGroup = substr_replace($compoundGroup, "", -1);
		}else{
			$compoundGroup = "";
		}

		if (!empty($_POST['compound'])) {
			foreach ($_POST['compound'] as $selected) {
				$compound .= "'".$selected."',";
			}
			$compound = substr_replace($compound, "", -1);
		}else{
			$compound = "";
		}

		$qry = "SELECT g.sample_id, g.strt_date, g.site_id, g.compound, g.compound_grp, g.conc_g_m3, c.flow_rate, c.sampling_time, g.i_tef, g.who_tef_2005, g.who_tef_1998 FROM glab_sample g left join contractor_sample c on c.sample_id = g.sample_id and c.compound = g.compound";
		
		if(!empty($_POST['site_code']) or !empty($compoundGroup) or !empty($compound) or !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
			$qry .= " where ";
			if(!empty($_POST['site_code'])){
				$subclause = true;
				$qry .= " g.site_id = '".$_POST['site_code']."' ";
			}

			if ($subclause and !empty($compoundGroup)) { 
				$qry .= " and g.compound_grp in (".$compoundGroup.") ";
			}else if(!$subclause and !empty($compoundGroup)){
				$subclause = true;
				$qry .= " g.compound_grp in (".$compoundGroup.") ";
			}

			if ($subclause and !empty($compound)) { 
				$qry .= " and g.compound in (".$compound.") ";
			}else if(!$subclause and !empty($compound)){
				$subclause = true;
				$qry .= " g.compound in (".$compound.") ";
			}

			if($subclause and !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
				$qry .= " and g.create_date between '".$_POST['dateFrom']."' and '".$_POST['dateTo']."' ";
			}else if(!$subclause and !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
				$subclause = true;
				$qry .= " g.create_date between '".$_POST['dateFrom']."' and '".$_POST['dateTo']."' ";
			}

			/*if(!empty($_POST['site_code']) and !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
				$qry .= " and create_date between '".$_POST['dateFrom']."' and '".$_POST['dateTo']."' ";
			}else if(empty($_POST['site_code']) and !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
				$qry .= " create_date between '".$_POST['dateFrom']."' and '".$_POST['dateTo']."' ";
			}

			if(!empty($_POST['site_code']) and !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
				$qry .= " and create_date between '".$_POST['dateFrom']."' and '".$_POST['dateTo']."' ";
			}else if(empty($_POST['site_code']) and !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
				$qry .= " create_date between '".$_POST['dateFrom']."' and '".$_POST['dateTo']."' ";
			}*/
		}
		
		$qry .= " order by g.id ASC; ";
		
		/* debug sql
		$_SESSION['message'] = $qry;
		header("Location: exportGlabReport.php");
		exit();*/

		$res=mysqli_query($con, $qry);

		if(mysqli_num_rows($res)>0){
			$output .= '<style>
							img {
								display: block;
								max-width:180px;
								max-height:180px;
								width: auto;
								height: auto;
							}
						</style>

						<table border="1" id="example">
							<thead>
								<th style="width:200px;">Sample ID</th>
								<th style="width:200px;">Start Date</th>
								<th style="width:200px;">Site</th>
								<th style="width:200px;">Compound</th>
								<th style="width:200px;">Compound Group</th>
								<th style="width:200px;">Conc g/m3</th>
								<th style="width:200px;">Flow Rate</th>
								<th style="width:200px;">Sampling Time</th>
								<th style="width:200px;">I-TEF (pg i-TEQ/m3)</th>
								<th style="width:200px;">WHO-TEF-2005 (pg WHO-TEQ/m3)</th>
								<th style="width:200px;">WHO-TEF-1998 (pg WHO-TEQ/m3)</th>
								<th style="width:200px;">Create Date</th>
							</thead>
						<tbody>';
			while($data=mysqli_fetch_array($res)){
				$sampleId=$data['sample_id'];
				$strtDate=$data['strt_date'];
				$siteId=$data['site_id'];
				$compoundGrp=$data['compound_grp'];
				$compound=$data['compound'];
				$concGM3=$data['conc_g_m3'];
				$flowRate=$data['flow_rate'];
				$samplingTime=$data['sampling_time'];
				$iTef=$data['i_tef'];
				$whoTef2005=$data['who_tef_2005'];
				$whoTef1998=$data['who_tef_1998'];
				$createDate=$data['create_date'];
				
				$output .= '<tr style="height:20px;">
								<td>'.$sampleId.'</td>
								<td>'.$strtDate.'</td>
								<td>'.$siteId.'</td>
								<td>'.$compoundGrp.'</td>
								<td>'.$compound.'</td>
								<td>'.$concGM3.'</td>
								<td>'.$flowRate.'</td>
								<td>'.$samplingTime.'</td>
								<td>'.$iTef.'</td>
								<td>'.$whoTef2005.'</td>
								<td>'.$whoTef1998.'</td>
								<td>'.$createDate.'</td>
							</tr>';
			}
			$output .= '</tbody></table>';
			header('Content-Type: application/force-download');
			header('Content-Disposition: attachment; filename=GlabReport.xls');
			header("Content-Transfer-Encoding: BINARY");
			
			echo $output;
		}else{
			
			$_SESSION['type'] = "error";
			$_SESSION['message'] = "No Record Found.";
			header("Location: exportGlabReport.php");
			exit();
		}
	}
?>