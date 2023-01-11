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
			
			.inline{   
				display: inline-block;   
				float: right;   
				margin: 20px 0px;   
			}   
		</style>   
	</head>

	<body>
		<?php
			require_once "connection.php";  
			require_once "iconn.php";
			require_once "header2.php";

			$_SESSION['prev_incid'] = basename($_SERVER['PHP_SELF']);

			if (isset($_SESSION['previous'])) {
				if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
					 unset($_SESSION['site_id']);
					 unset($_SESSION['dateFrom']);
					 unset($_SESSION['dateTo']);
					 ### or alternatively, you can use this for specific variables:
					 ### unset($_SESSION['varname']);
				}
			}
		?>

		<div>
			<h2 style="margin-left:10px">Download Data Conversion Template</h2>
			<hr> 
			<div>
				<table class="table table-striped table-condensed table-bordered"> 
					<thead>  
						<tr>
							<th width="50%">Data Conversion Template</th>
							<th width="50%">Action</th>
						</tr>
					</thead> 
					<tbody>   				    
						<tr> 
							<td>
								Carbonyl
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_CL.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>
						<tr> 
							<td>
								Chlordecone
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_CD.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>		
						<tr> 
							<td>
								Dl-PCB
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_DLPB.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>	
						<tr> 
							<td>
								Dioxin
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_DF.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>	
						<tr> 
							<td>
								HBCD
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_HB.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>
						<tr> 
							<td>
								mPCB
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_mPCB.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>
						<tr> 
							<td>
								OC Pesticides
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_OC.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>
						<tr> 
							<td>
								PAH
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_PH.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>
						<tr> 
							<td>
								PBDE
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_PBDE.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>	
						<tr> 
							<td>
								PFOS
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_PFOS.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>	
						<tr> 
							<td>
								Toxaphene
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_TP.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>
						<tr> 
							<td>
								VOCs
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell_VC.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>	
										
					</tbody>
				</table>
				<hr>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="	crossorigin="anonymous"></script>
		<script type="text/javascript" src="assets/validate.js"></script>
	</body>
</html>