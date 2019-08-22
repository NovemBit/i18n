<?php

namespace NovemBit\i18n\component;


use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
/**
 * @property Module $context
 */
class Request extends Component {


	function init() {

	}

	public function start(){
        ob_start();

        register_shutdown_function(function(){
            $content = ob_get_contents();
            ob_end_clean();
            $language = $this->context->languages->getCurrentLanguage();

            if($language != $this->context->languages->default_language) {
                $content = $this->context->translation->setLanguages([$language])->html->translate([$content])[$content][$language];
            }

            echo $content;
        });
    }
}