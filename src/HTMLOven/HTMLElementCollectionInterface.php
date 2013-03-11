<?php namespace HTMLOven;

interface HTMLElementCollectionInterface
{
	/**
	 * Sets all children of the element
	 * 
	 * @param array $children The array of children elements
	 */
	function setChildren(array $children);

	/**
	 * Gets all children of the element
	 * 
	 * @return array all Array of children elements
	 */
	function getChildren();

	/**
	 * Add a child element to the element
	 * 
	 * @param HTMLElementInterface $reference the child element
	 */
	function addChild(HTMLElementInterface $element);

	/**
	 * Returns the first child of the children list
	 * 
	 * @return HTMLElementInterface
	 */
	function firstChild();

	/**
	 * Returns the last child of the children list
	 * 
	 * @return HTMLElementInterface
	 */
	function lastChild();

	/**
	 * Returns the total number of child elements that the element has
	 * 
	 * @return int
	 */
	function countChildren();

	/**
	 * Removes all children from the element
	 * 
	 * @return void
	 */
	function clearChildren();

	/**
	 * Returns an indicator that the children list has any child
	 * 
	 * @return boolean
	 */
	function hasChildren();

	/**
	 * Execute a callback over each child.
	 * 
	 * @param  Closure $callback the callback
	 * @return void
	 */
	function eachChild($callback);

	/**
	 * Runs a map over each child, returning the result array.
	 * 
	 * @param  Closure $callback the callback
	 * @return array           the resulting map
	 */
	function mapChildren($callback);

	/**
	 * Runs a filter over each child.
	 * 
	 * @param  Closure $callback the filter callback
	 * @return void
	 */
	function filterChildren($callback);
}