<?php namespace HTMLOven;

class HTMLElementFactory
{
	/**
	 * Default HTML reference for creating elements
	 * 
	 * @var HTMLElement
	 */
	protected $HTMLReference;

	public function __construct(HTMLReference $reference = null)
	{
		if ( $reference instanceof HTMLReference )
			$this->setHTMLReference( $reference );
		else
			$this->setHTMLReference( HTMLReference::of('html5') );
	}

	/**
	 * Sets the default HTML reference
	 * 
	 * @param HTMLReference $reference
	 */
	public function setHTMLReference(HTMLReference $reference)
	{
		$this->HTMLReference = $reference;
	}

	/**
	 * Gets the default HTML reference
	 * 
	 * @return HTMLReference
	 */
	public function getHTMLReference()
	{
		return $this->HTMLReference;
	}

	/**
	 * Creates and returns a HTML element
	 * 
	 * @param  string $tagName   the tag name
	 * @param  array  $attrs     the element's attributes
	 * @param  string $innerText the element's inner text
	 * @return HTMLElementFacade
	 */
	public function create($tagName = 'div', $attrs = array(), $innerText = '')
	{
		return new HTMLElementFacade($tagName, $attrs, $innerText, $this->HTMLReference);
	}

	/**
	 * Syntax sugar for creating elements, alias of intance method "create"
	 * 
	 * @param  string $method the tag name
	 * @param  array $params
	 * @return HTMLElementFacade
	 */
	public static function __callStatic($method, $params)
	{
		$HTMLReference = isset($params[2]) ? $params[2] : null;
		$tagName = $method;
		$attrs = isset($params[0]) ? (array)$params[0] : array();
		$innerText = isset($params[1]) ? (array)$params[1] : '';

		$factory = new static( $HTMLReference );

		return $factory->create($tagName, $attrs, $innerText);
	}
}