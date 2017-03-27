/**
 * Created by Kwolson on 3/27/2017.
 */
/**
 * Created by Kwolson on 3/13/2017.
 */

var password = "Bez pracy nie ma kolaczy";

var hiddenpass = "";

var letters = new Array(35);

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

var tries = 1;

for(var i=0; i<password.length; i++) {
    if(password.charAt(i) == " ") hiddenpass = hiddenpass + " ";
    else hiddenpass = hiddenpass + "-";
}

function drawPassword() {
    $.ajax({
        type: "POST",
        url: "drawpassword.php",
        dataType: 'json',
        data: { },
        success: function (result) {
            console.log(result['info']);
            console.log(result);
        },
        complete: function () {

        },
        error: function () {
            console.log("Cos poszlo nie tak :(");
        }
    });
}

function showPassword() {
    document.getElementById("gameboard").innerHTML = hiddenpass.toUpperCase();
}

function start() {
    var alphabet_content = "";
    for(var i=0; i<35; i++) {
        var element = "lettt" + i;
        alphabet_content = alphabet_content + '<div class="letter" onclick="letterExists('+ i +')" id="'+ element +'">'+ letters[i] +'</div>';
        if ( (i+1)%7 == 0 ) alphabet_content = alphabet_content + '<div style="clear: both;"></div>';
    }
    document.getElementById("alphabet").innerHTML = alphabet_content;
}


String.prototype.setSign = function (sign, place) {
    if( place > this.length - 1) return this.toString();
    else return this.substr(0, place) + sign + this.substr(place+1);
}

function letterExists(num) {
    console.log(num);
    var exists = false;
    for(var i=0; i<password.length; i++) {
        if (password.charAt(i).toUpperCase() === letters[num]) {
            exists = true;
            hiddenpass = hiddenpass.setSign(letters[num],i);
        }
    }
    console.log(password);
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

}

document.addEventListener("DOMContentLoaded", function () {
    tries = 1;
    start();
    showPassword();
    drawPassword();
});
