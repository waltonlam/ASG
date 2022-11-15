<?php include 'header2.php';
	include 'iconn.php';
?>
<html>
<style>

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}


input[type=submit] {
  background-color: #87ceeb;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  width:100%
}

#main-content {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>

<script>
			
			function showSuccessAlert(){
				alert("Add Success");
			}
		
	
</script>

<?php

	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		if (!empty($_POST['uid']) and !empty($_POST['pwd']) ){
			//if (isset($_POST['userid']) and isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['pwd']))	
				
			if(isset($_POST['ronly'])){
				//yes
				$sql = "INSERT INTO uacc(uid,pwd,fname,lname,ronly) VALUES ('".$_POST['uid']."','".$_POST['pwd']."','".$_POST['fname']."','".$_POST['lname']."','0')";
			
				
			}else{
				//no
				
				$sql = "INSERT INTO uacc(uid,pwd,fname,lname,ronly) VALUES ('".$_POST['uid']."','".$_POST['pwd']."','".$_POST['fname']."','".$_POST['lname']."','1')";
			
			}
			
			//$sql = "INSERT INTO uacc(uid,pwd,fname,lname,ronly) VALUES ('".$_POST['uid']."','".$_POST['pwd']."','".$_POST['fname']."','".$_POST['lname']."','".$_POST['ronly']."')";
			
			if ($dbc->query($sql) === FALSE) {
				echo "Error: " . $dbc->error;
				exit();
			}else{					
				  echo "<script> window.onload = function() {showSuccessAlert();}; </script>";
			}

		}else if (!empty($_POST['uid']) ){
			
			$sql = "INSERT INTO uacc(uid) VALUES ('".$_POST['uid']."')";
			
			if ($dbc->query($sql) === FALSE) {
				echo "Error: " . $dbc->error;
				exit();
			}else{					
				 echo "<script> window.onload = function() {showSuccessAlert();}; </script>";
			}
			
		}
																															
		
	}
	
	




?>

 <h2>Add New Account</h2>
  <body>
<div id="main-content">
   
    <form class="post-form" action="addAccount.php" method="post">
       
			
			<label>User ID</label>
            <input type="text" name="uid" required/>
       
            <label>Password</label>
            <input type="text" name="pwd" />
			
			<label>First name</label>
            <input type="text" name="fname" />
			
			<label>Last name</label>
            <input type="text" name="lname" />
			
			<label>Read only</label>
            <input type="checkbox" name="ronly" />
        
			<br>
       
        <input class="submit" type="submit" value="Add"  />
    </form>

</div>
</body>
</html>
