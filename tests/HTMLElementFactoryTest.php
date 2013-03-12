<?php 

use \HTMLOven\HTMLElementFactory;
use \HTMLOven\HTMLReference;

class HTMLElementFactoryTest extends PHPUnit_Framework_TestCase
{
	public $factory;

	public function setUp()
	{
		$this->factory = new HTMLElementFactory;
	}

	public function testCreatingElementsAreWorkingAsExpected()
	{
		//Testing default way of creating elements
		$div = $this->factory->create('div', array('class' => 'container'));

		$this->assertEquals('<div class="container"></div>', $div->render());

		//Testing syntax sugar for creating elements
		$ul = HTMLElementFactory::ul(array('class' => 'nav'));

		$this->assertEquals('<ul class="nav"></ul>', $ul->render());

		//Testing HTMLReference changing
		$input = $this->factory->create('input', array('type' => 'text', 'name' => 'someName', 'value' => 'someValue', 'required'));

		$this->factory->setHTMLReference( HTMLReference::of('xhtml') );

		$inputX = $this->factory->create('input', array('type' => 'text', 'name' => 'someName', 'value' => 'someValue', 'required'));

		$this->assertEquals('<input type="text" name="someName" value="someValue" required>', $input->render());
		$this->assertEquals('<input type="text" name="someName" value="someValue" required="required"/>', $inputX->render());
	}

	public function tearDown()
	{
		$this->factory = null;
	}
}