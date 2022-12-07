<?php
	namespace Phppot;
	use Phppot\DataSource;
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

		<script type="text/javascript">
			function hideTr() {
				console.log('testing123');
			}
		</script>
	</head>

	<body>
		<?php
			require_once "connection.php";  
			require_once "iconn.php";
			require_once "header2.php";
			require_once __DIR__ . '/lib/CompoundModel.php';
			$compoundModel = new CompoundModel();

			//echo "User role: ".$_SESSION['vuserid']."      ".$_SESSION['utp'];

			//mark that the user has been on this page 
			$_SESSION['previous'] = basename($_SERVER['PHP_SELF']);

			if (isset($_SESSION['prev_incid'])) {
				if (basename($_SERVER['PHP_SELF']) != $_SESSION['prev_incid']) {
					 //session_destroy();
					 unset($_SESSION['site_code_inc']);
					 unset($_SESSION['dateFrom_inc']);
					 unset($_SESSION['dateTo_inc']);
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

			$compoundGrpList = "select id from category order by id ASC;";
			$compoundGrpResult=$dbc->query($compoundGrpList);
			if (!$compoundGrpResult->num_rows){
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

			//if($_SERVER['REQUEST_METHOD'] == 'POST' or $_SESSION['ttl_page'] > 1){
			//if($_SERVER['REQUEST_METHOD'] == 'POST'){

			if(!isset($_SESSION["site_id"]) or $_SERVER['REQUEST_METHOD'] == 'POST'){
				$_SESSION['site_id'] = $_POST['site_id'];
				$_SESSION['compound_grp'] = $_POST['compound_grp'];
				$_SESSION['dateFrom'] = $_POST['dateFrom'];
				$_SESSION['dateTo'] = $_POST['dateTo'];
			}

			$query = "SELECT * FROM glab_sample "; 

			if(!empty($_SESSION['site_id'])){
				$query .= " where site_id = '".$_SESSION['site_id']."' ";
				if(!empty($_SESSION['compound_grp'])){
					$query .= " and compound_grp = '".$_SESSION['compound_grp']."' ";
				}
	
				if(!empty($_SESSION['dateFrom']) and !empty($_SESSION['dateTo'])){
					$query .= " and strt_date between '".$_SESSION['dateFrom']."' and '".$_SESSION['dateTo']."' ";
				}	
			}else{
				if(!empty($_SESSION['compound_grp'])){
					$query .= " where compound_grp = '".$_SESSION['compound_grp']."' ";

					if(!empty($_SESSION['dateFrom']) and !empty($_SESSION['dateTo'])){
						$query .= " and strt_date between '".$_SESSION['dateFrom']."' and '".$_SESSION['dateTo']."' ";
					}	
				}else{
					if(!empty($_SESSION['dateFrom']) and !empty($_SESSION['dateTo'])){
						$query .= " where strt_date between '".$_SESSION['dateFrom']."' and '".$_SESSION['dateTo']."' ";
					}	
				}
			}

			$query .= " order by sample_id LIMIT $start_from, $per_page_record";     
			$rs_result = mysqli_query ($conn, $query);  
			//}

			if(isset($_POST['reset'])){
				unset($_SESSION['site_id']);
				unset($_SESSION['compound_grp']);
				unset($_SESSION['dateFrom']);
				unset($_SESSION['dateTo']);

				echo "<meta http-equiv='refresh' content='0'>";
			}

			if(isset($_POST['delete'])){
				if(!empty($_POST['glab_delete_id'])){
					$all_id = $_POST['glab_delete_id'];
					$extract_id = implode(',' , $all_id);
		
					//Delete QC Criteria in DB
					$query = "DELETE FROM glab_sample WHERE id IN($extract_id) ";
					$deleteQeury = $dbc->query($query);
					if($deleteQeury){
						$msg = "Glab Sample deleted successfully.";
					}else{
						$msg = "Glab Sample not deleted.";
					}
				}else{
					$msg = "Please select at least one sample to delete.";
				}
			}
		?>

		<div>
			<h2 style="margin-left:10px">Search Glab Sample</h2>
			<span id="message" style="margin-left:10px; color:red;"><?php echo $msg ?></span>
			<hr>
			<form class="post-form" action="showGlabSample.php" method="post">
				<div>
				
					<table style="margin-left:10px">
						<tr>
							<td>
								<label>Site Code: </label>
							</td>
							<td>
								<select style="width:100%; margin-left:10px;" name="site_id" id="site_id">
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
								<label>Compound Group: </label>
							</td>
							<td>
								<select style="width:100%; margin-left:10px;" name="compound_grp" id="compound_grp">
								<option value="">Please Select</option>
									<?php
										while ($r_c=$compoundGrpResult->fetch_object()){
											if ($r_c->id==$t[0]){
												print '<option value="'.$r_c->id.'" selected>'.$r_c->id.'</option>';
											}else{
												print '<option value="'.$r_c->id.'">'.$r_c->id.'</option>';
											}
										};
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<label>Start Date from: </label>
							</td>
							<td>							
								<input style="margin-left:10px;" type="date" name="dateFrom" />							 
								<label> to </label>							
								<input style="margin-left:10px;" type="date" name="dateTo"/>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>    
								<input style="margin-left:10px;" type="submit" value="Search"/>
								<input style="margin-left:10px;" type="submit" name="reset" value="Reset"/>
							</td>
						</tr>
					</table>
				
				</div>

				<div style="overflow-x:auto;">
					<!--input type="text" id="myInput" onkeyup="search()" placeholder="Search for id.." title="Type in a name" placeholder="Search.."-->
					<table class="table table-striped table-condensed table-bordered"> 
						<thead>  
							<tr>
								<th width="1%"></th>
								<th width="10%">Sample ID</th>
								<th width="10%">Start Date</th>
								<!--th width="5%">Duration</th-->
								<th width="5%">Site</th>
								<!--th width="5%">Cpd Cat</th>
								<th width="5%">Sample Type</th>
								<th width="10%">Case No. 1</th-->
								<th width="10%">Compound</th>
								<th width="5%">Compound Group</th>
								<th width="5%">CONC (µg/m3)</th>
								<th width="5%">Co-Located Sample Status</th>
								<!--th width="5%">Sampling Method</th>
								<th width="5%">Sampler</th>
								<th width="5%">Detector</th>
								<th width="5%">Sample By</th>
								<th width="5%">Analyse By</th-->
							</tr>
						</thead> 
						<tbody>   
						<?php  
							while ($row = mysqli_fetch_array($rs_result)) {   
								$hide='';
								$avgFrmThreeYrs = 0;
								$percentile = 0;
								$status = 'Valid';
								$countPercDiff = 0;
								$countTotalColocSample = 0;

								if(substr($row["sample_id"],5,1) == 'S' and !empty($row["conc_g_m3"])){
									$last3YrsConcList = $compoundModel->getConcFrmLast3Yrs($row["site_id"], $row["compound"], $row["compound_grp"], $row["strt_date"]);
									//print_r($last3YrsConcList);
									$percentile = $compoundModel->calPercentile((array)$last3YrsConcList, 99);
									//echo "Percentile: ".$percentile;
									$avgFrmThreeYrs = $compoundModel->calAvgFrmLast3Yrs($row["site_id"], $row["compound"], $row["compound_grp"], $row["strt_date"]);
									if($_SESSION['utp'] == 1){
										if($row["conc_g_m3"] > number_format((float)$percentile, 2, '.', '')
											or $row["conc_g_m3"] > number_format((float)$avgFrmThreeYrs[0]["avg_conc_g_m3"], 2, '.', '') ){
												$hide = ' style="display: none;"';
										} 
									}
								}

								// Display each field of the records.    
						?>     
							<tr <?php echo $hide;?>> 
								<td>
									<input type="checkbox" name="glab_delete_id[]" value="<?php echo $row["id"] ?>">
								</td>
								<td>
									<a href="compoundDetail.php?id=<?php echo $row['id']; ?>" class="btn-action">
										<?php echo $row["sample_id"]?>
									</a> 								
								</td>    
								<td>
									<?php echo $row["strt_date"]?>
								</td>
								<!--td>
									<?php //echo $row["duration"]?>
								</td-->
								<td>
									<?php echo $row["site_id"]?>
								</td>
								<!--td>
									<?php //echo $row["cpdcat"]?>
								</td>
								<td>
									<?php //echo $row["samp_type"]?>
								</td>
								<td>
									<?php //echo $row["casno1"]?>
								</td-->
								<td>
									<?php echo $row["compound"]?>
								</td>
								<td>
									<?php echo $row["compound_grp"]?>
								</td>
								<?php								
									if(substr($row["sample_id"],5,1) == 'S' and !empty($row["conc_g_m3"])){
										//$last3YrsConcList = $compoundModel->getConcFrmLast3Yrs($row["site_id"], $row["compound"], $row["compound_grp"], $row["strt_date"]);
										//print_r($last3YrsConcList);
										//$percentile = $compoundModel->calPercentile((array)$last3YrsConcList, 99);
										//echo "Percentile: ".$percentile;
										//$avgFrmThreeYrs = $compoundModel->calAvgFrmLast3Yrs($row["site_id"], $row["compound"], $row["compound_grp"], $row["strt_date"]);
										
										if($_SESSION['utp'] == 0){
											if($row["conc_g_m3"] > number_format((float)$percentile, 2, '.', '')
												or $row["conc_g_m3"] > number_format((float)$avgFrmThreeYrs[0]["avg_conc_g_m3"], 2, '.', '') ){
								?>
												<td bgcolor= "#f5ad9b">
													<?php echo $row["conc_g_m3"]?>
												</td>
								<?php 
											}else{
								?>		
											<td>
												<?php echo $row["conc_g_m3"]?>
											</td>	
								<?php 	
											}	
										}else{
											if($row["conc_g_m3"] > number_format((float)$percentile, 2, '.', '')
												or $row["conc_g_m3"] > number_format((float)$avgFrmThreeYrs[0]["avg_conc_g_m3"], 2, '.', '') ){
								?>
											<td>
												<?php echo " "?>
											</td>
								<?php
										}else{
								?>				
											<td>
												<?php echo $row["conc_g_m3"]?>
											</td>
								<?php
											}
										}
									}else{
								?>	
										<td>
											<?php echo $row["conc_g_m3"]?>
										</td>
								<?php
									}
								?>								

								<!--td bgcolor= "#f5ad9b"-->
									<?php 
									if(strlen($row["sample_id"]) > 12){
										$countPercDiff = $compoundModel->getCountOfPercentageDiff($row["sample_id"], $row["sample_id"]);
										$countTotalColocSample = $compoundModel->getCountOfTotalColocatedSample($row["sample_id"], $row["sample_id"]);
										$deviation = $compoundModel->getDeviationByCompoundGrp($row["compound_grp"]);
										//if($countPercDiff[0]["count_diff"] > round($countTotalColocSample[0]["total_sample"]/5)){
										if($countPercDiff[0]["count_diff"] > $deviation[0]["ptg_pollutant"]){
									?>		
											<td bgcolor= "#f5ad9b"><?php echo $status = 'Invalid'; ?> </td>
									<?php
										}else{
									?>
											<td bgcolor= "#84e084"><?php echo $status = 'Valid'; ?> </td>
									<?php
										}
									}else{
									?>
										<td><?php echo $status = 'N.A.'; ?> </td>
									<?php
									}
									?>
								<!--/td-->
								<!--td>
									<?php //echo $row["samp_mthd"]?>
								</td>
								<td>
									<?php //echo $row["sampler"]?>
								</td>
								<td>
									<?php //echo $row["detector"]?>
								</td>
								<td>
									<?php //echo $row["sample_by"]?>
								</td>
								<td>
									<?php //echo $row["analyse_by"]?>
								</td-->                                       
							</tr>     
						<?php     
							};    
						?>     
						</tbody>
					</table>
					<input type="submit" style="margin-left:10px" name="delete" value="Delete">
					<hr>
					<div class="pagination" style="margin-left:10px">    
						<?php  
							$query = "SELECT COUNT(*) FROM glab_sample ";					
							if(!empty($_SESSION['site_id'])){
								$query .= " where site_id = '".$_SESSION['site_id']."' ";
								if(!empty($_SESSION['compound_grp'])){
									$query .= " and compound_grp = '".$_SESSION['compound_grp']."' ";
								}
					
								if(!empty($_SESSION['dateFrom']) and !empty($_SESSION['dateTo'])){
									$query .= " and strt_date between '".$_SESSION['dateFrom']."' and '".$_SESSION['dateTo']."' ";
								}	
							}else{
								if(!empty($_SESSION['compound_grp'])){
									$query .= " where compound_grp = '".$_SESSION['compound_grp']."' ";
				
									if(!empty($_SESSION['dateFrom']) and !empty($_SESSION['dateTo'])){
										$query .= " and strt_date between '".$_SESSION['dateFrom']."' and '".$_SESSION['dateTo']."' ";
									}	
								}else{
									if(!empty($_SESSION['dateFrom']) and !empty($_SESSION['dateTo'])){
										$query .= " where strt_date between '".$_SESSION['dateFrom']."' and '".$_SESSION['dateTo']."' ";
									}	
								}
							}

							$rs_result = mysqli_query($conn, $query);     
							$row = mysqli_fetch_row($rs_result);     
							$total_records = $row[0];     
							
							// Number of pages required.   
							$total_pages = ceil($total_records / $per_page_record);     
							//$_SESSION['ttl_page'] = $total_pages;
							$pagLink = "";       
						
							if($page>=2){   
								echo "<a href='showGlabSample.php?page=".($page-1)."'>  Prev </a>";   
							}       
									
							for ($i=1; $i<=$total_pages; $i++) {   
								if ($i == $page) {   
									$pagLink .= "<a class = 'active' href='showGlabSample.php?page=".$i."'>".$i." </a>";   
								}               
								else  {   
									$pagLink .= "<a href='showGlabSample.php?page=".$i."'>".$i." </a>";     
								}   
							};     
							echo $pagLink;   
					
							if($page<$total_pages){   
								echo "<a href='showGlabSample.php?page=".($page+1)."'>  Next </a>";   
							}
						?>    
					</div> 
					<div class="inline" style="margin-right:10px">   
						<input id="page" type="number" min="1" max="<?php echo $total_pages?>"   
						placeholder="<?php echo $page."/".$total_pages; ?>">   
						<button onClick="go2Page();">Go</button>   
					</div>    
				</div>
			</form>
		</div>

		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="assets/validate.js"></script>
		<script>
			function go2Page(){   
				var page = document.getElementById("page").value;   
				page = ((page><?php echo $total_pages; ?>)?<?php echo $total_pages; ?>:((page<1)?1:page));   
				window.location.href = 'showGlabSample.php?page='+page;   
			}  
		</script>  
	</body>
</html>