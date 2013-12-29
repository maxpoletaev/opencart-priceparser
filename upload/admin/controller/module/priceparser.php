<?php

/**
 * @author Maxim Shkalin <dt5@bk.ru>
 * @package PriceParser
 */
class ControllerModulePriceParser extends Controller {

	/**
	 * @return void
	 */
	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->load->model('module/priceparser');
		$this->data['lang'] = $this->load->language('module/priceparser');
	}

	/**
	 * @return void
	 */
	public function index()
	{
		$this->document->setTitle($this->data['lang']['heading_title']);
		$this->data['breadcrumbs'] = $this->buidlBreadcrumbs();

		if ($this->request->server['REQUEST_METHOD'] == 'POST')
		{
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('priceparser', $this->request->post);
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['action'] = $this->url->link('module/priceparser', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['import'] = $this->url->link('module/priceparser/upload', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['vendors'] = $this->model_module_priceparser->getVendors();
		$this->data['parsers'] = $this->model_module_priceparser->getParsers();

		$this->template = 'module/priceparser.tpl';
		$this->children = array('common/header', 'common/footer');

		$this->response->setOutput($this->render());
	}

	/**
	 * @return void
	 */
	public function upload()
	{
		$format = $this->request->post['format'];
		$vendor = $this->request->post['vendor'];
		$file = (isset($this->request->files['file']))? $this->request->files['file'] : null;
		$stats = array('created' => 0, 'updated' => 0);

		if ( ! empty($file))
		{
			$uploadedFile = DIR_CACHE . $file['name'];		
			move_uploaded_file($file['tmp_name'], $uploadedFile);

			$stats = $this->model_module_priceparser->parseProducts($format, $uploadedFile, $vendor);
			unlink($uploadedFile);
		}

		$this->response->addHeader('Content-type: application/json');
		$this->response->setOutput(json_encode($stats));
	}

	/**
	 * @return array
	 */
	private function buidlBreadcrumbs()
	{
		return array(
			array(
				'text'       => $this->language->get('text_home'),
				'href'       => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator'  => false
			),
			array(
				'text'       => $this->language->get('text_module'),
				'href'       => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
				'separator'  => ' :: '
			),
			array(
				'text'       => $this->language->get('heading_title'),
				'href'       => $this->url->link('module/exchange1c', 'token=' . $this->session->data['token'], 'SSL'),
				'separator'  => ' :: '
			)
		);
	}


}
