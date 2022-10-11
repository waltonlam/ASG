<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'New Outlet');
include('templates/header.html');
include('templates/iconn.php');



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


print '<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
function fetch_select(val)
{

  document.getElementById("new_select").innerHTML="fk"; 
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


// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];

if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
		
					$q = "select d.district_id did, d.name dname, s.station_id sid, s.name sname 
								from user_district ud, district d, station s 
								where ud.district_id = d.district_id and s.district_id=ud.district_id and ud.userid = '".$_SESSION['vuserid']."'";

					//print $q;
					$result_ud=$dbc->query($q);
					if (!$result_ud->num_rows){
							print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
							exit();
					}				


					$q="select * from outlet;";
					$result_o=$dbc->query($q);
					if (!$result_o->num_rows){
							print '<p class="text--error">Outlet Configuration Empty! Please configure it before try again.</p>';
							exit();
					}				

/*
					$q="select * from out_prg;";
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
		

$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['trans_date']) and !empty($_POST['qty']) and !empty($_POST['unit_price']) and !empty($_POST['recp_no'])) 	
	{
				//$criteria = '屋苑/機構: '.$_POST['house']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				
				$q = "insert into out_trans (trans_date,station_id,prg_id,outlet_id,qty,unit_price,recp_no) 
				values ('".date('Y/m/d', strtotime($_POST['trans_date']))."','".$_POST['district_id']
						."','".$_POST['new_outlet_prg']."','".$_POST['oid']."',".$_POST['qty']
						.",".$_POST['unit_price'].",'".$_POST['recp_no']."');";


					//date('Y/m/d', $_SESSION['trans_date'])      
					print $q;

					if ($dbc->query($q) === TRUE) {
					    echo "New record created successfully";
					} else {
					    echo "Error: " . $q . "<br>" . $dbc->error;
					};

		}else{
				$criteria = "";
				$comp="false";				
		}


} 
	


print '
<form onsubmit="submitForm()" action="addout.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">新增送往下游回收商記錄</p>
      
      <table style="width:92%;margin-left:2%;">			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="district_id">區域</label>
				</td>				  
				<td>				  				  
				  <select style="margin-left:10px" name="district_id">';
				 	while ($r_ud=$result_ud->fetch_object()){
				    print '<option value="'.$r_ud->sid.'">'.'('.$r_ud->sid.') '.$r_ud->sname.'</option>';
				  }			
			
print	'</td><tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">送往日期</label>
				</td>				  
				
				<td>  
				  <input style="margin-left:10px" type="date" name="trans_date" id="trans_date"></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="oid">回收商名稱</label>
				</td>
				<td><select style="margin-left:10px" name="oid" onchange="showprg(this.value)">';
				 	while ($r_o=$result_o->fetch_object()){
				    print '<option value="'.$r_o->outlet_id.'">'.'('.$r_o->outlet_id.') '.$r_o->name.'</option>';
				  }			
				  
				  
print '</select></td>				 	
			</tr><tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="prg_id">回收物種類</label>
				</td>
				<td>';

print '<select style="margin-left:10px" name="new_outlet_prg" id="new_outlet_prg"></select>';
 				  
 				  
/*
				<select style="margin-left:10px" name="prg_id">';
				 	while ($r_op=$result_op->fetch_object()){
				    print '<option value="'.$r_op->prg_id.'">'.'('.$r_op->prg_id.') '.$r_op->name.'</option>';
				  }					  
*/

				  
print '</td>
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="qty">回收物數量(噸)</label>
				</td>
				<td>
				 	<input style="margin-left:10px"  type="number" step="0.00001"  min="0.00001" max="5000" name="qty" id="qty"></input>				 	
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="unit_price">單價</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.001"  min="0.001" max="50000" name="unit_price" id="unit_price"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="recp_no">收據編號</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="text" name="recp_no" id="recp_no"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="新增">
				</td>  
			</tr></table>';


  // Free result set
  	mysqli_free_result($result_o);
  	mysqli_free_result($result_ud);





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
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span>
';



	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保選項均已輸入!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}






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
　var Today=new Date();
　alert("今天日期是 " + Today.getFullYear()+ " 年 " + (Today.getMonth()+1) + " 月 " + Today.getDate() + " 日");
}


<script>

