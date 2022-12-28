<?php
namespace Phppot;
use Phppot\DataSource;
require_once "header2.php";
require_once __DIR__ . '/lib/ImageModel.php';
$imageModel = new ImageModel();
$incidentResult = $imageModel->getIncidentReport();
//print_r($incidentResult);
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

			#response {
				padding: 10px;
				margin-bottom: 10px;
				border-radius: 5px;
				display: none;
			}

			.success {
				background: #c7efd9;
				border: #bbe2cd 1px solid;
			}

			.error {
				background: #fbcfcf;
				border: #f3c6c7 1px solid;
			}

			div#response.display-block {
				display: block;
			}
		</style>	
	</head>
	<body>
		<div><h2>Export Incident Report</h2><hr></div>
		<div id="container" >
			<form action="exportToExc.php" method="post" id="export-form">
				<div id="response"
					class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
					<?php if(!empty($message)) { echo $message; } ?>
				</div> 
				<input type="submit" name="export" value="Export"/>
			</form>
		</div>  
	</body>   
</html>