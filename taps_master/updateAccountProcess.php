<?php

	$cid=$_POST['uid'];

	$allAccount=$_POST['allAccount'];
	// print "the arg2 =  ".$allCompound;	
	// print "the arg1 =  ".$cid;
	
		 // getMediaData();
		
	$someArray = json_decode($allAccount, true);

	// print $data;

	foreach ($someArray as $value) {
		if ($value["uid"] === $cid) {
			echo json_encode($value); 
		}
    	
	}


	


// print_r($someArray);
		// function getMediaData(){
			
			

		// 		$carray_2_json = json_encode((array)$compoundArray);




		// 		 echo $carray_2_json;


			
			
		// }
		
		
		

?>
		
