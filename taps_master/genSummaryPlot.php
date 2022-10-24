<?php
require_once "iconn.php";
require_once "header2.php";
?>

<html>
    <head>
        <meta charset="utf-8" />
    </head>
    <body>
        <form method="POST" ACTION="">
            <input type="text" name="number">
            <input type="submit" value="submit">            
        </form>

        <?php
        if(isset($_POST['number'])){
            $number = $_POST['number'];

            $var = rand();
            exec('C:\\"Program Files"\\R\\R-4.2.0\\bin\\Rscript.exe C:\xampp\htdocs\taps\rscript\Rscript.R '.$number);
            echo "<img src='temp.png?$var'> ";

            exec('C:\\"Program Files"\\R\\R-4.2.0\\bin\\Rscript.exe C:\xampp\htdocs\taps\rscript\Openair.R ');
            echo "<img src='summaryPlot.png?$var'> ";

            exec('C:\\"Program Files"\\R\\R-4.2.0\\bin\\Rscript.exe C:\xampp\htdocs\taps\rscript\Openair.R ');
            echo "<img src='summaryPlotFromCsv.png?$var'> ";

            exec('C:\\"Program Files"\\R\\R-4.2.0\\bin\\Rscript.exe C:\xampp\htdocs\taps\rscript\Openair.R ');
            echo "<img src='scatterPlot.png?$var'> ";
        }
        ?>        
    </body>
</html>