<?php
namespace Phppot;
use Phppot\DataSource;

class CompoundModel {
    private $conn;

    function __construct() {
        require_once 'DataSource.php';
        $this->conn = new DataSource();
    }

    public function getAllGlabSampleData($start_from, $per_page_record) {
        $sqlSelect = "SELECT * FROM glab_sample LIMIT $start_from, $per_page_record";
        $result = $this->conn->select($sqlSelect);
        return $result;
    }

    public function insertCompound($imageData, $site_code, $remark) {
        //$create_date = date('Y-m-d'); 
        $create_by = $_SESSION['vuserid'];
        //echo $create_date;

        //print_r($imageData);
        $query = "INSERT INTO site_photo(name,image,site_code,remark,create_date,last_upd_date,create_by,last_upd_by) VALUES(?,?,?,?,CURDATE(),CURDATE(),?,?)";
        $paramType = 'ssssss';

        $paramValue = array(
            $imageData[0],
            $imageData[1],
            $site_code,
            $remark,
            //"'".$create_date."'",
            //"'".$create_date."'",
            $create_by,
            $create_by
        );

        $id = $this->conn->insert($query, $paramType, $paramValue);
        return $id;
    }

    public function selectCompoundById($id) {
        $sql = "select * from glab_sample where id=? ";
        $paramType = 'i';
        $paramValue = array(
            $id
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }
	
	public function calAvgFieldBlank($compound, $compoundGrp, $year) {
        $sql = "SELECT avg(conc_g_m3) as avg_conc_g_m3 FROM glab_sample 
                    WHERE conc_g_m3 is not null and 
                    compound = ? and 
                    compound_grp = ? and 
                    field_blank = 'Y' and 
                    strt_date BETWEEN ? and ? ";
		
		$paramType = 'ssss';
        $paramValue = array(
            $compound,
			$compoundGrp,
			"20".$year."-01-01",
			"20".$year."-12-31"
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }
	
    public function calAvgFieldBlankSameLoc($siteId, $compound, $compoundGrp, $year) {
        $sql = "SELECT avg(conc_g_m3) as avg_conc_g_m3 FROM glab_sample 
                    WHERE conc_g_m3 is not null and 
                    site_id = ? and 
                    compound = ? and 
                    compound_grp = ? and 
                    field_blank = 'Y' and 
                    strt_date BETWEEN ? and ? ";
		
		$paramType = 'sssss';
        $paramValue = array(
            $siteId,
            $compound,
			$compoundGrp,
			"20".$year."-01-01",
			"20".$year."-12-31"
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    public function getCoLocatedSample($sampleId, $siteId, $compound, $compoundGrp) {
        $sql = "SELECT sample_id, conc_g_m3 FROM glab_sample 
                    WHERE site_id = ? and 
                    compound = ? and 
                    compound_grp = ? and 
                    field_blank = 'N' and
                    sample_id like ? and
                    sample_id <> ? and 
                    conc_g_m3 is not null ";
		
		$paramType = 'sssss';
        $paramValue = array(
            $siteId,
            $compound,
			$compoundGrp,
            substr_replace($sampleId, '%', -1),
            $sampleId
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    public function calPercentageDiff($number1,$number2) {
        return (abs($number1-$number2)/(($number1+$number2)/2))*100;  
    }

    public function calPercentile($data, $percentile) {
        if( 0 < $percentile && $percentile < 1 ) {
            $p = $percentile;
        }else if( 1 < $percentile && $percentile <= 100 ) {
            $p = $percentile * .01;
        }else {
            return "";
        }
        $count = count($data);
        $allindex = ($count-1)*$p;
        $intvalindex = intval($allindex);
        $floatval = $allindex - $intvalindex;
        sort($data);
        if(!is_float($floatval)){
            $result = $data[$intvalindex]["conc_g_m3"];
        }else {
            if($count > $intvalindex+1){
                $result = $floatval*($data[$intvalindex+1]["conc_g_m3"] - $data[$intvalindex]["conc_g_m3"]) + $data[$intvalindex]["conc_g_m3"];
            }else{
                $result = $data[$intvalindex]["conc_g_m3"];
            }
        }
        return $result;
    }

    public function getConcFrmLast3Yrs($siteId, $compound, $compoundGrp, $strtDate) {
        //$sql = "SELECT CAST(SUBSTRING_INDEX(SUBSTRING_INDEX( GROUP_CONCAT(conc_g_m3 ORDER BY conc_g_m3 SEPARATOR ','), ',', 99/100 * COUNT(*) + 1), ',', -1) AS DECIMAL) AS 99th_Per FROM glab_sample 
        //WHERE site_id = ? and compound = ? and compound_grp = ? and YEAR(strt_date) >= YEAR(?) - 3 ";
		
        $sql = "SELECT conc_g_m3 FROM glab_sample 
                    WHERE sample_id not like '%A2' and 
                    conc_g_m3 is not null and 
                    field_blank = 'N' and 
                    site_id = ? and 
                    compound = ? and 
                    compound_grp = ? and 
                    YEAR(strt_date) >= YEAR(?) - 3 ";

		$paramType = 'ssss';
        $paramValue = array(
            $siteId,
            $compound,
			$compoundGrp,
			$strtDate
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }
	
    public function calAvgFrmLast3Yrs($siteId, $compound, $compoundGrp, $strtDate) {
        $sql = "SELECT avg(conc_g_m3) as avg_conc_g_m3 FROM glab_sample 
                    WHERE sample_id not like '%A2' and  
                    conc_g_m3 is not null and 
                    site_id = ? and 
                    compound = ? and 
                    compound_grp = ? and 
                    field_blank = 'N' and 
                    YEAR(strt_date) >= YEAR(?) - 3 ";
		
		$paramType = 'ssss';
        $paramValue = array(
            $siteId,
            $compound,
			$compoundGrp,
			$strtDate
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    public function updateCompound($imageData, $id, $site_code, $remark) {
        $query = "UPDATE glab_sample SET name=?, image=?, site_code=?, remark=? WHERE id=?";
        $paramType = 'ssssi';
        $paramValue = array(
            $imageData[0],
            $imageData[1],
            $site_code, 
            $remark,
            $_GET["id"]
        );
        $id = $this->conn->execute($query, $paramType, $paramValue);
        return $id;
    }

    public function deleteCompoundById($id) {
        $query = "DELETE FROM glab_sample WHERE id=$id";
        $result = $this->conn->select($query);
        return $result;
    }
}
?>