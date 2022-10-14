<?php 
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Update Parameter');
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


/*
print '<script>function check_num() {
    alert(document.getElementById("other_qty").innerHTML);
		    document.getElementById("other_qty").focus();
		return false;

		if (document.getElementById("other_qty").innerHTML<>""){
			if (!is_numeric(document.getElementById("other_qty").innerHTML)){
		    alert("Please enter the number");
		    document.getElementById("other_qty").focus();
		  }
		}
};
*/

print '<script language="javascript">function Showparam() {
	var e = document.getElementById("eid");
	var str = e.options[e.selectedIndex].innerHTML;
	var info = str.split("**");

	var grp = document.getElementById("gid");
	grp.options[grp.options.selectedIndex].selected = true;

	document.getElementById("name").value = info[1];
	document.getElementById("unt").value = info[2];
	document.getElementById("gid").value = info[3];
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

print '<script>
function showprg(str) {

//	alert("str="+str);
    if (str == "") {
        document.getElementById("new_prg").innerHTML = "";
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
                document.getElementById("new_prg").innerHTML = this.responseText;
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


// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];

//$updated=FALSE;
$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['gid']) and !empty($_POST['eid']))	
		{
				$u= "update element set "
				."name='".$_POST['name']."',unt='".$_POST['unt']."',gcde='".$_POST['gid']."'"
				." where ele_id='".$_POST['eid']."';";
				
				//print $u;
				//$dbc->autocommit(FALSE);

					if ($dbc->query($u) === TRUE) {
					   // $updated=TRUE;
					    echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">Parameter has been updated</p>
					    <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="sampletx.php">Home</a></span>';

							}else{
								    echo "Error: " . $dbc->error;
								    exit();
												};

		}else{
				print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
				$criteria = "";
				$comp="false";				

				}
		exit();
		
} 


/*
if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
					if (empty($_REQUEST['cluster'])) {
							print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		

					}else{

							 $token = strtok($_REQUEST['cluster'], "|");
							 
								$i=0; 
								while ($token !== false)
							   {
							   	 $t[$i]=$token;
							   //echo "$token<br>";
								   $token = strtok("|");
							  	 $i++;
							   }
							if ($t[5]=="@"){$t[5]="";} 

					}		
					
					$q = "select d.district_id did, d.name dname
							from user_district ud, district d
						where ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";

					//print $q;
					$result_ud=$dbc->query($q);
					if (!$result_ud->num_rows){
							print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
							exit();
					}				

					$q = "select *
							from house_type;";
					//print $q;
					$result_hse_t=$dbc->query($q);
					if (!$result_hse_t->num_rows){
							print '<p class="text--error">'.'House Type Configuration Error!</p>';
							exit();
					}				



				}
*/				
	
$l = "select gcde gid, gname 
from eleg;";
//print $q;
$result_g=$dbc->query($l);
if (!$result_g->num_rows){
print '<p class="text--error">'.'Parameter Group Configuration Error!</p>';
exit();
}		
						
$l = "select * 
from element;";
//print $q;
$result_e=$dbc->query($l);
if (!$result_e->num_rows){
print '<p class="text--error">'.'Parameter Configuration Error!</p>';
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
<form action="update_ele_m.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">Update Parameter Group Info.</p>
      
	  <table style="width:92%;margin-left:2%;">';	
print '<tr style="color:#555555;">
	  <td style="width:48%;text-align:right">	
		  <label for="lid">Parameter</label>	  				
		  <td>
		  <select style="margin-left:10px" name="eid" id="eid" onchange="Showparam()">';
			 while ($r_e=$result_e->fetch_object()){
				 /*
				if ($r_e->eid==$t[0]){
					print '<option value="'.$r_e->ele_id.'" selected>'.$r_e->ele_id.'**'.$r_e->name.'**'.$r_e->unt.'**'.$r_e->gcde.'</option>';}
				else{
				   print '<option value="'.$r_e->ele_id.'">'.$r_e->ele_id.' > '.$r_e->name.'**'.$r_e->unt.'**'.$r_e->gcde.'</option>';}
				*/
				print '<option value="'.$r_e->ele_id.'">'.$r_e->ele_id.'**'.$r_e->name.'**'.$r_e->unt.'**'.$r_e->gcde.'</option>';


				};			  

print '</select><tr style="color:#555555;">
				<td style="width:48%;text-align:right">	
					<label for="lid">Parameter Group</label>	  				
					<td>
					<select style="margin-left:10px" name="gid" id="gid">';
					   while ($r_l=$result_g->fetch_object()){
						   /*
						  if ($r_l->gid==$t[0]){
							  print '<option value="'.$r_l->gid.'" selected>'.'('.$r_l->gid.') '.$r_l->gname.'</option>';}
						  else{
							 print '<option value="'.$r_l->gid.'">'.'('.$r_l->gid.') '.$r_l->gname.'</option>';}
							 */

						print '<option value="'.$r_l->gid.'">'.'('.$r_l->gid.') '.$r_l->gname.'</option>';
					};					

print '</select></td></tr>			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="name">Parameter Name</label>
				</td>				  				
				<td>  
					<input style="width:37%;margin-left:10px" type="text" name="name" id="name"></input>
				</td>				  				

			</tr>			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="name">Unit</label>
				</td>				  				
				<td>  
					<input style="margin-left:10px" type="text" name="unt" id="unt"></input>
				</td>				  				

			</tr>';			
	

			
print	'<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="Update">
				</td>  
			</tr></table>';



  /*
			mysqli_free_result($result_ud);
		mysqli_free_result($result_hse_t);
*/




/*
print '<table style="background-color:white;border-radius:8px;border:none;width:98%;margin-left:1%;">
<tr>
<td  style="border:none;text-align:center;">
    <br>
    <h3 style="display:block;margin-left:1%;padding-right:4px;color:#05CDB9;text-align:left;">&nbsp;刪除紀錄:</h3>
    <hr style="border:0.2px solid grey;">
    <form  method="post" action="">
        刪除
        <select name="category">
            <option value="">-----</option>
            <option value="SEQ_ID">紀錄序號</option>
            <option value="DATE">日期</option>
        </select>
        等於
        <input style="width:15%;"type="text" name="crit">的所有有關紀錄。
        <button type="submit" class="btn btn-default">確定刪除</button>
    </form>
    <br>
</td>
</tr>
</table>';
*/
print'
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="sampletx.php">Home</a></span>';



	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保查詢選項均已輸入!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}


function check_empty($x){
	//print '$x='.$x;
	if ($x==''){
		print "return 0";
		return 0;		
		}else{
			return $x;
		}
		//	print "nothing";
}	




include('templates/footer.html'); 






?>

<script language="javascript">
function ShowMeDate() {
　var Today=new Date();
　alert("今天日期是 " + Today.getFullYear()+ " 年 " + (Today.getMonth()+1) + " 月 " + Today.getDate() + " 日");
}





<script>

