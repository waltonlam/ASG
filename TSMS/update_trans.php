<?php
/*
$Text = urldecode($_REQUEST['cluster']);
$Mixed = json_decode($Text);
print_r( $Mixed);
print "<p>$Mixed->phone</p>";
*/

if (!empty($_REQUEST['cluster'])){
		//print $_REQUEST['cluster'].'<p></p>';
		
		$string = "Hello world. Beautiful day today.";
		 $token = strtok($_REQUEST['cluster'], "|");
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			echo $_POST['trans_date'];
			exit();
		}
		
		 
		$i=0; 
		while ($token !== false)
		   {
		   	$t[$i]=$token;
		//   echo "$token<br>";
		   $token = strtok("|");
		   $i++;
		   }
		
		$u=0;
		//print '<p></p>';
		while ($u < sizeof($t)){
			print $t[$u].'<br>';
			$u++;
		}
		//print '<input style="margin-left:10px" type="date" name="trans_date" id="trans_date" value='.$t[0].'></input>';	
}
	else{
		//print "cluster is empty";
	//	exit();
	}			

print '
<form action="update_trans.php?cluster=1" method="post">';
		print '<input style="margin-left:10px" type="date" name="trans_date" id="trans_date" value='.$t[0].'></input>'
.'<input class=button--general style="margin-left:10px" type="submit" value="submit"></form>';	
	

	
?>
