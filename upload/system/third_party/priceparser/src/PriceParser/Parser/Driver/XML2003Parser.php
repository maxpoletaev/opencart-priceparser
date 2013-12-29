<?php namespace PriceParser\Parser\Driver;

use PriceParser\Parser\ParserInterface;
use PriceParser\Parser\ParserCore;

/**
 * @author Maxim Shkalin <dt5@bk.ru>
 * @package PriceParser
 */
class XML2003Parser extends ParserCore implements ParserInterface {

	/**
	 * @var array
	 */
	private $data;

	/**
	 * {@inheritdoc}
	 */
	public function __construct($input, $isFile = false)
	{
		if ($isFile)
		{
			if (file_exists($input))
			{
				$xml = simplexml_load_file($input);
				$this->data = $this->parse($xml);
			}
		}
		else
		{
			$xml = simplexml_load_string($input);
			$this->data = $this->parse($xml);
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
	public function getRow($row)
	{
		--$row;
		return isset($this->data[$row])? $this->data[$row] : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCell($row, $cell)
	{
		--$row; --$cell;
		return isset($this->data[$row][$cell])? $this->data[$row][$cell] : null;
	}

	/**
	 * @param SimpleXMLElement
	 * @return array
	 */
	private function parse($xml)
	{
		$result = array();
		foreach ($xml->Worksheet->Table->Row as $rowData)
		{
			$row = array();
			foreach ($rowData->Cell as $cellData)
			{
				$row[] = (string) $cellData->Data;
			}

			$result[] = $row;
		}

		return $result;
	}

}
