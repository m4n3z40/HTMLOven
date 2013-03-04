<?php namespace HTMLOven;

class HTMLReference
{
	/**
	 * The array representing the tag reference
	 *
	 * <code>
	 * array(
	 *	'div' => array(
	 *		'tagName' => 'div',
	 *	  	'hasClosingTag' => true,
	 *	),
	 *	'p' => array(
	 *		'tagName' => 'p',
	 *  	'hasClosingTag' => true,
     * 	),
     * )
	 * </code>
	 * 
	 * @var array
	 */
	protected $tags = array();

	/**
	 * An indicator that the values on attributes tha can be omited, 
	 * should be omited or not.
	 * 
	 * @var boolean
	 */
	protected $valueOnOptionals = false;

	/**
	 * An indicator that the elements that doesn't have closing tags, 
	 * should be have a closing slash or not.
	 * 
	 * @var boolean
	 */
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
			return $this->tags[ $tagName ];
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
			return $this->tags[ $tagName ];
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

	/**
	 * Factory: Creates an child instance of the HTMLReference class
	 * @param  string $referenceName the HTMLReference class name
	 * @param  array  $data          the initial data (tag references)
	 * @return HTMLReference
	 */
	public static function of($referenceName, array $data = array())
	{
		$referenceName = strtoupper(trim( (string)$referenceName ));

		$classSuffix = 'Reference';

		$className = __NAMESPACE__ . '\\' . $referenceName . $classSuffix;

		return new $className( $data );
	}

}