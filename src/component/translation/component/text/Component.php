<?php

namespace i18n\component\translation\component\text;

/**
 * @property  \i18n\component\translation\Component context
 */
class Component extends \NovemBit\i18n\system\Component {

	function init() {
		// TODO: Implement init() method.
	}


	/**
	 * @param array $texts
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function translate(array $texts){
		return $this->context->method->translate($texts, $this->context->getLanguages());
	}
}