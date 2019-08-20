<?php
namespace NovemBit\i18n\system\exception;

class Exception extends \Exception {
	public function errorMessage() {
		//error message
		$errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
		            .': <b>'.$this->getMessage().'</b> is not a valid E-Mail address';
		return $errorMsg;
	}
}

