<?php

namespace NovemBit\i18n\component;


use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;

/**
 * @property Module $context
 */
class Request extends Component
{


    function init()
    {

    }

    /**
     * @param $translate
     * @param $to_language
     *
     * @return null
     * @throws \Exception
     */
    private function getSourceUrlFromTranslate($translate,$to_language){

        $re_translate = $this->context->translation->setLanguages($to_language)->url->reTranslate([$translate]);
        if(isset($re_translate[$translate])){
            return $re_translate[$translate];
        }

        return null;
    }

    public function start()
    {
        $language         = $this->context->languages->getCurrentLanguage();
        $default_language = $this->context->languages->default_language;
        $accepted_languages =  $this->context->languages->getAcceptLanguages();

        $dest = $this->context->languages->getUrlDest();

//        var_dump( [
//            'dest'=>$dest,
//            'def_lang'=>$default_language,
//            'cur_lang'=>$language,
//            'accept_langs'=>$accepted_languages
//        ]);

        if ($language == $default_language) {
            $url_translations = $this->context->translation->setLanguages($accepted_languages)->url->translate([$dest]);
            return;
        }


        $source_url = $this->getSourceUrlFromTranslate($dest,$language);

        if($dest!=null && $source_url==null){
            throw new \Exception("404 Not Found");
        }

//        echo "SOURCE: ".$source_url;

        $_SERVER['ORIG_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
//        $_SERVER['ORIG_HTTP_HOST'] = $_SERVER['HTTP_HOST'];
//        $_SERVER['HTTP_HOST']   = $_host;
        $_SERVER['REQUEST_URI'] = $source_url;


        ob_start();

        register_shutdown_function(function () use ($language) {
            $content = ob_get_contents();
            ob_end_clean();

            $content
                = $this->context->translation
                ->setLanguages([$language])
                ->html
                ->translate([$content])[$content][$language];


            echo $content;
        });

    }
}