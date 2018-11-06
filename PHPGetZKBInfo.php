<?php

ob_implicit_flush(true);
ob_end_flush();

$allianceID1 = $_GET["ID1"];
$allianceID2 = $_GET["ID2"];


$year = $_GET["year"];
$month = $_GET["month"];
$day = $_GET["day"];
$hour = $_GET["hour"];

$date = $year . $month . $day . $hour . "00";

$opts = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: https://evecampaigngenerator.azurewebsites.net/ Maintainer:Matt shadefox303@gmail.com" . "'Accept-Encoding: gzip'"

    ]
];
$context = stream_context_create($opts);


$A1KillGetFinished = false;
$A2KillGetFinished = false;
$A1LossGetFinished = false;
$A2LossGetFinished = false;

$Alliance1KillsArray = array();
$Alliance2KillsArray = array();
$Alliance1LossArray = array();
$Alliance2LossArray = array();

$Alliance1FinalKillsArray = array();
$Alliance2FinalKillsArray = array();


$pagenumber = 1;

$progressYear;
$progressMonth;
$progressDay;
$progressHour;


$progressURL = "https://zkillboard.com/api/history/" . $year . $month . $day . "/";             ////Start of progress info

$progresspageString = file_get_contents($progressURL, false, $context);
$progresspageArray = json_decode($progresspageString);
$progressID = key($progresspageArray);

$finalprogressChars = 0;


$GotprogressID2 = false;
$progressID2 = 0;
$number = 0;
$percentageUnder = 0;


while (!$A1KillGetFinished) {
    $URL = "https://zkillboard.com/api/kills/allianceID/" . $allianceID1 . "/page/" . $pagenumber . "/startTime/" . $date . "/";
    $jsonString = file_get_contents($URL, false, $context);
    $jsonarray = json_decode($jsonString);
    $x = 0;
    $y = count($jsonarray);

    if (!$GotprogressID2){
        $progressID2 = $jsonarray[0]->killmail_id;
        $percentageUnder = $progressID2- $progressID;
        $GotprogressID2 = true;
    }
    $percentageOver = $jsonarray[0]->killmail_id - $progressID ;


    while ( $x < $y ) {
        $CurrentKM = $jsonarray[$x]->killmail_id;
        $Alliance1KillsArray[] = $CurrentKM;
        $x = $x + 1;
    }
    $fullcount = $fullcount + $y;

    if ($y == 0){
        $A1KillGetFinished = true;
    }
    else {

        $finalprogress = 100 - round(($percentageOver / $percentageUnder) * 100);
        $finalprogressString = "Step 1 of 4 - Alliance One killmails - " . $finalprogress . "%";

        echo "`" . $finalprogressString;

    }
    $pagenumber = $pagenumber + 1;
}

$GotprogressID2 = false;
$progressID2 = 0;
$number = 0;
$pagenumber = 1;

while (!$A2LossGetFinished){
    $URL = "https://zkillboard.com/api/losses/allianceID/" . $allianceID2 . "/page/" . $pagenumber . "/startTime/" . $date . "/";
    $jsonString = file_get_contents($URL, false, $context);
    $jsonarray = json_decode($jsonString);
    $x = 0;
    $y = count($jsonarray);

    if (!$GotprogressID2){
        $progressID2 = $jsonarray[0]->killmail_id;
        $percentageUnder = $progressID2- $progressID;
        $GotprogressID2 = true;
    }
    $percentageOver = $jsonarray[0]->killmail_id - $progressID ;

    while ( $x < $y ) {
        $t = 0;
        $s = count($Alliance1KillsArray);
        while ($t < $s){
            if ($jsonarray[$x]->killmail_id == $Alliance1KillsArray[$t]){
                $Alliance1FinalKillsArray[] = $jsonarray[$x];
            }
            $t = $t + 1;
        }
        $x = $x + 1;
    }
    if ($y == 0){
        $A2LossGetFinished = true;
    }
    else {

        $finalprogress = 100 - round(($percentageOver / $percentageUnder) * 100);
        $finalprogressString = "Step 2 of 4 - Alliance Two lossmails - " . $finalprogress . "%";

        echo "`" . $finalprogressString;
    }
    $pagenumber = $pagenumber + 1;
}

$GotprogressID2 = false;
$progressID2 = 0;
$number = 0;
$pagenumber = 1;

while (!$A2KillGetFinished) {
    $URL = "https://zkillboard.com/api/kills/allianceID/" . $allianceID2 . "/page/" . $pagenumber . "/startTime/" . $date . "/";
    $jsonString = file_get_contents($URL, false, $context);
    $jsonarray = json_decode($jsonString);
    $x = 0;
    $y = count($jsonarray);

    if (!$GotprogressID2){
        $progressID2 = $jsonarray[0]->killmail_id;
        $percentageUnder = $progressID2- $progressID;
        $GotprogressID2 = true;
    }
    $percentageOver = $jsonarray[0]->killmail_id - $progressID ;

    while ( $x < $y ) {
        $CurrentKM = $jsonarray[$x]->killmail_id;
        $Alliance2KillsArray[] = $CurrentKM;
        $x = $x + 1;
    }
    if ($y == 0){
        $A2KillGetFinished = true;
    }
    else {

        $finalprogress = 100 - round(($percentageOver / $percentageUnder) * 100);
        $finalprogressString = "Step 3 of 4 - Alliance Two killmails - " . $finalprogress . "%";

        echo "`" . $finalprogressString;
    }
    $pagenumber = $pagenumber + 1;
}

$GotprogressID2 = false;
$progressID2 = 0;
$number = 0;
$pagenumber = 1;

while (!$A1LossGetFinished){
    $URL = "https://zkillboard.com/api/losses/allianceID/" . $allianceID1 . "/page/" . $pagenumber . "/startTime/" . $date . "/";
    $jsonString = file_get_contents($URL, false, $context);
    $jsonarray = json_decode($jsonString);
    $x = 0;
    $y = count($jsonarray);

    if (!$GotprogressID2){
        $progressID2 = $jsonarray[0]->killmail_id;
        $percentageUnder = $progressID2- $progressID;
        $GotprogressID2 = true;
    }
    $percentageOver = $jsonarray[0]->killmail_id - $progressID ;

    while ( $x < $y ) {
        $t = 0;
        $s = count($Alliance2KillsArray);
        while ($t < $s){
            if ($jsonarray[$x]->killmail_id == $Alliance2KillsArray[$t]){
                $Alliance2FinalKillsArray[] = $jsonarray[$x];
            }
            $t = $t + 1;
        }
        $x = $x + 1;
    }
    if ($y == 0){
        $A1LossGetFinished = true;
    }
    else {

        $finalprogress = 100 - round(($percentageOver / $percentageUnder) * 100);
        $finalprogressString = "Step 4 of 4 - Alliance One lossmails - " . $finalprogress . "%";

        echo "`" . $finalprogressString;
    }

    $pagenumber = $pagenumber + 1;
}



$AllKills = array();
$AllKills[] = $Alliance1FinalKillsArray;
$AllKills[] = $Alliance2FinalKillsArray;

echo "`" . json_encode($AllKills);

?>
