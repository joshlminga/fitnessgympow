<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{

	/**
	 *
	 * The main controller for Administrator Backend
	 * -> The controller require to login as Administrator
	 */

	private $Module = 'user'; //Module
	private $Folder = 'log'; //Set Default Folder For html files and Front End Use
	private $SubFolder = ''; //Set Default Sub Folder For html files and Front End Use Start with /

	private $AllowedFile = 'jpg|jpeg|png'; //Set Default allowed file extension, remember you can pass this upon upload to override default allowed file type. Allowed File Extensions Separated by | also leave null to validate using jpg|jpeg|png|doc|docx|pdf|xls|txt change this on validation function at the bottom

	private $Route = ''; //If you have different route Name to Module name State it here |This wont be pluralized

	private $Register = 'signup/account'; //Valid 
	private $Login = 'signin/account'; //New 
	private $OTP = 'signin/account/verify'; //New 

	private $ModuleName = '';

	/** Functions
	 * -> __construct () = Load the most required operations E.g Class Module
	 * 
	 */
	public function __construct()
	{
		parent::__construct();

		//Libraries
		$this->load->library('form_validation');
		$this->load->library('encryption');
		$this->load->helper('cookie');

		//Helpers
		date_default_timezone_set('Africa/Nairobi');

		//Models
		$this->load->model('CoreCrud');
		$this->load->model('CoreForm');
		$this->load->model('CoreData');
		$this->load->model('Fit/Notify', 'Notify');

		// Your own constructor code

	}

	/**
	 *
	 * Access Requred pre-loaded data
	 * The additional Model based data are applied here from passed function and join with load function
	 * The pageID variable can be left as null if you do not wish to access Meta Data values
	 * Initially what is passed is a pageID or Page Template Name
	 * 
	 */
	public function load($pageID = null)
	{

		//load Passed
		$passed = $this->passed();
		//Model Query
		$data = $this->CoreLoad->open($pageID, $passed);

		return $data;
	}

	/**
	 *
	 * Load the model/controller based data here
	 * The data loaded here does not affect the other models/controller/views
	 * It only can reach and expand to this controller only
	 * 
	 */
	public function passed($values = null)
	{

		//Time Zone
		date_default_timezone_set('Africa/Nairobi');
		$data['str_to_time'] = strtotime(date('Y-m-d, H:i:s'));
		$data['Module'] = $this->plural->pluralize($this->Route); //Module Show
		$data['routeURL'] = (is_null($this->Route)) ? $this->plural->pluralize($this->Folder) : $this->Route;

		//Form Submit URLs
		$data['form_register'] = $this->Register;
		$data['form_login'] = $this->Login;

		$data['notify_signin'] = $this->Notify->blank();

		return $data;
	}

	/**
	 *
	 * This is one of the most important functions in your project
	 * All pages used by this controller should be opened using pages function
	 * 1: The first passed data is an array containing all pre-loaded data N.B it can't be empty becuase page name is passed through it
	 * 2: Layout -> this can be set to default so it can open a particular layout always | also you can pass other layout N.B can't be empty
	 *
	 * ** To some page functions which are not public, use the auth method from CoreLoad model to check  is allowed to access the pages
	 * ** If your page is public ignore the use of auth method
	 * 
	 */
	public function pages($data, $layout = 'log')
	{

		//Theme Name
		$theme_name = $this->CoreCrud->selectSingleValue('settings', 'value', array('title' => 'theme_name', 'flg' => 1));

		//Check if site is online
		if ($this->CoreLoad->site_status() == TRUE) {
			//Layout
			$this->load->view("themes/$theme_name/layouts/$layout", $data);
		} else {
			$this->CoreLoad->siteOffline(); //Site is offline
		}
	}

	/**
	 *
	 * This is the first function to be accessed when  open this controller
	 * In here we can call the load function and pass data to passed as an array inorder to manupulate it inside passed function
	 * 	* Set your Page name/ID here N:B Page ID can be a number if you wish to access other values linked to the page opened E.g Meta Data
	 * 	* You can also set Page ID as actual pageName found in your view N:B do not put .php E.g home.php it should just be 'home'
	 * 	* Set Page template 
	 * 	* Set Notification here
	 * 	By Default index does not allow notification Message to be passed, it uses the default message howevr you can pass using the notifyMessage variable
	 * 	However we advise to use custom notification message while opening index utilize another function called open
	 * 
	 */
	public function index($notifyMessage = null)
	{
		//Model Query
		$data = $this->load($this->plural->pluralize($this->Folder) . $this->SubFolder . "/register");

		//Notification
		$notify = $this->Notify->notify();
		$data['notify'] = $this->Notify->$notify($notifyMessage);

		//Open Page
		$this->pages($data);
	}

	/**
	 *
	 * This is the function to be accessed when a user want to open specific page which deals with same controller E.g Edit data after saving
	 * In here we can call the load function and pass data to passed as an array inorder to manupulate it inside passed function
	 *** Set your Page name/ID here N:B Page ID can be a number if you wish to access other values linked to the page opened E.g Meta Data
	 *** You can also set Page ID as actual pageName found in your view N:B do not put .php E.g home.php it should just be 'home'
	 *** Set Page template 
	 *** Set Notification here
	 * 	Custom notification message can be set/passed via $message
	 * 	PageName / ID can be passed via $pageID
	 * 	Page layout can be passed via $layout
	 * 
	 */
	public function open($pageID, $message = null, $layout = 'log')
	{

		//Pluralize Module

		//Model Query
		$pageID = (is_numeric($pageID)) ? $pageID : $this->plural->pluralize($this->Folder) . $this->SubFolder . "/" . $pageID;
		$data = $this->load($pageID);

		//Notification
		$notify = $this->Notify->notify();
		$data['notify'] = $this->Notify->$notify($message);

		//Open Page
		$this->pages($data, $layout);
	}

	/**
	 *
	 * Module form values are validated here
	 * The function accept variable TYPE which is used to know which form element to validate by changing the validation methods
	 * All input related to this Module or controller should be validated here and passed to Create/Update/Delete
	 *
	 * Reidrect Main : Main is the controller which is acting as the default Controller (read more on codeigniter manual : route section) | inshort it will load 
	 * 				 first and most used to display the site/system home page
	 * 
	 */
	public function valid($type)
	{

		//Pluralize Module
		$module = $this->plural->pluralize($this->Module);
		$routeURL = (is_null($this->Route)) ? $module : $this->Route;
		$baseLoadPath = $this->plural->pluralize($this->Folder) . $this->SubFolder . '/';

		//Check Validation
		if ($type == 'register') {
			$formData = $this->CoreLoad->input(); //Input Data

			//Form Validation Values
			$this->form_validation->set_rules("first_name", "First Name", "required|trim|min_length[2]|max_length[60]");
			$this->form_validation->set_rules("last_name", "Last Name", "required|trim|min_length[2]|max_length[60]");
			$this->form_validation->set_rules(
				"user_email",
				"Email",
				"required|trim|max_length[150]|valid_email|is_unique[users.user_email]",
				['is_unique' => 'This email has already been registered.']
			);

			// Login Details

			$this->form_validation->set_rules("user_password", "Password", "required|trim|min_length[4]|max_length[20]");
			$this->form_validation->set_rules(
				"confirm_password",
				"Confirm Password ",
				"required|trim|min_length[4]|max_length[20]|matches[user_password]",
				['matches' => 'Password does not match.']
			);

			//Form Validation
			if ($this->form_validation->run() == TRUE) {

				//Verification Code
				$verification = $this->CoreLoad->random(10, 'ACDEFGHJKNMPRST23456789'); //OTP code
				// User Access
				$access = 'customer';

				// Extra
				$userData = [
					'terms' => 'I agree',
					'profile' => null,

					'user_level' => $access,
					'user_logname' => $formData['user_email'],
					'user_password' => $formData['user_password'],
					'user_name' => trim($formData['user_name']),
					'user_email' => $formData['user_email'],
					'user_default' => $verification,
					'user_flg' => 1,
				];

				// Unset Data
				$formData = $this->CoreCrud->unsetData($userData, array('confirm_password', 'access'));
				$unsetData = ['terms', 'profile'];

				// Create
				$userId = $this->create($formData, $unsetData);
				if ($userId) {
					// Send Email
					/*
					$this->CoreData->welcomeEmail($userId, $verification);

					//Model Query
					$pageID = $baseLoadPath . "/verify";
					$data = $this->load($pageID);

					//Notification
					$this->session->set_flashdata('notification', 'success'); //Notification Type
					$message = 'Account Created!, verify your email using link we sent'; //Notification Message	
					$data['notify'] = $this->Notify->success($message);

					// Data
					$data['user_info_id'] = 'mem-' . $userId;
					$data['account_email'] = $formData['user_email'];

					// WP Register User
					$this->CoreData->agentRegistration($userId);

					//Open Page
					$this->pages($data, 'log');
					*/
					// Redirect To Login
					redirect('signin', 'refresh');
				} else {
					$message = 'Failed!, account creating failed'; //Notification Message				
					//Notification
					$this->session->set_flashdata('notification', 'error'); //Notification Type
					$this->index($message);
				}
			} else {
				//Notification
				$this->session->set_flashdata('notification', 'error'); //Notification Type
				$message = 'Please check the fields, and try again'; //Notification Message				
				$this->index($message);
			}
		} else {
			$this->session->set_flashdata('notification', 'notify'); //Notification Type
			$this->index(); //Index Page
		}
	}

	/*
	* The function is used to save/insert data into table
	* First is the data to be inserted 
	*  N:B the data needed to be in an associative array form E.g $data = array('name' => 'theName');
	*      the array key will be used as column name and the value as inputted Data
	*  For colum default/details convert data to JSON on valid() method level
	*
	* Third is the data to be unset | Unset is to be used if some of the input you wish to be removed
	* 
	*/
	public function create($insertData, $unsetData = null)
	{

		//Pluralize Module
		$tableName = $this->plural->pluralize($this->Module);

		//Column Stamp
		$stamp = strtolower($this->CoreForm->get_column_name($this->Module, 'stamp'));
		$insertData["$stamp"] = date('Y-m-d H:i:s', time());

		//Column Password
		$column_password = strtolower($this->CoreForm->get_column_name($this->Module, 'password'));

		//Check IF there is Password
		if (array_key_exists($column_password, $insertData)) {
			$insertData[$column_password] = sha1($insertData[$column_password]);
		}

		$details = strtolower($this->CoreForm->get_column_name($this->Module, 'details'));
		$insertData["$details"] = json_encode($insertData);

		// Unset Data
		$insertData = $this->CoreCrud->unsetData($insertData, $unsetData);

		//Insert Data Into Table
		$user_response = $this->CoreCrud->insertData($tableName, $insertData);
		if ($user_response) {

			return $user_response; //Data Inserted
		} else {

			return false; //Data Insert Failed
		}
	}

	/**
	 * The function is used to update data in the table
	 * First parameter is the data to be updated 
	 *  N:B the data needed to be in an associative array form E.g $data = array('name' => 'theName');
	 *      the array key will be used as column name and the value as inputted Data
	 *  For colum default/details convert data to JSON on valid() method level
	 * Third is the values to be passed in where clause N:B the data needed to be in an associative array form E.g $data = array('column' => 'value');
	 * Fourth is the data to be unset | Unset is to be used if some of the input you wish to be removed
	 * 
	 */
	public function update($updateData, $valueWhere, $unsetData = null)
	{

		//Pluralize Module
		$tableName = $this->plural->pluralize($this->Module);

		//Column Stamp
		$stamp = $this->CoreForm->get_column_name($this->Module, 'stamp');
		$updateData["$stamp"] = date('Y-m-d H:i:s', time());

		//Column Password
		$column_password = strtolower($this->CoreForm->get_column_name($this->Module, 'password'));

		//Check IF there is Password
		if (array_key_exists($column_password, $updateData)) {
			$updateData[$column_password] = sha1($updateData[$column_password]);
		}

		//Details Column Update
		$details = strtolower($this->CoreForm->get_column_name($this->Module, 'details'));
		foreach ($valueWhere as $key => $value) {
			$whereData = array($key => $value);
			/** Where Clause */
		}

		$updateData = $this->CoreCrud->unsetData($updateData, $unsetData); //Unset Data

		$current_details = json_decode($this->db->select($details)->where($whereData)->get($tableName)->row()->$details, true);
		foreach ($updateData as $key => $value) {
			$current_details["$key"] = $value;
			/** Update -> Details */
		}
		$updateData["$details"] = json_encode($current_details);

		//Update Data In The Table
		if ($this->CoreCrud->updateData($tableName, $updateData, $valueWhere)) {

			return true; //Data Updated
		} else {

			return false; //Data Updated Failed
		}
	}

	/**
	 *
	 * Validate Email/Username (Logname)
	 * This function is used to validate if user email/logname already is used by another account
	 * Call this function to validate if nedited logname or email does not belong to another user
	 */
	public function lognamecheck($str)
	{
		// Set Parent Table
		$tableName = 'user';

		//Validate
		$check = (filter_var($str, FILTER_VALIDATE_EMAIL)) ? 'email' : 'logname'; //Look Email / Phone Number
		if (strtolower($str) == strtolower(trim($this->CoreCrud->selectSingleValue($tableName, $check, array('id' => $this->CoreLoad->session('id')))))) {
			return true;
		} elseif (is_null($this->CoreCrud->selectSingleValue($tableName, 'id', array($check => $str)))) {
			return true;
		} elseif ($this->CoreLoad->session('level') == 'admin') {
			return true;
		} else {
			$this->form_validation->set_message('lognamecheck', 'This {field} is already in use by another account');
			return false;
		}
	}

	/**
	 *
	 * Validate Full Name
	 * 
	 */
	public function fullname($str)
	{
		$full_name = explode(' ', $str);
		$count = count($full_name);
		if ($count < 2) {
			$this->form_validation->set_message('full_name', 'The %s field must be at-least two names.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * Check WP Nicename
	 * 
	 */
	public function wpnicename($str)
	{
		// WordPress Database
		$this->wpdb = $this->load->database('wpdb', TRUE);

		// Check Username
		$selectData = $this->wpdb->select('ID')->where(['user_nicename' => $str])->limit(1)->get('wp_users');
		$checkData = $this->CoreCrud->checkResultFound($selectData); //Check If Value Found
		$inputID = ($checkData == true) ? $selectData->row()->ID : null;

		// Check If ID is Null
		if (is_null($inputID)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('wpnicename', 'This {field} is already in use by another account');
			return FALSE;
		}
	}

	/**
	 * Check WP Email
	 * 
	 */
	public function wpuseremail($str)
	{
		// WordPress Database
		$this->wpdb = $this->load->database('wpdb', TRUE);
		// Check Username
		$selectData = $this->wpdb->select('ID')->where(['user_email' => $str])->limit(1)->get('wp_users');
		$checkData = $this->CoreCrud->checkResultFound($selectData); //Check If Value Found
		$inputID = ($checkData == true) ? $selectData->row()->ID : null;
		// Check If ID is Null
		if (is_null($inputID)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('wpuseremail', 'This {field} is already in use by another account');
			return FALSE;
		}
	}

	/**
	 * Check Special Characters
	 * 
	 */
	public function specialchars($str)
	{
		if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $str)) {
			$this->form_validation->set_message('specialchars', 'The {field} field must not contain special characters');
			return FALSE;
		}
		// Check if there is space in the string
		elseif (preg_match('/\s/', $str)) {
			$this->form_validation->set_message('specialchars', 'The {field} field must not contain space');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 *
	 * Validate Mobile/Phone Number
	 * This function accept/take input field value / Session  mobilecheck
	 *
	 * * The Method can be accessed via set_rules(callback_mobilecheck['+1']) // +1 is the country code
	 */
	public function mobilecheck($str = null, $dial_code = null)
	{

		// Set Parent Table
		$tableName = 'user';

		//Get The Phone/Mobile Number
		$number_reserve = trim(str_replace(" ", "", $str));
		$number = $number_reserve;

		// Check Default Dial Code
		$default_dial_code = (method_exists('CoreField', 'defaultDialCode')) ? $this->CoreField->defaultDialCode() : '+1';
		//Dial Code
		$dial_code = (!is_null($dial_code)) ? $dial_code : $default_dial_code; //Set Country Dial Code Here eg +1, by default it is empty
		$max_count = strlen($dial_code) - 1;

		// Replace + from Dial Code
		$dial_code_clear = str_replace('+', '', $dial_code);
		$dial_code_clear = str_replace(' ', '', $dial_code_clear);
		// If $number first digit is 0 replace it with dial code
		$number = (substr($number, 0, 1) == 0) ? substr_replace($number, $dial_code_clear, 0, 1) : $dial_code_clear . $number;

		//Check Rule
		$rules_validate = (method_exists('CoreField', 'mobileCheck')) ? $this->CoreField->mobileCheck($number) : false;
		$column_name = (filter_var($number, FILTER_VALIDATE_EMAIL)) ? 'email' : 'logname'; //Look Email / Phone Number
		//Validation
		if (!$rules_validate) {
			//Check First Letter if does not start with 0
			// if ($dial_code_clear == substr($number, 0, $max_count)) {
			if (preg_match("/$dial_code_clear/", $number)) {
				//Check If it Phone number belongs to you
				if (strtolower($number) == strtolower(trim($this->CoreCrud->selectSingleValue($tableName, $column_name, array('id' => $this->CoreLoad->session('id')))))) {
					return true;
				}
				//Must Be Unique
				elseif (strlen($this->CoreCrud->selectSingleValue($tableName, 'id', array($column_name => $number))) <= 0) {
					// Replace Empty Space with
					//Must be integer
					if (is_numeric($number) && strlen($number) >= 10) {
						//Check If number starts with country code
						if ('+' != substr($number, 0, 1)) {
							return true;
						} else {
							$this->form_validation->set_message('mobilecheck', "{field} should not include + (symbol)");
							return false;
						}
					} else {
						$this->form_validation->set_message('mobilecheck', "{field} must be atleast 10 numbers and should not include any symbol including (+) sign");
						return false;
					}
				} else {
					$this->form_validation->set_message('mobilecheck', 'This {field} is already in use by another account');
					return false;
				}
			} else {
				// $clear_number = $dial_code_clear . $number;
				$this->form_validation->set_message('mobilecheck', "$number {field} should start with $dial_code_clear");
				return false;
			}
		} else {
			//Check Status
			$status = $rules_validate['status'];
			$message = $rules_validate['message'];
			if ($status) {
				return true;
			} else {
				$this->form_validation->set_message('mobilecheck', "{field} $message");
				return false;
			}
		}
	}

	/**
	 *
	 * This Fuction is used to validate File Input Data
	 * The Method can be accessed via set_rules(callback_validimage[input_name])
	 *
	 * 1: To make file required use $this->form_validation->set_rules('file_name','File Name','callback_validimage[input_name|required]');
	 * 2: To force custom file type per file use $this->form_validation->set_rules('file_name','File Name','callback_validimage[input_name|jpg,jpeg,png,doc,docx,pdf,xls,txt]');
	 * 3: To have required and custom file type per file use $this->form_validation->set_rules('file_name','File Name','callback_validimage[input_name|required|jpg,jpeg,png,doc,docx,pdf,xls,txt]');
	 *
	 * N.B 
	 * -The callback_validimage method is used to validate the file input (file/images)
	 * - The input_name is the name of the input field (must be first passed callback_validimage[])
	 * - '|' is used to separate the input name and the allowed file types/required
	 *
	 */
	public function validimage($str, $parameters)
	{
		// Image and file allowed
		$allowed_ext = (!is_null($this->AllowedFile)) ? $this->AllowedFile : 'jpg|jpeg|png|doc|docx|pdf|xls|txt';
		$allowed_types = explode('|', $allowed_ext);
		// check if method uploadSettings is defined in Class CoreField
		$config = (method_exists('CoreField', 'uploadSettings')) ? $this->CoreField->uploadSettings() : array('max_size' => 2048);
		// Check if array $config has key max_size use ternarry
		$allowed_size = (array_key_exists('max_size', $config)) ? $config['max_size'] : 2048;
		// Change KB to Bytes
		$allowed_size_byte = $allowed_size * 1024;

		// Parameters
		$passed = explode('|', $parameters);
		// File name input_name
		$input_name = (isset($passed[0])) ? $passed[0] : null;
		$second_parameter = (isset($passed[1])) ? $passed[1] : null;
		// Check if there is key 2
		$third_parameter = (isset($passed[2])) ? $passed[2] : null;

		// Required
		$required = false;
		// Second Parameter
		if (strtolower($second_parameter) == 'required') {
			$required = true;
		} else {
			// check if $second_parameter is 
			$allowed_types = (!is_null($second_parameter)) ? explode(',', $second_parameter) : $allowed_types;
		}

		//Third Parameter
		if (strtolower($third_parameter) == 'required') {
			$required = true;
		} else {
			// check if $second_parameter is 
			$allowed_types = (!is_null($third_parameter)) ? explode(',', $third_parameter) : $allowed_types;
		}

		// Types show
		$allowed_types_show = implode(', ', $allowed_types);

		// If $str is array validate each
		if (array_key_exists($input_name, $_FILES)) {
			// File To be Uploaded | File Name &_FILES ['input_name]
			$file = $_FILES[$input_name];

			// Check if file['name'] is array
			if (is_array($file['name'])) {
				// Loop through each file
				for ($i = 0; $i < count($file['name']); $i++) {
					// Uploaad Values
					$value = array(
						'name' => $file['name'][$i],
						'type' => $file['type'][$i],
						'tmp_name' => $file['tmp_name'][$i],
						'error' => $file['error'][$i],
						'size' => $file['size'][$i]
					);

					//Get Values
					$file_name = $value['name'];
					// Size to int
					$file_size = (int) $value['size'];
					// Get file_name, explode where there is . and get the last array assign as file_ext
					$file_ext = explode('.', $file_name);
					$file_ext = strtolower(end($file_ext));

					// Check if Uploaded file exist
					if ($file_size > 0) {
						// Check if file is allowed
						if (!in_array($file_ext, $allowed_types)) {
							$this->form_validation->set_message('validimage', 'The {field} must be a file of type: ' . $allowed_types_show);
							return false;
						}

						// Check if file size is allowed
						if ($file_size > $allowed_size_byte) {
							$this->form_validation->set_message('validimage', 'The {field} must be less than ' . $file_size . ' - ' . $allowed_size . 'KB');
							return false;
						}
					} else {
						if ($required) {
							$this->form_validation->set_message('validimage', 'The {field} is required');
							return false;
						}
					}
				}
				return true;
			} else {
				$file_name = $file['name'];
				//Size to int
				$file_size = (int) $file['size'];
				// Get file_name, explode where there is . and get the last array assign as file_ext
				$file_ext = explode('.', $file_name);
				$file_ext = strtolower(end($file_ext));

				// Check if Uploaded file exist
				if ($file_size > 0) {
					// Check if file is allowed
					if (!in_array($file_ext, $allowed_types)) {
						$this->form_validation->set_message('validimage', 'The {field} must be a file of type: ' . $allowed_types_show);
						return false;
					}

					// Check if file size is allowed
					if ($file_size > $allowed_size_byte) {
						$this->form_validation->set_message('validimage', 'The {field} must be less than ' . $allowed_size . 'KB');
						return false;
					}
				} else {
					if ($required) {
						$this->form_validation->set_message('validimage', 'The {field} is required');
						return false;
					}
				}
				return true;
			}
		} else {
			$this->form_validation->set_message('validimage', 'The {field} is not passed, check your form input name');
			return false;
		}
	}
}

/* End of file Register.php */
