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
        <text cancel-button="cancel">OK</text>
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

/**
 * @description - an example implementation of search that reacts to the
 * keyboard onTextChange (see Presenter.js) to filter the lockup items based on the search text
 * @param {Document} doc - active xml document
 * @param {String} searchText - current text value of keyboard search input
 */
var buildResults = function(doc, searchText) {
    //Create parser and new input element
    var domImplementation = doc.implementation;
    var lsParser = domImplementation.createLSParser(1, null);
    var lsInput = domImplementation.createLSInput();

    if (searchText.trim() !== "") {
        lsInput.stringData = `<text class="heading">正在搜索中...</text>`;
        lsParser.parseWithContext(lsInput, doc.getElementsByTagName("collectionList").item(0), 2);

        jsonRequest({
            url: `${this.resourceLoader.BASEURL}search/${searchText}`,
            callback: function (error, json) {
                if (!error && json.status == "success") {
                    if (json.series.count == 0 && json.lessons.count == 0) {
                        //set default template fragment to display no results
                        lsInput.stringData = `<list>
                      <section>
                    <header>
                    <title>暂无相关课程</title>
                    </header>
                    </section>
                    </list>`;
                    } else {
                        lsInput.stringData = `<shelf><header><title>找到${json.series.count}个相关系列课程</title></header><section>`;
                        for (var i = 0; i < json.series.list.length; i++) {
                            var series = json.series.list[i];
                            lsInput.stringData += `<lockup class="series-list" template="${this.resourceLoader.BASEURL}templates/Series.${series.id}.xml" presentation="pushDocument">
                    <img class="cornered" src="${series.thumbnail}" width="350" height="350" />
                    <title>${series.title}</title>
                    </lockup>`;
                        }
                        lsInput.stringData += `</section></shelf>`;

                        lsInput.stringData += `<shelf><header><title>找到${json.lessons.count}个相关教程视频</title></header><section>`;
                        for (var i = 0; i < json.series.list.length; i++) {
                            var lesson = json.lessons.list[i];
                            lsInput.stringData += `<lockup class="lesson-list" videoURL="${lesson.source}">
                    <img src="${lesson.thumbnail}" width="240" height="240" />
                    <title>${lesson.title}</title>
                    <subtitle class="showOnHighlight">${lesson.series_title}</subtitle>
                    </lockup>`;
                        }
                        lsInput.stringData += `</section></shelf>`;
                    }

                    //add the new input element to the document by providing the newly created input, the context,
                    //and the operator integer flag (1 to append as child, 2 to overwrite existing children)
                    lsParser.parseWithContext(lsInput, doc.getElementsByTagName("collectionList").item(0), 2);
                } else {
                    //lsInput.stringData = `<text class="heading">搜索失败, 请重试...</text>`;
                    //lsParser.parseWithContext(lsInput, doc.getElementsByTagName("collectionList").item(0), 2);
                }
            }
        });
    }
}