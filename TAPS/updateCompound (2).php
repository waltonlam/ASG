<?php 
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Update Compound');
include ('iconn.php');
include 'header2.php';

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
	var e = document.getElementById("cid");
	var str = e.options[e.selectedIndex].innerHTML;
	var info = str.split("**");

	var grp = document.getElementById("gid");
	grp.options[grp.options.selectedIndex].selected = true;

	document.getElementById("cp_name").value = info[1];
	document.getElementById("gid").value = info[2];
	document.getElementById("who_tef").value = info[3];
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
	if (!empty($_POST['gid']) and !empty($_POST['cid']))	
		{
				$u= "update compound set "
				."name='".$_POST['cp_name']."',who_tef=".$_POST['who_tef'].",code='".$_POST['gid']."'"
				." where id='".$_POST['cid']."';";

				//$dbc->autocommit(FALSE);

					if ($dbc->query($u) === TRUE) {
					   // $updated=TRUE;
					    echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">Compound has been updated</p>
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
	
$l = "select id as gid, item 
from category;";
//print $q;
$result_g=$dbc->query($l);
if (!$result_g->num_rows){
print '<p class="text--error">'.'Compound Group Configuration Error!</p>';
exit();
}		
						
$l = "select * from compound;";
//print $q;
$result_cp=$dbc->query($l);
if (!$result_cp->num_rows){
print '<p class="text--error">'.'Compound Configuration Error!</p>';
exit();
}		




print '<body onload="Showparam()"><h2>Update Compound Info.</h2><div id="main-content"><br>
<form action="updateCompound.php" method="post">
	  <table>';	
print '<tr style="color:#555555;">
	  <td>	
		  <label for="cid">Compound</label>	  				
		  <td>
		  <select style="margin-left:10px;width:100%" name="cid" id="cid" onchange="Showparam()">';
			 while ($r_cp=$result_cp->fetch_object()){
				 /*
				if ($r_e->eid==$t[0]){
					print '<option value="'.$r_e->ele_id.'" selected>'.$r_e->ele_id.'**'.$r_e->name.'**'.$r_e->unt.'**'.$r_e->gcde.'</option>';}
				else{
				   print '<option value="'.$r_e->ele_id.'">'.$r_e->ele_id.' > '.$r_e->name.'**'.$r_e->unt.'**'.$r_e->gcde.'</option>';}
				*/
				print '<option value="'.$r_cp->id.'">'.$r_cp->id.'**'.$r_cp->name.'**'.$r_cp->code.'**'.$r_cp->who_tef.'</option>';


				};			  

print '</select></td><tr style="color:#555555;">
				<td>	
					<label for="gid">Compound Group</label>	  				
					<td>
					<select style="margin-left:10px" name="gid" id="gid">';
					   while ($r_l=$result_g->fetch_object()){
						   /*
						  if ($r_l->gid==$t[0]){
							  print '<option value="'.$r_l->gid.'" selected>'.'('.$r_l->gid.') '.$r_l->gname.'</option>';}
						  else{
							 print '<option value="'.$r_l->gid.'">'.'('.$r_l->gid.') '.$r_l->gname.'</option>';}
							 */

						print '<option value="'.$r_l->gid.'">'.$r_l->gid.'**'.$r_l->item.'</option>';
					};					

print '</select></td></tr>			
			<tr style="color:#555555;">
				<td>
				  <label for="name">Compound Name</label>
				</td>				  				
				<td>  
					<input style="width:100%;margin-left:10px" type="text" name="cp_name" id="cp_name"></input>
				</td>				  				

			</tr>			
			<tr style="color:#555555;">
				<td>
				  <label for="name">WHO TEF</label>
				</td>				  				
				<td>  
					<input style="margin-left:10px" type="number" step="0.01" name="who_tef" id="who_tef"></input>
				</td>				  				

			</tr>';			
	

			
print	'<tr style="color:#555555;">
				<td>
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="Update">
				</td>  
			</tr></table></form></div></body>';



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

