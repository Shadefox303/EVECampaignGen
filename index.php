<title>EVE Online Campaign Generator</title>


<h2 align="center">EVE Campaign Maker</h2>

<noscript><p>JavaScript is not enabled.  EVE Generator requires JavaScript to function.</p></noscript>

<div id="Alliances" align="center" style="float:inherit;height:775px;background-color:antiquewhite;margin-left:auto;margin-right:auto;max-width:700px;min-width:550px">


    <button onclick="removeAlliance(1)">Remove Last Alliance</button>

    <br />
    <div id="AllianceSide0" style="float:left;margin-right:10px ; margin-top:20px ; margin-bottom:10px; height:700px ; width : 300px; background-color:grey">



        <div style=" margin-left:27.5% ;margin-right:10px ;  margin-bottom:10px; ">
            <button onclick="addAlliance(0)">Add</button>
        </div>




        <div id="AllianceDIV1" style=" margin-left:20% ;margin-right:10px ; margin-top:20px ; margin-bottom:10px ">
            <img id="AllianceLogoURL1" style="min-height:128px;min-width:128px" src="http://image.eveonline.com/Alliance/1_128.png" /><br />
            Alliance 1 ID<br />
            <input id="Alliance1" onblur="allianceTest(1)" />
            <p id="ADBFound1"></p>
        </div>




    </div>




    <div id="AllianceSide1" style="float: right; margin-left:10px ; margin-top:20px ; margin-bottom:10px; height:700px ; width : 300px; background-color:grey">



        <div style="margin-right:27.5%; margin-left:10px ;  margin-bottom:10px ">
            <button onclick="addAlliance(1)">Add</button>
        </div>

        <div id="AllianceDIV2" style="margin-right:20%; margin-left:10px ; margin-top:20px ; margin-bottom:10px ">
            <img id="AllianceLogoURL2" style="min-height:128px;min-width:128px" src="http://image.eveonline.com/Alliance/1_128.png" /><br />
            Alliance 2 ID<br />
            <input id="Alliance2" onblur="allianceTest(2)" />
            <p id="ADBFound2"></p>
        </div>



    </div>






</div>



<div style="float:inherit;height:200px;background-color:antiquewhite;margin-left:auto;margin-right:auto;max-width:700px;min-width:550px">

    <p>Year (YYYY)<input onblur="isReady()" oninput="isReady()" id="Year" /></p>

    <p>Month (MM)<input onblur="isReady()" oninput="isReady()" id="Month" /></p>

    <p>Day (DD)<input onblur="isReady()" oninput="isReady()" id="Day" /></p>

    <p>Hour (HH)<input onblur="isReady()" oninput="isReady()" id="Hour" /></p>


    <button id="StartButton" onclick="RunProgram()" disabled>Start!</button>


</div>



<div style="float:inherit;height:120px;background-color:aliceblue;margin-left:auto;margin-right:auto;max-width:700px;min-width:550px">

    <p id="Progress">Program not started yet.</p>
    <p id="Side0Kill">Alliance One killed</p>
    <p id="Side1Kill">Alliance Two killed</p>

</div>


<script>


    var allianceID1;
    var allianceID2;
    var allianceID3;
    var allianceID4;
    var allianceID5;
    var allianceID6;
    var alliance1side;
    var alliance2side;
    var alliance3side;
    var alliance4side;
    var alliance5side;
    var alliance6side;
    var killPageNumber = 1;
    var lossPageNumber = 1;
    var JSONString = "N/A";
    var date;
    var year;
    var month;
    var day;
    var hour;
    var killMailIDArray = [];
    var listEndTest = "NotUsedYet";
    var FetchKMURL;
    var FetchLossMailURL;
    var timeOut;
    var LossmailEnd;
    var lossMailIDArray = [];
    var totalKillValue = 0;
    var KillmailDate;
    var ESIURL;
    var ESIHash;
    var ESIKMID;
    var lossMailsAmount = 0;
    var allKillMailsFound = false;
    var allLossMailsFound = false;
    var allianceOneReady = false;
    var allianceTwoReady = false;
    var allianceOneInDatabase = false;
    var allianceTwoInDatabase = false;
    var PHPReturn;
    var previouslength = 0;

    var PHPzkb = new XMLHttpRequest();

    var timer;


    var LastAlliance = 2;

    var listofAlliances = new Array();

    var listofAlliances = [['Alliance1', 'false', 0, 0], ['Alliance2', 'false', 1, 0], ['Alliance3', 'false', 1, 0], ['Alliance4', 'false', 1, 0], ['Alliance5', 'false', 1, 0], ['Alliance6', 'false', 1, 0]];


    function addAlliance(input) {

        if (LastAlliance == 6) {

        }
        else {

            LastAlliance = LastAlliance + 1;
            var float;
            var side = input;
            if (side == 0) {
                info = '<div id="AllianceDIV' + LastAlliance + '" style="margin-left:20%;margin-right:10px '
            }
            else {
                info = '<div id="AllianceDIV' + LastAlliance + '" style="margin-right:20%;margin-left:10px '
            }
            document.getElementById("AllianceSide" + side).innerHTML += '' + info + ' ; margin-top:20px ; margin-bottom:10px"> <img id="AllianceLogoURL' + LastAlliance + '" style="min-height:128px;min-width:128px" src="http://image.eveonline.com/Alliance/1_128.png" /><br />  Alliance ' + LastAlliance + ' ID<br />  <input id="Alliance' + LastAlliance + '" onblur="allianceTest(' + LastAlliance + ')" /> <p id="ADBFound' + LastAlliance + '"></p> </div>';

            var NewAllianceString = "Alliance" + LastAlliance;


            listofAlliances[LastAlliance - 1][3] = side;




        }


    }

    function removeAlliance(input) {

        if (LastAlliance == 1) {

        }
        else {

            var side = input;
            document.getElementById("AllianceDIV" + LastAlliance).remove();
            listofAlliances[LastAlliance - 1][1] = false;
            listofAlliances[LastAlliance - 1][2] = 0;
            listofAlliances[LastAlliance - 1][3] = 0;

            LastAlliance = LastAlliance - 1;


        }



    }







    function RunProgram() {

        resetVariables();




        if (listofAlliances[0][3] !== 0) {
            allianceID1 = listofAlliances[0][3];
            alliance1side = listofAlliances[0][2];
        }
        if (listofAlliances[1][3] !== 0) {
            allianceID2 = listofAlliances[1][3];
            alliance2side = listofAlliances[1][2];
        }
        if (listofAlliances[2][3] !== 0) {
            allianceID3 = listofAlliances[2][3];
            alliance3side = listofAlliances[2][2];
        }
        if (listofAlliances[3][3] !== 0) {
            allianceID4 = listofAlliances[3][3];
            alliance4side = listofAlliances[3][2];
        }
        if (listofAlliances[4][3] !== 0) {
            allianceID5 = listofAlliances[4][3];
            alliance5side = listofAlliances[4][2];
        }
        if (listofAlliances[5][3] !== 0) {
            allianceID6 = listofAlliances[5][3];
            alliance6side = listofAlliances[5][2];
        }













        date = document.getElementById("Year").value + document.getElementById("Month").value + document.getElementById("Day").value + document.getElementById("Hour").value + "00";

        year = document.getElementById("Year").value;
        month = document.getElementById("Month").value;
        day = document.getElementById("Day").value;
        hour = document.getElementById("Hour").value;



        PHPzkb.onreadystatechange = function () {
            if (PHPzkb.readyState == 4 && PHPzkb.status == 200) {

                clearInterval(timer);

                var foundBreak = false;
                var fulllength = PHPzkb.responseText.length
                var fullString = PHPzkb.responseText;
                var x = 0;
                y = fulllength;
                while ((y > x) && (foundBreak == false)) {
                    y = y - 1;
                    var currentChar = fullString.charAt(y);
                    if (currentChar == "`") {

                        foundBreak = true
                    };
                }


                PHPReturn = JSON.parse(PHPzkb.responseText.substring(y + 1));
                SortData();
            };
        }
        PHPzkb.open("GET", "PHPGetZKBInfo.php?ID1=" + allianceID1 + "&ID2=" + allianceID2 + "&ID3=" + allianceID3 + "&ID4=" + allianceID4 + "&ID5=" + allianceID5 + "&ID6=" + allianceID6 + "&Side1=" + alliance1side + "&Side2=" + alliance2side + "&Side3=" + alliance3side + "&Side4=" + alliance4side + "&Side5=" + alliance5side + "&Side6=" + alliance6side + "&year=" + year + "&month=" + month + "&day=" + day + "&hour=" + hour, true);
        PHPzkb.send();


        getProgress();

    }






    function getProgress() {

        timer = setInterval(function () {

            try {
                var foundBreak = false;
                var fulllength = PHPzkb.responseText.length
                var fullString = PHPzkb.responseText;
                var x = 0;
                y = fulllength;
                while ((y > x) && (foundBreak == false)) {
                    y = y - 1;
                    var currentChar = fullString.charAt(y);
                    if (currentChar == "`") {

                        foundBreak = true
                    };
                }
                document.getElementById("Progress").innerHTML = fullString.substring(y + 1);
            }
            finally {
            }
        }, 3000);

    }



    function SortData() {

        var Side0Value = 0;
        var Side1Value = 0;

        var Side0Kills = PHPReturn[0];
        var Side1Kills = PHPReturn[1];

        var x = 0;
        var y = Side0Kills.length;
        while (x < y) {
            Side0Value = Side0Value + Side0Kills[x].zkb.totalValue;
            x = x + 1;
        }

        x = 0;
        y = Side1Kills.length;
        while (x < y) {
            Side1Value = Side1Value + Side1Kills[x].zkb.totalValue;
            x = x + 1;
        }

        var FormatValueSide0 = Side0Value.toLocaleString("en-US", { minimumFractionDigits: 2 });
        var FormatValueSide1 = Side1Value.toLocaleString("en-US", { minimumFractionDigits: 2 });

        document.getElementById("Side0Kill").innerHTML = "Side 0 killed " + FormatValueSide0 + " isk";
        document.getElementById("Side1Kill").innerHTML = "Side 1 killed " + FormatValueSide1 + " isk";



    }


    function allianceTest(Alliancetested) {


        var allianceID = document.getElementById("Alliance" + Alliancetested).value;
        var ESIURL = "http://image.eveonline.com/Alliance/" + String(allianceID) + "_128.png";
        document.getElementById("AllianceLogoURL" + Alliancetested).src = ESIURL;
        var allianceURL;
        allianceURL = "https://esi.evetech.net/latest/alliances/" + document.getElementById("Alliance" + Alliancetested).value + "/?datasource=tranquility";
        var HTTPGet = new XMLHttpRequest();
        HTTPGet.open("GET", allianceURL, false);
        HTTPGet.send();
        var AllianceJSON = JSON.parse(HTTPGet.responseText);
        if (AllianceJSON.error == "Alliance not found") {

            listofAlliances[Alliancetested - 1][1] = false;
            listofAlliances[Alliancetested - 1][3] = 0;
        }
        else {
            listofAlliances[Alliancetested - 1][1] = true;
            listofAlliances[Alliancetested - 1][3] = allianceID;
        }

        var alliance1PHP = "kills" + allianceID;
        var allianceDatabase = new XMLHttpRequest();

        allianceDatabase.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (allianceDatabase.responseText == 1) {
                    document.getElementById("ADBFound" + Alliancetested).innerHTML = "Alliance in Database";
                }
                else {
                    document.getElementById("ADBFound" + Alliancetested).innerHTML = "Alliance not in Database";

                }
            }
        };

        allianceDatabase.open("GET", "PHPDatabaseCheck.php?ID=" + alliance1PHP, false)
        allianceDatabase.send();


        isReady();

    }


    function isReady() {
        var year = document.getElementById("Year").value;
        var month = document.getElementById("Month").value;
        var day = document.getElementById("Day").value;
        var hour = document.getElementById("Hour").value;

        var allAlliancesReady = true;

        var x = 0;
        var y = LastAlliance;
        while (x < y) {
            if (listofAlliances[x][1] == false) {
                allAlliancesReady = false;
            }
            x = x + 1;
        }


        if ((year > 2000) && (year < 2020) && (month < 13) && (String(month).length == 2) && (day < 34) && (String(day).length == 2) && (hour < 25) && (String(hour).length == 2) && (allAlliancesReady == true) && (LastAlliance > 1)) {
            document.getElementById("StartButton").disabled = "";
        }
        else {
            document.getElementById("StartButton").disabled = "disabled";
        }

    }

    function resetVariables() {


        killPageNumber = 1;
        lossPageNumber = 1;
        JSONString = "N/A";
        date;
        year;
        month;
        day;
        hour;
        killMailIDArray = [];
        listEndTest = "NotUsedYet";
        FetchKMURL;
        FetchLossMailURL;
        timeOut;
        LossmailEnd;
        lossMailIDArray = [];
        totalKillValue = 0;
        KillmailDate;
        ESIURL;
        ESIHash;
        ESIKMID;
        lossMailsAmount = 0;
        allKillMailsFound = false;
        allLossMailsFound = false;
        allianceOneReady = false;
        allianceTwoReady = false;
        allianceOneInDatabase = false;
        allianceTwoInDatabase = false;
        PHPReturn;
        previouslength = 0;

        PHPzkb = new XMLHttpRequest();

        timer;


    }

</script>

