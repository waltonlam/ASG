<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Facility');
include('templates/header.html');
include('templates/iconn.php');

// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];
$_SESSION['trans_type_curr']="FC";


if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
		
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

}


$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	
//	empty($_POST['act_no']) and !empty($_POST['frm_dt']) and !empty($_POST['to_dt']) and !empty($_POST['act_type']) and !empty($_POST['act_name']) and !empty($_POST['target_aud_type'])
//			and !empty($_POST['org_name']) and !empty($_POST['total_party']) and !empty($_POST['loc']
	
	if (!empty($_POST['station']) and !empty($_POST['frm_date']) and !empty($_POST['to_date'])) 
	{
				$criteria = 'CGS: '.$_POST['station']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				if ($_POST['station']=='ALL'){
					$station_id= " and ud.userid='".$_SESSION['vuserid']."'";
				}else{
					$station_id=" and ud.userid='".$_SESSION['vuserid']."' and t.station_id = '".$_POST['station']."' ";
				}
					$q = "select t.trans_date t_trans_date, t.trans_no t_trans_no, t.group_name t_grp_name, t.station_id sid, s.name sname, t.qty t_qty"
					." from fc_trans t, station s, user_district ud where ud.district_id=s.district_id ".$station_id." and t.trans_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."' and t.station_id=s.station_id order by t.trans_date desc, t.trans_no desc;";	
					
					$result_fc=$dbc->query($q);
					if ($result_fc->num_rows){						
						$criteria = $criteria.'<span> =>Total no. of records found: '.$result_fc->num_rows;
					}else{
						
						$criteria = $criteria.'<span class="text--error"> =>No Record has been found!</span>';
					
					}		

					$q = "select t.station_id sid, sum(t.qty) tot_qty"
					." from fc_trans t, station s, user_district ud where ud.district_id=s.district_id ".$station_id." and t.trans_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."' and t.station_id=s.station_id group by t.station_id order by t.trans_date desc, t.trans_no desc;";							
						$result_tot=$dbc->query($q);
						
						//$r_tot=$result_tot->fetch_object(); 					
		}else{
				$criteria = "";
				$comp="false";				
		}

} 
	
	
print '
    <table style="width:100%;margin-left:0%;">
		<th style="background-color:#008F00;color:yellow;font-weight:bold;font-size:28px">設施使用率記錄</th>
  <span style="padding-right:1cm"></span>
  </table>
<form action="fc_trans.php" method="post">
  <div column=2>
  <span style="padding-right:1cm"></span>
  <span style="align:right;color:blue;font-weight:bold;font-size:18px">CGS</span>
  <select name="station" style="font-size:18px">
  <option value="ALL">---ALL---</option>';
  
 	while ($r=$result->fetch_object()){
    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
    print '<option value="'.$r->sid.'">'.'('.$r->sid.') '.$r->sname.'</option>';
  }
  // Free result set
  	mysqli_free_result($result);


/*	
		  <option value="volvo">Volvo</option>
		  <option value="saab">Saab</option>
		  <option value="opel">Opel</option>
		  <option value="audi">Audi</option>
*/

print '</select>';
  print'<span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:30px">由</span>
  <input type="date" name="frm_date" style="size:5">
  <span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:15px">至</span>
  <input type="date" name="to_date">
  <input type="submit">
</form>
</div>
<p>';


if (!empty($criteria)){ 
  print '<span style="color:blue;font-weight:bold">查詢條件: '.$criteria.'</span>'; 
  
    print '<hr>';

    while ($r_tot=$result_tot->fetch_object()){
    	if (!empty($r_tot->sid)){
	    	print '<button id="b1" style="border-radius:20px;height: 50px;width: 100px;font-size:15px;border:none;background-color:#4CAF50;color:white">'.$r_tot->sid.'<br>'.$r_tot->tot_qty.' (人)</button>';
	    }    	
    }
	
    
/*    
    '<button id="b1" style="border-radius:20px;height: 45px;width: 150px;font-size:15px;border:none;background-color:#4CAF50;color:white">玻璃樽(噸)<br>'.$r_tot->tot_glass_bottle_qty.'</br></button>'
		 .'<button id="b1" style="border-radius:20px;height: 45px;width: 130px;font-size:15px;border:none;background-color:#4CAF50;color:white">鋁罐(噸)<br>'.$r_tot->tot_ee_qty.'</br></button>'
			.'<button id="b1" style="border-radius:20px;height: 45px;width: 150px;font-size:15px;border:none;background-color:#4CAF50;color:white">電腦(噸)<br>'.$r_tot->tot_comp_qty.'</br></button>'
			.'<button id="b1" style="border-radius:20px;height: 45px;width: 150px;font-size:15px;border:none;background-color:#4CAF50;color:white">電器(噸)<br>'.$r_tot->tot_battery_qty.'</br></button>'
			.'<button id="b1" style="border-radius:20px;height: 45px;width: 150px;font-size:15px;border:none;background-color:#4CAF50;color:white">廢鐵(噸)<br>'.$r_tot->tot_light_qty.'</br></button>'
			.'<button id="b1" style="border-radius:20px;height: 45px;width: 150px;font-size:15px;border:none;background-color:#4CAF50;color:white">光管、慳電膽(噸)<br>'.$r_tot->tot_paper_qty.'</br></button>'			
			.'<button id="b1" style="border-radius:20px;height: 45px;width: 150px;font-size:15px;border:none;background-color:#4CAF50;color:white">紙類(噸)<br>'.$r_tot->tot_plastic_qty.'</br></button>'				
			.'<button id="b1" style="border-radius:20px;height: 45px;width: 150px;font-size:15px;border:none;background-color:#4CAF50;color:white">塑膠(噸)<br>'.$r_tot->tot_metal_qty.'</br></button>'	;	
*/
}
 
print '<hr>  
  <p></p>  <p></p>  <p></p>
';

//欄位header
    echo '<table style="width:100%;margin-left:0%;">
			<tr style="text-align:center;color:#555555;">
			<th style="background-color:#008F00;color:white">紀錄序號</th>
			<th style="background-color:#008F00;color:white">CGS</th>
			<th style="background-color:#008F00;color:white">日期</th>
<!-- 			<th style="background-color:#008F00;color:white">團體名稱</th>  -->
			<th style="background-color: #008A8A;color:white">人流數量</th>
			<th style="background-color: #B87800;color:white">刪除</th>			
			</tr>';
			if (!empty($criteria)){ 
					while ($r_fc=$result_fc->fetch_object()){
						    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
					    
						    $param1=$r_fc->t_trans_no.'|'.$r_fc->t_trans_date.'|'.$r_fc->sid.'|'.$r_fc->sname.'|'.$r_fc->t_qty;
/* 
 								if (!empty($r_ot->ot_recp_no)){
										$param1=$param1.$r_ot->ot_recp_no;} 
										else{ 
											$param1=$param1.'@';} 
*/

/*
	    					print '<tr style="text-align:center;color:#555555;"><td style="background-color:#00B386;color:white">'.$r_ot->e_trans_no
	    					//.'</td><td style="background-color:#00B386;color:white">'.$r_st->trans_date.'</td>'
	    					.'<td style="background-color:#00B386;color:white">'.$r_ot->e_act_no
	    					.'<td style="background-color:#00B386;color:white"><a href="update.php?cluster='.$r_ot->e_trans_no.'|'.$r_ot->e_act_no.'|'.$r_ot->e_trans_date.'|'.$r_ot->e_frm_dt.'|'.$r_ot->e_to_dt
	    					.'|'.$r_ot->e_act_type.'|'.$r_ot->e_act_name.'|'.$r_ot->e_target_aud_type.'|'.$r_ot->e_org_name.'|'.$r_ot->e_total_party.'|';
	    						    					
								$timestamp1 = strtotime($r_ot->e_frm_dt);
								$timestamp2 = strtotime($r_ot->e_to_dt);
								$hour = abs($timestamp2 - $timestamp1)/(60*60);
								 
	    					print $hour.'|'.$r_ot->e_loc.'|'.$r_ot->e_spk.'|'.(($r_ot->e_to_dt - $r_ot->e_frm_dt)*$r_ot->e_total_party).'|'.$r_ot->e_rmk.'">'.$r_ot->e_trans_date.'</a></td>'
*/


								print '<tr style="text-align:center;color:#555555;"><td style="background-color:#00B386;color:white">'.$r_fc->t_trans_no
	    					//.'</td><td style="background-color:#00B386;color:white">'.$r_st->trans_date.'</td>'
	    					.'<td style="background-color:#00B386;color:white">'.$r_fc->sname
	    					.'<td style="background-color:#00B386;color:white"><a href="update_fc.php?cluster='.$param1.'">'.$r_fc->t_trans_date.'</a></td>'	    					
	    					//.'</td><td style="background-color:#00B386;color:white">'.$r_fc->t_grp_name
	    					.'</td><td style="background-color:#00ADB3;color:white">'.$r_fc->t_qty    					
								.'</td><td style="background-color:#B5B5B5"><a href="delete_fc.php?cluster='.$param1;							
								print '"><img src="img/del.png"  width="20" height="20" value='.$r_fc->t_trans_no.'></a></td></tr>';
	  					}
  				mysqli_free_result($result_fc);
  			}
			echo '</table>';

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
  <br></br><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span>
  <span style="color:blue;font-weight:bold;"><a href="addfc.php">新增設施使用率記錄</a></span>
';



	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保查詢選項均已輸入!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}


include('templates/footer.html'); // Need the footer.


?>