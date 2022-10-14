<?php  
	include ('iconn.php');
	
	if(isset($_POST['deleteId'])){
		$id= $_POST['deleteId'];
		delete_data( $id);
	}
	
	// delete data query
	function delete_data( $id){
	   echo 'hahahhawhaw  =  '.$id;
		$query="DELETE from location WHERE code=$id";
		if ($dbc->query($query) === TRUE) {
		  echo "Data was deleted successfully";
		}else{
			$msg= "Error: " . $query . "<br>" . mysqli_error($connection);
		  echo $msg;
		}
	}

		 
		 


?>
		

		
		
	
