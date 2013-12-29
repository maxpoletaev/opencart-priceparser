<?php namespace PriceParser;

use Exception;

/**
 * @author Maxim Shkalin <dt5@bk.ru>
 * @package PriceParser
 */
class Scheme {

	/**
	 * @var array
	 */
	private $scheme = array();

	/**
	 * @param string $schemeFile
	 * @param string $schemeBase
	 * @return void
	 */
	public function __construct($schemeBase, $schemeExtend = null)
	{
		$this->scheme = ($this->isJson($schemeBase))?
			json_decode($schemeBase, true) : json_decode(file_get_contents($schemeBase), true)
		;

		if ( ! empty($schemeExtend))
		{
			$extend = ($this->isJson($schemeExtend))?
				json_decode($schemeExtend, true) : json_decode(file_get_contents($schemeExtend), true)
			;

			$this->extend($extend);
		}
	}

	/**
	 * @return array
	 */
	public function build()
	{
		return $this->scheme;
	}

	/**
	 * @param array
	 * @return void
	 */
	public function extend($scheme)
	{
		$this->scheme = array_replace_recursive($this->scheme, $scheme);
	}

	/**
	 * @param array $rowData
	 * @param array $data
	 * @return void
	 */
	public function process($rowData, $data = array())
	{
		foreach ($this->scheme as $field => $scheme)
		{
			if (isset($scheme['column']) && $scheme['overwrite'])
			{
				$columnNumber = $scheme['column'] - 1;
				
				if ( ! empty($rowData[$columnNumber]))
				{
					$data[$field] = trim($rowData[$columnNumber]);
				}
			}
			else
			{
				if ( ! isset($data[$field]))
				{
					$data[$field] = $scheme['default'];
				}
			}

			if (isset($scheme['filters']))
			{
				foreach($scheme['filters'] as $filter)
				{
					if (isset($data[$field]))
					{
						$filterMethod = "{$filter['type']}Filter";
						$data[$field] = $this->{$filterMethod}($data[$field], $filter);
					}
				}
			}
		}

		return $data;
	}

	/**
	 * @param $string
	 * @return bool
	 */
	private function isJson($string)
	{
		return preg_match('/^[\[\{]\"/', $string);
	}

	/**
	 * @param mixed $data
	 * @param StdClass $filter
	 * @return string
	 */
	private function replaceFilter($data, $filter)
	{
		$default = array(
			'regexp'  => false,
			'search'  => '',
			'replace' => ''
		);

		$props = array_replace_recursive($default, $filter);

		$data = ($props['regexp'])?
			preg_replace($props['search'], $props['replace'], $data):
			str_replace($props['search'], $props['replace'], $data)
		;

		return $data;
	}

	/**
	 * @param mixed $data
	 * @param StdClass $filter
	 * @return string
	 */
	private function recalculateFilter($data, $filter)
	{
		$default = array(
			'percentage' => false,
			'action'     => '+',
			'value'      => 0
		);

		$props = array_replace_recursive($default, $filter);
		$value = ($props['percentage'])? $data / 100 * $props['value'] : $props['value'];

		switch ($props['action'])
		{
			case '+': $data = $data + $value; break;
			case '-': $data = $data - $value; break;
			case '*': $data = $data * $value; break;
			case '/': $data = $data / $value; break;
		}

		return $data;
	}

}
