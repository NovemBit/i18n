(function () {

    function parseURL(url) {
        let parser = document.createElement('a'),
            searchObject = {},
            queries, split, i;
        parser.href = url;
        queries = parser.search.replace(/^\?/, '').split('&');
        for (i = 0; i < queries.length; i++) {
            split = queries[i].split('=');
            searchObject[split[0]] = split[1];
        }
        return {
            protocol: parser.protocol,
            host: parser.host,
            hostname: parser.hostname,
            port: parser.port,
            pathname: parser.pathname,
            search: parser.search,
            searchObject: searchObject,
            hash: parser.hash
        };
    }

    function addParameterToURL(url, key, value) {
        url += (url.split('?')[1] ? '&' : '?') + key + '=' + value;
        return url;
    }

    let valid_hosts = [
        //    'test.com'
    ];
    let original_xhr = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function () {
        let req_parsed = parseURL(arguments[1]);
        let cur_parsed = parseURL(window.location.href);
        if (req_parsed.host === cur_parsed.host && valid_hosts.indexOf(req_parsed.host)) {
            arguments[1] = addParameterToURL(
                arguments[1],
                window.novembit.i18n.language_query_key,
                window.novembit.i18n.current_language
            );

            if (window.novembit.i18n.editor.is_editor === true) {
                arguments[1] = addParameterToURL(
                    arguments[1],
                    window.novembit.i18n.prefix + '-' + window.novembit.i18n.editor.query_key,
                    1
                );
            }
        }
        original_xhr.apply(this, arguments);
    }
})();