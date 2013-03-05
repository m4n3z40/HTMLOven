<?php 

use \HTMLOven\HTMLElementFacade;

class HTMLElementFacadeTest extends PHPUnit_Framework_TestCase
{
	public $el;

	public function setUp()
	{
		$this->el = new HTMLElementFacade;
	}

	public function testAttributesFacadeAcessorsAndMutatorsWorksCorrectly()
	{
		$actualAttributes = array(
			'id' => 'someId',
			'class' => 'some-class',
			'required' => '',
		);

		//Testing object property like access and mutations
		$this->el->id = $actualAttributes['id'];
		$this->el->class = $actualAttributes['class'];
		$this->el->required = $actualAttributes['required'];

		$this->assertTrue( isset($this->el->required) );
		$this->assertEquals($actualAttributes['id'], $this->el->id);
		$this->assertEquals($actualAttributes, $this->el->getAttributes());

		unset($this->el->class);

		$this->assertFalse($this->el->getAttribute('class'));

		$this->el->clearAttributes();

		//Testing array like access and mutations
		$this->el['id'] = $actualAttributes['id'];
		$this->el['class'] = $actualAttributes['class'];
		$this->el['required'] = $actualAttributes['required'];

		$this->assertTrue( isset($this->el['required']) );
		$this->assertEquals($actualAttributes['id'], $this->el['id']);
		$this->assertEquals($actualAttributes, $this->el->getAttributes());

		unset($this->el['class']);
		
		$this->assertFalse($this->el->getAttribute('class'));

		$this->el->clearAttributes();

		//Testing method like mutations and chainning methods
		$this->el->id( $actualAttributes['id'] )
				 ->class( $actualAttributes['class'] )
				 ->required();

		$this->assertEquals($actualAttributes, $this->el->getAttributes());
	}

	public function testMacrosCallsAndAssignmentsAreWorkingCorrectly()
	{
		//Testing macro assignment and calls with no arguments
		HTMLElementFacade::macro('macroTestNoArgs', function()
		{
			return 'This is a macro with no args.';
		});

		$this->assertEquals('This is a macro with no args.', HTMLElementFacade::macroTestNoArgs());

		//Testing macro assignment and calls with arguments
		HTMLElementFacade::macro('macroTestWithArgs', function($arg1, $arg2)
		{
			return "A $arg1 is nothing like a $arg2";
		});

		$this->assertEquals(
			'A panda is nothing like a duck', 
			HTMLElementFacade::macroTestWithArgs('panda', 'duck')
		);
	}

	public function tearDown()
	{
		$this->el = null;
	}
}