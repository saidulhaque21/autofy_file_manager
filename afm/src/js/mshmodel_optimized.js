let mshmodel = '';
const afm_base_url = "http://localhost/";
const afm_base = "afm/";
const afm_title = "My Modal Title";
let alreadyLoaded = false;
const ajax_loader = "src/img/ajax_loader.gif";
let afm_callback;
const load_asynchronize_status = "start";
let select_action = "";
const is_development_mode = true;
let version = "1.0.1";

window.onload = () => {
    const newDiv = document.createElement("div");
    const parser = new DOMParser();

    const mshmodel_html = `
        <div class="mshmodel" id="mshmodel" style="display:none;">
            <div class="mshmodel_content">
                <div class="mshmodel_body" id="afm_uploader_container"></div>
            </div>
        </div>`;

    const el = parser.parseFromString(mshmodel_html, "text/html");
    mshmodel = el.getElementById("mshmodel");
    newDiv.appendChild(mshmodel);
    document.body.appendChild(newDiv);

    const closer = document.getElementsByClassName("close")[0];
    closer.onclick = () => mshmodel.style.display = "none";

    window.onclick = (event) => {
        if (event.target === mshmodel) {
            mshmodel.style.display = "none";
        }
    };
};

function smartFileManager(options, callback) {
    const modal_title = options.title || afm_title;
    const base_url = options.base_url || afm_base_url;
    const remote_url = options.remote || `${base_url}${afm_base}src/main.php`;

    afm_callback = options.afterFileSelect || callback;
    mshmodel.style.display = "block";

    if (is_development_mode) {
        version = Math.random().toString(36).substr(2);
    }

    if (!alreadyLoaded) {
        loadCSSFiles(base_url);
        loadJSFiles(base_url, remote_url);
        if (!is_development_mode)
            alreadyLoaded = true;
    }
}

function loadCSSFiles(base_url) {
    cssLoader(`${base_url}${afm_base}src/bootstrap/css/bootstrap.min.css?v=${version}`);
    cssLoader(`${base_url}${afm_base}src/css/afm_modal.css?v=${version}`);
    cssLoader(`${base_url}${afm_base}src/css/afm.css?v=${version}`);
    cssLoader(`${base_url}${afm_base}src/css/dropzone.min.css?v=${version}`);
}

function loadJSFiles(base_url, remote_url) {
    jsLoader(`${base_url}${afm_base}src/vue/vue.min.js?v=${version}`);
    jsLoader(`${base_url}${afm_base}src/bootstrap/js/popper.min.js?v=${version}`);
    jsLoader(`${base_url}${afm_base}src/bootstrap/js/bootstrap.min.js?v=${version}`);

    afm_load(`${base_url}${afm_base}src/vue/axios.min.js`, (xhr) => {
        appendScript(xhr.responseText);
        jsLoader(`${base_url}${afm_base}src/vue/component/dropzone/vue2Dropzone.js`);
        afm_load(remote_url, (xhr) => {
            document.getElementById("afm_uploader_container").innerHTML = xhr.responseText;
            jsLoader(`${base_url}${afm_base}src/js/afm_fm.js?v=${version}`);
        });
    });
}

function afmClose() {
    mshmodel.style.display = "none";
}

function appendScript(responseText) {
    const script = document.createElement('script');
    script.type = "text/javascript";
    script.text = responseText;
    document.head.appendChild(script);
}

function jsLoader(src) {
    const script = document.createElement('script');
    script.type = "text/javascript";
    script.src = src;
    document.head.appendChild(script);
}

function cssLoader(href) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    document.head.appendChild(link);
}

function afm_load(url, callback) {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            callback(xhr);
        }
    };
    xhr.open('GET', url, true);
    xhr.send();
}

// XML HTTP Request Utilities
let xmlHttp;
const loadstatustext = `<span style='font-size:14px; color:#7F1F00;'><img src='${ajax_loader}' />Loading... </span>`;

function GetXmlHttpObject() {
    return window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
}

function afm_load_post(url, data) {
    afm_load(url, "POST", "", data);
}

function afm_load_get(url, containerId) {
    afm_load(url, "GET", containerId);
}


