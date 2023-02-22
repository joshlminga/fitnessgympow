<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CoreField extends CI_Model
{

	private $DB = "";

	/**
	 *
	 * To load libraries/Model/Helpers/Add custom code which will be used in this Model
	 * This can ease the loading work 
	 * 
	 */
	public function __construct()
	{

		parent::__construct();

		//libraries

		//Helpers

		//Models

		// Your own constructor code
	}

	/**
	 *
	 * This function is used to load all your custom data requred to be present for the system/website to oparate well
	 * All values are return as one array (data)
	 * 
	 */
	public function load()
	{
		//Values Assets

		//Loading 
		$data['customasset'] = $this->CoreLoad->ext_asset('/extensions'); //Extension
		$data['customcss'] = $data['customasset'] . '/custom-css'; //Extension - Custom Extension

		$data['h4_pagetitle'] = 'Dashboard';
		$data['breadcrumb'] = array();

		// Brand
		$data['brand_name'] = "Nelson Fitness";
		$data['product_name'] = "Fitness One";
		$data['dev_name'] = "Vormia";
		$data['coder_name'] = "Josh";

		// 

		//returned DATA
		return $data;
	}
}

/** End of file CoreField.php */
/** Location: ./application/models/CoreField.php */
