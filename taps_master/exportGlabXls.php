<?php
	$con = mysqli_connect('localhost', 'root', '', 'taps');
	$output = '';

	if(isset($_POST["export"])){
		if (!empty($_POST['compoundGrp'])) {
			foreach ($_POST['compoundGrp'] as $selectedCompoundGrp) {
				$compoundGroup = $selectedCompoundGrp;
			}
		}else{
			$compoundGroup = "";
		}

		if (!empty($_POST['compound'])) {
			foreach ($_POST['compound'] as $selected) {
				$compound .= $selected.',';
			}
		}else{
			$compound = "";
		}

		$qry = "SELECT * FROM glab_sample ";
		
		if(!empty($_POST['site_code']) or !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
			$qry .= " where ";
			if(!empty($_POST['site_code'])){
				$qry .= " site_id = '".$_POST['site_code']."' ";
			}
			if(!empty($_POST['site_code']) and !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
				$qry .= " and create_date between '".$_POST['dateFrom']."' and '".$_POST['dateTo']."' ";
			}else if(empty($_POST['site_code']) and !empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
				$qry .= " create_date between '".$_POST['dateFrom']."' and '".$_POST['dateTo']."' ";
			}
		}
		
		$qry .= " order by id ASC; ";
		
		//echo $qry;

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
								<th style="width:200px;">SAMPLE ID</th>
								<th style="width:200px;">Site ID</th>
								<th style="width:200px;">Compound Group</th>
								<th style="width:200px;">Compound</th>
								<th style="width:200px;">Create Date</th>
							</thead>
						<tbody>';
			while($data=mysqli_fetch_array($res)){
				$sampleId=$data['sample_id'];
				$siteId=$data['site_id'];
				$compoundGrp=$data['compound_grp'];
				$compound=$data['compound'];
				$createDate=$data['create_date'];
				
				$output .= '<tr style="height:20px;">
								<td>'.$sampleId.'</td>
								<td>'.$siteId.'</td>
								<td>'.$compoundGrp.'</td>
								<td>'.$compound.'</td>
								<td>'.$createDate.'</td>
							</tr>';
			}
			$output .= '</tbody></table>';
			header('Content-Type: application/force-download');
			header('Content-Disposition: attachment; filename=GlabReport.xls');
			header("Content-Transfer-Encoding: BINARY");
			
			echo $output;
		}else{
			session_start();
			$_SESSION['type'] = "error";
			$_SESSION['message'] = "No Record Found.";
			header("Location: exportGlabReport.php");
			exit();
		}
	}
?>