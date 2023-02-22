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

		// User
		$data['userinfo'] = $this->user_info();

		//returned DATA
		return $data;
	}

	/**
	 *
	 * This function is used to load user info
	 * All values are return as one array (data)
	 * 
	 * @param string $user_id
	 */
	public function user_info($user_id = null)
	{
		// check $user_id
		$user_id = (is_null($user_id)) ? $this->CoreLoad->session('id') : $user_id;

		// Default
		$found = (object) ['name' => '', 'email' => '', 'phone', 'profile' => $this->CoreForm->userProfile()];

		if (!is_numeric($user_id)) {
			return $found;
		}

		// User info
		$details = $this->CoreCrud->selectSingleValue('user', 'details', ['id' => $user_id]);
		$foundData = json_decode($details);

		// Returned Data
		$found = (object) [
			'name' => $foundData->user_name,
			'email' => $foundData->user_email,
			'phone' => $foundData->user_mobile,
			'profile' => $this->CoreForm->userProfile($user_id, 'profile'),
		];

		// Return
		return $found;
	}
}

/** End of file CoreField.php */
/** Location: ./application/models/CoreField.php */
