let x;

const ISA_LAT = 49.681790;
const ISA_LON = 12.083650;

const LINK = 0;
const PHOTO = 2;
const TEXT = 1;

let currLat, currLon, gpsId;

let page = 0;
let category = -1;

let threadId;

let geoOptions = {
    enableHighAccuracy: false,
    timeout: 5000,
    maximumAge: 0
}

let modalsOptions = {

}

let categories = {
    acdata: {},
    icon_data:{},
    strings: {}
};

class TableRow {
    constructor(dbData)
    {
        //console.log(dbData);
        this.data = {};
        this.data.title = decodeURIComponent(dbData.title);
        this.type = parseInt(dbData.type);
        this.data.category = "#" + categories.strings[parseInt(dbData.category)]; //categories.strings[d.id]
        this.data.distance = Math.round(dbData.distance) + " km";
        this.data.age = this.getAge(dbData.age);
        this.id = dbData.id;
        this.iconstring = ["link", "text_fields", "insert_photo"][this.type];
        this.setBackground(0);
        console.log(this);
    }

    //pass in milliseconds and get back textual representation
    //Age: 3 min old
    //Age: 5 hours old
    //etc...
    getAge(millisec) {

        var seconds = (millisec / 1000).toFixed(0);

        var minutes = (millisec / (1000 * 60)).toFixed(0);

        var hours = (millisec / (1000 * 60 * 60)).toFixed(0);

        var days = (millisec / (1000 * 60 * 60 * 24)).toFixed(0);

        if (seconds < 60) {
            return seconds + " s";
        } else if (minutes < 60) {
            return minutes + " m";
        } else if (hours < 24) {
            return hours + " h";
        } else {
            return days + " d"
        }
    }

    setBackground(i)
    {
        this.class = "mod" + i;
    }

    parse()
    {
        let row = document.createElement("tr");
        row.dataset.href = "view.php?id=" + this.id;
        row.append(this.getIcon());
        //row.className = "highlight";
        for (let val in this.data)
        {
            let td = document.createElement("td");
            td.innerText = this.data[val];
            row.append(td);
        }
        row.dataset.id = this.id;
        //row.className = this.class;
        row.addEventListener("click", this.callback);
        return row;
    }

    getIcon()
    {
        let td = document.createElement("td");
        let i = document.createElement("i");
        i.className = "material-icons";
        i.innerText =  this.iconstring;
        td.append(i);
        return td;
    }

    registerOnClickHandler(cb)
    {
        this.callback = cb;
    }
}

function askForLocation()
{
    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(retrieve_posts_in_location, showError);
    }
    else
    {
        alert("Geolocation is not supported by this device, try another one.");
    }
}

function debugPosition(position)
{
    console.log(position);
    //document.getElementById("debug").innerHTML = "Latitude: " + position.coords.latitude +" Longitude: " + position.coords.longitude;
}

function update()
{
    askForLocation();
}

function debugOut(d)
{
    document.getElementById("debug").innerHTML += d.toString() + "<br>";
}

function saveCoordinates(position)
{
    currLat = position.coords.latitude;
    currLon = position.coords.longitude;
}

function retrieve_posts_in_location(position)
{
    saveCoordinates(position);
    debugPosition(position);
    update();
}

function update()
{
    sendRequest("call.php",
    [
        "action=update",
        "lat=" + currLat,
        "lon=" + currLon,
        "cat=" + category,
        "page=" + page
    ],
    insertDataIntoTable);
}

function insertDataIntoTable(data)
{
    console.log(data);

    saveCategories(data.content.cat);

    let posts = document.getElementById("posts");
    posts.innerHTML = "";
    let table = buildTable(data.content.posts);
    posts.append(table);
}

function buildTable(data)
{
    console.log(data);
    var table = document.createElement("table");
    table.className = "highlight";

    //add Headers
    let header_text = ["Type", "Title", "Category", "Distance", "Age"];
    let header = document.createElement("tr");
    for (let i = 0; i < header_text.length; i++)
    {
        let th = document.createElement("th");
        th.innerText = header_text[i];
        header.append(th);
    }
    table.append(header);

    for (let i = 0; i < data.length; i++)
    {
        let row = new TableRow(data[i]);
        row.setBackground(i % 2);
        row.registerOnClickHandler(onMainClickUI);
        table.append(row.parse());
    }
    return table;
}

function onMainClickUI(e)
{
    console.log(e);
    location.href = location.origin + "/" + e.target.parentElement.dataset.href;
    /*let dataset = e.target.parentElement.dataset;
    console.log(dataset);
    sendRequest("call.php",
    [
        "action=open",
        "id=" + dataset.id
    ], function(data) {
        console.log(data);
        //location.href = "view.php?id=" + data.content.id;
    });*/
}

function showError(error) {
    let str = "";
    switch(error.code) {
        case error.PERMISSION_DENIED:
            str += "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            str += "Location information is unavailable."
            break;
        case error.TIMEOUT:
            str += "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            str += "An unknown error occurred."
            break;
    }

    console.log(str);
}

function add_post(elem)
{
    console.log(elem.dataset);
    let args = [
        "action=post",
        "type=" + parseInt(elem.dataset.t),
        "lat=" + currLat,
        "lon=" + currLon
    ];

    let error = false;

    for (let i = 0; i < elem.length; i++)
    {
        if (elem[i].type != "submit" && elem[i].type != "button")
        {
            if (elem[i].value != "")
            {
                console.log(elem[i].type);
                //debugger;
                switch (elem[i].type)
                {
                    case "text":
                    case "textarea":
                        if (elem[i].dataset.dontuse != undefined) break;

                        console.log(elem[i].value);
                        args.push(encodeForXfer(elem[i]));
                        break;

                    case "file":
                        let pic = AsyncProcessPicture(elem[i].files);
                        console.log(pic);
                        pic.then((data) => 
                        {
                            args.push("media=" + btoa(encodeURIComponent(data.result)));
                            sendRequest("call.php", args, showToast);
                            console.log(args);
                        });
                        //
                        break;
                }
            }
            else
            {
                console.log(elem[i]);
                M.toast({html: elem[i].id + " cant be empty"});
                error = true;
                break;
            }
        }
    }
    //console.log(args);
    //if (elem.dataset.t != "1")

    if (!error) sendRequest("call.php", args, closeModal);   //file requests are handled seperately
}

function closeModal(data)
{
    let add_text_elem = document.getElementById("add_text");
    console.log(add_text_elem);
    //debugger;
    let instance = M.Modal.getInstance(add_text_elem);
    instance.close();
    showToast(data);
}

function encodeForXfer(elem, onError)
{
    let length = elem.dataset.length != undefined ? elem.dataset.length : 100;
    if (elem.value != "")
    {
        return elem.id + "=" + encodeURIComponent(elem.value.substring(0, length));
    }
    else
    {
        if (onError != undefined)
        {
            onError();
        }
    }    
}

function showToast(data)
{
    update();
    console.log(data);
    M.toast({html: "Success! Post-Id: " + data.content});
}

async function AsyncProcessPicture(files)
{
    return new Promise((resolve, reject) => {
        let fr = new FileReader();
        fr.onload = resolve;
        fr.readAsText(files[0]);
    });
    //return btoa()
}

window.onload = function()
{
    let url = new this.URLSearchParams(location.search);
    threadId = url.get("id") != null ? url.get("id") : -1;
    category = url.get("cat") != null ? url.get("cat") : -1;
    
    x = document.getElementById("x");
    //initOverscrollLoad();
    M.AutoInit();
    initMaterialize();
    //

    enable_location();
}

function initOverscrollLoad()
{
    window.addEventListener("scroll", function(e)
    {
        console.log(e);
        let scrollPos = document.documentElement.scrollTop;
        if (scrollPos > 50)
        {
            console.log("Overscrolled");
        }
    });
}

function enable_location()
{
    //hideGPSCard();
    geoId = navigator.geolocation.watchPosition(this.retrieve_posts_in_location, this.showError, geoOptions);
}

function hideGPSCard()
{
    let card = document.getElementById("activate_gps");
    console.log(card);
    card.parentElement.remove(card);
}

function saveCategories(data)
{
    console.log(data);
    for (let d of data)
    {
        categories.acdata[d.value] = null;
        categories.icon_data[d.value] = d.icon;
        categories.strings[d.id] = d.value;
    }
    console.log(categories);
}

function initMaterialize()
{
    //document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.fixed-action-btn');
        var instances = M.FloatingActionButton.init(elems, {
            //direction: "left"
            toolbarEnabled: true
        });

        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems, modalsOptions);

        var elems = document.querySelectorAll('[data-use-counter="true"]');
        console.log(elems);
        //var instances = M.CharacterCounter.init(elems, {});
    //  });
    //Init modals
    let forms = document.querySelectorAll('[data-type="add_post"]');
    forms.forEach((elem, i) => {
        elem.addEventListener("submit", (e) => {
            console.log(e);
            e.preventDefault();
            add_post(e.target);
        });
    });

    //sendRequest("call.php", ["action=get_categories"], saveCategories);

    let ac_cat = document.querySelectorAll(".category-picker");
    console.log(ac_cat);
    M.Autocomplete.init(ac_cat,{data: categories.acdata, onAutocomplete: ac_test});
}

function ac_test()
{
    console.log(arguments);
}