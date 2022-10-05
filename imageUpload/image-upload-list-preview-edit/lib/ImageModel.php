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

    function getAllImages()
    {
        $sqlSelect = "SELECT * FROM tbl_image";
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

    public function insertImage($imageData)
    {
        print_r($imageData);
        $query = "INSERT INTO tbl_image(name,image) VALUES(?,?)";
        $paramType = 'ss';

        $paramValue = array(
            $imageData[0],
            $imageData[1]
        );
        $id = $this->conn->insert($query, $paramType, $paramValue);
        return $id;
    }

    public function selectImageById($id)
    {
        $sql = "select * from tbl_image where id=? ";
        $paramType = 'i';
        $paramValue = array(
            $id
        );
        $result = $this->conn->select($sql, $paramType, $paramValue);
        return $result;
    }

    public function updateImage($imageData, $id)
    {
        $query = "UPDATE tbl_image SET name=?, image=? WHERE id=?";
        $paramType = 'ssi';
        $paramValue = array(
            $imageData[0],
            $imageData[1],
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
    function deleteImageById($id)
    {
        $query = "DELETE FROM tbl_image WHERE id=$id";
        $result = $this->conn->select($query);
        return $result;
    }
}
?>