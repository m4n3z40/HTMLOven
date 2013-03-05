<?php namespace HTMLOven;

use \ArrayAccess;
use \Exception;

class HTMLElementFacade extends HTMLElement implements ArrayAccess
{
	/**
	 * List of functions that works as macros by calling its name as a static method
	 * 
	 * @var array
	 */
	protected static $macros = array();

	/**
	 * Registers a macro.
	 * 
	 * @param  string $name  the name of the macro
	 * @param  Closure $macro the macro function
	 * @return void
	 */
	public static function macro($name, $macro)
	{
		if ( is_callable( $macro ) ) {

			static::$macros[ $name ] = $macro;
			
		} else
			throw new Exception("A macro must be callable.");
		
	}

	/**
	 * Executes a macro with the static method name, if it exists.
	 * 
	 * @param  string $method name of the method
	 * @param  array $params method parameters
	 * @return mixed
	 */
	public static function __callStatic($method, $params)
	{
		if ( isset(static::$macros[ $method ]) ) {

			return call_user_func_array(static::$macros[ $method ], $params);
		}

		throw new Exception("Method $method does not exist.");		
	}

	/**
	 * Checks if attribute exists, as an array.
	 * 
	 * @param  string $offset the attribute's name
	 * @return bool
	 */
	public function offsetExists($offset) 
    { 
    	return isset($this->$offset);
    }

    /**
     * Adds an attribute with the given offset, as an array
     * 
     * @param  string $offset the attribute's name
     * @param  string $value  the attribute's value
     * @return void
     */
    public function offsetSet($offset, $value) 
    { 
    	$this->$offset = $value;
    } 

    /**
     * Gets an attribute with the given offset, as an array
     * 
     * @param  string $offset the attribute's name
     * @return string
     */
    public function offsetGet($offset) 
    { 
    	return $this->$offset;
    } 

    /**
     * Unsets an attribute with the given offset, as an array
     * 
     * @param  string $offset the attribute's name
     * @return void
     */
    public function offsetUnset($offset) 
    { 
    	unset($this->$offset);
    }

    /**
     * Sets an attribute's with the method as the name and the first param as the value
     * 
     * @param  string $method the method's name
     * @param  array $params the method's parameters
     * @return HTMLElement
     */
	public function __call($method, $params)
	{
		$this->$method = count($params) > 0 ? $params[0] : '';

		return $this;
	}

	/**
	 * Sets an attribute
	 * 
	 * @param string $key   the attribute's name
	 * @param string $value the attribute's value
	 */
	public function __set($key, $value)
	{
		$this->addAttribute($key, $value);
	}

	/**
	 * Gets an attribute
	 * 
	 * @param  string $key the attribute's name
	 * @return string
	 */
	public function __get($key)
	{
		return $this->getAttribute($key);
	}

	/**
	 * Checks if an attribute is set
	 * 
	 * @param  string  $key the attribute's name
	 * @return boolean
	 */
	public function __isset($key)
	{
		return $this->hasAttribute($key);
	}

	/**
	 * Removes an attribute from the list
	 * 
	 * @param string $key the attribute's name
	 */
	public function __unset($key)
	{
		$this->removeAttribute($key);
	}
}