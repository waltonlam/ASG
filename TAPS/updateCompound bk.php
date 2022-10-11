
<?php  
	


	if (!empty($_POST['compound_id'])) {
		$compoundID = $_POST['compound_id'];
		// print "**selecteddd value = ".$compoundID;		
	}



	include ('iconn.php');
	include 'header2.php';

	

	print '
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script >
			
				function editClick(){
					
					
					location.replace("updatelocationform.php")
				}
			
				function delClick(){
					alert("delete click");
				}
			
			
				function showSuccessAlert(){
					alert("Success");
				}
				
				
				function toggleSelect()
					{
					  var isChecked = document.getElementById("noFactor").checked;
					  document.getElementById("who_tef").disabled = isChecked;
				}
				
				
				
			
			</script>';



		 getMediaData();
		
		
	
		 
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			
				$success = false;
				if (!empty($_POST['id']) and !empty($_POST['name']) and !empty($_POST['cid']) )	{
							
					if (isset($_POST['factorCb'])){
						
						
						$u= "update compound set "
						."id='".$_POST['id'].
						"',who_tef='',name='".$_POST['name']."'"
						.",code ='".$_POST['cid']."' "
						." where id='".$_POST['id']."';";
						
					}else{
						
						
						
						$u= "update compound set "
						."id='".$_POST['id'].
						"',who_tef='".$_POST['who_tef'].
						"',name='".$_POST['name']."'"
						.",code ='".$_POST['cid']."' "
						." where id='".$_POST['id']."';";
					}
					
					// print($u);
					// print '<br>';
					// print '<br>';
					
					if ($dbc->query($u) === TRUE) {
					   // $updated=TRUE;
						
						$success = true;
						//header("Refresh:0");
					}else{
						$success = false;
						echo "Error: " . $dbc->error;
						exit();
					};
					
					
					$cu= "update compound_map set "
					."category_id='".$_POST['cid'].
					"',compound_id='".$_POST['id']."'"
					." where compound_id='".$_POST['id']."';";
					
					
					if ($dbc->query($cu) === TRUE) {
					   // $updated=TRUE;
					   $success = true;
					
						
						header("Refresh:0");
					}else{
						$success = false;
						echo "Error: " . $dbc->error;
						exit();
					};
					
					
					if ($success === TRUE){
						 echo "<script> window.onload = function() {showSuccessAlert();}; </script>";
					}else{
						echo '<p align="center" style="color:blue;font-weight:bold;font-size:28px">New Compound added Not successfully</p>';
					}
					

				}else{
					print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
				}
			exit();
				
		} 
		 

		 
		

		function getMediaData(){
			global $globalCompoundArray ;
			print '<h2>Update Compound</h2>';
			
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}


			
			$l = "select * from compound order by id ASC;";
			//print $q;


			
			
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){

				print '<p class="text--error">'.'Compound Configuration Error!</p>';

				

				exit();
			}else{

				// 
				// while ($row = $result_loc->fetch_object()) {
			        // $tempArray = $row;
			        // array_push($compoundArray, $tempArray);
			 //    }
			 //    // 
			 //    print_r($compoundArray);
			}		
			
			

		
			$lc = "select * from category order by id ASC;";
			//print $q;
			$result_cat=$dbc->query($lc);
			if (!$result_cat->num_rows){
				print '<p class="text--error">'.'Category Configuration Error!</p>';
				exit();
			}		
			
			
			$l2 = "select * from factor order by compound ASC;";
			$result = $dbc->query($l2);
			if (!$result->num_rows){
				print '<p class="text--error">'.'Factor Configuration Error!</p>';
				
			}
			
			print '<div id="main-content">';

			print '
			
				<table style="width:30%">
					<form action="updateCompound.php" method="post">
						<tr><br>';

			print'<td><label for="lid">Compound Code</label></td>';


			 $globalCompoundArray = array();

			print'			<td>
								<select name="id" id="compoundSelector" onchange="updateUi(value)">';

									
									

								   while ($r_l=$result_loc->fetch_object()){
								   	$compoundObj = new stdClass();
								   	$compoundObj -> id = $r_l->id;
								   	$compoundObj -> name = $r_l->name;
								   	$compoundObj -> who_tef = $r_l->who_tef;
								   	$compoundObj -> code = $r_l->code;


 									print '<option value="'.$r_l->id.'" id = "'.$r_l->id.'">'.'('.$r_l->id.') '.$r_l->name.'</option>';

 									array_push($globalCompoundArray, $compoundObj);

								};	
			
								
			print'				</select>


							</td>';

		
			
			print'			</tr>';

						
			print'		<tr>';

			print'<td><label for="lid">Factor</label></td>';
							
			print'			<td>
								<select  name="who_tef" id="who_tef">';

									print '<option value="noSelect_factor" id = "noSelect" disabled >Please select factor</option>';

								   while ($r_l=$result->fetch_object()){
									   
										 print '<option value="'.$r_l->compound.'" id = "'.$r_l->compound.'">'.'('.$r_l->compound.') '.$r_l->who_tef.'</option>';
									
								};	
								
			print'</select><input type="checkbox" id="noFactor" name="factorCb" value="Yes"onClick="toggleSelect()">No Factor</input></td></tr>';
					
			print'<tr><td><label for="lid">Category Code</label></td><td>
								<select name="cid" id="categorySelector">';
									print '<option value="noSelect_cate" id = "noSelect_cate" disabled>Please select</option>';
								    while ($r_l=$result_cat->fetch_object()){

										 print '<option value="'.$r_l->id.'" id = "'.$r_l->id.'">'.'('.$r_l->id.') '.$r_l->item.'</option>';
											 
									};	
								
			print'</select></td></tr><tr><td><label for="code">Compound name</label></td><td>
					<input  type="text" name="name" id="name"></input></td></tr>';		
			
			
			print'		<tr>';
			
			print'			<td  ></td><td>';
			
			print'      		<input class="button--general"  type="submit" value="Update">';
			
			print'			</td>';
			
			print'		</tr>';		
			
			print'	</form>
			
				</table>
						';

				
			print' <br></div>';

			echo "<script> window.onload = function() {selectFirstItem();}; </script>";
				


			// $compArr = $compoundArray;

			
		}

	
		// print '<pre>';
			
		// 	print_r($globalCompoundArray);
		// 	print '</pre>';

		include 'footer.html';

?>
		
<script type="text/javascript">
	
	function selectFirstItem(){

		// document.getElementById("compoundSelector").selectedIndex = 0;
		updateUi(document.getElementById("compoundSelector").value);
	
	}




	function updateUi(str){

		var jArray = <?php echo json_encode($globalCompoundArray); ?>;
		var json_string = JSON.stringify(jArray);

// alert(json_string);

		    

		  $.ajax({
		        url:"updateCompoundProcess.php",
		        method:"POST", //First change type to method here
		        
		        data:{
		          "compound_id": str, // Second add quotes on the value.
		          "allCompound":json_string
		        },
		        success:function(response) {
		        	// alert(response);
					var obj = JSON.parse(response);
					console.log(obj["name"] + "  |  "+obj["id"]+ "  |  "+obj["who_tef"]+ "  |  "+obj["code"] );

					if (obj["id"] == str) {
						document.getElementById("name").value = obj["name"];

						if ( obj["who_tef"] == null ||  obj["who_tef"].length === 0 || obj["who_tef"] == "NULL") {
							document.getElementById("noFactor").checked = true;
							document.getElementById("who_tef").disabled = true;
							document.getElementById("who_tef").selectedIndex = 0

						}else{
							document.getElementById("noFactor").checked = false;
							document.getElementById("who_tef").disabled = false;
							document.getElementById(obj["who_tef"]).selected = 'selected';

						
						}
						if  (obj["code"] == null ||  obj["code"].length === 0 || obj["code"] == "NULL" || obj["code"] == "") {
							document.getElementById("noSelect_cate").selected = true;
						}else{
							console.log('Option se Value : ' + obj["code"]);
							var replaceValue = obj["code"].replace('_','-');
							document.getElementById(replaceValue).selected = 'selected';
							
						}
					}
		        	
		       },
		       error:function(){
		        alert("error");
		       }

	      });

	}
	

	function testAlert(str){
		alert(str);
	}


</script>
		
		
	
