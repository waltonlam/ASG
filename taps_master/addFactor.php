<?php 
	include 'header2.php';
	include 'iconn.php';
?>
<html>
	<style>
	  input[type=submit] {
		background-color: #87ceeb;
		color: white;
		padding: 12px 20px;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		width:100
	  }
	</style>

	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		  if (!empty($_POST['compound']) and !empty($_POST['who_tef_1998']) or !empty($_POST['who_tef_2005']) or !empty($_POST['i_tef'])){
				//if (isset($_POST['userid']) and isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['pwd']))	
				$sql = "INSERT INTO factor(compound_code,compound,who_tef_1998,who_tef_2005,i_tef,who_tef) VALUES ('".$_POST['compound_code']."','".$_POST['compound']."','".$_POST['who_tef_1998']."','".$_POST['who_tef_2005']."','".$_POST['i_tef']."','".$_POST['who_tef']."')";
				
				if ($dbc->query($sql) === FALSE) {
					echo "Error: " . $dbc->error;
					exit();
				}else{					
					 echo '<p align="center" style="color:blue;font-weight:bold;font-size:28px">New Factor added successfully</p>';
				}
			}
		}
	?>
	<h2 style="margin-left:10px">Add Factor</h2>
	<hr>
	<body>
		<div>
			<form class="post-form" action="addFactor.php" method="post">     
				<table style="margin-left:10px">
					<tr>
						<td><label>Compound Code<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="compound_code" required/>
						</td>
					</tr>
					<tr>
						<td><label>Compound<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="compound" required/>
						</td>  
					</tr>
					<tr>
						<td><label>WHO-TEF-2005<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="who_tef_2005" required/>
						</td> 
					</tr>
					<tr>
						<td><label>WHO-TEF-1998<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="who_tef_1998" required/>
						</td>
					</tr>
					<tr>
						<td><label>I-TEF: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="i_tef"/>
						</td>
					</tr>
					<tr>
						<td><label>WHO-TEF: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="who_tef"/>
						</td>
					</tr>
				</table>
				<hr>
				<input style="margin-left:10px" class="submit" type="submit" value="Add"  />
			</form>
		</div>
	</body>
</html>