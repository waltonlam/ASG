<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'New Parameter');
include('templates/header.html');
include('templates/iconn.php');

if (empty($_SESSION['vuserid'])) {
	print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
	exit();
} else{
	if ($_SESSION['utp']=='R'){
		print '<p class="text--error">Access Deny</p>';		
		exit();
	}	
		/*
		$q = "select d.district_id did, d.name dname, s.station_id sid, s.name sname
				from user_district ud, district d, station s 
				where s.district_id = ud.district_id and ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";
		$result=$dbc->query($q);
		if (!$result->num_rows){
				print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
				exit();
		}				
			//$q = "select * from user_acc where userid = ? and pwd= ?";
			//$stmt = $db->prepare($q);
			//$stmt->bind_param('ss',$_POST['userid'], $_POST['pwd']);
			//$stmt->execute();
			//mysqli_query($dbc, $q);			
		*/			
}

print '<script>function check_num() {
    alert(document.getElementById("other_qty").innerHTML);
		    document.getElementById("other_qty").focus();
		return false;

		if (document.getElementById("other_qty").innerHTML<>""){
			if (!is_numeric(document.getElementById("other_qty").innerHTML)){
		    alert("Please input the number");
		    document.getElementById("other_qty").focus();
		  }
		}
}
</script>';


print '<script>
function submitForm(){

        return false;


    if(validationfails){
        return false;
    }
    else  {
        document.form.submit();
        return true;
    }
}
</script>';


print '<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
function fetch_select(val)
{

  document.getElementById("new_select").innerHTML="XXXX"; 
	alert("hello");
 $.ajax({
 method: "post",
 url: "fetch_data.php",
 data: {
  get_option:val
 },
 success: function (response) {
  document.getElementById("new_select").innerHTML=response; 
 }
 });
}

</script>';



print '<script>
function showprg(str) {

//	alert("str="+str);
    if (str == "") {
        document.getElementById("new_outlet_prg").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
//        	alert("XMLHttpRequest");
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
//                    alert("this.readyState == 4, this.responseText="+this.responseText);
                document.getElementById("new_outlet_prg").innerHTML = this.responseText;
            }else{
//            	alert("this.readyState <> 4.....,this.readyState="+this.readyState+"..."+"status="+this.status);
//            	alert("this.responseText="+this.responseText);
            	}
        };
//        alert("xmlhttp.open");
        xmlhttp.open("GET","fetch.php?q="+str,true);
        xmlhttp.send();
        
    }
}
</script>';


//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];

/*
if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
		  		$q = "select d.district_id did, d.name dname 
								from user_district ud, district d
								where ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";

					//print $q;
					$result_ud=$dbc->query($q);
					if (!$result_ud->num_rows){
							print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
							exit();
					}				
}
*/

$created=FALSE;
$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['eid']) and !empty($_POST['ename']) )	
		//if (isset($_POST['userid']) and isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['pwd']))	
	{
/*		
		print '<p>userid='.$_POST['userid'].'</p>';
				print '<p>fname'.$_POST['fname'].'</p>';
						print '<p>lname'.$_POST['lname'].'</p>';
*/
		//and isset($_POST['did']) 
			$q = "insert into element (ele_id,name,unt,gcde) 
						values ('".$_POST['eid']."','".$_POST['ename']."','".$_POST['unt']."','".$_POST['gc']."');";

				//date('Y/m/d', $_SESSION['trans_date'])    					
				//$mysqli->autocommit(FALSE);				
				//$dbc->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);  
					if ($dbc->query($q) === FALSE) {
					    echo "Error: " . $dbc->error;
					    exit();
						}else{					
					    		$created=TRUE;
					    	}
																															
				} else {
						//print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
						$criteria = "";
						$comp="false";								
	
				    //echo "Error-> " . $q . "<br>" . $dbc->error;
//				    exit();
					}
}					
				//$dbc->commit();
//					$dbc->autocommit(TRUE);

$l = "select gcde gid, gname 
from eleg;";
//print $q;
$result_g=$dbc->query($l);
if (!$result_g->num_rows){
print '<p class="text--error">'.'Parameter Group Configuration Error!</p>';
exit();
}		
	 
print '<hr>
	<div class="topnav">
    <a href="imp.php">Data Template Input</a>
		<a href="loc_new.php">Add Loc</a>
		<a href="update_loc_m.php">Edit Loc</a>
		<a href="del_loc.php">Delete Loc</a>
		<a href="egp_new.php">Add Param Group</a>
		<a href="update_gp_m.php">Edit Param Group</a>
		<a href="del_gp.php">Delete Param Group</a>
		<a href="ele_new.php">Add Param</a>
		<a href="update_ele_m.php">Edit Param</a>
		<a href="del_ele.php">Delete Param</a>
		<a href="ua_new.php">Add UAC</a>
		<a href="update_ua_m.php">Edit UAC</a>
        <a href="del_ua.php">Delete UAC</a>
        <a href="logout.php">Logout</a>;
	</div><hr>';
	
print '
<form onsubmit="submitForm()" action="ele_new.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">New Parameter</p>
      
      <table style="width:92%;margin-left:2%;">		     	
			<tr style="color:#555555;">';
				  /*
				 	while ($r_ud=$result_ud->fetch_object()){
				    print '<option value="'.$r_ud->did.'">'.'('.$r_ud->did.') '.$r_ud->dname.'</option>';
				  };
				  */			
print '</td></tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="sid">Group Code</label>
				</td>
				<td>
				  <select style="margin-left:10px" name="gc">';
				  while ($r_l=$result_g->fetch_object()){
					 if ($r_l->gid==$t[0]){
						 print '<option value="'.$r_l->gid.'" selected>'.'('.$r_l->gid.') '.$r_l->gname.'</option>';}
					 else{
						print '<option value="'.$r_l->gid.'">'.'('.$r_l->gid.') '.$r_l->gname.'</option>';}
			   };

print '</td></tr>
			<tr style="color:#555555;">
			<td style="width:48%;text-align:right">
				<label for="eid">Parameter Code</label>
			</td>
			<td>
			<input style="margin-left:10px" type="text" name="eid" id="eid"></input>
			</td>				 	
			</tr>	
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="ename">Parameter Name</label>
				</td>
				<td>
				  <input style="margin-left:10px" type="text" name="ename" id="ename"></input>
				</td>				 	
			</tr>			
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="ename">Unit</label>
				</td>
				<td>
				  <input style="margin-left:10px" type="text" name="unt" id="unt"></input>
				</td>				 	
			</tr>				
		
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="Add New">
				</td>  
			</tr></table></form>';


  	//mysqli_free_result($result_ud);





/*
print '<table style="background-color:white;border-radius:8px;border:none;width:98%;margin-left:1%;">
<tr>
<td  style="border:none;text-align:center;">
    <br>
    <h3 style="display:block;margin-left:1%;padding-right:4px;color:#05CDB9;text-align:left;">&nbsp;????????????:</h3>
    <hr style="border:0.2px solid grey;">
    <form  method="post" action="">
        ??????
        <select name="category">
            <option value="">-----</option>
            <option value="SEQ_ID">????????????</option>
            <option value="DATE">??????</option>
        </select>
        ??????
        <input style="width:15%;"type="text" name="crit">????????????????????????
        <button type="submit" class="btn btn-default">????????????</button>
    </form>
    <br>
</td>
</tr>
</table>';
*/
print'
  <span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="sampletx.php">Home</a></span>';


	if ($comp=="false"){
			print '<p align="center" class="text--error">Please enter the missing entry!</p>';
			}else{
						if ($created===TRUE){					
				echo '<p align="center" style="color:blue;font-weight:bold;font-size:28px">New Parameter Group is added successfully</p>';}
					}

	if ($invq=="true"){
				print '<p align="center" class="text--error">?????????????????????</p>';}






function check_empty($x){
	//print '$x='.$x;
	if ($x==''){
		//print "return 0";
		return 0;		
		}else{
			return $x;
		}
		//	print "nothing";
}	




include('templates/footer.html'); // Need the footer.






?>

<script language="javascript">
function ShowMeDate() {
???var Today=new Date();
???alert("??????????????? " + Today.getFullYear()+ " ??? " + (Today.getMonth()+1) + " ??? " + Today.getDate() + " ???");
}


<script>

