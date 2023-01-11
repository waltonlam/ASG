<?php
namespace Phppot;
use Phppot\DataSource;
require_once "iconn.php";
require_once "header2.php";

if(!empty($_GET['compoundGrp_id'])) {
	$compoundGrpId = $_GET["compoundGrp_id"];    
	$query ="SELECT * FROM compound WHERE code IN (".$compoundGrpId.")";
	$results = $dbc->query($query);
?>
	<option value="">Select Compound</option>
<?php
	foreach($results as $compound) {
?>
	<option value="<?php echo $compound["name"]; ?>"><?php echo $compound["name"]; ?></option>
<?php
	}
}else{?>
	<option value="">Select Compound</option>
<?php 
}
?>