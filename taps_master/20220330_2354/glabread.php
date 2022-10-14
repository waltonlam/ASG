<html>
<body>
<center>
<h2>Read Excel By PHPExcel</h2>
<?php
require_once "Classes/PHPExcel.php";

$path="test1.xlsx";
$reader= PHPExcel_IOFactory::createReaderForFile($path);
$excel_Obj = $reader->load($path);
//Get the last sheet in excel
//$worksheet=$excel_Obj->getActiveSheet();

//Get the first sheet in excel
$worksheet=$excel_Obj->getSheet('1');
echo $worksheet->getCell('B1')->getValue()."<br>";
$lastRow = $worksheet->getHighestRow();
$colomncount = $worksheet->getHighestDataColumn();
//$colomncount_number=PHPExcel_Cell::columnIndexFromString($colomncount);
echo $lastRow.'     ';
echo $colomncount;




/*
echo "<table border='1'>";
	for($row=0;$row<=$lastRow;$row++){
		echo "<tr>";
		for($col=0;$col<=$colomncount_number;$col++){
			echo "<td>";
			echo $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col).$row)->getValue();
			echo "</td>";
		}
		echo "</tr>";
	}	
echo "</table>";
*/



// https://www.coder.work/article/723792   ---php - 在 PHPExcel 中复制样式和数据----------------------------------------------------------



// https://www.coder.work/article/723792    -----------------------------------------------------------------



?>
</center>
</body>
</html>