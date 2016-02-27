var resourceLoader,
    xmlHttpReq = new XMLHttpRequest();

App.onLaunch = function (options) {
    // 1
    var javascriptFiles = [
        `${options.BASEURL}js/Presenter.js`,
        `${options.BASEURL}js/ResourceLoader.js`,
    ];
    var templateURL = `${options.BASEURL}templates/`;

    // 2
    evaluateScripts(javascriptFiles, function(success) {
        if(success) {
            resourceLoader = new ResourceLoader(options.BASEURL);
            resourceLoader.loadResource(`${templateURL}MenuBar.xml.js`, function (resource) {
                var menuDoc = Presenter.makeDocument(resource);
                menuDoc.addEventListener('select', Presenter.load.bind(Presenter));
                Presenter.pushDocument(menuDoc);
            });

        } else {
            var errorDoc = createAlert("Evaluate Scripts Error", "Error attempting to evaluate external JavaScript files.");
            navigationDocument.presentModal(errorDoc);
        }
    });
}

var createAlert = function(title, description) {
    var alertString = `<?xml version="1.0" encoding="UTF-8" ?>
    <document>
      <alertTemplate>
        <title>${title}</title>
        <description>${description}</description>
        <button type="cancel">
        <text>OK</text>
        </button>
      </alertTemplate>
    </document>`
    var parser = new DOMParser();
    var alertDoc = parser.parseFromString(alertString, "application/xml");
    return alertDoc
}

function jsonRequest(options) {

    var url = options.url;
    var method = options.method || 'GET';
    var headers = options.headers || {} ;
    var body = options.body || '';
    var callback = options.callback || function(err, data) {
            console.error("options.callback was missing for this request");
        };

    if (!url) {
        throw 'loadURL requires a url argument';
    }

    var xhr = new XMLHttpRequest();
    xhr.responseType = 'json';
    xhr.onreadystatechange = function() {
        try {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    callback(null, JSON.parse(xhr.responseText));
                } else {
                    callback(new Error("Error [" + xhr.status + "] making http request: " + url));
                }
            }
        } catch (err) {
            console.error('Aborting request ' + url + '. Error: ' + err);
            xhr.abort();
            callback(new Error("Error making request to: " + url + " error: " + err));
        }
    };

    xhr.open(method, url, true);

    Object.keys(headers).forEach(function(key) {
        xhr.setRequestHeader(key, headers[key]);
    });

    xhr.send();

    return xhr;
}