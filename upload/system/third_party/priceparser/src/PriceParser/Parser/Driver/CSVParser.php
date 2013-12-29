<?php namespace PriceParser\Parser\Driver;

use PriceParser\Parser\ParserInterface;
use PriceParser\Parser\ParserCore;

/**
 * @author Maxim Shkalin <dt5@bk.ru>
 * @package PriceParser
 */
class CSVParser extends ParserCore implements ParserInterface {

	/**
	 * @var array
	 */
	private $data =  array();

	/**
	 * {@inheritdoc}
	 */
	public function __construct($input, $isFile = false)
	{
		if ($isFile)
		{
			if (file_exists($input))
			{
				$file = file_get_contents($input);
				$this->data = $this->parse($file);
			}
		}
		else
		{
			$this->data = $this->parse($input);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function fetchArray()
	{
		return $this->data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRow($row = 1)
	{
		--$row;
		return isset($this->data[$row])? $this->data[$row] : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCell($row = 1, $cell = 1)
	{
		--$row; --$cell;
		return isset($this->data[$row][$cell])? $this->data[$row][$cell] : null;
	}

	/**
	 * @param string $data
	 * @param string $delmitter
	 * @return array
	 */
	private function parse($data, $delmitter = ';')
	{
		$rows = explode("\n", $data);
		$result = array();

		foreach ($rows as $row)
		{
			$result[] = explode($delmitter, $row);
		}

		return $result;
	}

}
