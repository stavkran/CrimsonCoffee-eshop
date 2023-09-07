<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';



if (isset($_SESSION['loggedInUser'])) {

    $loggedInUsername = $_SESSION['loggedInUser'];
    $loginDate = $_SESSION['loginDate'];
    $loginTime = $_SESSION['loginTime'];
    include_once 'sessionHandler.php';


    date_default_timezone_set('Europe/Athens');
    $logoutDate = date("Y-m-d");
    $logoutTime = date("H:i:s");


    $sessionDuration = calculateSessionDuration($loginDate, $loginTime, $logoutDate, $logoutTime);


    recordLogoutHistory($loggedInUsername, $loginDate, $loginTime, $logoutDate, $logoutTime, $sessionDuration);
}

session_destroy();


header('Location: homepage.php');
exit();
?>
