<?php
$con  = mysqli_connect("localhost","root","","taps");
 if (!$con) {
     # code...
    echo "Problem in database connection! Contact administrator!" . mysqli_error();
 }else{
         $sql ="SELECT * FROM factor";
         $result = mysqli_query($con,$sql);
         $chart_data="";
         while ($row = mysqli_fetch_array($result)) { 
            $compound[]  = $row['compound']  ;
            $who_tef[] = $row['who_tef_2005'];
        }
 }
?>