<?php
	namespace Phppot;
	use Phppot\DataSource;

	require_once "connection.php";  
	require_once "iconn.php";
	require_once "header2.php";
	require_once __DIR__ . '/lib/ImageModel.php';
	$imageModel = new ImageModel();
?>
	<html>
	<head>
		<link href="assets/style.css" rel="stylesheet" type="text/css" />
		<style> 
			input[type=button], input[type=submit], input[type=reset] {
				background-color: #87ceeb;
				color: white;
				padding: 12px 20px;
				border: none;
				border-radius: 4px;
				cursor: pointer;
				width:100
			}
			
			.inline{   
				display: inline-block;   
				float: right;   
				margin: 20px 0px;   
			}   
			
			.pagination {   
				display: inline-block;   
			}   
			.pagination a {   
				font-weight:bold;   
				font-size:10px;   
				color: black;   
				float: left;   
				padding: 8px 16px;   
				text-decoration: none;   
				border:1px solid black;   
			}   
			.pagination a.active {   
					background-color: #E7E3E2;   
			}   
			.pagination a:hover:not(.active) {   
				background-color: #848282;   
			}   
		</style>   
	</head>

	<body>
		<?php
			$_SESSION['prev_incid'] = basename($_SERVER['PHP_SELF']);

			if (isset($_SESSION['previous'])) {
				if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
					 //session_destroy();
					 unset($_SESSION['site_id']);
					 unset($_SESSION['dateFrom']);
					 unset($_SESSION['dateTo']);
					 ### or alternatively, you can use this for specific variables:
					 ### unset($_SESSION['varname']);
				}
			}

			if (isset($_SESSION['type'])) {
				unset($_SESSION['type']);	
			}

			if (isset($_SESSION['message'])) {
				unset($_SESSION['message']);	
	   		}

			$l = "select code from site order by code ASC;";
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Site Configuration Error!</p>';
				exit();
			}

			$per_page_record = 50;  // Number of entries to show in a page.   
			// Look for a GET variable page if not found default is 1.        
			if (isset($_GET["page"])) {    
				$page  = $_GET["page"];    
			} else {    
				$page=1;    
			}    

			$start_from = ($page-1) * $per_page_record;   

			//$result = $imageModel->getAllImages($start_from, $per_page_record);
			
			//if($_SERVER['REQUEST_METHOD'] == 'POST' or $_SESSION['ttl_incident_page'] > 1){
				if(!isset($_SESSION["site_code_inc"]) or $_SERVER['REQUEST_METHOD'] == 'POST'){
					$_SESSION['site_code_inc'] = $_POST['site_code'];
					$_SESSION['dateFrom_inc'] = $_POST['dateFrom'];
					$_SESSION['dateTo_inc'] = $_POST['dateTo'];
				}

				$query = "SELECT * FROM incident_report "; 
				if(!empty($_SESSION['site_code_inc'])){
					$query .= " where site_id = '".$_SESSION['site_code_inc']."' ";
				}
				if(!empty($_SESSION['dateFrom_inc']) and !empty($_SESSION['dateTo_inc'])){
					$query .= " and create_date between '".$_SESSION['dateFrom_inc']."' and '".$_SESSION['dateTo_inc']."' ";
				}
				
				$query .= " order by id LIMIT $start_from, $per_page_record";     
				$rs_result = mysqli_query ($conn, $query);  
				
				
				/*$incidentResult = $imageModel->getIncidentReport();
				if(isset($_POST["export"])){
					// Submission from
					$filename = "test.xls";		 
					header("Content-Type: application/vnd.ms-excel; charset=utf-8"); 
					header("Content-type: application/x-msexcel; charset=utf-8");
					header("Pragma: public"); 
					header("Expires: 0"); 
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
					header("Content-Type: application/force-download"); 
					header("Content-Type: application/octet-stream"); 
					header("Content-Type: application/download"); 
					//header("Content-Disposition: attachment;filename=11.xls "); 
					header("Content-Disposition: attachment; filename=\"$filename\"");
					header("Content-Transfer-Encoding: binary "); 
					
					ExportFile($incidentResult);
					exit();
				}

				function ExportFile($records) {
					echo ($records);
					$heading = false;
						if(!empty($records))
						foreach($records as $row) {
							if(!$heading) {
							// display field/column names as a first row
							echo implode("\t", array_keys($row)) . "\n";
							$heading = true;
							}
							echo implode("\t", array_values($row)) . "\n";
						}
						exit;
				}*/
			//}
		?>


		<div> 
			<h2 style="margin-left:10px">All Incident Report</h2>
			<hr> 
			<div style="overflow-x:auto;">
				<form class="post-form" action="incidentReportList.php" method="post">
					<table style="margin-left:10px">
						<td style="width:25%">
							<label>Site:</label>
						</td>
						<td>
							<select style="width:100%; margin-left:10px;" name=site_code id="site_code">
							<option value="">Please Select</option>
								<?php
									while ($r_l=$result_loc->fetch_object()){
										if ($r_l->code==$t[0]){
											print '<option value="'.$r_l->code.'" selected>'.$r_l->code.$r_l->location.'</option>';
										}else{
											print '<option value="'.$r_l->code.'">'.$r_l->code.$r_l->location.'</option>';
										}
									};
								?>
							</select>
						</td>
						</tr>
						<tr>
							<td>
								<label>Date from: </label>
							</td>
							<td>							
								<input style="margin-left:10px;" type="date" name="dateFrom"/>							 
								<label> to </label>							
								<input style="margin-left:10px;" type="date" name="dateTo"/>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>    
								<input style="margin-left:10px;" type="submit" name="search" value="Search"/>
								<!--input type="submit" name="export" value="Export"/-->
							</td>
						</tr>
					</table>
				</form>
			</div>

			<div>
				<!--input type="text" id="myInput" onkeyup="search()" placeholder="Search for id.." title="Type in a name" placeholder="Search.."-->
				<table id="mediaTb" class="table table-striped table-condensed table-bordered"> 
					<thead>  
						<tr>
							<th width="10%">Site</th>
							<!--th width="40%">Site Photo</th-->
							<th width="30%">Remark</th>
							<th width="30%">Detail</th>
							<th width="15%">Incident Date</th>
							<th width="15%">Creation Date</th>
						</tr>
					</thead> 
					<tbody>   
					<?php     
						while ($row = mysqli_fetch_array($rs_result)) {    
							// Display each field of the records.    
					?>     
						<tr> 
							<td>
								<?php echo $row["site_id"]?>
							</td>    
							<!--td>
								<a href="<?php echo $row['image']; ?>" class="btn-action" target="_blank">
									<img src="<?php echo $row["image"]?>" class="img-preview" alt="photo"> 
								</a>
								<?php //echo $row["name"]?>
							</td-->
							<td>
								<?php echo $row["remark"]?>
							</td>
							<td>
								<a href="updateIncidentReport.php?id=<?php echo $row['id']; ?>" class="btn-action">View Details</a> 
								<!--a onclick="confirmDelete(<?php //echo $row['id']; ?>)" class="btn-action">Delete</a-->
								<!--a href="<?php //echo $row['image']; ?>" class="btn-action" target="_blank">Download</a--> 
							</td>  
							<td>
								<?php echo $row["incident_date"]?>
							</td>    
							<td>
								<?php echo $row["create_date"]?>
							</td>                                    
						</tr>     
					<?php     
						};    
					?>     
					</tbody>
				</table>
				<!--a href="insert.php" class="btn-link">Add Image</a-->
				<hr>
				<div class="pagination" style="margin-left:10px">    
					<?php  
						$query = "SELECT COUNT(*) FROM incident_report";   
						if(!empty($_SESSION['site_code_inc'])){
							$query .= " where site_id = '".$_SESSION['site_code_inc']."' ";
						}
						if(!empty($_SESSION['dateFrom_inc']) and !empty($_SESSION['dateTo_inc'])){
							$query .= " and create_date between '".$_SESSION['dateFrom_inc']."' and '".$_SESSION['dateTo_inc']."' ";
						}
						  
						$rs_result = mysqli_query($conn, $query);     
						$row = mysqli_fetch_row($rs_result);     
						$total_records = $row[0];     
  
						// Number of pages required.   
						$total_pages = ceil($total_records / $per_page_record);
						//$_SESSION['ttl_incident_page'] = $total_pages;
						$pagLink = "";       
					
						if($page>=2){   
							echo "<a href='incidentReportList.php?page=".($page-1)."'>  Prev </a>";   
						}       
								
						for ($i=1; $i<=$total_pages; $i++) {   
						if ($i == $page) {   
							$pagLink .= "<a class = 'active' href='incidentReportList.php?page="  
																.$i."'>".$i." </a>";   
						}               
						else  {   
							$pagLink .= "<a href='incidentReportList.php?page=".$i."'>   
																".$i." </a>";     
						}   
						};     
						echo $pagLink;   
				
						if($page<$total_pages){   
							echo "<a href='incidentReportList.php?page=".($page+1)."'>  Next </a>";   
						}
					?>    
				</div> 
				<div class="inline" style="margin-right:10px">   
					<input id="page" type="number" min="1" max="<?php echo $total_pages?>"   
					placeholder="<?php echo $page."/".$total_pages; ?>" required>   
					<button onClick="go2Page();">Go</button>   
				</div>    
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="	crossorigin="anonymous"></script>
		<script type="text/javascript" src="assets/validate.js"></script>

		<script>
			/*function search(){
				var input, filter, table, tr, td, i, txtValue;
				input = document.getElementById("myInput");
				filter = input.value.toUpperCase();
				table = document.getElementById("mediaTb");
				tr = table.getElementsByTagName("tr");
				for (i = 0; i < tr.length; i++) {
					td = tr[i].getElementsByTagName("td")[1];
					if (td) {
						txtValue = td.textContent || td.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						} else {
						tr[i].style.display = "none";
						}
					}       
				}
			}*/

			function delClick(){
				alert("delete click");
			}

			function go2Page(){   
				var page = document.getElementById("page").value;   
				page = ((page><?php echo $total_pages; ?>)?<?php echo $total_pages; ?>:((page<1)?1:page));   
				window.location.href = 'incidentReportList.php?page='+page;   
			}  
		</script>  
	</body>
</html>