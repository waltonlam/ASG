﻿<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'HousingMaster');
include('templates/header.html');
include('templates/iconn.php');

// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];
/*
print '<script>
function collect() {

alert(document.getElementById("trans_date").innerHTML)';
//$A=document.getElementById("trans_date").innerHTML;
$Mixed = array("trans_date" => document.getElementById("trans_date").innerHTML, "phone" => '65436576');
//$Mixed = array("name" => $A, "phone" => '65436576');
$Text = json_encode($Mixed);
$RequestText = urlencode($Text);

print '}
</script>';
*/

print '
<script type="text/javascript">
function collect2() {
    var $row = $(this).closest("tr");    // Find the row
    var $text = $row.find(".nr").text(); // Find the text
    
    // Lets test it out
    alert($text);
}
</script>';


$_SESSION['trans_type_curr']="HSE";


if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
					$q = "select d.district_id did, d.name dname, h.house_id hid, h.name hname, ht.name htname 
									from user_district ud, district d, housing h, house_type ht 
									where ht.house_type_id = h.house_type_id and h.district_id = ud.district_id and ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";
					$result=$dbc->query($q);
					//print $q;
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
	if (!empty($_POST['house'])) 
	{
				//$criteria = '屋苑/機構: '.$_POST['house']. ';  由: '.$_POST['frm_house_type'].' 至: '.$_POST['to_house_type'];
				$criteria = '屋苑/機構: '.$_POST['house'];
				if ($_POST['house']=='ALL'){
					$house_id= " and ud.userid='".$_SESSION['vuserid']."'";
				}else{
					$house_id=" and ud.userid='".$_SESSION['vuserid']."' and h.house_id = '".$_POST['house']."' ";
				}
/*
					$q = "select ht.name htname, h.district_id did, h.house_id hid, house_type_id htid, h.name hname,h.address haddr "
					." from house_type ht, housing h, user_district ud where ud.district_id=h.district_id".$house_id." and h.house_type_id between '".$_POST['frm_house_type']."' and '".$_POST['to__house_type']."' order by h.house_id, house_type_id ;";
*/
					$q = "select ht.name htname, h.district_id did, h.house_id hid, h.house_type_id htid, h.name hname,h.address haddr "
					." from house_type ht, housing h, user_district ud where h.house_type_id=ht.house_type_id and ud.district_id=h.district_id".$house_id." order by h.house_id, h.house_type_id ;";

					$result_hse=$dbc->query($q);
					if ($result_hse->num_rows){						
						$criteria = $criteria.'<span> =>Total no. of records found: '.$result_hse->num_rows;
						
						
					}else{
						
						$criteria = $criteria.'<span class="text--error"> =>No Record has been found!</span>';
					
					}					
		}else{
				$criteria = "";
				$comp="false";				
		}

} 
	



print '	
<form action="house_m.php" method="post">

    <table style="width:100%;margin-left:0%;">
		<th style="background-color:#008F00;color:yellow;font-weight:bold;font-size:28px">屋苑/機構資料維護</th>
  <span style="padding-right:1cm"></span>
  </table>

  <div column=2>
  <span style="padding-right:1cm"></span>
  <span style="align:right;color:blue;font-weight:bold;font-size:18px">屋苑/機構</span>
  <select name="house" style="font-size:18px">
  <option value="ALL">---ALL---</option>';
  
 	while ($r=$result->fetch_object()){
    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
    print '<option value="'.$r->hid.'">'.'('.$r->htname.') '.$r->hname.'</option>';
  }


/*	
		  <option value="volvo">Volvo</option>
		  <option value="saab">Saab</option>
		  <option value="opel">Opel</option>
		  <option value="audi">Audi</option>
*/



print '</select>';
/*
  print'<span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:30px">由</span>
  <input type="date" name="frm_date" style="size:5">
  <span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:15px">至</span>
  <input type="date" name="to_date">
*/
	print  '<input type="submit">
</form>
</div>
<p>';


/*
						$q="select sum(t.glass_bottle_qty) tot_glass_bottle_qty, sum(t.ee_qty) tot_ee_qty, sum(t.comp_qty) tot_comp_qty, sum(t.battery_qty) tot_battery_qty, sum(t.light_qty) tot_light_qty,
						
						             sum(t.paper_qty) tot_paper_qty,sum(t.plastic_qty) tot_plastic_qty, sum(t.metal_qty) tot_metal_qty, sum(t.toner_qty) tot_toner_qty, sum(t.clothes_qty) tot_clothes_qty,
						             sum(t.book_qty) tot_book_qty, sum(t.toy_qty) tot_toy_qty, sum(t.other_qty) tot_other_qty "
*/
if (!empty($criteria)){ 

  print '<span style="color:blue;font-weight:bold">查詢條件: '.$criteria.'</span>'; 
 //玻璃樽(kg)	電器(kg)	電腦及相關用品(kg)	充電池(kg)	光管及慳電膽(kg)	廢紙(kg)	塑膠廢料(kg)	金屬(kg)	碳粉盒(kg)	衣物(kg)	書本(kg)	玩具	其他
 /*
print '<hr><p></p><p style="font-weight:bold">累計:</p>[玻璃樽(kg) = '.$r_tot->tot_glass_bottle_qty.']    [電器(kg) = '.$r_tot->tot_ee_qty.']  [電腦及相關用品(kg) = '.$r_tot->tot_comp_qty.']
      [充電池(kg) = '.$r_tot->tot_battery_qty.'][光管及慳電膽(kg) = '.$r_tot->tot_battery_qty.'][光管及慳電膽(kg) = '.$r_tot->tot_light_qty.'][廢紙(kg) = '.$r_tot->tot_paper_qty.'][塑膠廢料(kg) = '.$r_tot->tot_plastic_qty
      .']<p></p>[金屬(kg) = '.$r_tot->tot_metal_qty.'][碳粉盒(kg) = '.$r_tot->tot_toner_qty.'][衣物(kg) = '.$r_tot->tot_clothes_qty.'][書本(kg) = '.$r_tot->tot_book_qty.'][玩具(kg) = '.$r_tot->tot_toy_qty.'][其他(kg) = '.$r_tot->tot_other_qty.']</p>';
*/

			
}

print '<hr> 
  <p></p>  <p></p>  <p></p>
';

    echo '<table style="width:100%;margin-left:0%;">
			<tr style="text-align:center;color:#555555;">
			<th style="background-color:#008F00;color:white">區域</th>
			<th style="background-color:#008F00;color:white">屋苑/機構(編號)</th>
			<th style="background-color:#008F00;color:white">屋苑/機構(名稱)</th>
			<th style="background-color: #008A8A;color:white">屋苑類別(編號)</th>
			<th style="background-color: #008A8A;color:white">屋苑類別(名稱)</th>
			<th style="background-color: #008A8A;color:white">地址</th>
			<th style="background-color: #008A8A;color:white">刪除</th>
			</tr>';
			if (!empty($criteria)){ 
					while ($r_hse=$result_hse->fetch_object()){
						    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
	    					print '<tr style="text-align:center;color:#555555;">'
	    					.'<td style="background-color:#00B386;color:white"><a href="update_hse_m.php?cluster='.$r_hse->did.'|'.$r_hse->hid.'|'.$r_hse->hname.'|'.$r_hse->htid.'|'.$r_hse->htname.'|'.$r_hse->haddr.'">'.$r_hse->did.'</a></td>'
	    					.'<td style="background-color:#00B386;color:white">'.$r_hse->hid.'</td><td id="trans_date" style="background-color:#00B386;color:white">'.$r_hse->hname.'</td><td style="background-color:#00ADB3;color:white">'.$r_hse->htid.'</td>'
	    					.'<td style="background-color:#00ADB3;color:white">'.$r_hse->htname.'</td><td style="background-color:#EB6E80;color:white">'.$r_hse->haddr.'</td>'
								.'<td style="background-color:#B5B5B5"><a href="del_hse_m.php?cluster='.$r_hse->did.'|'.$r_hse->hid.'|'.$r_hse->hname.'|'.$r_hse->htid.'|'.$r_hse->htname.'|'.$r_hse->haddr.'"><img src="img/del.png"  width="20" height="20" value='.$r_hse->hid.'></a></td></tr>';
															
/*								
								//iif(!empty($r_hse->other_desc), $r_hse->other_desc, '@'); 
								if (!empty($r_hse->t_other_desc)){
										print $r_hse->t_other_desc;} 
										else{ 
											print '@';} 
*/
			
	  					}
	  					
		 				mysqli_free_result($result_hse);
		 				mysqli_free_result($result);
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
  <span style="color:blue;font-weight:bold;"><a href="hse_addnew.php">新增屋苑/機構</a></span>
';




	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保查詢選項均已輸入!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}


include('templates/footer.html'); // Need the footer.


?>

