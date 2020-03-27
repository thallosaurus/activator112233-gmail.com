let id;

let collapsibleOptions = {
    accordion: false
}

let modalsOptions = {

}

window.onload = function ()
{

    let url = new this.URLSearchParams(location.search);
    id = url.get("id") != null ? url.get("id") : -2;

    //Init collapsibles
    var elems = document.querySelectorAll('.collapsible');
    var instances = M.Collapsible.init(elems, collapsibleOptions);

    var elems = document.querySelectorAll('.fixed-action-btn');
        var instances = M.FloatingActionButton.init(elems, {
            //direction: "left"
            toolbarEnabled: !FAB_USE_SUBBUTTONS
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
            add_comment(e.target);
        });
    });

    //Init sidenav:
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems, options);
}

function add_comment(elem)
{
    let args = [
        "action=comment",
        "id=" + id,
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
    if (!error) sendRequest("call.php", args, closeModal);   //file requests are handled seperately
}

function closeModal(data)
{
    let add_text_elem = document.getElementById("add_comment");
    //console.log(add_text_elem);
    //debugger;
    let instance = M.Modal.getInstance(add_text_elem);
    instance.close();
    location.reload();
    //showToast(data);
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