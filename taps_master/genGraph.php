<?php 
require_once 'graphRecord.php';
require_once "iconn.php";
require_once "header2.php";
 ?>

<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div style="width:60%;hieght:20%;text-align:center">
            <h2>Graph of Pollutants</h2>
            <p style="align:center;"><canvas  id="chartjs_bar"></canvas></p>
        </div>    
    </body>
  <script src="js/jquery.js"></script>
  <script src="js/Chart.min.js"></script>
<script type="text/javascript">
      var ctx = document.getElementById("chartjs_bar").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels:<?php echo json_encode($compound); ?>,
                        datasets: [{
                            backgroundColor: [
                               "#5969aa",
                                "#ff407b",
                                "#25d5f2",
                                "#ffc750"
                            ],
                            data:<?php echo json_encode($who_tef); ?>,
                        }]
                    },
                    options: {
                           legend: {
                        display: true,
                        position: 'bottom',
 
                        labels: {
                            fontColor: '#71748d',
                            fontFamily: 'Circular Std Book',
                            fontSize: 14,
                        }
                    }, 
                }
                });
    </script>
</html>