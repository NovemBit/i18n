<?php

namespace NovemBit\i18n\component\translation\type;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use NovemBit\i18n\component\Translation;

/**
 * @property  Translation context
 */
class HTML extends Type
{


    public $type = 3;

	public $alias_domains = [];

	public $to_translate_xpath_query_expression = './/*[not(child::*) and (not(self::html) and not(self::body) and not(self::style) and not(self::script) and not(self::body)) and text()]';

	/**
	 * @param array $htmls
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function doTranslate( array $htmls ) {
		$languages         = $this->context->getLanguages();
		$result            = [];
		$to_translate_text = [];
		$to_translate_url  = [];

		/*
		 * Finding translatable node values and attributes
		 * */
		foreach ( $htmls as $html ) {
			$dom = $this->getHtmlDom( $html );

			$xpath = new DOMXpath( $dom );

			$tags = $xpath->query( $this->to_translate_xpath_query_expression );

			/** @var DOMElement $tag */
			foreach ( $tags as $tag ) {
				if (
					$tag->tagName == 'a'
					&& $tag->hasAttribute( 'href' )
					&& $this->validateUrl( $tag->getAttribute( 'href' ) )
				) {
					$to_translate_url[] = $tag->getAttribute( 'href' );
				}

				if ( $tag->hasAttribute( 'title' ) ) {
					$to_translate_text[] = $tag->getAttribute( 'title' );
				}

				if ( $tag->hasAttribute( 'alt' ) ) {
					$to_translate_text[] = $tag->getAttribute( 'alt' );
				}

				$to_translate_text[] = $tag->nodeValue;
			}
		}

		/*
		 * Translate texts with method
		 * */
		$text_translations = $this->getTextTranslations( $to_translate_text );

		/*
		 * Translate urls with method
		 * */
		$url_translations = $this->getUrlTranslations( $to_translate_url );


		/*
		 * Replace html node values to
		 * Translated values
		 * */
		foreach ( $htmls as $html ) {

			foreach ( $languages as $language ) {

				$dom = $this->getHtmlDom( $html );

				$xpath = new DOMXpath( $dom );

				$tags = $xpath->query( $this->to_translate_xpath_query_expression );

				/** @var DOMElement $tag */
				foreach ( $tags as $tag ) {


					if (
						$tag->tagName == 'a'
						&& $tag->hasAttribute( 'href' )
						&& $this->validateUrl( $tag->getAttribute( 'href' ) )
					) {
					    if(isset($url_translations[ $tag->getAttribute( 'href' ) ][ $language ] )) {
                            $tag->setAttribute('href', $url_translations[$tag->getAttribute('href')][$language]);
                        }
					}
					if ( $tag->hasAttribute( 'title' ) ) {
						$tag->setAttribute( 'title', $text_translations[ $tag->getAttribute( 'title' ) ][ $language ] );
					}

					if ( $tag->hasAttribute( 'alt' ) ) {
						$tag->setAttribute( 'alt', $text_translations[ $tag->getAttribute( 'alt' ) ][ $language ] );
					}

					$tag->nodeValue = $text_translations[ $tag->nodeValue ][ $language ];
				}


				$result[ $html ][ $language ] = $this->getDomHtmlString( $dom );
			}
		}

		return $result;

	}

	/**
	 * @param $url
	 *
	 * @return bool
	 */
	private function validateUrl( $url ) {
		$parts = parse_url( $url );

		/*
		 * Relative url
		 * */
		if ( ! isset( $parts['host'] ) ) {
			return true;
		} elseif ( in_array( $parts['host'], $this->alias_domains ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param DOMDocument $dom
	 *
	 * @return string|string[]|null
	 */
	private function getDomHtmlString( $dom ) {
		return preg_replace( '/(^\<root>|\<\/root>$)/', '', $dom->saveHTML() );
	}

	/**
	 * @param $html
	 *
	 * @return DOMDocument
	 */
	private function getHtmlDom( $html ) {

		if ( ! preg_match( '/\<html.*?\>/i', $html ) ) {
			$html = '<root>' . $html . '</root>';
		}
		$dom = new DomDocument();

		@$dom->loadHTML( $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

		return $dom;
	}

	/**
	 * @param array $strings
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function getTextTranslations( array $strings ) {

		$translate = $this->context->text->translate( $strings );

		return $translate;
	}

	/**
	 * @param array $urls
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function getUrlTranslations( array $urls ) {

		$translate = $this->context->url->translate( $urls );

		return $translate;
	}
}