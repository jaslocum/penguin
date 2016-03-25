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
$session_token = array();

init_session($url_base, $client, $jar, $session_token);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//upload Eastside active work order images
//**
$sql =
    "
      SELECT
        WORKORDR,
        eoWorkorder.CUSTCODE,
        eoWorkorder.PROCNAME,
        eoWorkorder.PARTNUM,
        eoWorkorder.PartID AS partID,
        eoWorkorder.ImageID AS WoImageID,
        Part.ImageID AS PtImageID
      FROM
        eoWorkorder
      JOIN Part Where eoWorkorder.PartID = Part.ID
      ORDER BY WORKORDR DESC
    ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        $workorder = $row["WORKORDR"];
        $WoImageId = $row["WoImageID"];
        $PartID    = $row["partID"];
        $PtImageId = $row["PtImageID"];
        $custCode  = $row["CUSTCODE"];
        $partNum   = $row["PARTNUM"];
        $process   = $row["PROCNAME"];

        $fileName = "$WoImageId.jpg";
        $filePath = $path.$fileName;
        $uri = $url_base."eowoimg/$workorder";
        $description = "$custCode, $process, $partNum";

        if ($WoImageId!=$PtImageId and $WoImageId>0) {

            //Open file as stream to upload
            $body = fopen($filePath, 'r');

            if ($body) {
                $resultPost = $client->post($uri, [
                    'cookies' => $jar,
                    'multipart' => [
                        [
                            'name' => 'description',
                            'contents' => $description,
                        ],
                        [
                            'name' => '_token',
                            'contents' => $session_token['_token'],
                        ],
                        [
                            'name' => "file",
                            'contents' => $body,
                            'filename' => "$WoImageId.jpg",
                            'type' => $mime,
                        ],
                    ]
                ]);

                $image_id = $resultPost->getHeaders()['image_id'][0];
                $file_path = $resultPost->getHeaders()['file_path'][0];
                echo "eoWorkOrder: $workorder, WoPartID: $PartID, filePath: $filePath, mime: $mime, image_id:$image_id, file_path: $file_path\r\n";
            }
        }
    }

} else {
    echo "0 results\r\n";
}
//**/

//upload Eastside history work order images

//**
$sql =
    "
      SELECT
        WORKORDR,
        eoHistory.CUSTCODE,
        eoHistory.PROCNAME,
        eoHistory.PARTNUM,
        eoHistory.PartID AS partID,
        eoHistory.ImageID AS WoImageID,
        Part.ImageID AS PtImageID
      FROM
        eoHistory
      JOIN Part Where eoHistory.PartID = Part.ID
      ORDER BY WORKORDR DESC
    ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // output data of each row
    while($row = $result->fetch_assoc()) {

        $workorder = $row["WORKORDR"];
        $WoImageId = $row["WoImageID"];
        $PartID    = $row["partID"];
        $PtImageId = $row["PtImageID"];
        $custCode  = $row["CUSTCODE"];
        $partNum   = $row["PARTNUM"];
        $process   = $row["PROCNAME"];

        $fileName = "$WoImageId.jpg";
        $filePath = $path.$fileName;
        $uri = $url_base."eowoimg/$workorder";
        $description = "$custCode, $process, $partNum";

        if ($WoImageId!=$PtImageId and $WoImageId>0) {

            //Open file as stream to upload
            $body = fopen($filePath, 'r');

            try {
                if ($body) {
                    $resultPost = $client->post($uri, [
                        'cookies' => $jar,
                        'multipart' => [
                            [
                                'name' => 'description',
                                'contents' => $description,
                            ],
                            [
                                'name' => '_token',
                                'contents' => $session_token['_token'],
                            ],
                            [
                                'name' => "file",
                                'contents' => $body,
                                'filename' => "$WoImageId.jpg",
                                'type' => $mime,
                            ],
                        ]
                    ]);

                    $image_id = $resultPost->getHeaders()['image_id'][0];
                    $file_path = $resultPost->getHeaders()['file_path'][0];
                    echo "eoHistory: $workorder, WoPartID: $PartID, filePath: $filePath, mime: $mime, image_id:$image_id, file_path: $file_path\r\n";
                }
            } catch (Exception $e) {
                echo "\r\n";
                var_dump($e->getResponse()->getBody()->getContents());
                echo "\r\n";

                init_session($url_base, $client, $jar, $session_token);

            }
        }
    }

} else {
    echo "0 results\r\n";
}

//**/

//upload Surface active work order images

$sql =
    "
      SELECT
        WORKORDR,
        Workorder.CUSTCODE,
        Workorder.PROCNAME,
        Workorder.PARTNUM,
        Workorder.PartID AS partID,
        Workorder.ImageID AS WoImageID,
        Part.ImageID AS PtImageID
      FROM
        Workorder
      JOIN Part Where Workorder.PartID = Part.ID
      ORDER BY WORKORDR DESC
    ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        $workorder = $row["WORKORDR"];
        $WoImageId = $row["WoImageID"];
        $PartID    = $row["partID"];
        $PtImageId = $row["PtImageID"];
        $custCode  = $row["CUSTCODE"];
        $partNum   = $row["PARTNUM"];
        $process   = $row["PROCNAME"];

        $fileName = "$WoImageId.jpg";
        $filePath = $path.$fileName;
        $uri = $url_base."woimg/$workorder";
        $description = "$custCode, $process, $partNum";

        if ($WoImageId!=$PtImageId and $WoImageId>0) {

            //Open file as stream to upload
            $body = fopen($filePath, 'r');

            if ($body) {
                $resultPost = $client->post($uri, [
                    'cookies' => $jar,
                    'multipart' => [
                        [
                            'name' => 'description',
                            'contents' => $description,
                        ],
                        [
                            'name' => '_token',
                            'contents' => $session_token['_token'],
                        ],
                        [
                            'name' => "file",
                            'contents' => $body,
                            'filename' => "$WoImageId.jpg",
                            'type' => $mime,
                        ],
                    ]
                ]);

                $image_id = $resultPost->getHeaders()['image_id'][0];
                $file_path = $resultPost->getHeaders()['file_path'][0];
                echo "WorkOrder: $workorder, WoPartID: $PartID, filePath: $filePath, mime: $mime, image_id:$image_id, file_path: $file_path\r\n";
            }
        }
    }

} else {
    echo "0 results\r\n";
}

//upload Surface history work order images

//**
$sql =
    "
      SELECT
        WORKORDR,
        History.CUSTCODE,
        History.PROCNAME,
        History.PARTNUM,
        History.PartID AS partID,
        History.ImageID AS WoImageID,
        Part.ImageID AS PtImageID
      FROM
        History
      JOIN Part Where History.PartID = Part.ID
      ORDER BY WORKORDR DESC
    ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // output data of each row
    while($row = $result->fetch_assoc()) {

        $workorder = $row["WORKORDR"];
        $WoImageId = $row["WoImageID"];
        $PartID    = $row["partID"];
        $PtImageId = $row["PtImageID"];
        $custCode  = $row["CUSTCODE"];
        $partNum   = $row["PARTNUM"];
        $process   = $row["PROCNAME"];

        $fileName = "$WoImageId.jpg";
        $filePath = $path.$fileName;
        $uri = $url_base."woimg/$workorder";
        $description = "$custCode, $process, $partNum";

        if ($WoImageId!=$PtImageId and $WoImageId>0) {

            //Open file as stream to upload
            $body = fopen($filePath, 'r');

            try {
                if ($body) {
                    $resultPost = $client->post($uri, [
                        'cookies' => $jar,
                        'multipart' => [
                            [
                                'name' => 'description',
                                'contents' => $description,
                            ],
                            [
                                'name' => '_token',
                                'contents' => $session_token['_token'],
                            ],
                            [
                                'name' => "file",
                                'contents' => $body,
                                'filename' => "$WoImageId.jpg",
                                'type' => $mime,
                            ],
                        ]
                    ]);

                    $image_id = $resultPost->getHeaders()['image_id'][0];
                    $file_path = $resultPost->getHeaders()['file_path'][0];
                    echo "History: $workorder, WoPartID: $PartID, filePath: $filePath, mime: $mime, image_id:$image_id, file_path: $file_path\r\n";
                }
            } catch (Exception $e) {
                echo "\r\n";
                var_dump($e->getResponse()->getBody()->getContents());
                echo "\r\n";

                init_session($url_base, $client, $jar, $session_token);

            }
        }
    }

} else {
    echo "0 results\r\n";
}
//**/

//upload Surface part images

$sql =
    "
      SELECT
        ID, CUSTCODE, PROCNAME, PARTNUM, ImageID AS PtImageID
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
        $custCode  = $row["CUSTCODE"];
        $procName   = $row["PROCNAME"];
        $partNum   = $row["PARTNUM"];

        $fileName = "$PtImageId.jpg";
        $filePath = $path.$fileName;
        $uri = $url_base."ptimg/$PartID";
        $description = "$custCode, $procName, $partNum";

        if ($PtImageId>0) {

            //Open file as stream to upload
            $body = fopen($filePath, 'r');

            try {
                if ($body && $PtImageId > 0) {
                    $resultPost = $client->post($uri, [
                        'cookies' => $jar,
                        'multipart' => [
                            [
                                'name' => 'description',
                                'contents' => $description,
                            ],
                            [
                                'name'     => '_token',
                                'contents' => $session_token['_token'],
                                'headers'  => ['description' => "$custCode, $partNum"]
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

            } catch (Exception $e) {

                /*** show the error message ***/
                echo "\r\n";
                var_dump($e->getResponse()->getBody()->getContents());
                echo "\r\n";

                init_session($url_base, $client, $jar, $session_token);

            }
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

function init_session($url_base, &$client, &$jar, &$session_token){

    $session = $url_base."session";
    $sessionResult = $client->get("$session",['cookies'=>$jar]);
    $sessionBody = str_replace('&quot;','"',(string) $sessionResult->getBody());
    $session_token = (array) json_decode($sessionBody);

}
