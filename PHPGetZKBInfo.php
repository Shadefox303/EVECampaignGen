<?php

ob_implicit_flush(true);
ob_end_flush();

$allianceID1 = $_GET["ID1"];
$allianceID2 = $_GET["ID2"];
$allianceID3 = $_GET["ID3"];
$allianceID4 = $_GET["ID4"];
$allianceID5 = $_GET["ID5"];
$allianceID6 = $_GET["ID6"];

$alliance1Side = $_GET["Side1"];
$alliance2Side = $_GET["Side2"];
$alliance3Side = $_GET["Side3"];
$alliance4Side = $_GET["Side4"];
$alliance5Side = $_GET["Side5"];
$alliance6Side = $_GET["Side6"];


$allianceArray[0] = array("AllianceID" => $allianceID1 , "Side" => $alliance1Side);
$allianceArray[1] = array("AllianceID" => $allianceID2 , "Side" => $alliance2Side);
$allianceArray[2] = array("AllianceID" => $allianceID3 , "Side" => $alliance3Side);
$allianceArray[3] = array("AllianceID" => $allianceID4 , "Side" => $alliance4Side);
$allianceArray[4] = array("AllianceID" => $allianceID5 , "Side" => $alliance5Side);
$allianceArray[5] = array("AllianceID" => $allianceID6 , "Side" => $alliance6Side);


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


$Side0KillsArray = array();
$Side1KillsArray = array();
$Side0LossArray = array();
$Side1LossArray = array();

$Side0FinalKillsArray = array();
$Side1FinalKillsArray = array();


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

$currentAlliancenumber = 1;


foreach ($allianceArray as $value){

    $validAID = $value["AllianceID"];

    if ($validAID == "undefined"){
        break;
    };



    $CurrentAllianceID = $value["AllianceID"];
    $CurrentAllianceSide = $value["Side"];
    $KillGetFinished = false;
    $LossGetFinished = false;


    while (!$KillGetFinished) {
        $URL = "https://zkillboard.com/api/kills/allianceID/" . $CurrentAllianceID . "/page/" . $pagenumber . "/startTime/" . $date . "/";
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
            if ($CurrentAllianceSide == 0){
                $Side0KillsArray[] = $jsonarray[$x]->killmail_id;
            }
            else {
                $Side1KillsArray[] = $jsonarray[$x]->killmail_id;
            }
            $x = $x + 1;
        }
        $fullcount = $fullcount + $y;
        if ($y == 0){
            $KillGetFinished = true;
        }
        else {
            $finalprogress = 100 - round(($percentageOver / $percentageUnder) * 100);
            $finalprogressString = "Alliance " . $currentAlliancenumber . " killmails - " . $finalprogress . "%";
            echo "`" . $finalprogressString;
        }
        $pagenumber = $pagenumber + 1;
    }

    $pagenumber = 1;

    while (!$LossGetFinished){
        $URL = "https://zkillboard.com/api/losses/allianceID/" . $CurrentAllianceID . "/page/" . $pagenumber . "/startTime/" . $date . "/";
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

            if ($CurrentAllianceSide == 0){
                $Side0LossArray[] = $jsonarray[$x];
            }
            else{
                $Side1LossArray[] = $jsonarray[$x];
            }
            $x = $x + 1;
        }
        if ($y == 0){
            $LossGetFinished = true;
        }
        else {

            $finalprogress = 100 - round(($percentageOver / $percentageUnder) * 100);
            $finalprogressString = "Alliance " . $currentAlliancenumber . " lossmails - " . $finalprogress . "%";

            echo "`" . $finalprogressString;
        }
        $pagenumber = $pagenumber + 1;
    }

    $currentAlliancenumber = $currentAlliancenumber + 1;

}


$x = 0;
$y = count($Side0KillsArray);
while ( $x < $y ){
    $t = 0;
    $s = count($Side1LossArray);
    while ($t < $s){
        if ( $Side0KillsArray[$x] == $Side1LossArray[$t]->killmail_id){
            $Side0FinalKillsArray[] = $Side1LossArray[$t];
        }
        $t = $t + 1;
    }
    $x = $x + 1;
}



$x = 0;
$y = count($Side1KillsArray);
while ( $x < $y ){
    $t = 0;
    $s = count($Side0LossArray);
    while ($t < $s){
        if ( $Side1KillsArray[$x] == $Side0LossArray[$t]->killmail_id){
            $Side1FinalKillsArray[] = $Side0LossArray[$t];
        }
        $t = $t + 1;
    }
    $x = $x + 1;
}

$AllKills = array();

$AllKills[] = $Side0FinalKillsArray;
$AllKills[] = $Side1FinalKillsArray;


echo "`" . json_encode($AllKills);

?>
