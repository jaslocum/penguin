<?php
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

require 'vendor/autoload.php';
require 'config/getBmpImages.php';
require 'config/uploadImages.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT WORKORDR, ImageID FROM Workorder ORDER BY WORKORDR DESC";
$result = $conn->query($sql);

$client = new Client;
$jar = new CookieJar();

$session = $url_base."session";
$sessionResult = $client->get("$session",['cookies'=>$jar]);
$sessionBody = str_replace('&quot;','"',(string) $sessionResult->getBody());
$sessionToken = (array) json_decode($sessionBody);

if ($result->num_rows > 0) {
    $i = 0;
    // output data of each row
    while(($row = $result->fetch_assoc()) && ($i <= 100000)) {

        $workorder = $row["WORKORDR"];
        $imageId = $row["ImageID"];

        $fileName = "$imageId.jpg";
        $filePath = $path.$fileName;
        $uri = $url_base."woimg/$workorder";

        //Open file as stream to upload
        $body = fopen($filePath,'r');

        if ($body)
        {
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
                        'filename' => "$imageId.jpg",
                        'type' => $mime
                    ],
                ]
            ]);
        }

        echo "$workorder: $filePath\r\n";
        ++$i;
    }

} else {
    echo "0 results";
}

$conn->close();