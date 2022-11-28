<?php
    require_once "iconn.php";
    require_once "header2.php";
?>

<html>
    <head>
        <meta charset="utf-8" />
        <style>
            input[type=submit] {
                background-color: #87ceeb;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                width:100
            }
        </style>
    </head>
    
    <body>
        <h2 style="margin-left:10px">Generate Summary Plot</h2>
        <form method="POST" ACTION="">
            <table style="margin-left:10px">
                <tr>
                    <td><label>Please input a number: </label></td>
                    <td><input type="text" name="number"></td>          
                </tr>      
            </table>
            <input class="btn-submit" style="margin-left:10px" type="submit" value="submit">
        </form>

        <?php
        if(isset($_POST['number'])){
            $number = $_POST['number'];

            $var = rand();
			try {
				shell_exec('Rscript ../taps/rscript/Rscript.R '. $number);
				echo "<img src='../taps/rscript/temp.png?$var'> ";	

				exec('Rscript ../taps/rscript/Graph.R ');
				echo "<img src='../taps/rscript/graph.png?$var'> ";
				
				exec('Rscript ../taps/rscript/Openair.R ');
				echo "<img src='../taps/rscript/summaryPlot.png?$var'> ";

				exec('Rscript ../taps/rscript/Openair.R ');
				echo "<img src='../taps/rscript/summaryPlotFromCsv.png?$var'> ";

				exec('Rscript ../taps/rscript/Openair.R ');
				echo "<img src='../taps/rscript/scatterPlot.png?$var'> ";				
			}catch(Exception $e) {
			  echo 'Message: ' .$e->getMessage();
			}

            /*shell_exec('Rscript ../taps/rscript/Rscript.R '. $number);
            echo "<img src='../taps/rscript/temp.png?$var'> ";

            exec('C:\\"Program Files"\\R\\R-4.2.0\\bin\\Rscript.exe C:\xampp\htdocs\taps\rscript\Openair.R ');
            echo "<img src='summaryPlot.png?$var'> ";

            exec('C:\\"Program Files"\\R\\R-4.2.0\\bin\\Rscript.exe C:\xampp\htdocs\taps\rscript\Openair.R ');
            echo "<img src='summaryPlotFromCsv.png?$var'> ";

            exec('C:\\"Program Files"\\R\\R-4.2.0\\bin\\Rscript.exe C:\xampp\htdocs\taps\rscript\Openair.R ');
            echo "<img src='scatterPlot.png?$var'> ";*/
        }
        ?>        
    </body>
</html>