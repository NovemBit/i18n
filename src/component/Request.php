<?php

namespace NovemBit\i18n\component;

use Exception;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\DataType;
use NovemBit\i18n\system\helpers\URL;

/**
 * @property Module $context
 */
class Request extends Component
{
    /*
     * Translation component
     * */
    private $translation;


    private $language;
    private $referer_language;

    private $destination;
    private $referer;

    private $source_url;
    private $referer_source_url;

    private $url_translations;
    private $referer_translations;

    public $editor_query_key = "editor";

    public $editor;

    /**
     * @return mixed
     */
    public function getRefererSourceUrl()
    {
        return $this->referer_source_url;
    }

    /**
     * @param mixed $referer_source_url
     */
    public function setRefererSourceUrl($referer_source_url)
    {
        $this->referer_source_url = $referer_source_url;
    }

    /**
     * @return mixed
     */
    public function getRefererTranslations()
    {
        return $this->referer_translations;
    }

    /**
     * @param mixed $referer_translations
     */
    public function setRefererTranslations($referer_translations)
    {
        $this->referer_translations = $referer_translations;
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

    /**
     * @return bool
     */
    private function prepareDestination()
    {
        $dest = '/' . trim($_SERVER['REQUEST_URI'], '/');
        $dest = URL::removeQueryVars($dest, $this->context->languages->language_query_key);
        $dest = urldecode($dest);
        $this->setDestination($dest);
        return true;
    }

    /**
     * @return mixed
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param mixed $referer
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function prepareReferer()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->prepareRefererLanguage();

            $referer = '/' . trim($_SERVER['HTTP_REFERER'], '/');
            $referer = URL::removeQueryVars($referer, $this->context->languages->language_query_key);
            $referer = urldecode($referer);
            $this->setReferer($referer);

            $this->prepareRefererSourceUrl();
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function prepareRefererSourceUrl()
    {
        /*
         * If current language is default language
         * Then translate current url for all languages
         * */
        if ($this->getLanguage() == $this->context->languages->from_language) {
            $this->setRefererTranslations(
                $this->getTranslation()
                    ->setLanguages($this->context->languages->getAcceptLanguages())
                    ->url->translate([$this->getReferer()])[$this->getReferer()]
            );
            return false;
        }

        /*
         * Set source origin URL
         * */
        $this->setRefererSourceUrl($this->getSourceUrlFromTranslate(
            $this->getReferer(),
            $this->getRefererLanguage())
        );

        /*
         * Set current url all translations
         * */
        $this->setRefererTranslations(
            $this->getTranslation()
                ->setLanguages($this->context->languages->getAcceptLanguages())
                ->url->translate([$this->getSourceUrl()])[$this->getSourceUrl()]
        );

        /**
         * Setting source url as @REQUEST_URI
         * */
        $_SERVER['HTTP_REFERER'] = $this->getRefererSourceUrl();


        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function prepareSourceUrl()
    {
        /*
         * If current language is default language
         * Then translate current url for all languages
         * */
        if ($this->getLanguage() == $this->context->languages->from_language) {
            $this->setUrlTranslations(
                $this->getTranslation()
                    ->setLanguages($this->context->languages->getAcceptLanguages())
                    ->url->translate([$this->getDestination()])[$this->getDestination()]
            );
            return false;
        }

        /*
         * Set source origin URL
         * */
        $this->setSourceUrl($this->getSourceUrlFromTranslate(
            $this->getDestination(),
            $this->getLanguage())
        );

        /*
         * Set current url all translations
         * */
        $this->setUrlTranslations(
            $this->getTranslation()
                ->setLanguages($this->context->languages->getAcceptLanguages())
                ->url->translate([$this->getSourceUrl()])[$this->getSourceUrl()]
        );

        /**
         * Setting source url as @REQUEST_URI
         * */
        $_SERVER['REQUEST_URI'] = $this->getSourceUrl();

        if ($this->getDestination() != null && $this->getSourceUrl() == null) {
            throw new Exception("404 Not Found", 404);
        }

        return true;
    }

    /**
     * @throws \NovemBit\i18n\system\exception\Exception
     * @throws Exception
     */
    private function prepare()
    {
        $this->setTranslation($this->context->translation);

        if (isset($_GET[$this->context->prefix . '-' . $this->editor_query_key])) {
            $this->editor = true;
        }

        return $this->prepareLanguage() && $this->prepareDestination() && $this->prepareSourceUrl()
            && $this->prepareReferer();
    }

    /**
     * @return mixed
     */
    public function getRefererLanguage()
    {
        return $this->referer_language;
    }

    /**
     * @param mixed $referer_language
     */
    public function setRefererLanguage($referer_language)
    {
        $this->referer_language = $referer_language;
    }

    /**
     * @throws Exception
     */
    private function prepareRefererLanguage()
    {

        $_SERVER["ORIG_HTTP_REFERER"] = $_SERVER["HTTP_REFERER"];

        /**
         * Taking language from current @REQUEST_URI
         * */
        $language = $this->context->languages->getLanguageFromUrl($_SERVER["HTTP_REFERER"]);

        /**
         * If language does not exists in @URL
         * */
        if ($language == null) {
            $language = $this->context->languages->from_language;
        }

        /*
         * Setting current instance language
         * */
        $this->setRefererLanguage($language);

        /*
         * Remove Language from URI
         * */
        $this->removeLanguageFromURI($_SERVER['HTTP_REFERER']);

        return true;
    }

    /**
     * @throws \NovemBit\i18n\system\exception\Exception
     * @throws Exception
     */
    private function prepareLanguage()
    {
        /*
         * Check if tried to access from cli
         * */
        if (!isset($_SERVER['REQUEST_URI'])) {
            throw new \NovemBit\i18n\system\exception\Exception('Access without http request was denied.');
        }


        $_SERVER["ORIG_REQUEST_URI"] = $_SERVER["REQUEST_URI"];

        /**
         * Taking language from current @REQUEST_URI
         * */
        $language = $this->context->languages->getLanguageFromUrl($_SERVER['REQUEST_URI']);

        /**
         * If language does not exists in @URL
         * */
        if ($language == null) {
            $language = $this->context->languages->from_language;
        }

        /*
         * Setting current instance language
         * */
        $this->setLanguage($language);

        /*
         * Remove Language from URI
         * */
        $this->removeLanguageFromURI($_SERVER['REQUEST_URI']);

        return true;
    }

    /**
     * @param $uri
     */
    private function removeLanguageFromURI(&$uri)
    {
        $parts = parse_url(trim($uri, '/'));
        if (isset($parts['path'])) {
            $path = explode('/', ltrim($parts['path'], '/'));

            if ($this->context->languages->validateLanguage($path[0])) {
                unset($path[0]);
            }
            $parts['path'] = implode('/', $path);
            $new_url = URL::buildUrl($parts);
            $uri = empty($new_url) ? '/' : $new_url;
        }
    }

    /**
     * @param $content
     * @return string|string[]|null
     * @throws Exception
     */
    public function translateBuffer($content)
    {

        $status = http_response_code();

        /*
         * If response status is success
         * */
        if (in_array($status, range(200, 299))) {

            $type = DataType::getType($content);

//            return var_export($type);
            if ($type !== 0) {

                /*
                 * Translate content
                 * */
                $content = $this
                    ->getTranslation()->setLanguages($this->getLanguage())
                    ->{$type}
                    ->translate([$content])[$content][$this->getLanguage()];

                if ($type == "html") {
                    $content = preg_replace('/(<head.*?>)/is', '$1' . PHP_EOL . $this->getHeadAdditionalTags(),
                        $content,
                        1);
                }

            }
        }

        return $content;
    }

    /**
     * Start request
     *
     * @throws Exception
     */
    public function start()
    {
        if (!$this->prepare()) {
            return;
        }

        /*var_dump([
            'url_dest' => $this->getDestination(),
            'from_language' => $this->context->languages->from_language,
            'language' => $this->getLanguage(),
            'source_url'=>$this->getSourceUrl(),
            'orig_request_uri'=>$_SERVER["ORIG_REQUEST_URI"]
        ]);*/

        ob_start([$this, 'translateBuffer']);
    }

    /**
     * @return string
     */
    private function getHeadAdditionalTags()
    {
        $tags = '';

        $tags .= $this->getMainJavaScriptTag();

        $tags .= $this->getXHRManipulationJavaScriptTag();

        if ($this->editor) {
            $tags .= $this->getEditorJavaScriptTag();
        }

        $tags .= $this->getAlternateLinkTags();
        return $tags;
    }

    private function getAlternateLinkTags()
    {
        $tags = '';
        foreach ($this->getUrlTranslations() as $language => $translate) {
            $tags .= '<link rel="alternate" hreflang="' . $language . '" href="' . $translate . '">';
        }
        return $tags;
    }

    private function getMainJavaScriptTag()
    {
        $config = json_encode(
            [
                'i18n' => [
                    'current_language' => $this->getLanguage(),
                    'language_query_key' => $this->context->languages->language_query_key,
                    'editor_query_key' => $this->editor_query_key,
                    'prefix' => $this->context->prefix,
                ]
            ]);
        $script = <<<js
(function() {
    /*
    * NovemBit i18n object
    * */
    window.novembit={$config}
})()
js;
        return '<script type="application/javascript" id="NovemBit-i18n-main">' . $script . '</script>';
    }

    /**
     * @return string
     */
    private function getEditorJavaScriptTag()
    {
        $script = file_get_contents(__DIR__ . '/request/assets/js/editor.js');
        $css = file_get_contents(__DIR__ . '/request/assets/css/editor.css');

        return implode('', [
            '<script type="application/javascript" id="NovemBit-i18n-editor">' . $script . '</script>',
            '<style type="text/css">' . $css . '</style>'
        ]);
    }

    /**
     * @return string
     */
    private function getXHRManipulationJavaScriptTag()
    {
        $script = <<<js
(function() {

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
    function addParameterToURL(url,key,value){
        url += (url.split('?')[1] ? '&':'?') + key+'='+value;
        return url;
    }
    let valid_hosts = [
    //    'test.com'
    ];
    let original_xhr = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function(){
        let req_parsed = parseURL(arguments[1]);
        let cur_parsed = parseURL(window.location.href);
        if(req_parsed.host === cur_parsed.host && valid_hosts.indexOf(req_parsed.host)){
           arguments[1] = addParameterToURL(
               arguments[1],
               window.novembit.i18n.language_query_key, 
               window.novembit.i18n.current_language
           );
        }
        original_xhr.apply(this, arguments);
    }
})()
js;
        return '<script type="application/javascript" id="NovemBit-i18n-xhr-manipulation">' . $script . '</script>';
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param mixed $destination
     */
    private function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return mixed
     */
    public function getSourceUrl()
    {
        return $this->source_url;
    }

    /**
     * @param mixed $source_url
     */
    private function setSourceUrl($source_url)
    {
        $this->source_url = $source_url;
    }

    /**
     * @return mixed
     */
    public function getUrlTranslations()
    {
        return $this->url_translations;
    }

    /**
     * @param mixed $url_translations
     */
    private function setUrlTranslations($url_translations)
    {
        $this->url_translations = $url_translations;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    private function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return Translation
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @param Translation $translation
     */
    private function setTranslation(Translation $translation)
    {
        $this->translation = $translation;
    }
}