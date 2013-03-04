<?php namespace HTMLOven;

class HTMLReference
{
	protected $tags = array();
	protected $valueOnOptionals = false;
	protected $slashOnUnclosables = false;

	public function __construct(array $tags = array())
	{
		$this->setTags( array_merge($this->defaultTags(), $tags) );
	}

	/**
	 * Sets the tags array that represents the HTML reference.
	 * 
	 * @param array $tags The tags that represente the HTML reference.
	 */
	public function setTags(array $tags)
	{
		$this->clearTags();

		foreach ($tags as $tagName => $tagReference) {
			$this->addTag($tagName, $tagReference);
		}
	}

	/**
	 * Gets the tags reference.
	 * 
	 * @return array the tags reference
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * Add a tag reference to the tags reference array
	 *
	 * @param string $tagName The tag name that wil work as a key
	 * @param array $tagReference the tag reference
	 */
	public function addTag($tagName, array $tagReference)
	{
		$tagName = (string)$tagName;

		if ( $tagName != '' and count($tagReference) > 0 )
			$this->tags[ $tagName ] = $tagReference;
	}

	/**
	 * Gets a tag reference for the requested tag name
	 * 
	 * @param  string $tagName the tag name
	 * @return array          the tag reference of the requested tag name
	 */
	public function getTag($tagName)
	{
		$tagName = (string)$tagName;

		if ( $this->hasTag($tagName) ) {
			return $this->tags[ (string)$tagName ];
		}
	}

	/**
	 * Removes a tag reference with the tag name passed as param
	 * 
	 * @param  string $tagName the tagNam
	 * @return void
	 */
	public function removeTag($tagName)
	{
		$tagName = (string)$tagName;

		if ( $this->hasTag($tagName) ) {
			return $this->tags[ (string)$tagName ];
		}
	}

	/**
	 * Clears the tag reference array
	 * 
	 * @return void
	 */
	public function clearTags()
	{
		$this->tags = array();
	}

	/**
	 * Renders the element passed as param within the standards of the HTML reference for validation
	 * @param  HTMLElementInterface $element the element
	 * @return string                        the rendered element
	 */
	public function render(HTMLElementInterface $element)
	{
		$html = "<{$element->getTagName()}";

		if ( count( $element->getAttributes() ) > 0 )
			$html .= ' ' . $this->compileAttributes( $element );

		if ( $this->closingTag( $element->getTagName() ) ) {

			$html .= '>';
			$html .= $this->escape( $element->getText() );
			$html .= "</{$element->getTagName()}>";

		} elseif ( $this->slashOnUnclosables ) {

			$html .= '/>';

		} else {

			$html .= '>';

		}

		return $html;
	}

	/**
	 * Returns an indicator that the tag reference exists
	 * 
	 * @param  string $tagName The tag name
	 * @return array          the tag Reference
	 */
	public function hasTag($tagName)
	{
		return $tagName != '' and isset( $this->tags[$tagName] );
	}

	/**
	 * Escapes the string content
	 * 
	 * @param  string $string the string to be escaped
	 * @return string         the escaped string
	 */
	protected function escape($string)
	{
		return htmlentities( trim((string)$string), ENT_QUOTES, 'UTF-8' );
	}

	/**
	 * Decodes the escaped string content
	 * 
	 * @param  string $string the escaped string
	 * @return string         the decoded string
	 */
	protected function unescape($string)
	{
		return html_entity_decode( (string)$string, ENT_QUOTES, 'UTF-8' );
	}

	/**
	 * Returns a indicator tha the tag passed as param needs a closing tag or not
	 * 
	 * @param  string $tagName the tag name
	 * @return bool
	 */
	protected function closingTag( $tagName )
	{
		$tagName = (string)$tagName;

		if ( $this->hasTag($tagName) and isset( $this->tags[ $tagName ]['hasClosingTag'] ) ) {
		   return $this->tags[ $tagName ]['hasClosingTag'];
		}

		return true;
	}

	/**
	 * Compile the element's attributes array to a string of html attributes
	 * 
	 * @return string the compiled string
	 */
	protected function compileAttributes(HTMLElementInterface $element)
	{
		$attrs = array();

		foreach ($element->getAttributes() as $name => $value) {

			if ( $value == '' ) {

				$attrs[] = $this->valueOnOptionals ? 
						   $this->escape($name) . '="' . $this->escape($name) . '"' : 
						   $this->escape($name);

			} elseif( is_numeric($name) ) {

				$attrs[] = $this->valueOnOptionals ? 
						   $this->escape($value) . '="' . $this->escape($value) . '"' : 
						   $this->escape($value);

			} else {

				$attrs[] = $this->escape($name) . '="' . $this->escape($value) . '"';
				
			}

		}

		return implode(' ', $attrs);
	}

	/**
	 * Returns default tag reference
	 * 
	 * @return array
	 */
	protected function defaultTags()
	{
		//TODO: Terminar a referencia html
		return array(
			'div' => array(
				'tagName' => 'div',
				'hasClosingTag' => true,
			),
			'p' => array(
				'tagName' => 'p',
				'hasClosingTag' => true,
			),
			'span' => array(
				'tagName' => 'span',
				'hasClosingTag' => true,
			),
			'input' => array(
				'tagName' => 'input',
				'hasClosingTag' => false,
			),
		);
	}

}