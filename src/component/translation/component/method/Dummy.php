<?php


namespace i18n\component\translation\component\method;

/*
 * Dummy method of translation
 * That returns {lang}-{text} as translation
 * */
class Dummy extends Component {

	public $exclusion_pattern = '{e-$0-e}';


	protected function doTranslate(array $texts, array $languages){
		$result = [];

		foreach ( $texts as $key => $text ) {

			foreach ( $languages as $language ) {
				$result[ $text ][ $language ] = $language . '-' . $this->prepareText( $text );
			}

		}

		return $result;
	}

}