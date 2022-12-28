<?php
$con = mysqli_connect('localhost', 'root', '', 'taps');
$output = '';
if(isset($_POST["export"])){
	$qry = "SELECT i.id, i.site_id, i.compound_grp, i.compound, i.remark, i.create_date, GROUP_CONCAT(s.path SEPARATOR ' ') as path, s.incident_id FROM incident_report i left join site_photo s on i.id = s.incident_id GROUP BY id order by i.id ASC;";
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
							<th style="width:200px;">ID</th>
							<th style="width:200px;">Site ID</th>
							<th style="width:200px;">Compound Group</th>
							<th style="width:200px;">Compound</th>
							<th style="width:200px;">Remark</th>
							<th style="width:200px;">Create Date</th>
							<th style="width:200px;">Incident ID</th>
							<th style="width:1000px;">Site Photo</th>
							<!--th style="width:200px;">Site Photo Link</th-->
						</thead>
					<tbody>';
		while($data=mysqli_fetch_array($res)){
			$id=$data['id'];
			$siteId=$data['site_id'];
			$compoundGrp=$data['compound_grp'];
			$compound=$data['compound'];
			$remark=$data['remark'];
			$createDate=$data['create_date'];
			$incidentId=$data['incident_id'];
			$path=$data['path'];
			

			$output .= '<tr style="height:110px;">
							<td>'.$id.'</td>
							<td>'.$siteId.'</td>
							<td>'.$compoundGrp.'</td>
							<td>'.$compound.'</td>
							<td>'.$remark.'</td>
							<td>'.$createDate.'</td>
							<td>'.$incidentId.'</td>';
			
			if(!empty($path)){
				$pathArray = explode(" ",$path);
				$output .= '<td>';
				foreach ($pathArray as $pathValue) {
					//echo "$pathValue <br>";
					$output .= '<img width="100" height="100" src="http://10.17.8.252'.$pathValue.'"/>';
						//<td><a href="http://10.17.8.252'.$pathValue.'"/>http://10.17.8.252'.$pathValue.'</a></td>';
				}
				$output .= '</td>';
			}else{
				$output .= '<td></td>';
				//<td></td>';
			}	
			
			/*if(!empty($path)){
				$output .= '<td><img width="100" height="100" src="http://10.17.8.252'.$path.'"/></td>
				<td><a href="http://10.17.8.252'.$path.'"/>http://10.17.8.252'.$path.'</a></td>';
			}else{
				$output .= '<td></td>
				<td></td>';
			}*/				
							
			$output .= 	'</tr>';
		}
		$output .= '</tbody></table>';
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename=IncidentReport.xls');
		header("Content-Transfer-Encoding: BINARY");
		
		echo $output;
	}
}
?>