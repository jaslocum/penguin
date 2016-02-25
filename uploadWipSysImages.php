<?php
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

require 'vendor/autoload.php';
require 'config/bmpImages.php';
require 'config/uploadImages.php';

/*** set error handler level to E_WARNING ***/
set_error_handler('custom_handler', E_WARNING);

$client = new Client;
$jar = new CookieJar();

$session = $url_base."session";
$sessionResult = $client->get("$session",['cookies'=>$jar]);
$sessionBody = str_replace('&quot;','"',(string) $sessionResult->getBody());
$sessionToken = (array) json_decode($sessionBody);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql =
    "
      SELECT
        WORKORDR, Workorder.ImageID AS WoImageID, Part.ImageID AS PtImageID
      FROM
        Workorder
      JOIN Part Where Workorder.PartID = Part.ID
      ORDER BY WORKORDR DESC
    ";

$result = $conn->query($sql);

if ($result->num_rows < 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        $workorder = $row["WORKORDR"];
        $WoImageId = $row["WoImageID"];
        $PtImageId = $row["PtImageID"];

        $fileName = "$WoImageId.jpg";
        $filePath = $path.$fileName;
        $uri = $url_base."woimg/$workorder";

        if ($WoImageId!=$PtImageId) {

            //Open file as stream to upload
            $body = fopen($filePath, 'r');

            if ($body) {
                $resultPost = $client->post($uri, [
                    'cookies' => $jar,
                    'multipart' => [
                        [
                            'name' => '_token',
                            'contents' => $sessionToken['_token']
                        ],
                        [
                            'name' => "file",
                            'contents' => $body,
                            'filename' => "$WoImageId.jpg",
                            'type' => $mime
                        ],
                    ]
                ]);
            }
        }

        echo "$workorder: $filePath\r\n";

    }

} else {
    echo "0 results\r\n";
}

$sql =
    "
      SELECT
        ID, ImageID AS PtImageID
      FROM
        Part
      ORDER BY ID DESC
    ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // output data of each row
    while($row = $result->fetch_assoc()) {

        $PartID = $row["ID"];
        $PtImageId = $row["PtImageID"];

        $fileName = "$PtImageId.jpg";
        $filePath = $path.$fileName;
        $uri = $url_base."ptimg/$PartID";

        //Open file as stream to upload
        $body = fopen($filePath, 'r');

        try {
            if ($body && $PtImageId > 0) {
                $resultPost = $client->post($uri, [
                    'cookies' => $jar,
                    'multipart' => [
                        [
                            'name' => '_token',
                            'contents' => $sessionToken['_token']
                        ],
                        [
                            'name' => "file",
                            'contents' => $body,
                            'filename' => "$PtImageId.jpg",
                            'type' => $mime
                        ],
                    ]
                ]);

                $image_id = $resultPost->getHeaders()['image_id'][0];
                $file_path = $resultPost->getHeaders()['file_path'][0];
                echo "PartID: $PartID, filePath: $filePath, mime: $mime, image_id:$image_id, file_path: $file_path\r\n";

            }

        }
        catch (\GuzzleHttp\Exception\ServerException $e)
        {

            $error = $e->getResponse()->getBody()->getContents();

            /*** show the error message ***/
            var_dump($error);

            echo "\r\n";
            echo $e->getMessage();
            echo "\r\n";

            $client = new Client;
            $jar = new CookieJar();

            $session = $url_base."session";
            $sessionResult = $client->get("$session",['cookies'=>$jar]);
            $sessionBody = str_replace('&quot;','"',(string) $sessionResult->getBody());
            $sessionToken = (array) json_decode($sessionBody);

            $row = $result->fetch_assoc();

            $PartID = $row["ID"];
            $PtImageId = $row["PtImageID"];

            $fileName = "$PtImageId.jpg";
            $filePath = $path.$fileName;
            $uri = $url_base."ptimg/$PartID";

            //Open file as stream to upload
            $body = fopen($filePath, 'r');
        }

    }

} else {
    echo "0 results\r\n";
}

$conn->close();

function custom_handler($errno, $errmsg){

    //throw new Exception('This Error Happened '.$errno.': '. $errmsg);
    echo "errno: $errno, errmsg: $errmsg\r\n";

}
