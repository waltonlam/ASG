<?php
namespace Phppot;

use Phppot\DataSource;

class ImageModel
{

    private $conn;

    function __construct()
    {
        require_once 'DataSource.php';
        $this->conn = new DataSource();
    }

    function getAllImages($start_from, $per_page_record)
    {
        $sqlSelect = "SELECT * FROM site_photo LIMIT $start_from, $per_page_record";
        $result = $this->conn->select($sqlSelect);
        return $result;
    }

    function uploadImage()
    {
        $imagePath = "uploads/" . $_FILES["image"]["name"];
        $name = $_FILES["image"]["name"];
        $result = move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
        $output = array(
            $name,
            $imagePath
        );
        return $output;
    }

    public function insertIncidentReport($site_id, $remark) {
        //$create_date = date('Y-m-d'); 
        $create_by = $_SESSION['vuserid'];
        //echo $create_date;

        $query = "INSERT INTO incident_report(site_id,remark,create_date,last_upd_date,create_by,last_upd_by) VALUES(?,?,CURDATE(),CURDATE(),?,?)";
        $paramType = 'ssss';

        $paramValue = array(
            $site_id,
            $remark,
            //"'".$create_date."'",
            //"'".$create_date."'",
            $create_by,
            $create_by
        );

        $id = $this->conn->insert($query, $paramType, $paramValue);
        return $id;
    }

    public function insertImage($incidentId, $imageData, $site_code) {
        //$create_date = date('Y-m-d'); 
        $create_by = $_SESSION['vuserid'];
        //echo $create_date;

        //print_r($imageData);
        $query = "INSERT INTO site_photo(name,image,incident_id,site_code,create_date,last_upd_date,create_by,last_upd_by) VALUES(?,?,?,?,CURDATE(),CURDATE(),?,?)";
        $paramType = 'ssssss';

        $paramValue = array(
            $imageData[0],
            $imageData[1],
            $incidentId,
            $site_code,
            //"'".$create_date."'",
            //"'".$create_date."'",
            $create_by,
            $create_by
        );

        $id = $this->conn->insert($query, $paramType, $paramValue);
        return $id;
    }

    public function getIncidentReportById($id) {
        $sql = "SELECT * FROM incident_report WHERE id=? ";
        $paramType = 'i';
        $paramValue = array(
            $id
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    public function getSitePhotoById($id) {
        $sql = "SELECT * FROM site_photo WHERE incident_id=? ";
        $paramType = 'i';
        $paramValue = array(
            $id
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    public function getTobeDeleteItemsById($id) {
        $sql = "SELECT path FROM site_photo WHERE id IN (".$id.") ";
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }


    public function updateIncidentReport($incidentReportId, $site_code, $remark) {
        $query = "UPDATE incident_report SET site_id=?, remark=? WHERE id=?";
        $paramType = 'ssi';
        $paramValue = array(
            $site_code, 
            $remark,
            $incidentReportId
        );
        $id = $this->conn->execute($query, $paramType, $paramValue);
        return $id;
    }

    /*
     * public function execute($query, $paramType = "", $paramArray = array())
     * {
     * $id = $this->conn->prepare($query);
     *
     * if (! empty($paramType) && ! empty($paramArray)) {
     * $this->bindQueryParams($id, $paramType, $paramArray);
     * }
     * $id->execute();
     * }
     */
    function deleteImageById($id) {
        $query = "DELETE FROM site_photo WHERE id=$id";
        $result = $this->conn->select($query);
        return $result;
    }
}
?>