<?php 

use \HTMLOven\HTMLElement;
use \HTMLOven\HTMLReference;
use \HTMLOven\HTML5Reference;
use \HTMLOven\XHTMLReference;

class HTMLElementTest extends PHPUnit_Framework_TestCase
{
	public $el;

	public function setUp()
	{
		$this->el = new HTMLElement;
	}

	public function testHTMLReferenceIsAccessibleAndMutable()
	{
		$reference = HTMLReference::of('html5');

		//test if the element's HTML reference set correctly
		$this->el->setHTMLReference($reference);
		$this->assertEquals($reference, $this->el->getHTMLReference());
	}

	public function testTagNameIsAccessibleAndMutable()
	{
		$tagName = 'table';

		//test if the element's tag name set correctly
		$this->el->setTagName($tagName);
		$this->assertEquals($tagName, $this->el->getTagName());

		//test if setting a blank tagName does not change the tagName value
		$this->el->setTagName('');
		$this->assertEquals($tagName, $this->el->getTagName());
	}

	public function testTextIsAccessibleAndMutable()
	{
		$innerText = 'This is the text content of the tag.';

		//test if the element's inner text set correctly
		$this->el->setText($innerText);
		$this->assertEquals($innerText, $this->el->getText());
	}

	public function testAllAttributesAreAccessibleAndMutable()
	{
		$attrs = array(
			'id' => 'elementId',
			'class' => 'element-class',
			'name' => 'elementName',
			'value' => 'elementsValue',
		);

		//Test if the element's attributes sets correctly
		$this->el->setAttributes($attrs);
		$this->assertEquals($attrs, $this->el->getAttributes());

		//Test if the element's attribus clears correctly
		$this->el->clearAttributes();
		$this->assertEquals(0, count($this->el->getAttributes()));

		//Test if it adds an attribute corectly
		$this->el->addAttribute('id', $attrs['id']);
		$this->assertEquals(1, count($this->el->getAttributes()));

		//Test if it retrieves an attribute correctly
		$this->assertEquals($attrs['id'], $this->el->getAttribute('id'));

		//Test if it returns false when an attribute is not found And if it removes an attribute
		$this->el->removeAttribute('id');
		$this->assertFalse($this->el->getAttribute('id'));
		$this->assertEquals(0, count($this->el->getAttributes()));
	}

	public function testClosableHTMLElementRendersCorrectly()
	{
		$this->el->setTagName('p');

		//Test if the basic element renders correclty without text or attributes.
		$this->assertEquals('<p></p>', $this->el->render());

		//Test if the element renders correctly with the inner text.
		$this->el->setText('Im a content!');
		$this->assertEquals('<p>Im a content!</p>', $this->el->render());

		//Test if the element renders correctly with the attributes.
		$this->el->setText('');
		$this->el->addAttribute('id', 'someId');
		$this->el->addAttribute('class', 'some-class');
		$this->el->addAttribute('data-toggle', 'dialog');
		$this->assertEquals(
			'<p id="someId" class="some-class" data-toggle="dialog"></p>', 
			$this->el->render()
		);

		//Test if the element renders correctly with the attributes and text.
		$this->el->removeAttribute('data-toggle');
		$this->el->setText('Im a content!');
		$this->assertEquals('<p id="someId" class="some-class">Im a content!</p>', $this->el->render());
	}

	public function testUnclosableHTMLElementRendersCorrectly()
	{
		$this->el->setTagName('input');

		//Test if the basic unclosable element renders correctly without attributes
		$this->assertEquals('<input>', $this->el->render());

		//Test if the unclosable element does not includes the inner text, even if it is set
		$this->el->setText('Im a content!');
		$this->assertEquals('<input>', $this->el->render());

		//Test if the unclosable element renders correnctly with attributes
		$this->el->addAttribute('id', 'someId');
		$this->el->addAttribute('class', 'some-class');
		$this->assertEquals('<input id="someId" class="some-class">', $this->el->render());

		//Test if attributes that doesn't need values, doesn't get values (HTML5 default), 
		$this->el->addAttribute('disabled');
		$this->assertEquals('<input id="someId" class="some-class" disabled>', $this->el->render());

		//Test if attributes that doesn't need values, doesn't get values (HTML5 default, ALTERNATIVE WAY), 
		$this->el->clearAttributes();
		$this->el->setAttributes(array(
			'id' => 'someId',
			'class' => 'some-class',
			'disabled'
		));
		$this->assertEquals('<input id="someId" class="some-class" disabled>', $this->el->render());
	}

	public function testXHTMLElementRendersCorrectly()
	{
		$this->el->setTagName('input');
		$this->el->setHTMLReference( HTMLReference::of('xhtml') );

		//Test if the basic unclosable element renders correctly without attributes
		$this->assertEquals('<input/>', $this->el->render());

		//Test if the unclosable element does not includes the inner text, even if it is set
		$this->el->setText('Im a content!');
		$this->assertEquals('<input/>', $this->el->render());

		//Test if the unclosable element renders correnctly with attributes
		$this->el->addAttribute('id', 'someId');
		$this->el->addAttribute('class', 'some-class');
		$this->assertEquals('<input id="someId" class="some-class"/>', $this->el->render());

		//Test if attributes that doesn't need values, gets the values (XHTML default), 
		$this->el->addAttribute('disabled');
		$this->assertEquals(
			'<input id="someId" class="some-class" disabled="disabled"/>', 
			$this->el->render()
		);

		//Test if attributes that doesn't need values, gets the values (XHTML default, ALTERNATIVE WAY), 
		$this->el->clearAttributes();
		$this->el->setAttributes(array(
			'id' => 'someId',
			'class' => 'some-class',
			'disabled'
		));
		$this->assertEquals(
			'<input id="someId" class="some-class" disabled="disabled"/>', 
			$this->el->render()
		);
	}

	public function tearDown()
	{
		$this->el = null;
	}
}