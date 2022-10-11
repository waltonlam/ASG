<?php include ('iconn.php');
	require_once 'sqlHelper.php';
	global $reviewSql ;
	global $reviewSqlWithDate ;
	global $filterValue ;

 	$con = new MyDB();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['review'])) {
			//header('Cache-Control: no cache'); //no cache
			//session_cache_limiter('private_no_expire');

			print '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
					<style type="text/css">
						
						#popup { display:none; border-radius: 10px; position:absolute;top:0px; left:0px;  width:100%; height:100%; z-index:10; } 
						#popupOverlay { position:absolute;top:0px;  left:0px;  width:100%; height:100%;background:black;opacity:0.5; z-index:11; }
						#popupContent { position:relative; border-radius: 10px; width:450px; margin:10 auto;  background:white; margin-top:150px; border:0.5px solid; z-index:12; padding: 20px;}
						.popupTitle {margin-left: 20px; margin-right: 20px; }

						#sampleId{visibility: visible;}

						.button {
						  background-color: #4CAF50; 
						  border: none;
						  color: white;
						  padding: 10px 30px;
						  text-align: center;
						  text-decoration: none;
						  display: inline-block;
						  font-size: 13px;
						  border-radius: 10px;
						  margin: 4px 2px;
						  cursor: pointer;
						}

						.button1 {background-color: #008CBA;}
						.button2 {background-color: #f44336;}

						.row {
						  width: 100%;
						  display: flex;
						  flex-direction: row;
						  justify-content: center;
						}

					</style>';
 

			include 'header2.html';
    		//update action
   //  		print '<br>';
			// print "review function click 2";	
			// print '<br>';

			print '<h2>Filter Result</h2>';
			//echo 'line 59: <br>'.json_encode($_POST["compound_id"]).'<br>';

			$sql_value = "";

			$val_list ="";
			$compounds = $_POST["compound_id"];
			consol.log(json_encode($_POST["compound_id"]));

/*K
			foreach($compounds as $compound){
			// print ($compound);
			// print '<br>';
				$finalVal = str_replace("'","\'",$compound);
			// print ($finalVal);
				$val_list.='"'.$finalVal.'"'.','; 
			}

			$compound_sub = substr($val_list, 0, -1);
			if (empty($compound_sub)) {
				$compound_sub = "''";
			}
K*/			
			$sComp='';
			for($i = 0; $i <= count($_POST["compound_id"])-1; $i++) {	
				if ($i==count($_POST["compound_id"])-1){
					$sComp.= "'".$_POST["compound_id"][$i]."'";
				}
				else{
					$sComp.= "'".$_POST["compound_id"][$i]."',";
				}
			}
			$sql_value.=" glab_template.compound_code in (".$sComp.")";

			$site_list = "";
			// if(!empty($_POST["site_id"])){
				// to check the username checkboxes values you can use loop to display each checkbox value
/*K				
				$site = $_POST["site_id"];
				foreach($site as $site_value){
					$site_list.="'".$site_value."',"; 
					
				}
K*/
			$sSt='';
			for($i = 0; $i <= count($_POST["site_id"])-1; $i++) {	
				if ($i==count($_POST["site_id"])-1){
					$sSt.= "'".$_POST["site_id"][$i]."'";
				}
				else{
					$sSt.= "'".$_POST["site_id"][$i]."',";
				}
			}

		   // } 
/*K		   
			   $site_sub = substr($site_list, 0, -1);
			   if (empty($site_sub)) {
			   		$site_sub = "''";
			   }
K*/

			$sql_value.=" and glab_template.site IN (".$sSt.") and 
							glab_template.start_date between '".$_POST['start']."' and '".$_POST['end']."'";

			print '<p>sql_value = '.$sql_value;




			$haveSite = false;
			$haveCompound = false;

			$sql_value_final = "";
			   // if ($haveSite || $haveCompound || (!empty($_POST['start']) && !empty($_POST['end']))) {
			   		$sql_value_final = "WHERE ".$sql_value;
			   // }
			 
			$l = "SELECT glab_template.start_date AS 'Start Date' , glab_template.sample_id AS 'Sample ID',
				glab_template.compound_code as 'Compound',glab_template.conc as 'Conc./SAMPLE',
				contractor_template.flow_rate as 'Flow(LPM)',contractor_template.duration as 'Time(min)',
				(contractor_template.flow_rate/1000)*contractor_template.duration AS 'Volume(m3)',
				IF (glab_template.conc < 0,
					(glab_template.conc/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)),
					(glab_template.conc/((contractor_template.flow_rate/1000)*contractor_template.duration)) ) AS 'Conc/M3',
					contractor_template.who_tef AS 'WHO TEF Ratio' ,
				ROUND( (IF (glab_template.conc < 0,
					(glab_template.conc/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)),
					(glab_template.conc/((contractor_template.flow_rate/1000)*contractor_template.duration)) ))*contractor_template.who_tef,6)  AS 'WHO TEQ conc/m3',
				glab_template.casno1 
				FROM glab_template JOIN contractor_template 
				ON contractor_template.sample_id = glab_template.sample_id 
				AND glab_template.compound_group = contractor_template.compound_group ".$sql_value_final." 
				ORDER BY glab_template.start_date , glab_template.compound_code ;";
		   
			    $reviewSqlWithDate = $l;
				  print "<p>l = ".$l;
				 // exit();  

				$filterValue = $sql_value_final;

				$reviewSql = "SELECT glab_template.sample_id AS 'Sample ID',glab_template.compound_code as 'Compound',
								glab_template.conc as 'Conc./SAMPLE',contractor_template.flow_rate as 'Flow(LPM)',
								contractor_template.duration as 'Time(min)',
								(contractor_template.flow_rate/1000)*contractor_template.duration AS 'Volume(m3)',
								IF (glab_template.conc < 0,
									(glab_template.conc/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)),
								(glab_template.conc/((contractor_template.flow_rate/1000)*contractor_template.duration)) ) AS 'Conc/M3',
								contractor_template.who_tef AS 'WHO TEF Ratio' ,
								ROUND( (IF (glab_template.conc < 0,
									(glab_template.conc/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)),
									(glab_template.conc/((contractor_template.flow_rate/1000)*contractor_template.duration)) ))*contractor_template.who_tef,6)  AS 'WHO TEQ conc/m3'
								FROM glab_template JOIN contractor_template 
								ON contractor_template.sample_id = glab_template.sample_id 
								AND glab_template.compound_group = contractor_template.compound_group ".$sql_value_final." 
								ORDER BY glab_template.start_date , glab_template.compound_code ;";

				$glabResult_array = $con -> selectData($l);
				// echo '<pre>';
				// print_r($glabResult_array['result']);
				// echo '</pre>';

				?>
					<table class="table" cellspacing="0" width="100%">
					<tr>
						<th>Edit</th>
						<th>Start Date</th>
						<th>Sample ID</th>
						<th>Compound</th>
						<th>Conc./SAMPLE</th>
						<th>Flow(LPM)</th>
						<th>Time(min)</th>
						<th>Volume(m3)</th>
						<th>Conc/M3</th>
						<th>WHO TEF Ratio</th>
						<th>WHO TEQ conc/m3</th>
						<th>Casno1</th>
					</tr>
					<?php 

					if (!empty($glabResult_array['result'])) {
					
						foreach ($glabResult_array['result'] as $order): ?>
						  <tr>
						 <!-- <style>[contenteditable="true"]:focus {background-color: yellow;}</style> -->

						  	<td><input type="image" src="img/edit.jpg"  class="togglePopup" alt="Submit" width="20" height="20" data-target="#edit-modal"></td>
						  	<td><?php echo $order['Start Date'] ?></td>
						    <td><?php echo $order['Sample ID'] ?></td>
						    <td><?php echo $order['Compound'] ?></td>

						    <td><input type="number" id="sample_conc[]" value=<?php echo $order['Conc./SAMPLE']?>></td>
							<td><input type="number" id="flow[]" value=<?php echo $order['Flow(LPM)'] ?>></td>					
						    <td><input type="number" id="mtime[]" value=<?php echo $order['Time(min)'] ?>></td>
						    <td><input type="number" id="vol[]" value=<?php echo round($order['Volume(m3)'],5) ?>></td>
							<td><input type="number" id="vol[]" value=<?php echo round($order['Conc/M3'],5) ?>></td>							
						    <td><input type="number" id="who_tef[]" value=<?php echo $order['WHO TEF Ratio'] ?>></td>
						    <td><input type="number" id="who_teq[]" value=<?php echo round($order['WHO TEQ Conc/m3'],5) ?>></td>
						    <td><input type="text" id="casno1[]" value=<?php echo $order['casno1'] ?>></td>
						    
						  </tr>
					<?php 
						endforeach;
					}
					 ?>

					</table>

					<div id="plotChartDiv"></div>

					<div id="outerdiv"></div>

					<button type="submit"  class="button button1" style="margin-top: 15px;" id="reviewPlotBtn" >Fuck Export</button> 
					<button type="submit"  class="button button2" style="margin-top: 15px;" id="plotChart" >Plot</button> 

					
				<div id="popup">
				    <div id="popupOverlay"></div>
				    <div id="popupContent">
				        <h2>Update Review Result</h2>
				        <h3 id="sampleID"></h3>
						<hr style="margin-left:10px; margin-right:10px;">
						<label id='cmp'></label><br><br><br>
				        <p>Conc./SAMPLE: </p>
				        <input type="number"  id="conc_value"   >
				        <p>Flow(LPM): </p>
				        <input type="number"  id="flow_value"  >
				        <p>Time(min): </p>
				        <input type="number"  id="time_value"  ><br><br><br>
				        <div class="row">
						  <div class="submitPopup"><button class="button button1"  type="togglePopup">Submit</button></div>
						  <div class="togglePopup"><button class="button button2"  type="togglePopup">Cancel</button></div>
						</div>
				        
				    </div>
				</div>

				<script src="https://cdn.plot.ly/plotly-2.3.0.min.js"></script>
				
				
		<script>
	 

			var currentSampleId = "";
			var orignalCompound = "";
			var orignalConc = "";
			var orignalFlow = "";
			var orignalTime = "";
			var currentRowGlobal ;

			function exportFilterResult(sql){
				console.log(sql);
			}



			$(".togglePopup").on('click',function(){

				var currentRow=$(this).closest("tr");
				currentRowGlobal = currentRow;
				var col1=currentRow.find("td:eq(1)").html();
				var col2=currentRow.find("td:eq(2)").html();
				var col3=currentRow.find("td:eq(3)").html();
				var col4=currentRow.find("td:eq(4)").html();
				var col5=currentRow.find("td:eq(5)").html();
				var col6=currentRow.find("td:eq(6)").html();

				 currentSampleId = col2;

				 orignalCompound = col3;
				 orignalConc = col4;
				 orignalFlow = col5;
				 orignalTime = col6;

				 // alert(currentSampleId);

				 //$('#personalSave').text('edit');
				 $("#sampleID").text('Sample ID: ' + col2) ;
				//$("#compound_value").text('Compound: ' + col3) ;
				$("#cmp").text('Compound: ' + col3) ;
				$("#conc_value").val(col4);
				$("#flow_value").val(col5);
				$("#time_value").val(col6);


			    $("#popup").fadeToggle();
			});


			$("#reviewPlotBtn").on('click',function(){
				
			    var x = <?php echo json_encode($reviewSql); ?>;

				alert("Go to reviewFunction");

                $.ajax({
                    url: "reviewFunction.php",
                    type: "POST",
                    data: {
                            "reviewSql": x
                           }, 
                    success: function(data){
                            console.log(data);
                            var downloadLink = document.createElement("a");
                              var fileData = [data];
                              var blobObject = new Blob(fileData,{
                                 type:"text/csv;charset=utf-8;"
                               });
                              var url = URL.createObjectURL(blobObject);
							  alert("URL = " + url);
                              downloadLink.href = url;
                              downloadLink.download ="export_csv.csv";
                              document.body.appendChild(downloadLink);
                              downloadLink.click();
                              document.body.removeChild(downloadLink);
                        }
				
					})
			});

            

			$("#plotChart").on('click',function(){

				// console.log("hahah")
				// window.location = 'reviewFunction.php';

			     var layout = {barmode: 'group',title:currentCasno1};
				 var x = <?php echo json_encode($glabResult_array); ?>;
				 var monthArray = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
				 var resultArray = x["result"];
				 var casno1Arr = [];
				 var compoundArr = [];

				 for (var i = 0; i < resultArray.length; i++) {
				 	// console.log(resultArray[i]);
				 	casno1Arr.push(resultArray[i]["casno1"]);
				 	compoundArr.push(resultArray[i]["Compound"]);

				 }

				//https://www.runoob.com/try/try.php?filename=tryjsref_filter
				//https://ithelp.ithome.com.tw/articles/10229458
 				 let uniqueCasno1 = casno1Arr.filter((c, index) => {
				    return casno1Arr.indexOf(c) === index;		//return true if 

				});


				let uniqueCompound = compoundArr.filter((c, index) => {
				    return compoundArr.indexOf(c) === index;
				});


				for (var i = 0; i < uniqueCasno1.length; i++) {
				 	
				 	// console.log(uniqueCasno1[i]);
					$(document).ready(function() {
					    $('#outerdiv').append(
					        $('<div>').prop({
					            id: 'innerdiv'+i
					        })
					    );
						  
					});
				}

				for (var i = 0; i < uniqueCompound.length; i++) {
				 	
				 	// console.log(uniqueCompound[i]);
				}

				
				for (var cas = 0;cas < uniqueCasno1.length; cas++) {
					var barArray = [];
					var currentCasno1 = uniqueCasno1[cas];
					console.log(currentCasno1);
					var currentCompount ="";
					var trace1=null;
					var selectCasno1 = "";

					for (var x = 0; x < uniqueCompound.length; x++) {
						var selectCompound = "";
						currentCompount = uniqueCompound[x];
						console.log(currentCompount);
						var concArray = [];
						var jan = 0;
						var feb = 0;
						var mar = 0;
						var apl = 0;
						var may = 0;
						var jun = 0;
						var jul = 0;
						var aug = 0;
						var sep = 0;
						var oct = 0;
						var nov = 0;
						var dec = 0;
						var janAve = 0;
						var febAve = 0;
						var marAve = 0;
						var aplAve = 0;
						var mayAve = 0;
						var junAve = 0;
						var julAve = 0;
						var augAve = 0;
						var sepAve = 0;
						var octAve = 0;
						var novAve = 0;
						var decAve = 0;


							for (var i = 0; i < resultArray.length; i++) {
							
							if (resultArray[i]["Compound"] == currentCompount && resultArray[i]["casno1"] == currentCasno1) {
								selectCasno1 = currentCasno1;
								selectCompound = currentCompount;
								console.log("***compount = "+currentCompount + "  & casno1 = "+currentCasno1);

								// for (var v = 0; v < uniqueCasno1.length; v++) {


								// 	var currentCasno1 = uniqueCasno1[v];

								// 	if (resultArray[i]["casno1"] == currentCasno1) {

									var d = new Date(resultArray[i]["Start Date"]);

										// concArray.push(resultArray[i]["Conc/M3"]);
										console.log("haha  =  "+d.getMonth());

										
										if (d.getMonth() == 0) {
											janAve++;
											jan+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("jan  = "+jan);
										}
										if (d.getMonth() == 1) {
											febAve++;
											feb+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("feb  = "+feb);
										}
										if (d.getMonth() == 2) {
											marAve++;
											mar+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("mar  = "+mar);
										}
										if (d.getMonth() == 3) {
											aplAve++;
											apl+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("apl  = "+apl);
										}
										if (d.getMonth() == 4) {
											mayAve++;
											may+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("may  = "+may);
										}
										if (d.getMonth() == 5) {
											junAve++;
											jun+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("jun  = "+jun);
										}
										if (d.getMonth() == 6) {
											julAve++;
											jul+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("jul  = "+jul);
										}
										if (d.getMonth() == 7) {
											augAve++;
											aug+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("aug  = "+aug);
										}
										if (d.getMonth() == 8) {
											sepAve++;
											sep+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("sep  = "+sep);
										}
										if (d.getMonth() == 9) {
											octAve++;
											oct+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("oct  = "+oct);
										}
										if (d.getMonth() == 10) {
											novAve++;
											nov+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("nov  = "+nov);
										}
										if (d.getMonth() == 11) {
											decAve++;
											dec+=parseFloat(resultArray[i]["Conc/M3"]);
											// console.log("dec  = "+dec);
										}
							}

						}



						var jarValue = jan/janAve;
						// console.log("jar avg = "+jarValue);

						var febValue = feb/febAve;
						// console.log("feb avg = "+febValue);
						var marValue = mar/marAve;
						// console.log("mar avg = "+marValue);
						var aprValue = apl/aplAve;
						// console.log("apl avg = "+jarValue);
						var mayValue = may/mayAve;
						// console.log("may avg = "+jarValue);


						var junValue = jun/junAve;
						// console.log("jun avg = "+jarValue);
						var julValue = jul/julAve;
						// console.log("jul avg = "+julValue);
						var augValue = aug/augAve;
						// console.log("aug avg = "+augValue);
						var sepValue = sep/sepAve;
						// console.log("sep avg = "+sepValue);
						var octValue = oct/octAve;
						// console.log("oct avg = "+octValue);

						var novValue = nov/novAve;
						// console.log("nov avg = "+novValue);
						var decValue = dec/decAve;
						// console.log("dec avg = "+decValue);

						concArray.push(jarValue);
						concArray.push(febValue);
						concArray.push(marValue);
						concArray.push(aprValue);
						concArray.push(mayValue);
						concArray.push(junValue);

						concArray.push(julValue);
						concArray.push(augValue);
						concArray.push(sepValue);
						concArray.push(octValue);
						concArray.push(novValue);
						concArray.push(decValue);

						// if (resultArray[i]["Compound"] == currentCompount && resultArray[i]["casno1"] == currentCasno1) {

						trace1 = {
					          x:monthArray,
					          y:concArray,
					          name: currentCompount,
					          type: 'line'
					        };
					        barArray.push(trace1);

					        console.log("-------------------end-----------------");

					       console.log("-------------------end-----------------");

						
					// }


					var data = barArray;

			        //KN: var layout = {barmode: 'group',title:currentCasno1};

			        //KN: Plotly.newPlot('innerdiv'+cas, data, layout);
			    	}
						 console.log("-------------------end2-----------------");				


				}
				//KN
				Plotly.newPlot('innerdiv'+cas, barArray, layout);


			});



			$(".submitPopup").on('click',function(){

				var sampleID= currentSampleId;

				var oCompound = orignalCompound;
				var oConc = orignalConc;
				var oFlow = orignalFlow;
				var oTime = orignalTime;

				var col1=$("#compound_value").val();
				var col2=$("#conc_value").val();
				var col3=$("#flow_value").val();
				var col4=$("#time_value").val();

				var data=col1+"\n"+col2+"\n"+col3+"\n"+col4;
				console.log(sampleID+ " | "+col1+ " | "+col2+ " | "+col3+ " | "+col4);

				$.ajax({
		            url: "updateReviewItem.php",
		            type: "POST",

		            data: {
		            		"sampleId": sampleID,
		            		"compound": col1,
				            "conc": col2,
				            "flow": col3,
				            "time": col4,
				            "orignalCompound": oCompound,
				            "orignalConc": oConc,
				            "orignalFlow": oFlow,
				            "orignalTime": oTime,
				           }, 
		            success: function(data){
		                console.log(data);

						window.location.reload();

		            }

		       	});

				$("#popup").fadeToggle();
				
			});


		</script>




	<?php

		} else if (isset($_POST['export'])) {
			    //delete action


			if (!$dbc = new mysqli('localhost', 'root', '', 'taps')){
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "SELECT glab_template.sample_id AS 'Sample ID',glab_template.compound_code as 'Compound',
					glab_template.conc as 'Conc./SAMPLE',contractor_template.flow_rate as 'Flow(LPM)',
					contractor_template.duration as 'Time(min)',
					(contractor_template.flow_rate/1000)*contractor_template.duration AS 'Volume(m3)',
					IF (glab_template.conc < 0,(glab_template.conc/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)),
					(glab_template.conc/((contractor_template.flow_rate/1000)*contractor_template.duration)) ) AS 'Conc/M3',
					contractor_template.who_tef AS 'WHO_TEF Ratio' ,
					ROUND( (IF (glab_template.conc < 0,(glab_template.conc/-2/((contractor_template.flow_rate/1000)*contractor_template.duration)),
					(glab_template.conc/((contractor_template.flow_rate/1000)*contractor_template.duration)) ))*contractor_template.who_tef,6)  AS 'WHO_TEQ conc/m3'
					FROM glab_template JOIN contractor_template 
					ON contractor_template.sample_id = glab_template.sample_id 
					AND glab_template.compound_code = contractor_template.compound_group 
					ORDER BY glab_template.sample_id";


			$result = mysqli_query($dbc, $l);

			$num_column = mysqli_num_fields($result);		

			$csv_header = '';
			for($i=0;$i<$num_column;$i++) {
				$csv_header .= '"' . mysqli_fetch_field_direct($result,$i)->name . '",';
			}	
			$csv_header .= "\n";

			$csv_row ='';
			while($row = mysqli_fetch_row($result)) {
				for($i=0;$i<$num_column;$i++) {
					$csv_row .= '"' . $row[$i] . '",';
				}
				$csv_row .= "\n";
			}	

			/* Download as CSV File */
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=export_csv.csv');
			echo $csv_header . $csv_row;


		} else {
		    //no button pressed
		}

	}


	?>













