<?php
require_once __DIR__ . '/vendor/autoload.php';


function connectToDB() {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    return $mongoClient->crimsonCoffee; 
}


function insertUserData($firstname, $lastname, $address, $telephone, $email, $username, $password) {
    $db = connectToDB();
    $collection = $db->users;

    $dataArray = [
        "firstname" => $firstname,
        "lastname" => $lastname,
        "address" => $address,
        "telephone" => $telephone,
        "email" => $email,
        "username" => $username,
        "password" => $password
    ];

    $insertResult = $collection->insertOne($dataArray);

    return $insertResult->getInsertedCount() > 0;
}


function isValidInput($firstname, $lastname, $address, $telephone, $email, $username, $password) {

    if (empty($firstname) || empty($lastname) || empty($address) || empty($telephone) || empty($email) || empty($username) || empty($password)) {
        return false;
    }


    return true;
}
?>
