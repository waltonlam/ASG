<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Housing');
include('templates/header.html');
include('templates/iconn.php');

// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];
$_SESSION['trans_type_curr']="";


if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
					$q = "select *
									from user_acc 
									where role='admin' and userid = '".$_SESSION['vuserid']."'";
					$result=$dbc->query($q);
					if (!$result->num_rows){
							print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for district maintenance!</p>';
							mysqli_free_result($result);
							exit();
					}				
  				mysqli_free_result($result);


					$q = "select region_code rcode, name from region"; 
					$result_r = $dbc->prepare($q);
					if (!$result_r->num_rows){
							print '<p class="text--error">'.'No Region Found!</p>';
							exit();
					}		

					$q = "select d.district_id did, d.name dname from district"; 
					$result_d = $dbc->prepare($q);
					if (!$result_d->num_rows){
							print '<p class="text--error">'.'No District Found!</p>';
							exit();
					}		

						
			}


$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['region_code']) and !empty($_POST['district_id'])) 
	{
				$criteria = 'Region: '.$_POST['region_code']. ';  District: '.$_POST['district_id'];
				if ($_POST['region_code']=='ALL'){
					$region_code= "";
				}else{
					$region_code=" where region_code='".$_POST['region_code']."'";
				}

				if ($_POST['district_id']=='ALL'){
					$district_id= "";
				}else{
					$district_id=" and district_id='".$_POST['district_id']."'";					
				}
				
					$q = "select * from district ".$region_code.$district_id;
					print $q;
				
					$result_st=$dbc->query($q);
					if ($result_st->num_rows){						
						$criteria = $criteria.'<span> =>Total no. of records found: '.$result_st->num_rows;
					}else{
						
						$criteria = $criteria.'<span class="text--error"> =>No Record has been found!</span>';
					
					}					
		}else{
				$criteria = "";
				$comp="false";				
		}

} 
	



print '
<form action="district.php" method="post">
  <div column=2>
  <span style="padding-right:1cm"></span>
  <span style="align:right;color:blue;font-weight:bold;font-size:18px">Region</span>
  <select name="region" style="font-size:18px">
  <option value="ALL">---ALL---</option>';
  
 	while ($r=$result->fetch_object()){
    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
    print '<option value="'.$r->region_code.'">'.'('.$r->rname.') '.$r->rname.'</option>';
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
}
 
print '<hr>  
  <p></p>  <p></p>  <p></p>
';

//欄位header
    echo '<table style="width:100%;margin-left:0%;">
			<tr style="text-align:center;color:#555555;">
			<th style="background-color:#008F00;color:white">紀錄序號</th>
			<th style="background-color:#008F00;color:white">日期</th>
			<th style="background-color:#008F00;color:white">CGS(編號)</th>
			<th style="background-color:#008F00;color:white">CGS(名稱)</th>
			<th style="background-color: #008A8A;color:white">玻璃樽(kg)</th>
			<th style="background-color: #008A8A;color:white">電器(kg)</th>
			<th style="background-color: #008A8A;color:white">電腦及相關用品(kg)</th>
			<th style="background-color: #008A8A;color:white">充電池(kg)</th>
			<th style="background-color: #008A8A;color:white">光管及慳電膽(kg)</th>
			<th style="background-color: #FF5959;color:white">廢紙(kg)</th>
			<th style="background-color: #FF5959;color:white">塑膠廢料(kg)</th>
			<th style="background-color: #FF5959;color:white">金屬(kg)</th>
			<th style="background-color: #B87800;color:white">碳粉盒(kg)</th>
			<th style="background-color: #B87800;color:white">衣物(kg)</th>
			<th style="background-color: #B87800;color:white">書本(kg)</th>
			<th style="background-color: #B87800;color:white">玩具</th>
			<th style="background-color: #B87800;color:white">其他</th>
			<th style="background-color: #B87800;color:white">註明</th>
			<th style="background-color: #B87800;color:white">刪除</th>
			</tr>';
			if (!empty($criteria)){ 
					while ($r_st=$result_st->fetch_object()){
						    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
	    					print '<tr style="text-align:center;color:#555555;"><td style="background-color:#00B386;color:white">'.$r_st->trans_no
	    					//.'</td><td style="background-color:#00B386;color:white">'.$r_st->trans_date.'</td>'
	    					
	    					.'<td style="background-color:#00B386;color:white"><a href="update.php?cluster='.$r_st->trans_no.'|'.$r_st->trans_date.'|'.$r_st->source_id.'|'.$r_st->name.'|'.$r_st->glass_bottle_qty.'|'.$r_st->ee_qty.'|'.$r_st->comp_qty.'|'.$r_st->battery_qty.'|'.$r_st->light_qty.'|'.$r_st->paper_qty.'|'.$r_st->plastic_qty.'|'.$r_st->metal_qty.'|'.$r_st->toner_qty.'|'.$r_st->clothes_qty.'|'.$r_st->book_qty.'|'.$r_st->toy_qty.'|'.$r_st->other_qty.'|'.$r_st->other_desc.'">'.$r_st->trans_date.'</a></td>'

	    					.'<td style="background-color:#00B386;color:white">'.$r_st->source_id.'</td><td style="background-color:#00B386;color:white">'.$r_st->name.'</td><td style="background-color:#00ADB3;color:white">'.$r_st->glass_bottle_qty.'</td>'
	    					.'<td style="background-color:#00ADB3;color:white">'.$r_st->ee_qty.'</td><td style="background-color:#00ADB3;color:white">'.$r_st->comp_qty.'</td><td style="background-color:#00ADB3;color:white">'.$r_st->battery_qty.'</td>'
	    					.'<td style="background-color:#00ADB3;color:white">'.$r_st->light_qty.'</td><td style="background-color:#EB6E80;color:white">'.$r_st->paper_qty.'</td><td style="background-color:#EB6E80;color:white">'.$r_st->plastic_qty.'</td>'
	    					.'<td style="background-color:#EB6E80;color:white">'.$r_st->metal_qty.'</td><td style="background-color:#EB9900;color:white">'.$r_st->toner_qty.'</td><td style="background-color:#EB9900;color:white">'.$r_st->clothes_qty.'</td>'
	    					.'<td style="background-color:#EB9900;color:white">'.$r_st->book_qty.'</td><td style="background-color:#EB9900;color:white">'.$r_st->toy_qty.'</td><td style="background-color:#EB9900;color:white">'.$r_st->other_qty.'</td>'
								.'<td style="background-color:#EB9900;color:white">'.$r_st->other_desc						
								.'</td><td style="background-color:#B5B5B5"><a href="delete.php?cluster='.$r_st->trans_no.'|'.$r_st->trans_date.'|'.$r_st->source_id.'|'.$r_st->name.'|'
								.$r_st->glass_bottle_qty.'|'.$r_st->ee_qty.'|'.$r_st->comp_qty.'|'.$r_st->battery_qty.'|'.$r_st->light_qty.'|'.$r_st->paper_qty.'|'.$r_st->plastic_qty
								.'|'.$r_st->metal_qty.'|'.$r_st->toner_qty.'|'.$r_st->clothes_qty.'|'.$r_st->book_qty.'|'.$r_st->toy_qty.'|'.$r_st->other_qty.'|';
								
								//iif(!empty($r_hse->other_desc), $r_hse->other_desc, '@'); 
								if (!empty($r_st->other_desc)){
										print $r_st->other_desc;} 
										else{ 
											print '@';} 
								
								print '"><img src="img/del.png"  width="20" height="20" value='.$r_st->trans_no.'></a></td></tr>';
	  					}
  				mysqli_free_result($result_st);
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
  <span style="color:blue;font-weight:bold;"><a href="addnew.php">新增回收記錄</a></span>
';



	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保查詢選項均已輸入!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}


include('templates/footer.html'); // Need the footer.


?>