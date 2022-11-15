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

		<div class="container">
			<h2>Download Glab Data Template</h2>
			<hr> 
			<div>
				<br>
				<table class="table table-striped table-condensed table-bordered"> 
					<thead>  
						<tr>
							<th width="50%">Data Template</th>
							<th width="50%">Download Link</th>
						</tr>
					</thead> 
					<tbody>   				    
						<tr> 
							<td>
								CSV Format Conversion
							</td>    
							<td>
								<a href="../taps/macro/RestructureCell.xlsm" class="btn-action" target="_blank">Download</a>
							</td>							                                      
						</tr>			
					</tbody>
				</table>   
				<br><hr>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="	crossorigin="anonymous"></script>
		<script type="text/javascript" src="assets/validate.js"></script>
	</body>
</html>