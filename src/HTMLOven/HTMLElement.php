<?php namespace HTMLOven;

use InvalidArgumentException;

class HTMLElement implements HTMLElementInterface, HTMLElementCollectionInterface
{
	/**
	 * The HTML reference for HTML validators compliance
	 * 
	 * @var HTMLReference
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

	/**
	 * The list of child elements that the element has
	 * @var array
	 */
	protected $children = array();


	public function __construct(
		$tagName = 'div', $attributes = array(), $text = '', HTMLReference $reference = null
	) {

		$this->setTagName($tagName);
		$this->setAttributes($attributes);
		$this->setText($text);

		if ( ! is_null($reference) )
			$this->setHTMLReference( $reference );
		else
			$this->setHTMLReference( HTMLReference::of('html5') );

	}

	/**
	 * Sets all children of the element
	 * 
	 * @param array $children The array of children elements
	 */
	public function setChildren(array $children)
	{
		$this->clearChildren();

		foreach ($children as $child) {
			$this->addChild($child);
		}
	}

	/**
	 * Gets all children of the element
	 * 
	 * @return array all Array of children elements
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * Add a child element to the element
	 * 
	 * @param HTMLElementInterface $reference the child element
	 */
	public function addChild(HTMLElementInterface $element)
	{
		if ( ! $this->reference->closingTag( $this->tagName ) )
			throw new InvalidArgumentException('Children is not allowed in a "' . $this->tagName . '" element.');

		$this->children[] = $element;
	}

	/**
	 * Returns the first child of the children list
	 * 
	 * @return HTMLElementInterface
	 */
	public function firstChild()
	{
		return $this->hasChildren() ? reset($this->children) : null;
	}

	/**
	 * Returns the last child of the children list
	 * 
	 * @return HTMLElementInterface
	 */
	public function lastChild()
	{
		return $this->hasChildren() ? end($this->children) : null;
	}

	/**
	 * Returns the total number of child elements that the element has
	 * 
	 * @return int
	 */
	public function countChildren()
	{
		return count($this->children);
	}

	/**
	 * Removes all children from the element
	 * 
	 * @return void
	 */
	public function clearChildren()
	{
		$this->children = array();
	}

	/**
	 * Returns an indicator that the children list has any child
	 * 
	 * @return boolean
	 */
	public function hasChildren()
	{
		return $this->countChildren() > 0;
	}

	/**
	 * Execute a callback over each child.
	 * 
	 * @param  Closure $callback th callback
	 * @return void
	 */
	public function eachChild($callback)
	{
		array_map($callback, $this->children);
	}

	/**
	 * Runs a map over each child.
	 * 
	 * @param  Closure $callback the callback
	 * @return array           the resulting map
	 */
	public function mapChildren($callback)
	{
		return array_map($callback, $this->children);
	}

	/**
	 * Runs a filter over each child.
	 * 
	 * @param  Closure $callback the filter callback
	 * @return void
	 */
	public function filterChildren($callback)
	{
		$this->setChildren( array_filter($this->children, $callback) );
	}

	/**
	 * Sets the HTML reference for HTML validators compliance
	 * 
	 * @param HTMLReference $reference the HTMLReference object
	 */
	public function setHTMLReference(HTMLReference $reference)
	{
		$this->reference = $reference;
	}

	/**
	 * Gets the HTML reference for HTML validators compliance
	 * 
	 * @return HTMLReference the HTMLReference object
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
	 * @return boolean       true if found, false if not
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
		return $this->reference->render( $this );
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
}