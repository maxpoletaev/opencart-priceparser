<?php namespace PriceParser\Parser;

/**
 * @author Maxim Shkalin <dt5@bk.ru>
 * @package PriceParser
 */
interface ParserInterface {

	/**
	 * @param string $input
	 * @param bool $isFile
	 * @return void
	 */
	public function __construct($input, $isFile);

	/**
	 * @return array
	 */
	public function fetchArray();

	/**
	 * @param int $row
	 * @return array
	 */
	public function getRow($row);

	/**
	 * @param int $row
	 * @param int $cell
	 * @return string
	 */
	public function getCell($row, $cell);

}
