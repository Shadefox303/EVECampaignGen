<title>EVE Online Campaign Generator</title>


<h2 align="center">EVE Campaign Generator</h2>

<noscript><p>JavaScript is not enabled.  EVE Generator requires JavaScript to function.</p></noscript>


<body>



<div id="Alliances" align="center" style="border-radius:10px;background-color:rgb(108, 129, 199);height:775px;margin-left:auto;margin-right:auto;max-width:700px;min-width:550px">


    <button onclick="removeAlliance(1)">Remove Last Alliance</button>

    <br />
    <div id="AllianceSide0" style="border-radius:5px;float:left;margin-left:50px;margin-right:10px ; margin-top:20px ; margin-bottom:10px; height:700px ; width : 250px;  background-color:cornflowerblue;overflow-y:scroll">



        <div style="margin-right:10px ;  margin-bottom:10px; ">
            <button onclick="addAlliance(0)">Add</button>
        </div>




        <div id="AllianceDIV1" style="border-radius:5px;width:170px;height:220px ;margin-left:10px ;margin-right:10px ; margin-top:20px ; margin-bottom:10px ; background-color:grey">
            <img id="AllianceLogoURL1" style="min-height:128px;min-width:128px" src="http://image.eveonline.com/Alliance/1_128.png" /><img id="AlliancePassed1" src="RedCross.png" style="margin-bottom:40px;width:30px;height:30px"/>
            <br />
            <select onchange="IsAlliance(0,value)"><option value="1">Alliance</option><option value="0">Corp</option></select> 
             1 ID<br />
            <input id="Alliance1" onblur="allianceTest(1)" />
            <p id="ADBFound1"></p>
        </div>


    </div>




    <div id="AllianceSide1" style="border-radius:5px;float: right;margin-right:50px ;margin-left:10px ; margin-top:20px ; margin-bottom:10px; height:700px ; width : 250px; background-color:indianred;overflow-y:scroll">



        <div style=" margin-left:10px ;  margin-bottom:10px ">
            <button onclick="addAlliance(1)">Add</button>
        </div>

        <div id="AllianceDIV2" style="border-radius:5px;width:170px;height:220px;margin-right:10px; margin-left:10px ; margin-top:20px ; margin-bottom:10px ; background-color:grey">
            <img id="AlliancePassed2" src="RedCross.png" style="margin-bottom:40px;width:30px;height:30px"/><img id="AllianceLogoURL2" style="min-height:128px;min-width:128px" src="http://image.eveonline.com/Alliance/1_128.png" /><br />
            <select onchange="IsAlliance(1,value)"><option value="1">Alliance</option><option value="0">Corp</option></select>
            2 ID<br />
            <input id="Alliance2" onblur="allianceTest(2)" />
            <p id="ADBFound2"></p>
        </div>



    </div>






</div>



<div style="padding-left:10px;padding-top:1px;border-radius:15px;float:inherit;height:200px;background-color:antiquewhite;margin-left:auto;margin-right:auto;max-width:700px;min-width:550px">

    <p>Year (YYYY)<input onblur="isReady()" oninput="isReady()" id="Year" /></p>

    <p>Month (MM)<input onblur="isReady()" oninput="isReady()" id="Month" /></p>

    <p>Day (DD)<input onblur="isReady()" oninput="isReady()" id="Day" /></p>

    <p>Hour (HH)<input onblur="isReady()" oninput="isReady()" id="Hour" /></p>


    <button id="StartButton" onclick="RunProgram()" disabled>Start!</button>


</div>



<div style="padding-left:10px;padding-top:1px;border-radius:15px;float:inherit;height:120px;background-color:aliceblue;margin-left:auto;margin-right:auto;max-width:700px;min-width:550px">

    <p id="Progress">Program not started yet.</p>
    <p id="Side0Kill">Blue side killed</p>
    <p id="Side1Kill">Red side killed</p>

</div>


</body>


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
    var allianceAlliance1;
    var allianceAlliance2;
    var allianceAlliance3;
    var allianceAlliance4;
    var allianceAlliance5;
    var allianceAlliance6;
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

    var listofAlliances = new Array();                                      //          0              1               2                       3               4
                                                                            // Alliance number  /  Is ready  /   Side they're on      /  AllianceID    /  Is an alliance?     //
    var listofAlliances = [['Alliance1', 'false', 0, 0, 1], ['Alliance2', 'false', 1, 0, 1], ['Alliance3', 'false', 1, 0, 1], ['Alliance4', 'false', 1, 0, 1], ['Alliance5', 'false', 1, 0, 1], ['Alliance6', 'false', 1, 0, 1]];


    function IsAlliance(number, istrue) {
        listofAlliances[number][4] = istrue;
        var checkstring = "Alliance" + (number + 1);
        var check = document.getElementById(checkstring).value;
        if (check != "") {
            allianceTest(number + 1)
        }
    }



    function addAlliance(input) {

        if (LastAlliance == 6) {

        }
        else {

            LastAlliance = LastAlliance + 1;
            var float;
            var side = input;
            if (side == 0) {
                info = '<div id="AllianceDIV' + LastAlliance + '" style="border-radius:5px;width:170px;height:220px;margin-left:10px;margin-right:10px  ; margin-top:20px ; margin-bottom:10px; background-color:grey"> <img id="AllianceLogoURL' + LastAlliance + '" style="min-height:128px;min-width:128px" src="http://image.eveonline.com/Alliance/1_128.png" />  <img id="AlliancePassed' + LastAlliance + '" src="RedCross.png" style="margin-bottom:40px;width:30px;height:30px"/>'
            }
            else {
                info = '<div id="AllianceDIV' + LastAlliance + '" style="border-radius:5px;width:170px;height:220px;margin-right:10px;margin-left:10px  ; margin-top:20px ; margin-bottom:10px; background-color:grey"> <img id="AlliancePassed' + LastAlliance + '" src="RedCross.png" style="margin-bottom:40px;width:30px;height:30px"/> <img id="AllianceLogoURL' + LastAlliance + '" style="min-height:128px;min-width:128px" src="http://image.eveonline.com/Alliance/1_128.png" />'
            }
            document.getElementById("AllianceSide" + side).innerHTML += '' + info + ' <br /><select onchange="IsAlliance('+(LastAlliance - 1)+',value)"><option value="1">Alliance</option><option value="0">Corp</option></select> ' + LastAlliance + ' ID<br />  <input id="Alliance' + LastAlliance + '" onblur="allianceTest(' + LastAlliance + ')" /> <p id="ADBFound' + LastAlliance + '"></p> </div>';

            var NewAllianceString = "Alliance" + LastAlliance;


            listofAlliances[LastAlliance - 1][2] = side;




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
            allianceAlliance1 = listofAlliances[0][4];
        }
        if (listofAlliances[1][3] !== 0) {
            allianceID2 = listofAlliances[1][3];
            alliance2side = listofAlliances[1][2];
            allianceAlliance2 = listofAlliances[1][4];
        }
        if (listofAlliances[2][3] !== 0) {
            allianceID3 = listofAlliances[2][3];
            alliance3side = listofAlliances[2][2];
            allianceAlliance3 = listofAlliances[2][4];
        }
        if (listofAlliances[3][3] !== 0) {
            allianceID4 = listofAlliances[3][3];
            alliance4side = listofAlliances[3][2];
            allianceAlliance4 = listofAlliances[3][4];
        }
        if (listofAlliances[4][3] !== 0) {
            allianceID5 = listofAlliances[4][3];
            alliance5side = listofAlliances[4][2];
            allianceAlliance5 = listofAlliances[4][4];
        }
        if (listofAlliances[5][3] !== 0) {
            allianceID6 = listofAlliances[5][3];
            alliance6side = listofAlliances[5][2];
            allianceAlliance6 = listofAlliances[5][4];
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
        PHPzkb.open("GET", "PHPGetZKBInfo.php?ID1=" + allianceID1 + "&ID2=" + allianceID2 + "&ID3=" + allianceID3 + "&ID4=" + allianceID4 + "&ID5=" + allianceID5 + "&ID6=" + allianceID6 + "&Side1=" + alliance1side + "&Side2=" + alliance2side + "&Side3=" + alliance3side + "&Side4=" + alliance4side + "&Side5=" + alliance5side + "&Side6=" + alliance6side + "&isAlliance1=" + allianceAlliance1 + "&isAlliance2=" + allianceAlliance2 + "&isAlliance3=" + allianceAlliance3 + "&isAlliance4=" + allianceAlliance4 + "&isAlliance5=" + allianceAlliance5 + "&isAlliance6=" + allianceAlliance6 + "&year=" + year + "&month=" + month + "&day=" + day + "&hour=" + hour, true);
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

        document.getElementById("Side0Kill").innerHTML = "Blue side killed " + FormatValueSide0 + " isk";
        document.getElementById("Side1Kill").innerHTML = "Red side killed " + FormatValueSide1 + " isk";



    }


    function allianceTest(Alliancetested) {

        var AorCimage;
        var AorCURL;
        if (listofAlliances[Alliancetested - 1][4] == 1) {
            AorCimage = "Alliance";
            AorCURL = "alliances";
        }
        else {
            AorCimage = "Corporation";
            AorCURL = "corporations"
        }


        var allianceID = document.getElementById("Alliance" + Alliancetested).value;
        var ESIURL = "http://image.eveonline.com/" + AorCimage + "/" + String(allianceID) + "_128.png";
        document.getElementById("AllianceLogoURL" + Alliancetested).src = ESIURL;
        var allianceURL;
        allianceURL = "https://esi.evetech.net/latest/" + AorCURL + "/" + document.getElementById("Alliance" + Alliancetested).value + "/?datasource=tranquility";



        var HTTPGet = new XMLHttpRequest();
        HTTPGet.onreadystatechange = function () {
            if (HTTPGet.readyState == 4) {
                var AllianceJSON = JSON.parse(HTTPGet.responseText);
                if (AllianceJSON.error == "Alliance not found" || AllianceJSON.error == "Corporation not found"  ||  AllianceJSON.error == "Not found") {

                    listofAlliances[Alliancetested - 1][1] = false;
                    listofAlliances[Alliancetested - 1][3] = 0;
                    document.getElementById("AlliancePassed" + Alliancetested).src = "RedCross.png"
                }
                else {
                    listofAlliances[Alliancetested - 1][1] = true;
                    listofAlliances[Alliancetested - 1][3] = allianceID;
                    document.getElementById("AlliancePassed" + Alliancetested).src = "GreenTick.png";
                }



            }
        }
        HTTPGet.open("GET", allianceURL, true);
        HTTPGet.send();








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




        allianceID1 = 0;
        allianceID2 = 0;
        allianceID3 = 0;
        allianceID4 = 0;
        allianceID5 = 0;
        allianceID6 = 0;
        alliance1side = 0;
        alliance2side = 0;
        alliance3side = 0;
        alliance4side = 0;
        alliance5side = 0;
        alliance6side = 0;
        allianceAlliance1 = 0;
        allianceAlliance2 = 0;
        allianceAlliance3 = 0;
        allianceAlliance4 = 0;
        allianceAlliance5 = 0;
        allianceAlliance6 = 0;
        killPageNumber = 1;
        lossPageNumber = 1;
        JSONString = "N/A";
        date = 0;
        year = 0;
        month = 0;
        day = 0;
        hour = 0;
        killMailIDArray = [];
        listEndTest = "NotUsedYet";
        FetchKMURL = 0;
        FetchLossMailURL = 0;
        timeOut = 0;
        LossmailEnd = 0;
        lossMailIDArray = [];
        totalKillValue = 0;
        KillmailDate = 0;
        ESIURL = 0;
        ESIHash = 0;
        ESIKMID = 0;
        lossMailsAmount = 0;
        allKillMailsFound = false;
        allLossMailsFound = false;
        allianceOneReady = false;
        allianceTwoReady = false;
        allianceOneInDatabase = false;
        allianceTwoInDatabase = false;
        PHPReturn = 0;
        previouslength = 0;

        PHPzkb = new XMLHttpRequest();

        timer = 0;





    }

</script>

