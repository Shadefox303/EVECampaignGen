<title>EVE Online Campaign Generator</title>


<h2 align="center">EVE Campaign Maker</h2>

<noscript><p>JavaScript is not enabled.  EVE Generator requires JavaScript to function.</p></noscript>

<div align="center" style="float:inherit;height:475px;background-color:antiquewhite;margin-left:auto;margin-right:auto;max-width:700px;min-width:550px">

    <div style="float:left; margin-left:20% ;margin-right:10px ; margin-top:10px ; margin-bottom:10px">
        <img id="AllianceOneLogoURL" style="min-height:128px;min-width:128px" src="http://image.eveonline.com/Alliance/1_128.png" /><br />
        Alliance One ID<br />
        <input id="AllianceOne" onblur="allianceOneTest()" />
        <p id="A1DBFound"></p>
    </div>

    <div style="float:right; margin-right:20%; margin-left:10px ; margin-top:10px ; margin-bottom:10px">
        <img id="AllianceTwoLogoURL" style="min-height:128px;min-width:128px" src="http://image.eveonline.com/Alliance/1_128.png" /><br />
        Alliance Two ID<br />
        <input id="AllianceTwo" onblur="allianceTwoTest()" />
        <p id="A2DBFound"></p>
    </div>
    <div style="height:170px">

    </div>

    <div style="margin-top:80px">

        <p>Year (YYYY)<input onblur="isReady()" oninput="isReady()" id="Year" /></p>

        <p>Month (MM)<input onblur="isReady()" oninput="isReady()" id="Month" /></p>

        <p>Day (DD)<input onblur="isReady()" oninput="isReady()" id="Day" /></p>

        <p>Hour (HH)<input onblur="isReady()" oninput="isReady()" id="Hour" /></p>


        <button id="StartButton" onclick="RunProgram()" disabled>Start!</button>


    </div>

</div>


<div style="float:inherit;height:120px;background-color:aliceblue;margin-left:auto;margin-right:auto;max-width:700px;min-width:550px">

    <p id="Progress">Program not started yet.</p>
    <p id="AllianceOneKills">Alliance One killed</p>
    <p id="AllianceTwoKills">Alliance Two killed</p>

</div>


<script>
    var alliance1;
    var alliance2;
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





    function RunProgram() {

        resetVariables();



        allianceID1 = document.getElementById("AllianceOne").value;
        allianceID2 = document.getElementById("AllianceTwo").value;
        date = document.getElementById("Year").value + document.getElementById("Month").value + document.getElementById("Day").value + document.getElementById("Hour").value + "00";

        year = document.getElementById("Year").value;
        month = document.getElementById("Month").value;
        day = document.getElementById("Day").value;
        hour = document.getElementById("Hour").value;

        //allianceID1 = "99006371";                    //testing
        //allianceID2 = "99003214";                   //testing


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
        PHPzkb.open("GET", "PHPGetZKBInfo.php?ID1=" + allianceID1 + "&ID2=" + allianceID2 + "&year=" + year + "&month=" + month + "&day=" + day + "&hour=" + hour, true);
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

        var AllianceOneValue = 0;
        var AllianceTwoValue = 0;

        var AllianceOneKills = PHPReturn[0];
        var AllianceTwoKills = PHPReturn[1];

        var x = 0;
        var y = AllianceOneKills.length;
        while (x < y) {
            AllianceOneValue = AllianceOneValue + AllianceOneKills[x].zkb.totalValue;
            x = x + 1;
        }

        x = 0;
        y = AllianceTwoKills.length;
        while (x < y) {
            AllianceTwoValue = AllianceTwoValue + AllianceTwoKills[x].zkb.totalValue;
            x = x + 1;
        }

        var FormatValueAllianceOne = AllianceOneValue.toLocaleString("en-US", { minimumFractionDigits: 2 });
        var FormatValueAllianceTwo = AllianceTwoValue.toLocaleString("en-US", { minimumFractionDigits: 2 });

        document.getElementById("AllianceOneKills").innerHTML = "Alliance One killed " + FormatValueAllianceOne + " isk";
        document.getElementById("AllianceTwoKills").innerHTML = "Alliance Two killed " + FormatValueAllianceTwo + " isk";



    }


    function allianceOneTest() {


        var allianceID = document.getElementById("AllianceOne").value;
        var ESIURL = "http://image.eveonline.com/Alliance/" + String(allianceID) + "_128.png";
        document.getElementById("AllianceOneLogoURL").src = ESIURL;
        var allianceURL;
        allianceURL = "https://esi.evetech.net/latest/alliances/" + document.getElementById("AllianceOne").value + "/?datasource=tranquility";
        var HTTPGet = new XMLHttpRequest();
        HTTPGet.open("GET", allianceURL, false);
        HTTPGet.send();
        var AllianceJSON = JSON.parse(HTTPGet.responseText);
        if (AllianceJSON.error == "Alliance not found") {
            allianceOneReady = false;
        }
        else {
            allianceOneReady = true;
        }

        var alliance1PHP = "kills" + allianceID;
        var allianceDatabase = new XMLHttpRequest();

        allianceDatabase.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (allianceDatabase.responseText == 1) {
                    allianceOneInDatabase = true;
                    document.getElementById("A1DBFound").innerHTML = "Alliance in Database";
                }
                else {
                    document.getElementById("A1DBFound").innerHTML = "Alliance not in Database";

                }
            }
        };

        allianceDatabase.open("GET", "PHPDatabaseCheck.php?ID=" + alliance1PHP, false)
        allianceDatabase.send();




        isReady();

    }

    function allianceTwoTest() {
        var allianceID = document.getElementById("AllianceTwo").value;
        var ESIURL = "http://image.eveonline.com/Alliance/" + String(allianceID) + "_128.png";
        document.getElementById("AllianceTwoLogoURL").src = ESIURL;
        var allianceURL;
        allianceURL = "https://esi.evetech.net/latest/alliances/" + document.getElementById("AllianceTwo").value + "/?datasource=tranquility";
        var HTTPGet = new XMLHttpRequest();
        HTTPGet.open("GET", allianceURL, false);
        HTTPGet.send();
        var AllianceJSON = JSON.parse(HTTPGet.responseText);
        if (AllianceJSON.error == "Alliance not found") {
            allianceTwoReady = false;
        }
        else {
            allianceTwoReady = true;
        }


        var alliance2PHP = "kills" + allianceID;
        var allianceDatabase = new XMLHttpRequest();

        allianceDatabase.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (allianceDatabase.responseText == 1) {
                    allianceTwoInDatabase = true;
                    document.getElementById("A2DBFound").innerHTML = "Alliance in Database";
                }
                else {
                    document.getElementById("A2DBFound").innerHTML = "Alliance not in Database";

                }
            }
        };

        allianceDatabase.open("GET", "PHPDatabaseCheck.php?ID=" + alliance2PHP, false)
        allianceDatabase.send();



        isReady();

    }

    function isReady() {
        var year = document.getElementById("Year").value;
        var month = document.getElementById("Month").value;
        var day = document.getElementById("Day").value;
        var hour = document.getElementById("Hour").value;


        if ((year > 2000) && (year < 2020) && (month < 13) && (String(month).length == 2) && (day < 34) && (String(day).length == 2) && (hour < 25) && (String(hour).length == 2) && (allianceOneReady == true) && (allianceTwoReady == true)) {
            document.getElementById("StartButton").disabled = "";
        }
        else {
            document.getElementById("StartButton").disabled = "disabled";
        }

    }

    function resetVariables() {
        alliance1;
        alliance2;
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

