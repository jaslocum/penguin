<?php
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

require 'vendor/autoload.php';
require 'config/uploadImages.php';

if (isset($argv[1]))
{
    $first = $argv[1];
    if (isset($argv[2]))
    {
        $last = $argv[2];
    }
} else {
    $first = 0;
    $last = 0;
}

$client = new Client;
$jar = new CookieJar();

$session = $url_base."session";
$sessionResult = $client->get("$session",['cookies'=>$jar]);
$sessionBody = str_replace('&quot;','"',(string) $sessionResult->getBody());
$sessionToken = (array) json_decode($sessionBody);

for ($i = $first; $i <= $last; ++$i){

    $fileName = "$i.jpg";
    $filePath = $path.$fileName;
    $uri = $url_base."woimg/$i";

    //Open file as stream to upload
    $body = fopen($filePath,'r');

    $result = $client->post($uri, [
        'cookies' => $jar,
        'multipart' => [
            [
                'name'     => '_token',
                'contents' => $sessionToken['_token']
            ],
            [
                'name'     => "file",
                'contents' => $body,
                'filename' => "$i.jpg",
                'type' => $mime
            ],
        ]
    ]);
}