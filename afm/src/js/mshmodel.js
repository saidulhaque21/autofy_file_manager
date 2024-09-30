var mshmodel = '';
var afm_base_url = "http://localhost/";
var afm_base = "autofy_file_manager/";
var afm_title = "My Modal Title";
var alreadyLoaded = false;
var ajax_loader = "src/img/ajax_loader.gif";
var afm_callback;
var load_asynchronize_status = "start";
var select_action = ""; 
window.onload = function () {
    var newDiv = document.createElement("div"); //new Div
    var parser = new DOMParser();
//    var mshmodel_html = '<div class="mshmodel" id="mshmodel" style="display:none;"><div id="tag_header"></div>' +
//            '<div class="mshmodel_content">' +
//            '<div class="mshmodel_header"><span class="close">&times;</span><h2 id="mshmodel_title">Modal Header</h2></div>' +
//            '<div class="mshmodel_body" id="afm_uploader_container"></div> ' +
//            '<div class="mshmodel_footer" id=mshmodel_footer>...</div>' +
//            '</div>' +
//            '</div>';
    
      var mshmodel_html = '<div class="mshmodel" id="mshmodel" style="display:none;">' +
            '<div class="mshmodel_content">' +
           
            '<div class="mshmodel_body" id="afm_uploader_container"></div> ' +
            ' ' +
            '</div>' +
            '</div>';
    
    
    var el = parser.parseFromString(mshmodel_html, "text/html");
    mshmodel = el.getElementById("mshmodel");
    newDiv.appendChild(mshmodel);
    document.body.appendChild(newDiv);

    alreadyLoaded = false;
// Get the <span> element that closes the mshmodel
    var closer = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the mshmodel
    closer.onclick = function () {
        mshmodel.style.display = "none";
    };

// When the user clicks anywhere outside of the mshmodel, close it
    window.onclick = function (event) {
        if (event.target === mshmodel) {
            mshmodel.style.display = "none";
        }
    };
};

function smartFileManager(options, callback) {
//    console.log(options);
    var modal_title = options.title !== undefined ? options.title : afm_title;
    afm_base_url = options.base_url !== undefined ? options.base_url : afm_base_url;
  //  select_action = options.select_action !== undefined ? options.select_action : select_action;
    var remote_url = options.remote !== undefined ? options.remote : afm_base_url + afm_base + 'src/main.php';
 
    afm_callback = options.afterFileSelect !== undefined ? options.afterFileSelect : callback;
    mshmodel.style.display = "block";
   // document.getElementById("mshmodel_title").innerHTML = modal_title;
    // Immediately-invoked function expression
    (function () {
        if (!alreadyLoaded) {
            cssloader(afm_base_url + afm_base + "src/bootstrap/css/bootstrap.min.css");
            cssloader(afm_base_url + afm_base + "src/css/afm_modal.css");
            cssloader(afm_base_url + afm_base + "src/css/afm.css");
            cssloader(afm_base_url + afm_base + "src/css/dropzone.min.css");

            //bootstrap
            jsload(afm_base_url + afm_base + "src/vue/vue.min.js");
            jsload(afm_base_url + afm_base + "src/bootstrap/js/popper.min.js");
            jsload(afm_base_url + afm_base + "src/bootstrap/js/bootstrap.min.js");
//            alert(remote_url);
            afm_load(afm_base_url + afm_base + "src/vue/axios.min.js", function (xhr) {
                jsAppendChild(xhr.responseText);
                jsload(afm_base_url + afm_base + "src/vue/component/dropzone/vue2Dropzone.js");
                afm_load(remote_url, function (xhr) {
                    document.getElementById("afm_uploader_container").innerHTML = xhr.responseText;
                    jsload(afm_base_url + afm_base + "src/js/afm_fm.js?v=111");
                });

            });

            alreadyLoaded = true;

        }
        if (alreadyLoaded) {
            // jsload("src/js/afm_fm.js");
        }
    })();
}

function mshAfterFileSelect(paramas) {
    afm_callback(paramas);
    mshmodel.style.display = "none";
}

function afmClose() {
    mshmodel.style.display = "none";
}

function jsload(src) {
    afm_load(src, function (xhr) {
        jsAppendChild(xhr.responseText);

    });
}
function jsAppendChild(responseText) {
    var se = document.createElement('script');
    se.type = "text/javascript";
    se.text = responseText;
    document.getElementsByTagName('head')[0].appendChild(se);

}



function jsloader(src) {
    var script = document.createElement('script');
    script.type = "text/javascript";
    script.src = src;
    document.getElementsByTagName("head")[0].appendChild(script);
}
function cssloader(href) {
    var link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = href;
    document.getElementsByTagName("head")[0].appendChild(link);
}// Get the button that opens the mshmodel


function afm_load(url, callback) {
    var xhr;

    if (typeof XMLHttpRequest !== 'undefined')
        xhr = new XMLHttpRequest();
    else {
        var versions = ["MSXML2.XmlHttp.5.0",
            "MSXML2.XmlHttp.4.0",
            "MSXML2.XmlHttp.3.0",
            "MSXML2.XmlHttp.2.0",
            "Microsoft.XmlHttp"]

        for (var i = 0, len = versions.length; i < len; i++) {
            try {
                xhr = new ActiveXObject(versions[i]);
                break;
            } catch (e) {
            }
        } // end for
    }

    xhr.onreadystatechange = ensureReadiness;

    function ensureReadiness() {
        if (xhr.readyState < 4) {
            return;
        }

        if (xhr.status !== 200) {
            return;
        }

        // all is well  
        if (xhr.readyState === 4) {


            callback(xhr);

        }
    }

    xhr.open('GET', url, true);
    xhr.send('');
}


//Raw JavaScript Ajax
var xmlHttp // xmlHttp variable
var loadstatustext1 = "<div style='font-size:14px; height:400px; color:#7F1F00;'><img src='" + ajax_loader + "' />Loading... </div>"
var loadstatustext = "<span style='font-size:14px; color:#7F1F00;'><img src='" + ajax_loader + "' />Loading... </span>"
function GetXmlHttpObject() { // This function we will use to call our xmlhttpobject.
    var objXMLHttp = null // Sets objXMLHttp to null as default.
    if (window.XMLHttpRequest) { // If we are using Netscape or any other browser than IE lets use xmlhttp.
        objXMLHttp = new XMLHttpRequest() // Creates a xmlhttp request.
    } else if (window.ActiveXObject) { // ElseIf we are using IE lets use Active X.
        objXMLHttp = new ActiveXObject("Microsoft.XMLHTTP") // Creates a new Active X Object.
    } // End ElseIf.
    return objXMLHttp // Returns the xhttp object.
} // Close Function

 
function afm_load_post(url, data) {
    afm_load(url, "POST", "", data);
}

function afm_load_get(url, afm_uploader_container) {
    afm_load(url, "GET", afm_uploader_container);
}
function X_afm_load(url, rtype, afm_uploader_container, data) {
    var is_script = false;
    if (rtype === 'script') {
        is_script = true;
        rtype = "GET";
    }
    var request_type = rtype !== undefined ? rtype : "GET";
    var ajax_container = afm_uploader_container !== undefined ? afm_uploader_container : "afm_uploader_container";
    xmlHttp = GetXmlHttpObject() // Creates a new Xmlhttp object.
    if (xmlHttp === null) { // If it cannot create a new Xmlhttp object.
        alert("Browser does not support HTTP Request") // Alert Them!
        return // Returns.
    } // End If.

    xmlHttp.open(request_type, url, true) // Opens the URL using GET
    if (data !== undefined) {
        xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlHttp.send(data);
    } else {
        xmlHttp.onreadystatechange = function () { // This is the most important piece of the puzzle, if onreadystatechange is equal to 4 than that means the request is done.
            document.getElementById(ajax_container).innerHTML = loadstatustext //Display "fetching page message"
            if (xmlHttp.readyState === 4) { // If the onreadystatechange is equal to 4 lets show the response text.
                document.getElementById(ajax_container).innerHTML = xmlHttp.responseText; // Updates the div with the response text from check.php
            } // End If.
        }; // Close Function

        xmlHttp.send(null); // Sends NULL instead of sending data.
    }
    if (is_script === true) {
        var se = document.createElement('script');
        se.type = "text/javascript";
        se.text = xmlHttp.responseText;
        document.getElementsByTagName('head')[0].appendChild(se);
    }
}