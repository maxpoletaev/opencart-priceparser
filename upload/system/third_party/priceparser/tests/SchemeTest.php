<?php

use PriceParser\Scheme;

class SchemeTest extends PHPUnit_Framework_TestCase {

	public function testBuid()
	{
		$result = array(
			'field' => [
				'overwrite' => true,
				'default'   => 'b',
				'column'    => 1
			]
		);

		$scheme = $this->createTestScheme();
		$this->assertEquals($scheme->build(), $result);
	}

	public function testExtend()
	{
		$extend = array(
			'field' => [
				'overwrite' => false,
				'column'    => 10
			]
		);

		$result = array(
			'field' => [
				'overwrite' => false,
				'default'   => 'b',
				'column'    => 10
			]
		);

		$scheme = $this->createTestScheme();
		$scheme->extend($extend);

		$this->assertEquals($scheme->build(), $result);
	}

	public function testProcess()
	{
		$newData = array('new value');
		$oldData = array('field' => 'old value');
		$result  = array('field' => 'new value');

		$scheme = $this->createTestScheme();
		$processResult = $scheme->process($newData, $oldData);

		$this->assertEquals($processResult, $result);
	}

	public function testProcessOverwrite()
	{
		$newData = array('new value');
		$oldData = array('field' => 'old value');
		$result  = array('field' => 'old value');

		$scheme = $this->createTestScheme(false);
		$processResult = $scheme->process($newData, $oldData);

		$this->assertEquals($processResult, $result);
	}

	public function testReplaceFilter()
	{
		$data = array('new value');
		$result = array('field' => 'replaced value');

		$filters = array(
			'field' => [
				'filters' => [
					[
						'type'    => 'replace',
						'search'  => 'new',
						'replace' => 'replaced'
					]
				]
			]
		);

		$scheme = $this->createTestScheme();
		$scheme->extend($filters);

		$processResult = $scheme->process($data);
		$this->assertEquals($processResult, $result);
	}

	public function testRecalculateFilter()
	{
		$data = array(100);
		$result  = array('field' => 120);

		$filters = array(
			'field' => [
				'filters' => [
					[
						'type'       => 'recalculate',
						'action'     => '+',
						'value'      => 20,
						'percentage' => true
					]
				]
			]
		);

		$scheme = $this->createTestScheme();
		$scheme->extend($filters);

		$processResult = $scheme->process($data);
		$this->assertEquals($processResult, $result);
	}

	private function createTestScheme($overwrite = true, $default = 'b', $column = 1)
	{
		$scheme = json_encode([
			'field' => [
				'overwrite' => $overwrite,
				'default'   => $default,
				'column'    => $column
			]
		]);

		return new Scheme($scheme);
	}

}
