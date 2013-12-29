<?php require(DIR_SYSTEM.'third_party/priceparser/autoload.php');

use PriceParser\Parser\Parser;
use PriceParser\Scheme;

/**
 * @author Maxim Shkalin <dt5@bk.ru>
 * @package PriceParser
 */
class ModelModulePriceParser extends Model {

	/**
	 * @var array
	 */
	private $paths = array();
	
	/**
	 * @return void
	 */
	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->load->model('catalog/product');

		$this->paths = array(
			'extend' => DIR_CONFIG . 'priceparser/vendor',
			'base'   => DIR_CONFIG . 'priceparser/scheme'
		);
	}

	/**
	 * @param string $format
	 * @param string $file
	 * @param string $scheme
	 * @param string $vendor
	 * @return void
	 */
	public function parseProducts($format, $file, $vendor)
	{
		$stats = array('created' => 0, 'updated' => 0);
		$parser = new Parser($format, $file);
		$dbPrefix = DB_PREFIX;

		$scheme = $this->paths['base'];
		$vendor = $this->paths['extend'].'/'.$vendor;

		$productScheme = new Scheme("{$scheme}/product.json", "{$vendor}/product.json");
		$productDescrptionScheme = new Scheme("{$scheme}/product_description.json", "{$vendor}/product_description.json");

		foreach ($parser->fetchArray() as $row)
		{
			$product = $productScheme->process($row);
			foreach ($this->getLanguageIds() as $lang)
			{
				$product['product_description'][$lang] = $productDescrptionScheme->process($row);
			}

			if ( ! empty($product['model']))
			{
				$model = $product['model'];
				$query = $this->db->query("SELECT * FROM {$dbPrefix}product WHERE model = '{$model}'");

				if ( ! empty($product['price']))
				{
					if ($query->num_rows) {
						$this->model_catalog_product->editProduct($query->row['product_id'], $product);
						$stats['updated']++;
					} else {
						$this->model_catalog_product->addProduct($product);
						$stats['created']++;
					}
				}
			}
		}

		return $stats;
	}

	/**
	 * @return array
	 */
	public function getLanguageIds()
	{
		$dbPrefix = DB_PREFIX;
		$languageIds = array();

		foreach ($this->db->query("SELECT * FROM {$dbPrefix}language")->rows as $language)
		{
			$languageIds[] = $language['language_id'];
		}

		return $languageIds;
	}

	/**
	 * @return array
	 */
	public function getVendors()
	{
		$files = scandir($this->paths['extend']);

		array_shift($files); array_shift($files);
		return $files;
	}

	/**
	 * @return array
	 */
	public function getParsers()
	{
		$directory = DIR_SYSTEM . 'third_party/priceparser/src/PriceParser/Parser/Driver';
		$files = scandir($directory);
		$parsers = array();

		foreach ($files as $file) {
			if ($file != '.' && $file != '..') {
				$parsers[] = strtolower(str_replace(array('Parser', '.php'), '', $file));
			}
		}

		return $parsers;
	}

}
