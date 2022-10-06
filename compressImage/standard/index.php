<?php
namespace Phppot;
require_once __DIR__ . '/lib/ImageModel.php';
$imageModel = new ImageModel();

if (isset($_POST['send'])) {
    $source = $_FILES["image"]["tmp_name"];
    $destination = "uploads/" . $_FILES["image"]["name"];
    //$id = $imageModel->insertImage($destination);
    $response = $imageModel->compressImage($source, $destination, 60);
    $response = (array)$response;
    if (!empty($response)) {
        $id = $imageModel->insertImage($destination);
        if (!empty($response)) {
            $response["type"] = "success";
            $response["message"] = "Upload Successfully";
            $result = $imageModel->getImageById($id);
        }
    } else {
        $response["type"] = "error";
        $response["message"] = "Unable to Upload:$response";
    }
}
?>

<html>
    <head>
        <link href="style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="container">
            <h1>Image Compress & Upload</h1>
            <div class="form-container">
                <form class="form-horizontal" action="" method="post" id="image" enctype="multipart/form-data" name="form" onsubmit="return validateImage()">
                    <label>Choose image to compress</label>
                    <div Class="input-row">
                        <input type="file" name="image" id="file" class="file" value=""	accept=".jpg, .jpeg, .png, .gif">
                    </div>
                    <?php if (! empty($result[0]["image"])) { ?>  
                        <!-- PHP image compress preview  -->
                        <img src="<?php echo $result[0]["image"] ;?>" class="img-preview" alt="photo">
                    <?php } ?>
                    <div class="input-row">
                        <input type="submit" name="send" value="Upload" class="btn">
                        <div id="response" class="<?php if(!empty($response["type"])) { echo $response["type"] ; } ?>">
                            <?php if(!empty($response["message"])) { echo $response["message"]; } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script src="jquery-3.2.1.min.js"></script>
        <script>
        function validateImage() {
            var InputFile = document.forms["form"]["image"].value;
            if (InputFile == "") {
                error = "No source found";
                $("#response").html(error).addClass("error");;
                return false;
            }
            return true;
        }
        </script>
    </body>
</html>
