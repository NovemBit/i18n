<?php


namespace i18n\component\translation;


use NovemBit\i18n\Module;
use NovemBit\i18n\system\exception\Exception;

/**
 * @property component\method\Component method
 * @property component\text\Component $text
 * @property component\url\Component $url
 * @property component\html\Component $html
 * @property Module $context
 */
class Component extends \NovemBit\i18n\system\Component {

	private $languages;

	function init() {

	}

	/**
	 * @param array $languages
	 *
	 * @return Component
	 * @throws \Exception
	 */
	public function setLanguages(array $languages){
		if($this->context->languages->validateLanguages($languages)) {
			$this->languages = $languages;
			return $this;
		} else{
			throw new Exception('Language not supporting.');
		}
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function getLanguages(){
		if(isset($this->languages)){
			return $this->languages;
		} else{
			throw new Exception('Languages not set.');
		}
	}


}