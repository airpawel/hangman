/**
 * Created by Kwolson on 3/27/2017.
 */
/**
 * Created by Kwolson on 3/13/2017.
 */

var tries = 1;
var letters = new Array(35);

{
    letters[0] = "A";
    letters[1] = "Ą";
    letters[2] = "B";
    letters[3] = "C";
    letters[4] = "Ć";
    letters[5] = "D";
    letters[6] = "E";
    letters[7] = "Ę";
    letters[8] = "F";
    letters[9] = "G";
    letters[10] = "H";
    letters[11] = "I";
    letters[12] = "J";
    letters[13] = "K";
    letters[14] = "L";
    letters[15] = "Ł";
    letters[16] = "M";
    letters[17] = "N";
    letters[18] = "Ń";
    letters[19] = "O";
    letters[20] = "Ó";
    letters[21] = "P";
    letters[22] = "Q";
    letters[23] = "R";
    letters[24] = "S";
    letters[25] = "Ś";
    letters[26] = "T";
    letters[27] = "U";
    letters[28] = "V";
    letters[29] = "W";
    letters[30] = "X";
    letters[31] = "Y";
    letters[32] = "Z";
    letters[33] = "Ż";
    letters[34] = "Ź";
}


function drawPassword() {
    $.ajax({
        type: "POST",
        url: "/drawpassword.php",
        dataType: 'json',
        data: { },
        success: function (json) {
            // console.log(result['info']);
            // console.log(json);
            showPassword();
            // console.log("drawPassword");
        },
        error: function (error) {
            // console.log(error);
            console.log("Cos poszlo nie tak :( draw");
        }
    });
}

function showPassword() {
    // console.log("show");
    $.ajax({
        type: "GET",
        url: "/readpassword.php",
        dataType: 'json',
        data: { },
        success: function (json) {
            // console.log(json['info']);
            // console.log(json);
            document.getElementById("gameboard").innerHTML = json['password'].toUpperCase();
            // console.log("showPassword");
        },
        complete: function () {
        },
        error: function () {
            console.log("Cos poszlo nie tak :( show");
        }
    });
}

function start() {
    drawPassword();
    var alphabet_content = "";
    for(var i=0; i<35; i++) {
        var element = "lett" + i;
        alphabet_content = alphabet_content + '<div class="letter" onclick="letterExists('+ i +')" id="'+ element +'">'+ letters[i] +'</div>';
        if ( (i+1)%7 == 0 ) alphabet_content = alphabet_content + '<div style="clear: both;"></div>';
    }
    document.getElementById("alphabet").innerHTML = alphabet_content;
}


function letterExists(num) {
    // console.log("check");
    $.ajax({
        type: "POST",
        url: "/checkletter.php",
        dataType: 'json',
        data: { num: num },
        success: function (json) {
            // console.log(json['info']);
            // console.log(json);
            var exists = (json['exists'] == 1)? true : false;

            var element = "lett"+ num;
            document.getElementById(element).setAttribute("onclick", null);

            if( exists == true ) {
                document.getElementById(element).className = "exists";
                showPassword();
            } else {
                document.getElementById(element).className = "notexists";
                if( tries < 9 ) {
                    // console.log(document.getElementById("gallows").firstElementChild.getAttribute("src"));
                    document.getElementById("gallows").firstElementChild.setAttribute("src", 'img/s'+ tries +'.jpg');
                    tries = tries + 1;
                }
            }
        },
        error: function () {
            console.log("Cos poszlo nie tak :( letterExists");
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    tries = 1;
    start();
});

