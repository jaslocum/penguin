<?php
/***
 * Created by PhpStorm.
 * User: jack
 * Date: 2/17/2016
 * Time: 11:33 AM
 */

require('config/bmpImages.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT ID FROM Image ORDER BY ID DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $id = $row["ID"];
        $sql = "SELECT image FROM Image WHERE ID=$id";
        $image = $conn->query($sql)->fetch_assoc();
        $filename = $path.$id.".bmp";
        echo "$id) image: $filename\n\r";
        file_put_contents ( $filename, substr($image['image'],78));
    }
} else {
    echo "0 results";
}

$conn->close();
exit(0);

