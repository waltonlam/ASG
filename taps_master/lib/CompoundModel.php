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

    public function insertGlabSample($site_id) {
        $query = "INSERT INTO glab_sample(site_code,create_date,last_upd_date) VALUES(?,CURDATE(),CURDATE())";
        $paramType = 's';
        $paramValue = array(
            $site_id
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

    //Retrieve conc g/m3 from last 3 years
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
	
    //Calculate average from last 3 years
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

    //calculate TEF ratio
    public function calTEF($tefRatio, $sampleId, $siteId) {
        if($tefRatio == "I-TEF"){
            $tefRatio = "i_tef";
        }else if($tefRatio == "WHO-TEF-1998"){
            $tefRatio = "who_tef_1998";
        }else if($tefRatio == "WHO-TEF-2005"){
            $tefRatio = "who_tef_2005";
        }

        $sql = "SELECT COALESCE (SUM(g.conc_ppbv * f.".$tefRatio."),0) AS total_tef FROM glab_sample g 
                    LEFT JOIN factor f ON g.compound = f.compound COLLATE utf8mb4_unicode_ci 
                    WHERE g.site_id = ? 
                    AND g.sample_id LIKE ? 
                    AND g.compound NOT LIKE 'Total%' 
                    AND g.compound_grp IN ('DF', 'DI_PB') 
                    AND g.field_blank = 'N' ";
		
		$paramType = 'ss';
        $paramValue = array(
            $siteId,
            $sampleId."%"
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    //get count of diff for each colocated sample > 30%
    public function getCountOfPercentageDiff($locA1, $locA2) {
        $sql = "SELECT  COUNT(diff.difference) AS 'count_diff' FROM 
                    (SELECT ABS((g1.conc_g_m3 - g2.conc_g_m3) / ((g1.conc_g_m3 + g2.conc_g_m3) / 2)) * 100 AS difference
                    FROM glab_sample g1, glab_sample g2 
                    WHERE g1.id <> g2.id AND g1.sample_id = ?
                    AND g2.sample_id = ? 
                    AND g1.compound = g2.compound) AS diff 
                    WHERE diff.difference > 30";
		
		$paramType = 'ss';
        $paramValue = array(
            substr($locA1,0,-1)."1",
            substr($locA2,0,-1)."2"
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    //get count of co-located sample A1 only
    public function getCountOfTotalColocatedSample($locA1, $locA2) {
        $sql = "SELECT count(g1.id) AS total_sample FROM glab_sample g1, glab_sample g2 
                    WHERE g1.id <> g2.id 
                    AND g1.sample_id = ? 
                    AND g2.sample_id = ? 
                    AND g1.compound = g2.compound;";
		
		$paramType = 'ss';
        $paramValue = array(
            substr($locA1,0,-1)."1",
            substr($locA2,0,-1)."2"
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    //Get deviation from qc_criteria
    public function getDeviationByCompoundGrp($compoundGrp) {
        $sql = "SELECT * FROM qc_criteria WHERE compound_grp = ? ;";
		$paramType = 's';
        $paramValue = array(
            $compoundGrp
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    public function updateSiteIdSampleId($id, $sample_id, $site_id) {
        $query = "UPDATE glab_sample SET sample_id = ?, site_id = ? WHERE id=?";
        $paramType = 'sss';
        $paramValue = array(
            $sample_id,
            $site_id,
            $id
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