<?php include 'header2.php';
	include 'iconn.php';



?>
<script type="text/javascript">

	function showSuccessAlert(){
		alert("Success");
	}

</script>
<html>

<style>

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}


input[type=submit] {
  background-color: #87ceeb;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  width:100%
}

#main-content {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>

<?php  
		 if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{				
						$u= "DELETE FROM contractor_template;";
						
						if ($dbc->query($u) === TRUE) {
						   // $updated=TRUE;
							
							echo '<script>alert("Delete Success");
							window.location.href = "delContractor.php"; </script>';
						}else{
							echo "Error: " . $dbc->error;
							exit();
						};

				exit();
		} 
		 
		

?>




<h2>Delete Contractor file</h2>
<body>
	 

    
    <div class="main-content">
        <div class="row">

            <form class="form-horizontal" action="" method="post"
                name="delSampleTx" id="delSampleTx"
                enctype="multipart/form-data">
                <div class="input-row">
                    <h4><label class="col-md-4 control-label">Delete all record?</label></h4> 
                    <br />

                </div>
				
				
				
				 <button type="submit" id="submit" name="import"
                        class="btn-submit">Delete</button>
			
				
            </form>

        </div>

    </div>
	
</body>

</html>
		
		
	
