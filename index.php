<?php
$con = mysqli_connect('localhost', 'root', '', 'jsondata');

$filename = "products.json";
$data = file_get_contents($filename);
$array = json_decode($data, true);

foreach($array['channel']['item'] as $value){
    $title = mysqli_real_escape_string($con, $value['title']);
    $description = mysqli_real_escape_string($con, $value['description']);
    $link = mysqli_real_escape_string($con, $value['link']);
    $guid = mysqli_real_escape_string($con, $value['guid']);
    $pubDate = mysqli_real_escape_string($con, $value['pubDate']);

    if (isset($value['dc:creator'])) {
        $dcCreator = mysqli_real_escape_string($con, $value['dc:creator']['#text']);
    } else {
        $dcCreator = null;
    }

    if (isset($value['enclosure'])) {
        $enclosureType = mysqli_real_escape_string($con, $value['enclosure']['@type']);
        $enclosureUrl = mysqli_real_escape_string($con, $value['enclosure']['@url']);
    } else {
        $enclosureType = null;
        $enclosureUrl = null;
    }

    $query = "INSERT INTO data (`title`, `description`, `link`, `guid`, `pubDate`, `dc_creator`, `enclosure_type`, `enclosure_url`) VALUES ('$title', '$description', '$link', '$guid', '$pubDate', '$dcCreator', '$enclosureType', '$enclosureUrl')";

    mysqli_query($con, $query);
}

mysqli_close($con);
?>