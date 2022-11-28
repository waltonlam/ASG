<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	
<?php 
	include ('iconn.php');
	include 'header2.php';
	require_once 'sqlHelper.php';

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
	
    $con = new MyDB();

	$glabArray = $con -> selectFrom("glab_template", $columns =null, $where = null, $like = false, $orderby = "sample_id", $direction = "ASC", $limit = null, $offset = null);
	$siteArray = $con -> selectFrom("site", $columns =null, $where = null, $like = false, $orderby = "code", $direction = "ASC", $limit = null, $offset = null);
	$compoundArray = $con -> selectFrom("compound", $columns =null, $where = null, $like = false, $orderby = "id", $direction = "ASC", $limit = null, $offset = null);
	$categoryArray = $con -> selectFrom("category", $columns =null, $where = null, $like = false, $orderby = "id", $direction = "ASC", $limit = null, $offset = null);
?>

<style type="text/css">
	fieldset {
		border:0;
		padding:10px;
		margin-bottom:
		10px;background:#EEE;
		border-radius: 8px;
		background:-webkit-liner-gradient(top,#EEEEEE,#FFFFFF);
		background:linear-gradient(top,#EFEFEF,#FFFFFF);
		box-shadow:3px 3px 10px #666;
	}

	legend {
		padding:5px 10px;
		background-color:black;
		color:#FFF;
		border-radius:3px;
		box-shadow:2px 2px 4px #666;
		left:10px;top:-11px;
		}

	.box {
		display: flex;
		flex-wrap: flex-flow: rowwrap;
		padding-left: 20px;
		padding-right: 20px;
		align-content: center;
	}

	input[type=button], input[type=submit], input[type=reset] {
		background-color: #87ceeb;
		color: white;
		padding: 12px 20px;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		width:100
	}
</style>

<html>
	<h2 style="margin-left:10px">Export GLab Report</h2><hr>
    <form class="form-horizontal" action="function.php" name="upload_excel"  onsubmit="return validateForm()" enctype="multipart/form-data" method="post" >
        <fieldset>
        	<legend>Date</legend>
			<div class="box">
				<label for="sDate" style="margin-right:15px">From :</label>
				<input type="date" id="start" name="start" style="margin-right:15px" >
				<label for="eDate" style="margin-right:15px">to</label>
				<input type="date" id="end" name="end" style="margin-right:15px" >
			</div>
		</fieldset>
		<br>

        <fieldset>
        	<legend>Site</legend>
			<div class="box">
				<table style="margin-left:20px; margin-right: 20px;">
					<?php 
						foreach ($siteArray as $value) {
						// echo $value;
							foreach ($value as $vals) {
								echo '
									<div>
									<label style="margin: 10px;"><input type="checkbox"  name="site_id[]"   id="'
									.$vals['code'].'"  value="'.$vals['code'].'">'.$vals['location'].'</label>
									</div>
									';
							}
						};
					?>
				</table>
			</div>
		</fieldset>
		<br>

		<fieldset>
			<legend>Compound & Category</legend>
			<div class="box">
				<div style="margin-top:10px" width= "100%">
					<table  width= "100%">
						<tr>
							<td>
								<label id="selectedCate">Selected Category : </label>
							</td>
							<td></td>
							<td>
								<div class="col-md-4 col-md-offset-4"></div>
							</td>
						</tr>
						<tr>
							<td style="margin-left:50px;">
								<select  name="uid" id="categorySelector"  onchange="updateCompound(value)">
									<option value="all" selected="True">All</option>
									<?php 
										foreach ($categoryArray as $value) {
										// echo $value;
											foreach ($value as $vals) {
												echo '<option value="'.$vals['id'].'">('.$vals['id'].')  '
												.$vals['item'].'</option>';

											}
										}
									?>	
								</select>
							</td>
							<td>
								<label><input type="checkbox" style="margin-left:30px;"   id="selectAllCb" onclick="selectAllClickEvent()">Select All</label>
							</td>
							<td>									
								<label><input type="checkbox" style="margin-left:30px;"   id="unselectAllCb" onclick="unselectAllClickEvent()">Unselect All</label>
							</td>
						</tr>
					</table>
				</div>
			</div>

			<hr style="margin-left:20px; margin-right: 20px;">

			<div id="compoundGp" style="margin-top:10px">
				<table style="margin-left:20px; margin-right: 20px;">
					<?php 
					// echo '<p style="margin-left:20px; margin-right: 20px;"">Total number : "' . count($compoundArray['result']).'"</p><br>';
						$y=0;
						for ($i=0; $i < count($compoundArray['result']); $i++) { 
							$y++; 
							echo 
								//'<td>
								//<label><input type="checkbox" onclick="compoundClick()" style="margin: 10px;" name="compound_id[]"  id="'
								//.$compoundArray['result'][$i]['name'].'"  value="'.$compoundArray['result'][$i]['name'].'" >'
								//.$compoundArray['result'][$i]['name'].'</label>';
								'<td><label><input type="checkbox" onclick="compoundClick()" style="margin: 10px;" name="compound_id[]"  id="'
								.$compoundArray['result'][$i]['id'].'"  value="'.$compoundArray['result'][$i]['id'].'" >'
								.$compoundArray['result'][$i]['name'].'</label>';
							if (($y % 4) === 0) {
								echo '<tr>';
							}
						}
					?>
				</table>
			</div>
		</fieldset>

		<input type="submit" style="margin-left:10px" id="review" name="review" class="btn btn-success" value="CURRENT"  align="right" /> 
		<input type="submit" style="margin-left:10px" id="raw" name="review" class="btn btn-success" value="RAW"  align="right" />
		<input type="submit" style="margin-left:10px" id="hist" name="review" class="btn btn-success" value="HISTORY"  align="right" />
		<!-- <input type="submit" id="submit" name="export" action="plot" class="btn btn-success" value="Export"  align="right"  /> -->
    </form>
 	<?php echo "<script> window.onload = function() {cateItemSelect();}; </script>";?>
</html>

<script type="text/javascript">
	var currentCategoryTotal = 0

	function cateItemSelect(){
		// updateCompound(document.getElementById("categorySelector").value);
		document.forms['upload_excel'].reset();
	}

	function sampleIdToggleSelect(){
		var isChecked = document.getElementById("sampleIdCb").checked;
		document.getElementById("sampleIdSelector").disabled = !isChecked;
	}

	function selectAllClickEvent(){
		document.getElementById("unselectAllCb").checked = false;
		console.log("select All click");
		var items = document.getElementsByName("compound_id[]");
		for (var i = 0; i < items.length; i++) {
			if (items[i].type == "checkbox")
				items[i].checked = document.getElementById("selectAllCb").checked;
		}
	}

	function unselectAllClickEvent(){
		document.getElementById("selectAllCb").checked = false;
		console.log("unselect All click");

		var items = document.getElementsByName("compound_id[]");
		for (var i = 0; i < items.length; i++) {
			if (items[i].type == "checkbox")
				items[i].checked = false;
		}
	}

	function compoundClick(){
		// var items = document.getElementsByName("compound_id[]");
		// console.log("items count = "+items.length);
		// var i = 0;
		// for (var i = 0; i < items.length; i++) {
		// 	if (items[i].type == "checkbox" && items[i].checked == true)
		// 		console.log("The checked value is "+i)
		// 		i++;
		// }
		//console.log("---------------------");
		var x = 0;
		var checkboxes = document.getElementsByName('compound_id[]');
	    for (var checkbox of checkboxes)
	    {
	        if (checkbox.checked) {
	        	x++;
	           //console.log(checkbox.value + ' ');
	        }
	    }
		//console.log("x = " +x);
		// console.log("The checked value is "+i)
		// i=0;
		if (x != checkboxes.length) {
			document.getElementById("selectAllCb").checked = false;
		}else{
			document.getElementById("selectAllCb").checked = true;
		}
		
		if (x>0) {
			document.getElementById("unselectAllCb").checked = false;
		}
		//console.log("---------------------");
	}	

	function myFunction(){
	  var x = document.getElementById("main-content");
	  if (x.style.display === "none") {
	    x.style.display = "block";
	  } else {
	    x.style.display = "none";
	  }
	}

	function hideFilter(){
		document.getElementById("main-content").style.display = "none";
	}
	// const submit = document.getElementById("submit");
	// submit.addEventListener("click", validateForm);

	function validateForm(){
		var valid = false;
		var startDate = new Date(document.getElementById("start").value);
		var endDate = new Date(document.getElementById("end").value);
		// alert(startDate);
		// alert(endDate);
		// alert (valid);
		var value_list = ""
		var items = document.getElementsByName("compound_id[]");
		for (var i = 0; i < items.length; i++) {
			if (items[i].type == "checkbox" && items[i].checked == true) {
				value_list += items[i].value +" , ";
			}
		}

		// alert(value_list);
		var site_list = ""
		var sites = document.getElementsByName("site_id[]");
		for (var i = 0; i < sites.length; i++) {
			if (sites[i].type == "checkbox" && sites[i].checked == true) {
				site_list += sites[i].value +" , ";
			}
		}

		if (startDate > endDate) {
			valid = false;
		}else{
			valid = true;
		}
		return valid;  
	}

	function updateCompound(str){
		// alert(str);
		var jArray = <?php echo json_encode($compoundArray); ?>;			
		//console.log(jArray['result']);
		var divBox = ""

	 	if (str == "all") {
	 		var tbl = "<table style='margin-left:20px; margin-right: 20px;'>";
	 		var h = 0;
			for (var i = 0; i <jArray['result'].length; i++) {
				h++
				tbl += "<td><label><input type='checkbox' onclick='compoundClick()' style='margin: 10px;' name='compound_id[]'  id='"
						+jArray['result'][i]['id']+"'   value='"+jArray['result'][i]['id']+"'>"+jArray['result'][i]['name']+"</label>";

				// tbl += "<label style='margin-right: 30px;'>"+jArray['result'][i]['name']+"</label></td>";
				if (h%4 == 0) {
					tbl+="<tr>";
				}
			}
		}else{
			var tbl = "<table  style='margin-left:20px; margin-right: 20px;'>"
			var h = 0;
			for (var i = 0; i <jArray['result'].length; i++) {
				if (jArray['result'][i]['code'] == str) {
					h++;
					console.log(jArray['result'][i]);
					tbl += "<td><label><input type='checkbox' onclick='compoundClick()' style='margin: 10px;' name='compound_id[]'  id='"
							+jArray['result'][i]['id']+"' value='"+jArray['result'][i]['id']+"'>"+jArray['result'][i]['name']+"</label>";
					// tbl += "<label style='margin-right: 30px;'>"+jArray['result'][i]['name']+"</label></td>";
					var td = "";
					if (h%4 == 0) {
						tbl+="<tr>";	
					}
				}				
			}
			// tbl+="</table>";
			// console.log(tbl);

			currentCategoryTotal = jArray.length;
			//console.log("total size  = "+currentCategoryTotal);
		}
		tbl+="</table>";
		document.getElementById("compoundGp").innerHTML = tbl;
		document.getElementById("selectAllCb").checked = false;
		document.getElementById("unselectAllCb").checked = false;
	}

	$(window).bind("pageshow", function(event){
		if (event.originalEvent.persisted) {
			window.location.reload(); 
		}
	});
</script>

<!-- <div>
	<input type="checkbox"   id="'.$vals['code'].'" onclick="sampleIdToggleSelect()" value=" '.$vals['code'].'">

	<label style="margin-right: 30px;" for="ex">'.$vals['location'].'</label>
	</div> -->