<?php
require "../bootstrap.php";

$username  = "praveen";
$password = "praveen";

$url = "http://127.0.0.1/basic_auth/person";

getAllPersons($url, $username, $password);

$id = 8;
getPerson($url, $username, $password, $id);



function getAllPersons($url, $username, $password) {
    echo "Getting all persons...";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Accept: application/json",
        "Authorization: Basic " . base64_encode("$username:$password")
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    var_dump($response);
}

function getPerson($url, $username, $password, $id) {
    echo "Getting perosn with id#$id...";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url .'/'. $id);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Accept: application/json",
        "Authorization: Basic " . base64_encode("$username:$password")
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    var_dump($response);
}