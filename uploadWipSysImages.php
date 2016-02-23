<?php
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

require 'vendor/autoload.php';
require 'config/getBmpImages.php';
require 'config/uploadImages.php';

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

if ($result->num_rows > 0) {
    $i = 0;
    // output data of each row
    while(($row = $result->fetch_assoc()) && ($i <= 100000)) {

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
        ++$i;
    }

} else {
    echo "0 results";
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

    $i = 1001;

    // output data of each row
    while($row = $result->fetch_assoc()) {


        if ($i>1000) {
            //refresh session csrf token
            $session = $url_base."session";
            $sessionResult = $client->get("$session",['cookies'=>$jar]);
            $sessionBody = str_replace('&quot;','"',(string) $sessionResult->getBody());
            $sessionToken = (array) json_decode($sessionBody);
            $i=0;
        } else {
            ++$i;
        }


        $PartID = $row["ID"];
        $PtImageId = $row["PtImageID"];

        $fileName = "$PtImageId.jpg";
        $filePath = $path.$fileName;
        $uri = $url_base."ptimg/$PartID";

        //Open file as stream to upload
        $body = fopen($filePath, 'r');

        if ($body && $PtImageId>0) {
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
        }

        echo "$PartID: $filePath\r\n";

        ++$i;
    }

} else {
    echo "0 results";
}

$conn->close();