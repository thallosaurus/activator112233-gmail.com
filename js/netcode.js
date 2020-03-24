const IN = 1;
const OUT = 0;

const RPC_PATH = "db/"

const xhr = new XMLHttpRequest({
    async: true
});

var currentPerm = "0";

function sendRequest(url, arguments, callback, onError, external) {
    document.body.style.cursor = "wait";
    console.log("SendRequest", arguments);
    let params =
        parameterize(arguments);

    xhr.open("POST", RPC_PATH + url);
    
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function(e) {

            if (xhr.readyState == xhr.DONE && xhr.status == 200) {
                let data;

                try
                {
                    data = JSON.parse(xhr.responseText);
                }
                catch (e)
                {
                    console.log(e);
                    console.log(xhr.responseText);
                }

                document.body.style.cursor = "default";

                if (data.status == "~Error") {
                    if (onError != undefined) {
                        onError(data);
                    } else {
                        alert(data.error_message);
                    }
                } 
                else if (data.status == "~Fatal")
                {
                    //Fatal Server Error!
                    console.log(alert(
                        "Fatal Server Error\r\n" + 
                        data.details.errornumber + ": " + data.details.errorstring + "\r\n"
                        + "in " + data.details.errorfile + " at line " + data.details.errorline
                        + "\r\n\r\nEs wurde keine Serverantwort erhalten. Versuchen Sie es später erneut und informieren Sie die Programmierabteilung."
                    ));
                    console.log(data);
                    return;
                }
                else {
                    callback(data);
                }
            } else {
                //debugOut(IN, xhr.responseText);
            }
        }
        //xhr.send();
    xhr.send(params);
}

function parameterize(array) {
    var url = "";
    for (i = 0; i < array.length; i++) {
        //if (i == 0) {
        //    url += "" + array[i];
        //} else {
        url += "&" + array[i];
        //}
    }

    return url;
}

function sendExternalRequest(url, arguments, callback, onError)
{
	
}
/*
//dont use pls
function off(str) {
    let retStr = "";
    for (i = 0; i < str.length; i++) {
        if (table[str[i]] != undefined) {
            retStr += table[str[i]];
        } else {
            retStr += str[i];
        }
    }
    return retStr;
}

function on(str) {
    retStr = str.replaceAll(table[key], key);
    return retStr;
}

function setArbitraryPermissions(p) {
    currentPerm = p;
}
*/