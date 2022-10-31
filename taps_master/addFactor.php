<?php 
  include 'header2.php';
	include 'iconn.php';
?>
<html>
<style>
  input[type=text], select, textarea {
    width: 200;
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
    width:100
  }

  #main-content {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
  }
</style>

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	  if (!empty($_POST['compound']) and !empty($_POST['who_tef_1998']) or !empty($_POST['who_tef_2005']) or !empty($_POST['i_tef'])){
			//if (isset($_POST['userid']) and isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['pwd']))	
			$sql = "INSERT INTO factor(compound_code,compound,who_tef_1998,who_tef_2005,i_tef) VALUES ('".$_POST['compound_code']."','".$_POST['compound']."','".$_POST['who_tef_1998']."','".$_POST['who_tef_2005']."','".$_POST['i_tef']."')";
			
			if ($dbc->query($sql) === FALSE) {
				echo "Error: " . $dbc->error;
				exit();
			}else{					
				 echo '<p align="center" style="color:blue;font-weight:bold;font-size:28px">New Factor added successfully</p>';
      }
    }
  }
?>
  <h2>Add New Compound record</h2>
  <body>
    <div id="main-content">
        <form class="post-form" action="addFactor.php" method="post">     
          <table>
            <tr>
              <td>Compound Code: </td>
              <td><input type="text" name="compound_code" required/></td>
            </tr>
            <tr>
              <td>Compound: </td>
              <td><input type="text" name="compound" required/></td>  
            </tr>
            <tr>
              <td>WHO-TEF-2005: </td>
              <td><input type="text" name="who_tef_2005" required/></td> 
            </tr>
            <tr>
              <td>WHO-TEF-1998: </td>
              <td><input type="text" name="who_tef_1998" required/></td>
            </tr>
            <tr>
              <td>I-TEF: </td>
              <td><input type="text" name="i_tef"/></td>
            </tr>
          </table>
         
          <input class="submit" type="submit" value="Add"  />
        </form>
    </div>
  </body>
</html>