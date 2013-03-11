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

	public function testChildrenAreAccessibleAndMutable()
	{
		//Basic setting and getting tests
		$child1 = new HTMLElement('span');
		$child2 = new HTMLElement('p');
		$child3 = new HTMLElement('input');

		//Testing adding a child
		$this->el->addChild( $child1 );

		$this->assertEquals(array($child1), $this->el->getChildren());

		//Testing seting multiple children
		$this->el->setChildren(array($child1, $child2));

		$this->assertEquals(array($child1, $child2), $this->el->getChildren());
		$this->assertEquals(2, $this->el->countChildren());

		//Testing clearing children
		$this->el->clearChildren();

		$this->assertFalse($this->el->hasChildren());

		//Testing first and last child getters
		$this->el->setChildren(array($child1, $child2, $child3));

		$this->assertEquals($child1, $this->el->firstChild());
		$this->assertEquals($child3, $this->el->lastChild());
	}

	public function testChildrenBatchMutatorAndTraversalMethodsWorksCorrectly()
	{		
		$this->el->setChildren(array(
			new HTMLElement('span'),
			new HTMLElement('p'),
			new HTMLElement('input')
		));

		//Testing mutator for each child, aplying callback for each child
		$this->el->eachChild(function($childEl)
		{
			$childEl->setText('Im an inner text!');
		});

		foreach ($this->el->getChildren() as $childEl) {
			$this->assertEquals('Im an inner text!', $childEl->getText());
		}

		//Testing map over each child
		$map = $this->el->mapChildren(function($childEl)
		{
			$childEl = clone $childEl;

			$childEl->setText('Inner text changed!');

			return $childEl;
		});

		foreach ($this->el->getChildren() as $i => $childEl) {			
			$this->assertEquals('Im an inner text!', $childEl->getText());
			$this->assertEquals('Inner text changed!', $map[$i]->getText());
		}

		//Testing filter on children
		$this->el->filterChildren(function($childEl)
		{
			return $childEl->getTagName() === 'input';
		});

		$this->assertEquals(1, $this->el->countChildren());
		$this->assertEquals('input', $this->el->firstChild()->getTagName());
	}

	public function testChildrenElementsAreRenderedCorrectly()
	{
		//Test simple children redering
		$childEl1 = new HTMLElement('h3');
		$childEl1->setText('This is a title');

		$childEl2 = new HTMLElement('p');
		$childEl2->setText('This is a content.');

		$this->el->addChild( $childEl1 );
		$this->el->addChild( $childEl2 );

		$this->assertEquals(
			'<div><h3>This is a title</h3><p>This is a content.</p></div>',
			$this->el->render()
		);

		//Test attributes children rendering
		$this->el->firstChild()->addAttribute('class', 'title');
		$this->el->lastChild()->addAttribute('class', 'pretty-paragraph');
		$this->el->lastChild()->addAttribute('id', 'p1');

		$this->assertEquals(
			'<div><h3 class="title">This is a title</h3><p class="pretty-paragraph" id="p1">This is a content.</p></div>',
			$this->el->render()
		);

		//Test deep childs
		$this->el->firstChild()->addChild( new HTMLElement('span', array(), 'DEEP!') );

		$this->assertEquals(
			'<div><h3 class="title">This is a title<span>DEEP!</span></h3><p class="pretty-paragraph" id="p1">This is a content.</p></div>',
			$this->el->render()
		);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testThrowsExceptionWhenAddingChildOnUnclosables()
	{
		$this->el->setTagName('input');

		$this->el->addChild( new HTMLElement );
	}

	public function tearDown()
	{
		$this->el = null;
	}
}