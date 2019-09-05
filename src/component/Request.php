<?php

namespace NovemBit\i18n\component;


use Exception;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\URL;

/**
 * @property Module $context
 */
class Request extends Component
{

    public $language;
    public $dest;
    public $from_language;
    public $accepted_languages;
    public $source_url;
    public $url_translations;

    public $types = [
        'text/html' => 'html',
        'application/json' => 'json'
    ];

    /**
     * @throws Exception
     */
    function init()
    {

    }

    /**
     * @param $translate
     * @param $to_language
     *
     * @return null
     * @throws Exception
     */
    private function getSourceUrlFromTranslate($translate, $to_language)
    {

        $re_translate = $this->context->translation->setLanguages($to_language)->url->reTranslate([$translate]);
        if (isset($re_translate[$translate])) {
            return $re_translate[$translate];
        }

        return null;
    }

    private function getContentType()
    {
        return explode(',', $_SERVER['HTTP_ACCEPT'])[0];
    }

    /**
     * @return string
     */
    private function getCurrentLanguage()
    {
        return $this->context->languages->getCurrentLanguage();
    }

    /**
     * @return string
     */
    private function getDefaultLanguage()
    {
        return $this->context->languages->from_language;
    }

    /**
     * @return array|null
     */
    private function getAcceptLanguage()
    {
        return $this->context->languages->getAcceptLanguages();
    }

    /**
     * @return mixed
     */
    private function getUrlDest()
    {
        $dest = trim($_SERVER['REQUEST_URI'], '/');
        $dest = URL::removeQueryVars($dest, $this->context->languages->language_query_key);

        $dest = urldecode($dest);
        return $dest;
    }

    private function prepare()
    {

        $this->language = $this->getCurrentLanguage();
        $this->from_language = $this->getDefaultLanguage();
        $this->accepted_languages = $this->getAcceptLanguage();
        $this->dest = $this->getUrlDest();
    }

    /**
     * Start request
     *
     * @throws Exception
     */
    public function start()
    {
        $this->prepare();

        /*var_dump([
            'url_dest'         => $this->getUrlDest(),
            'default_language' => $this->getDefaultLanguage(),
            'current_language' => $this->getCurrentLanguage(),
            'accept_languages' => $this->getAcceptLanguage()
        ]);*/

        /*
         * If current language is default language
         * Then translate current url for all languages
         * */
        if ($this->language == $this->from_language) {
            $this->url_translations
                = $this->context->translation
                ->setLanguages($this->accepted_languages)
                ->url->translate([$this->dest]);

            return;
        }

        $this->source_url = $this->getSourceUrlFromTranslate($this->dest, $this->language);


        if ($this->dest != null && $this->source_url == null) {
            throw new \Exception("404 Not Found", 404);
        }

        /*var_dump([
            'source_url'         => $this->source_url
        ]);*/
        /*
         * Manipulating REQUEST_URI
         * */
        $_SERVER['REQUEST_URI'] = '/' . $this->source_url;

        ob_start();

        /*
         * Register Shutdown action to take buffer content
         * And after determine content type do translation
         *
         * */
        register_shutdown_function(function () {

            $content = ob_get_contents();

            $status = http_response_code();

            /*
             * If response status is not success
             * Then print $content and break function
             * */
            if ($status < 200 || $status >= 300) {

                echo $content;

                return;
            }

            ob_end_clean();

            $type = isset($this->types[$this->getContentType()]) ? $this->types[$this->getContentType()] : null;

            if ($type !== null) {
                $content = $this
                    ->context
                    ->translation
                    ->setLanguages([$this->language])
                    ->{$type}
                    ->translate([$content])[$content][$this->language];

                if ($type == "html") {
                    $content = preg_replace_callback('/(<head.*?>)(.*)(<\/head>)/s', function ($matches) {
                        $script = '<script type="application/javascript" id="NovemBit-i18n">' . $this->getXHRManipulationJavaScript() . '</script>';
                        $matches[2] = $script . $matches[2];
                        return $matches[1] . $matches[2] . $matches[3];
                    }, $content, 1);
//                    var_dump(htmlspecialchars($content));die;
                }
            }

            echo $content;

        });

    }

    private function getXHRManipulationJavaScript()
    {
        $language_query_key = $this->context->languages->language_query_key;
        return <<<js
(function() {
    /*
    * NovemBit i18n object
    * */
    window.novembit = {
        'i18n':{
            'current_language': "$this->language",
            'language_query_key':"{$this->context->languages->language_query_key}"
        }
    };
    
    function parseURL(url) {
        let parser = document.createElement('a'),
            searchObject = {},
            queries, split, i;
        parser.href = url;
        queries = parser.search.replace(/^\?/, '').split('&');
        for( i = 0; i < queries.length; i++ ) {
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
    
    let valid_hosts = [
    //    'test.com'
    ];
    
    let original_xhr = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function(){ 
        
        let req_parsed = parseURL(arguments[1]);
        let cur_parsed = parseURL(window.location.href);
        
        if(req_parsed.host === cur_parsed.host && valid_hosts.indexOf(req_parsed.host)){
            arguments[1] += '&{$language_query_key}={$this->language}';
        }
        
        original_xhr.apply(this, arguments);
    }
})()
js;

    }
}