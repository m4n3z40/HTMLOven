<?php namespace HTMLOven;

interface HTMLElementInterface
{
	/**
	 * Sets the HTML reference for HTML validators compliance
	 * 
	 * @param HTMLReference $reference the HTMLReference object
	 */
	function setHTMLReference(HTMLReference $reference);

	/**
	 * Gets the HTML reference for HTML validators compliance
	 * 
	 * @return HTMLReference the HTMLReference object
	 */
	function getHTMLReference();

	/**
	 * Sets the element's tag name
	 * 
	 * @param string $name the tag name
	 */
	function setTagName($name);

	/**
	 * Gets the element's tag name
	 * 
	 * @return string the tag name
	 */
	function getTagName();

	/**
	 * Sets the element's inner text or body
	 * 
	 * @param string $text the inner text or body
	 */
	function setText($text);

	/**
	 * Sets the element's inner text or body
	 * 
	 * @return string the inner text or body
	 */
	function getText();

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
	function setAttributes(array $attrs);

	/**
	 * Gets the element's attributes.
	 * 
	 * @return array the attributes
	 */
	function getAttributes();

	/**
	 * Adds an attribute to the list of the element's attributes.
	 * 
	 * @param string $name  the attribute's name
	 * @param string $value the attribute's value
	 */
	function addAttribute($name, $value = '');

	/**
	 * Gets the attribute's value from the element, if it exists, otherwise return false.
	 * 
	 * @param  string $name the attribute's name
	 * @return string|bool the attribute's value or false if not found
	 */
	function getAttribute($name);

	/**
	 * Removes an attribute of the list of the element's attributes.
	 * 
	 * @param  string $name the attribute's name
	 */
	function removeAttribute($name);

	/**
	 * Empties the element's attribute list.
	 * 
	 * @return void
	 */
	function clearAttributes();

	/**
	 * Renders and returns the element's HTML code representation "as is".
	 * 
	 * @return string
	 */
	function render();

	/**
	 * Returns the element's HTML code representation.
	 * 
	 * @return string
	 */
	function __toString();
}