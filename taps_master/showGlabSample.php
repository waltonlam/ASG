<html>
	<head>
		<link href="assets/style.css" rel="stylesheet" type="text/css" />
		<style> 
			input[type=button], input[type=submit], input[type=reset] {
				background-color: #4D9BF3;
				border: none;
				color: white;
				padding: 16px 32px;
				text-decoration: none;
				margin: 4px 2px;
				cursor: pointer;
			}
			
			/*table {  
				border-collapse: collapse;  
			} */ 
			.inline{   
				display: inline-block;   
				float: right;   
				margin: 20px 0px;   
			}   
			
			/*input, button{   
				height: 34px;   
			}*/
		
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
			//namespace Phppot;

			//use Phppot\DataSource;
			require_once "connection.php";  
			require_once "iconn.php";
			require_once "header2.php";
			//require_once __DIR__ . '/lib/ImageModel.php';
			//$imageModel = new ImageModel();
			//$tmp_page_cnt = 0;

			//mark that the user has been on this page 
			$_SESSION['previous'] = basename($_SERVER['PHP_SELF']);

			if (isset($_SESSION['prev_incid'])) {
				if (basename($_SERVER['PHP_SELF']) != $_SESSION['prev_incid']) {
					 //session_destroy();
					 unset($_SESSION['ttl_incident_page']);
					 ### or alternatively, you can use this for specific variables:
					 ### unset($_SESSION['varname']);
				}
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

			/*if (!isset($_SESSION["site_id"])) {    
				$_SESSION["site_id"] = $_POST['site_id'];        
			} */ 

			$start_from = ($page-1) * $per_page_record;   

			//$result = $imageModel->getAllImages($start_from, $per_page_record);
			//echo '$total_pages:  '.$_SESSION['ttl_page'];

			if($_SERVER['REQUEST_METHOD'] == 'POST' or $_SESSION['ttl_page'] > 1){
				$query = "SELECT * FROM glab_sample "; 
				if(!empty($_POST['site_id'])){
					$query .= " where site_id = '".$_POST['site_id']."' ";
				}
				if(!empty($_POST['dateFrom']) and !empty($_POST['dateTo'])){
					$query .= " and strt_date between '".$_POST['dateFrom']."' and '".$_POST['dateTo']."' ";
				}
				
				$query .= " LIMIT $start_from, $per_page_record";     
				$rs_result = mysqli_query ($conn, $query);  
			}
		?>

		<div class="container">
			<br> 
			<h2>Search Glab Sample</h2>
			<br> 
			<div>
				<form class="post-form" action="showGlabSample.php" method="post">
					<table>
						<td style="width:25%">
							<label>Site Code:</label>
						</td>
						<td>
							<select name=site_id id="site_id">
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
							<td style="width:25%">
								<label>Date from: </label>
							</td>
							<td>							
								<input type="date" name="dateFrom"/>							 
								<label> to </label>							
								<input type="date" name="dateTo"/>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>    
								<input type="submit" value="Search"/>
							</td>
						</tr>
					</table>
				</form>
			</div>

			<div>
				<!--input type="text" id="myInput" onkeyup="search()" placeholder="Search for id.." title="Type in a name" placeholder="Search.."-->
				<table class="table table-striped table-condensed table-bordered"> 
					<thead>  
						<tr>
							<th width="10%">Sample ID</th>
							<th width="10%">Start Date</th>
							<th width="5%">Duration</th>
							<th width="5%">Site</th>
							<th width="5%">Cpd Cat</th>
							<th width="5%">Sample Type</th>
							<th width="10%">Case No. 1</th>
							<th width="10%">Compound</th>
							<th width="5%">CONC (µg/m3)</th>
							<th width="5%">Sampling Method</th>
							<th width="5%">Sampler</th>
							<th width="5%">Detector</th>
							<th width="10%">Sample By</th>
							<th width="10%">Analyse By</th>
						</tr>
					</thead> 
					<tbody>   
					<?php     
						while ($row = mysqli_fetch_array($rs_result)) {    
							// Display each field of the records.    
					?>     
						<tr> 
							<td>
								<a href="updateSitePhoto.php?id=<?php echo $row['id']; ?>" class="btn-action">
									<?php echo $row["sample_id"]?>
								</a> 								
							</td>    
							<td>
								<?php echo $row["strt_date"]?>
							</td>
							<td>
								<?php echo $row["duration"]?>
							</td>
							<td>
								<?php echo $row["site_id"]?>
							</td>
							<td>
								<?php echo $row["cpdcat"]?>
							</td>
							<td>
								<?php echo $row["samp_type"]?>
							</td>
							<td>
								<?php echo $row["casno1"]?>
							</td>
							<td>
								<?php echo $row["compound"]?>
							</td>

							<?php
								if($row["conc_g_m3"] > 5){
							?>
								<td bgcolor= "yellow">
							<?php }else{ ?>
								<td>								
							<?php }?>
							
								<?php echo $row["conc_g_m3"]?>
							</td>

							<td>
								<?php echo $row["samp_mthd"]?>
							</td>
							<td>
								<?php echo $row["sampler"]?>
							</td>
							<td>
								<?php echo $row["detector"]?>
							</td>
							<td>
								<?php echo $row["sample_by"]?>
							</td>
							<td>
								<?php echo $row["analyse_by"]?>
							</td>                                       
						</tr>     
					<?php     
						};    
					?>     
					</tbody>
				</table>

				<div class="pagination">    
					<?php  
						$query = "SELECT COUNT(*) FROM glab_sample";     
						$rs_result = mysqli_query($conn, $query);     
						$row = mysqli_fetch_row($rs_result);     
						$total_records = $row[0];     
						
						echo "</br>";     
						// Number of pages required.   
						$total_pages = ceil($total_records / $per_page_record);     
						$_SESSION['ttl_page'] = $total_pages;
						$pagLink = "";       
					
						if($page>=2){   
							echo "<a href='showGlabSample.php?page=".($page-1)."'>  Prev </a>";   
						}       
								
						for ($i=1; $i<=$total_pages; $i++) {   
						if ($i == $page) {   
							$pagLink .= "<a class = 'active' href='showGlabSample.php?page="  
																.$i."'>".$i." </a>";   
						}               
						else  {   
							$pagLink .= "<a href='showGlabSample.php?page=".$i."'>   
																".$i." </a>";     
						}   
						};     
						echo $pagLink;   
				
						if($page<$total_pages){   
							echo "<a href='showGlabSample.php?page=".($page+1)."'>  Next </a>";   
						}
					?>    
				</div> 
				<div class="inline">   
					<input id="page" type="number" min="1" max="<?php echo $total_pages?>"   
					placeholder="<?php echo $page."/".$total_pages; ?>" required>   
					<button onClick="go2Page();">Go</button>   
				</div>    
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="	crossorigin="anonymous"></script>
		<script type="text/javascript" src="assets/validate.js"></script>
		<script>
			function delClick(){
				alert("delete click");
			}
			function go2Page(){   
				var page = document.getElementById("page").value;   
				page = ((page><?php echo $total_pages; ?>)?<?php echo $total_pages; ?>:((page<1)?1:page));   
				window.location.href = 'showGlabSample.php?page='+page;   
			}  
		</script>  
	</body>
</html>