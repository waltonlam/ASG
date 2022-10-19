<html>
    <head>
        <meta charset="utf-8" />

        <title>Integrating PHP & R</title>
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
            exec('C:\\"Program Files"\\R\\R-4.2.0\\bin\\Rscript.exe C:\xampp\htdocs\rProject\Rscript.R '.$number);
            echo "<img src='temp.png?$var'> ";
        }
        ?>
    </body>
</html>