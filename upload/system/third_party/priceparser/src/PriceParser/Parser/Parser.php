<?php namespace PriceParser\Parser;

/**
 * @author Maxim Shkalin <dt5@bk.ru>
 * @package PriceParser
 */
class Parser {

	/**
	 * @var ParserInterface
	 */
	private $parser;

	/**
	 * @param string $format
	 * @param string $input
	 * @return void
	 */
	public function __construct($format, $input)
	{
		$format = strtoupper($format);
		$classname = "PriceParser\Parser\Driver\\{$format}Parser";

		$this->parser = new $classname($input, true);
	}

	/**
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public function __call($method, $args)
	{
		return call_user_func_array(array($this->parser, $method), $args);
	}

}
