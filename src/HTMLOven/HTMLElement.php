<?php namespace HTMLOven;

class HTMLElement implements HTMLElementInterface
{
	/**
	 * The HTML reference for HTML validators compliance
	 * 
	 * @var HTMLReferenceInterface
	 */
	protected $reference;

	/**
	 * The element's tag name
	 * @var string
	 */
	protected $tagName = 'div';

	/**
	 * The element's inner text content
	 * @var string
	 */
	protected $text = '';

	/**
	 * The element's attributes
	 * @var array
	 */
	protected $attributes = array();


	public function __construct(
		$tagName = 'div', $attributes = array(), $text = '', HTMLReferenceInterface $reference = null
	) {

		$this->setTagName($tagName);
		$this->setAttributes($attributes);
		$this->setText($text);

		if ( ! is_null($reference) )
			$this->setHTMLReference($reference);

	}

	/**
	 * Sets the HTML reference for HTML validators compliance
	 * 
	 * @param HTMLReferenceInterface $reference the HTMLReference object
	 */
	public function setHTMLReference(HTMLReferenceInterface $reference)
	{
		$this->reference = $reference;
	}

	/**
	 * Gets the HTML reference for HTML validators compliance
	 * 
	 * @return HTMLReferenceInterface the HTMLReference object
	 */
	public function getHTMLReference()
	{
		return $this->reference;
	}

	/**
	 * Sets the element's tag name
	 * 
	 * @param string $name the tag name
	 */
	public function setTagName($name)
	{
		$name = (string)$name;

		if ($name == '')
			return;

		$this->tagName = $name;
	}

	/**
	 * Gets the element's tag name
	 * 
	 * @return string the tag name
	 */
	public function getTagName()
	{
		return $this->tagName;
	}

	/**
	 * Sets the element's inner text or body
	 * 
	 * @param string $text the inner text or body
	 */
	public function setText($text)
	{
		$this->text = (string)$text;
	}

	/**
	 * Sets the element's inner text or body
	 * 
	 * @return string the inner text or body
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * Sets the element's attributes by passing a associative array.
	 * Example:
	 * 
	 * <code>
	 * $attributes = array(
	 * 	  'id' => 'someId',
	 * 	  'name' => 'someName'
	 * );
	 * </code>
	 * 
	 * @param array $attrs the associati array.
	 */
	public function setAttributes(array $attrs)
	{
		$this->clearAttributes();

		foreach ($attrs as $name => $value) {
			$this->addAttribute($name, $value);
		}
	}

	/**
	 * Gets the element's attributes.
	 * 
	 * @return array the attributes
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Adds an attribute to the list of the element's attributes.
	 * 
	 * @param string $name  the attribute's name
	 * @param string $value the attribute's value
	 */
	public function addAttribute($name, $value = '')
	{
		$this->attributes[ (string)$name ] = (string)$value;
	}

	/**
	 * Gets the attribute's value from the element, if it exists, otherwise return false.
	 * 
	 * @param  string $name the attribute's name
	 * @return string|bool the attribute's value or false if not found
	 */
	public function getAttribute($name)
	{
		$name = (string)$name;

		//if the name is blank or it is not found, returns false.
		if ( ! $this->hasAttribute($name) )
			return false;

		return $this->attributes[ $name ];
	}

	/**
	 * Removes an attribute of the list of the element's attributes.
	 * 
	 * @param  string $name the attribute's name
	 */
	public function removeAttribute($name)
	{
		$name = (string)$name;

		if ( $this->hasAttribute($name) )
			unset( $this->attributes[ $name ] );
	}

	/**
	 * Empties the element's attribute list.
	 * 
	 * @return void
	 */
	public function clearAttributes()
	{
		$this->attributes = array();
	}

	/**
	 * Returns if a attribute with the given name has been added to the element
	 * 
	 * @param  string  $name the attribute's name
	 * @return boolean       true if found, false if note
	 */
	public function hasAttribute($name)
	{
		return ( $name != '' ) and isset( $this->attributes[ $name ] );
	}

	/**
	 * Renders and returns the element's HTML code representation "as is".
	 * 
	 * @return string
	 */
	public function render()
	{
		$html = "<{$this->tagName}";

		if ( count( $this->attributes ) > 0 )
			$html .= ' ' . $this->compileAttributes();

		$html .= '>';

		if ( $this->reference->innerTextAllowed($this->tagName) and $this->text != '' )
			$html .= $this->escape($this->text);

		if ( $this->reference->needsClosingTag($this->tagName) )
			$html .= "</{$this->tagName}>";

		return $html;
	}

	/**
	 * Returns the element's HTML code representation.
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Escapes the string content
	 * 
	 * @param  string $string the string to be escaped
	 * @return string         the escaped string
	 */
	protected function escape($string)
	{
		return htmlentities( (string)$string );
	}

	/**
	 * Decodes the escaped string content
	 * 
	 * @param  string $string the escaped string
	 * @return string         the decoded string
	 */
	protected function unescape($string)
	{
		return html_entity_decode( (string)$string );
	}

	/**
	 * Compile the element's attributes array to a string of html attributes
	 * 
	 * @return string the compiled string
	 */
	protected function compileAttributes()
	{
		$attrs = array();

		$optionalValuesAllowed = $this->reference->optionalValuesAllowed();

		foreach ($this->attributes as $name => $value) {

			if ( $value == '' ) {

				$attrs[] = $optionalValuesAllowed ? 
						   $this->escape($name) : 
						   $this->escape($name) . '="' . $this->escape($name) . '"';

			} elseif( is_numeric($name) ) {

				$attrs[] = $optionalValuesAllowed ? 
						   $this->escape($value) : 
						   $this->escape($value) . '="' . $this->escape($value) . '"';

			} else {

				$attrs[] = $this->escape($name) . '="' . $this->escape($value) . '"';
				
			}

		}

		return implode(' ', $attrs);
	}
}