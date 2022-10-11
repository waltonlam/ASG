<?php 
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'User Account');
include('templates/header.html');
include('templates/iconn.php');


print '<script type="text/javascript">
    document.getElementById("cancel").onclick = function () {
        location.href = "home.php";
    }';


print '<script>function check_num() {
    alert(document.getElementById("other_qty").innerHTML);
		    document.getElementById("other_qty").focus();
		return false;

		if (document.getElementById("other_qty").innerHTML<>""){
			if (!is_numeric(document.getElementById("other_qty").innerHTML)){
		    alert("請輸入數字");
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
	if (isset($_POST['userid']) and isset($_POST['did']) and isset($_POST['first_name']) and isset($_POST['last_name']) and isset($_POST['pwd']))		
		{
				$u= "update user_acc set "
				."trans_date='".date('Y/m/d')."',first_name='".$_POST['first_name']."',last_name='".$_POST['last_name']."',pwd='".$_POST['pwd']."'"
				." where userid='".$_POST['userid']."';";
				
				$dbc->autocommit(FALSE);

					if ($dbc->query($u) === TRUE) {
						
						 $u="delete from user_district where userid='".$_POST['userid']."';";
						 
							if ($dbc->query($u) === TRUE) {
									 foreach($_POST['did'] as $did_list) { 
								 	   $u = "insert into user_district (trans_date,userid,district_id) 
													values ('".date('Y/m/d')."','".$_POST['userid']
													."','".$did_list."');";
																										
				  						if ($dbc->query($u) === FALSE) {
											    echo "Error: " . $u . "<br>" . $dbc->error;
											    exit();
														}
											}
							}else{
								    echo "Error: " . $u . "<br>" . $dbc->error;
								    exit();
												};

					    $dbc->autocommit(TRUE);
					   // $updated=TRUE;
					    echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">記錄已成功更新</p>
					    <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="ua.php">Back</a></span>';
					} else {
					    echo "Error: " . $u . "<br>" . $dbc->error;
									};
		}else{
				print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
				$criteria = "";
				$comp="false";				

				}
		exit();
		
} 



if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
					if (empty($_REQUEST['cluster'])) {
							print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		

					}else{

								print $_REQUEST['cluster'].'<p></p>';
							 $token = strtok($_REQUEST['cluster'], "|");
							 
								$i=0; 
								while ($token !== false)
							   {
							   	 $t[$i]=$token;
							   //echo "$token<br>";
								   $token = strtok("|");
							  	 $i++;
							   }
							if ($t[4]=="@"){$t[4]="";} 
							   
							   //exit();
							   							
							 //  print "-".$t[17]."-";
							   														   							
						//	$u=0;
							//print '<p></p>';
						//	while ($u < sizeof($t)){
						//		print $t[$u].'<br>';
						//		$u++;
					//		}
	
					}
		
					
					
					
					$q = "select d.district_id did, d.name dname, s.station_id sid, s.name sname 
							from user_district ud, district d, station s 
						where ud.district_id = d.district_id and ud.district_id=s.district_id and ud.userid = '".$_SESSION['vuserid']."'";

					//print $q;
					$result_ud=$dbc->query($q);
					if (!$result_ud->num_rows){
							print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
							exit();
					}				

/*
select ud.userid,d.district_id, d.name
from district d left join (select ud.userid, ud.district_id
							from user_district ud where ud.userid='tsw') ud
on d.district_id=ud.district_id
*/
					$q = "select ud.userid uid,d.district_id did, d.name dname
									from district d left join (select ud.userid, ud.district_id
																							from user_district ud where ud.userid='".$t[1]."') ud on d.district_id=ud.district_id order by region_code,d.district_id;";
									
					$result_rd=$dbc->query($q);
					if (!$result_rd->num_rows){
							print '<p class="text--error">'.'District Configuration Problem!</p>';
							exit();
					}				


/*
					$q="select * from user_acc;";
					$result_ua=$dbc->query($q);
					if (!$result_ua->num_rows){
							print '<p class="text--error">Outlet Configuration Empty! Please configure it before try again.</p>';
							exit();
					}		
*/
					
						
/*
					$q="select op.prg_id op_id,pc.name pc_name from out_prg op left join prg_cat pc on op.prg_id=pc.prg_id where outlet_id='".$t[4]."'";
					print $q;
					$result_op=$dbc->query($q);
					if (!$result_op->num_rows){
							print '<p class="text--error">Outlet Program Configuration Empty! Please configure it before try again.</p>';
							exit();
					}			
					
*/					
					

						//$q = "select * from user_acc where userid = ? and pwd= ?";
						//$stmt = $db->prepare($q);
						//$stmt->bind_param('ss',$_POST['userid'], $_POST['pwd']);
						//$stmt->execute();
						//mysqli_query($dbc, $q);			

				}
				
	
	
/*
						    $param1=$r_ua->u_uid.'|'.$r_ua->u_fname.'|'.$r_ua->u_lname.'|'.$r_ua->u_pwd.'|';
 								if (!empty($r_ua->u_email)){
										$param1=$param1.$r_ua->u_email;} 
										else{ 
											$param1=$param1.'@';} 
*/							
print '
<form action="update_ua.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">更新用戶記錄</p>
      
      <table style="width:92%;margin-left:2%;">
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="userid">用戶編碼</label>
				</td>		
				<td><input style="margin-left:10px" type="hidden" name="trans_date" id="trans_date" value="'.$t[0].'"></input>
						<input style="margin-left:10px" type="text" name="userid" id="userid" value="'.$t[1].'"></input>
				</td></tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">	
					<label for="first_name">姓氏</label>	  				
				<td>  
					<input style="margin-left:10px" type="text" name="first_name" id="first_name" value="'.$t[2].'"></input>
</td></tr>			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="last_name">名字</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="text" name="last_name" id="last_name" value="'.$t[3].'"></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="pwd">密碼</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="password" name="pwd" id="pwd" value="'.$t[4].'"></input>
		</td>				  				
			</tr>
			
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>

			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="did[]">負責地區</label>
				</td>
				<td>';
					$i=1;
			   	while ($r=$result_rd->fetch_object()){
			   		if (!empty($r->uid)){
						    print '<input style="margin-left:20px" type="checkbox" name="did[]" id="did[]" checked value="'.$r->did.'">('.$r->did.') '.$r->dname.'</option>';
						  	if ($i==6){print '<br><br>'; $i=0;}
						    $i++;
						 }else{
						   	print '<input style="margin-left:20px" type="checkbox" name="did[]" id="did[]" value="'.$r->did.'">('.$r->did.') '.$r->dname.'</option>';
						  	if ($i==6){print '<br><br>'; $i=0;}
						    $i++;
						  }
  				}
print '</td>				 	
			</tr>				
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="更新">
				</td>  
			</tr></table>';




  	mysqli_free_result($result_ud);
		mysqli_free_result($result_rd);





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
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="ua.php">Back</a></span>
';



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




include('templates/footer.html'); // Need the footer.






?>

<script language="javascript">
function ShowMeDate() {
　var Today=new Date();
　alert("今天日期是 " + Today.getFullYear()+ " 年 " + (Today.getMonth()+1) + " 月 " + Today.getDate() + " 日");
}


<script>

