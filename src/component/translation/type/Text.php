<?php

namespace NovemBit\i18n\component\translation\type;

use Exception;
use NovemBit\i18n\component\Translation;

/**
 * @property  Translation context
 */
class Text extends Type
{

    public $type = 1;

	/**
	 * @param array $texts
	 *
	 * @return array
	 * @throws Exception
	 */
	public function translate(array $texts){
		return $this->context->method->translate($texts);
	}
}