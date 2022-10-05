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

    function getAll()
    {
        $query = "SELECT * FROM tbl_image";
        $result = $this->conn->select($query);
        return $result;
    }

    function getImageById($id)
    {
        $query = "SELECT * FROM tbl_image WHERE id=?";
        $paramType = 'i';
        $paramValue = array(
            $id
        );
        $result = $this->conn->select($query, $paramType, $paramValue);
        return $result;
    }

    function insertImage($destination)
    {
        $insertId = 0;
        if (! empty($destination)) {
            $query = "INSERT INTO tbl_image(name,image) VALUES(?,?)";
            $paramType = 'ss';

            $paramValue = array(
                $_FILES["image"]["name"],
                $destination
            );
            $insertId = $this->conn->insert($query, $paramType, $paramValue);
        }
        return $insertId;
    }

    function compressImage($sourceFile, $outputFile, $outputQuality)
    {
        $imageInfo = getimagesize($sourceFile);
        if ($imageInfo['mime'] == 'image/gif')
        {
            $imageLayer = imagecreatefromgif($sourceFile);
        }
        else if ($imageInfo['mime'] == 'image/jpeg')
        {
            $imageLayer = imagecreatefromjpeg($sourceFile);
        }
        else if ($imageInfo['mime'] == 'image/png')
        {
            $imageLayer = imagecreatefrompng($sourceFile);
        }
        $response = imagejpeg($imageLayer, $outputFile, $outputQuality);
        
        return $response;
    }
}
?>