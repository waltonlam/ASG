<?php 
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Outlet Trans');
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


$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['trans_date']) and !empty($_POST['qty']) and !empty($_POST['unit_price']) and !empty($_POST['recp_no'])) 	
	
	{
				//$criteria = '屋苑/機構: '.$_POST['house']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				
			  //$q = "select ot.trans_no ot_trans_no,ot.district_id ot_did,d.name dname,ot.trans_date ot_trans_date,ot.prg_id ot_prg_id,op.name op_name,o.outlet_id oid,o.name o_name,ot.qty ot_qty, ot.unit_price ot_unit_price,ot.recp_no ot_recp_no "

				
	

//						    $param1=$r_ot->ot_trans_no.'|'.$r_ot->ot_sid.'|'.$r_ot->sname.'|'.$r_ot->ot_trans_date.'|'.$r_ot->oid.'|'.$r_ot->o_name.'|'.$r_ot->ot_prg_id
//	    					.'|'.$r_ot->pc_name.'|'.$r_ot->ot_qty.'|'.$r_ot->ot_unit_price.'|';

				$u= "update out_trans set "
				."trans_date='".$_POST['trans_date']."',station_id='".$_POST['sid']."',outlet_id='".$_POST['new_outlet_prg']."',prg_id='".$_POST['new_prg']."',qty=".$_POST['qty']
				.",unit_price=".$_POST['unit_price'].",recp_no='".$_POST['recp_no']."'"
				." where trans_no=".$_POST['trans_no'];
				
				/*
				."trans_date='".$_POST['trans_date']."',source_id='".$_POST['source_id']."',glass_bottle_qty=".check_empty($_POST['glass_bottle_qty']).",ee_qty=".check_empty($_POST['ee_qty']).",comp_qty=".check_empty($_POST['comp_qty'])
				.",battery_qty=".check_empty($_POST['battery_qty']).",light_qty=".check_empty($_POST['light_qty']).",paper_qty=".check_empty($_POST['paper_qty']).",plastic_qty=".check_empty($_POST['plastic_qty'])
				.",metal_qty=".check_empty($_POST['metal_qty']).",toner_qty=".check_empty($_POST['toner_qty']).",clothes_qty=".check_empty($_POST['clothes_qty']).",book_qty=".check_empty($_POST['book_qty']).",toy_qty=".check_empty($_POST['toy_qty'])
				.",other_desc='".$_POST['other_desc']."',other_qty=".check_empty($_POST['other_qty'])." where trans_no=".$t[0];	
				*/
				
					//date('Y/m/d', $_SESSION['trans_date'])      

					if ($dbc->query($u) === TRUE) {
					    echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">記錄已成功更新</p>
					    <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="out_trans.php">Back</a></span>';
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

							 $token = strtok($_REQUEST['cluster'], "|");
							 
								$i=0; 
								while ($token !== false)
							   {
							   	 $t[$i]=$token;
							   //echo "$token<br>";
								   $token = strtok("|");
							  	 $i++;
							   }
							if ($t[10]=="@"){$t[10]="";} 
							   
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
					
					$q="select * from prg_cat";
					$result_pc=$dbc->query($q);
					if (!$result_pc->num_rows){
							print '<p class="text--error">Prgram Category Configuration Empty! Please configure it before try again.</p>';
							exit();
					}			
					
					

					$q="select op.outlet_id oid,o.name oname from out_prg op left join outlet o on op.outlet_id=o.outlet_id where op.prg_id='".$t[6]."'";
					$result_op=$dbc->query($q);
					if (!$result_op->num_rows){
							print '<p class="text--error">Outlet Program Configuration Empty! Please configure it before try again.</p>';
							exit();
					}			

						//$q = "select * from user_acc where userid = ? and pwd= ?";
						//$stmt = $db->prepare($q);
						//$stmt->bind_param('ss',$_POST['userid'], $_POST['pwd']);
						//$stmt->execute();
						//mysqli_query($dbc, $q);			

				}
				
								
print '
<form action="update_otrans.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">更新回收物送往下游回收商記錄</p>
      
      <table style="width:92%;margin-left:2%;">
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="sid">CGS</label>
				  <input style="margin-left:10px" type="hidden" name="trans_no" id="trans_no" value='.$t[0].'></input>
				</td>				  				
				<td>  
				  <select style="margin-left:10px" name="sid" value="'.$t[1].'">';
				 	while ($r_ud=$result_ud->fetch_object()){
				 		if ($r_ud->sid==$t[1]){
				 			print '<option value="'.$r_ud->sid.'" selected>'.'('.$r_ud->sid.') '.$r_ud->sname.'</option>';
				 		}else{
				 			print '<option value="'.$r_ud->sid.'">'.'('.$r_ud->sid.') '.$r_ud->sname.'</option>';
				 		}
				  }	
print	'</td></tr>			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">送往下游日期</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="date" name="trans_date" id="trans_date" value='.$t[3].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">回收物種類</label>
				</td>				  				
				<td>';  
print '<select style="margin-left:10px" name="new_prg" id="new_prg" onchange="showprg(this.value)" value="'.$t[6].'">';
				 	while ($r_pc=$result_pc->fetch_object()){
				 		if ($r_pc->prg_id==$t[6]){
				 			print '<option value="'.$r_pc->prg_id.'" selected>'.'('.$r_pc->prg_id.') '.$r_pc->name.'</option>';
				 		}else{
				 			print '<option value="'.$r_pc->prg_id.'">'.'('.$r_pc->prg_id.') '.$r_pc->name.'</option>';
				 		}
				  }			

print	'</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">回收商名稱</label>
				</td>				  				
				<td>  
				  <select style="margin-left:10px" name="new_outlet_prg" id="new_outlet_prg" value="'.$t[4].'">';
				 	while ($r_op=$result_op->fetch_object()){
				 		if ($r_op->oid==$t[4]){
				 			print '<option value="'.$r_op->oid.'" selected>'.'('.$r_op->oid.') '.$r_op->oname.'</option>';
				 		}else{
				 			print '<option value="'.$r_op->oid.'">'.'('.$r_op->oid.') '.$r_op->oname.'</option>';
				 		}
				  }	
		  				  
print '</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="qty">回收物數量(噸)</label>
				</td>				  				
				<td>  
				 	<input style="margin-left:10px"  type="number" step="0.00001"  min="0.00001" max="5000" name="qty" id="qty" value='.$t[8].'></input>';				
print	'</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="unit_price">單價</label>
				</td>				  				
				<td>  
				 	<input style="margin-left:10px" type="number" step="0.001"  min="0.001" max="50000" name="unit_price" id="unit_price" value='.$t[9].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="target_aud_type">收據編號</label>
				</td>				  				
				<td>  
				 	<input style="margin-left:10px" type="text" name="recp_no" id="recp_no" value="'.$t[10].'"></input>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="更新">
				</td>  
			</tr></table>';




  	mysqli_free_result($result_ud);
  //	mysqli_free_result($result_dst);





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
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="out_trans.php">Back</a></span>
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

