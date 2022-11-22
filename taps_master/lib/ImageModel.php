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
        $sql = "select * from incident_report where id=? ";
        $paramType = 'i';
        $paramValue = array(
            $id
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    public function getSitePhotoById($id) {
        $sql = "select * from site_photo where incident_id=? ";
        $paramType = 'i';
        $paramValue = array(
            $id
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    public function updateImage($imageData, $id, $site_code, $remark) {
        $query = "UPDATE site_photo SET name=?, image=?, site_code=?, remark=? WHERE id=?";
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