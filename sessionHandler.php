<?php


function recordLogoutHistory($email, $logindate, $logintime, $logoutDate, $logoutTime, $sessionDuration) {
    try {
  
        $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
        $db = $mongoClient->crimsonCoffee;
        $loginHistoryCollection = $db->loginHistory;


        $mostRecentLogin = $loginHistoryCollection->findOne(
            ['email' => $email],
            ['sort' => ['logindate' => -1, 'logintime' => -1]]
        );

        if ($mostRecentLogin) {

            $loginHistoryCollection->updateOne(
                ['_id' => $mostRecentLogin['_id']],
                [
                    '$set' => [
                        'logoutdate' => $logoutDate,
                        'logouttime' => $logoutTime,
                        'sessionduration' => $sessionDuration,
                    ],
                ]
            );
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {

        echo 'Error recording logout history: ' . $e->getMessage();
    }
}


function calculateSessionDuration($loginDate, $loginTime, $logoutDate, $logoutTime) {
    $loginDateTime = strtotime("$loginDate $loginTime");
    $logoutDateTime = strtotime("$logoutDate $logoutTime");

    if ($loginDateTime === false || $logoutDateTime === false) {

        return 0;
    }

    $interval = date_diff(new DateTime("@$loginDateTime"), new DateTime("@$logoutDateTime"));


    $sessionDuration = ($interval->days * 24 * 60 * 60) + ($interval->h * 60 * 60) + ($interval->i * 60) + $interval->s;

    return $sessionDuration;
}

?>
