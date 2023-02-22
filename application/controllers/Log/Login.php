<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	/**
	 *
	 * The main controller for Administrator Backend
	 * -> The controller require to login as Administrator
	 */

	private $Module = 'user'; //Module
	private $Folder = 'log'; //Set Default Folder For html files and Front End Use
	private $SubFolder = ''; //Set Default Sub Folder For html files and Front End Use Start with /

	private $AllowedFile = null; //Set Default allowed file extension, remember you can pass this upon upload to override default allowed file type. Allowed File Extensions Separated by | also leave null to validate using jpg|jpeg|png|doc|docx|pdf|xls|txt change this on validation function at the bottom

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

		// Generate search breadcrumb
		$breadcrumbset[0] = "<a rel='nofollow' href='" . site_url() . "'>Home</a>";
		// Add search_value
		$breadcrumbset_last = "<span></span> Login / Register Account";
		array_push($breadcrumbset, $breadcrumbset_last);

		foreach ($breadcrumbset as $key => $value) {
			if (!is_null($value)) {
				$breadcrumb[$key] = $value;
			}
		}
		$breadcrumb = array_values($breadcrumb);
		$data['breadcrumb'] = (count($breadcrumb) > 1) ? $breadcrumb : null;

		//Form Submit URLs
		$data['form_register'] = $this->Register;
		$data['form_login'] = $this->Login;
		$data['notify_signup'] = $this->Notify->blank();

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
		$data = $this->load($this->plural->pluralize($this->Folder) . $this->SubFolder . "/login");

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
		if ($type == 'login') {
			$formData = $this->CoreLoad->input(); //Input Data

			$this->form_validation->set_rules("logname", "Email", "required|trim|min_length[1]|max_length[40]|callback_lognamecheck|valid_email");
			$this->form_validation->set_rules("password", "Password", "trim|required|min_length[1]|max_length[20]");
			$this->form_validation->set_rules("remember", "", "trim|max_length[5]");

			//Form Validation
			if ($this->form_validation->run() == TRUE) {

				// Login
				$formLOG['user_email'] = $formData['logname'];
				$formLOG['user_password'] = $formData['password'];

				// Remember
				if (array_key_exists('remember', $formData)) {
					$formLOG['remember'] = $formData['remember'];
				}
				// Login User
				$log_status = $this->login($formLOG);

				//Check if login is successful
				if ($log_status == 'success') {
					$this->session->set_flashdata('notification', 'notify'); //Notification Type

					// Get level
					$access_level = $this->CoreLoad->session('level');
					$redirect = ($access_level == 'individual') ? 'user' : 'comp';

					// Get User Level
					redirect("$redirect-dashboard", "refresh"); //Redirect to Page
				} elseif ($log_status == 'wrong') {
					$this->session->set_flashdata('notification', 'error'); //Notification Type
					$message = 'Failed!, wrong password or username'; //Notification Message				
					$this->index($message); //Open Page
				} elseif ($log_status == 'deactivated') {
					$this->session->set_flashdata('notification', 'error'); //Notification Type
					$message = 'Failed!, your account is suspended. Contact Admin'; //Notification Message				
					$this->index($message); //Open Page
				} else {
					$this->session->set_flashdata('notification', 'error'); //Notification Type
					$message = 'Failed!, account does not exist'; //Notification Message				
					$this->index($message); //Open Page
				}
			} else {
				$this->session->set_flashdata('notification', 'error'); //Notification Type
				$message = 'Please check the fields, and try again'; //Notification Message				
				$this->index($message); //Open Page
			}
		} elseif ($type == 'logout') {
			$this->session->sess_destroy(); //User Logout

			// Get CookieName
			$cookie_name = $this->CoreLoad->getCookieName();
			delete_cookie($cookie_name);

			// Redirect to Home Page
			redirect('/', 'refresh');
		} else {
			$this->session->set_flashdata('notification', 'notify'); //Notification Type
			$this->index(); //Index Page
		}
	}

	/**
	 *
	 * Fuction for Login Validation
	 * The fuction takes, accept form data which passed through CoreLoad Input
	 * 
	 */
	public function login($formData)
	{
		//Pluralize Module
		$tableName = $this->plural->pluralize($this->Module);
		$column_email = $this->CoreForm->get_column_name($this->Module, 'email'); //Email Column
		$column_logname = $this->CoreForm->get_column_name($this->Module, 'logname'); //Logname Column
		$column_password = $this->CoreForm->get_column_name($this->Module, 'password'); //Password Column
		$column_stamp = $this->CoreForm->get_column_name($this->Module, 'stamp'); //Stamp Column
		$column_level = $this->CoreForm->get_column_name($this->Module, 'level'); //Stamp Level
		$column_flg = $this->CoreForm->get_column_name($this->Module, 'flg'); //Stamp FLG
		$column_id = $this->CoreForm->get_column_name($this->Module, 'id'); //Stamp ID
		$column_default = $this->CoreForm->get_column_name($this->Module, 'default'); //Default

		//Get Array Data
		foreach ($formData as $key => $value) {
			if (strtolower($key) == $column_logname) {
				$logname = $value; //Set user logname
			} elseif (strtolower($key) == $column_email) {
				$email = $value; //Set user email
			} elseif (strtolower($key) == $column_password) {
				$password = $value; //Set user Password
			}
		}

		//Get Date Time
		$result = $this->db->select($column_stamp)->from($tableName)->where($column_email, $email)->limit(1)->get();
		if ($result->num_rows() === 1) {

			$row = $result->row();
			$stamp = $row->$column_stamp; //Date Time

			//Check If Enabled
			if ($this->db->select($column_flg)->where($column_email, $email)->get($tableName)->row()->$column_flg) {
				$hased_password = sha1($password); //Hashed Password
				$where = array($column_email => $email, $column_password => $hased_password); // Where Clause
				$query = $this->db->select("$column_id, $column_level, $column_default")->where($where)->limit(1)->get($tableName)->result(); //Set Query Select

				if ($query) {

					//Session ID
					$session_id = $this->CoreLoad->sessionName('id');
					$newsession[$session_id] = $query[0]->$column_id;

					//Session LEVEL
					$session_level = $this->CoreLoad->sessionName('level');
					$newsession[$session_level] = $query[0]->$column_level;

					//Session LOGGED
					$session_logged = $this->CoreLoad->sessionName('logged');
					$newsession[$session_logged] = TRUE;

					$this->session->set_userdata($newsession); //Create Session

					if (array_key_exists('remember', $formData)) {
						if ($formData['remember'] == 'yes') {

							$value  = $newsession[$session_id];
							$expire = 604800; // 1 week in seconds                                                    
							$secure = False;
							$domain = base_url();

							// CookieName
							$name = $this->CoreLoad->getCookieName();

							// Get Cookie Value
							$value = $this->encryption->encrypt($value);
							set_cookie($name, $value, $expire, $secure);
						}
					}

					// Check Default
					$default = $query[0]->$column_default;
					if ($default != 'no' || $default != 'yes') {
						// Updated Default to no
						$this->db->update($tableName, [$column_default => 'no'], [$column_id => $newsession[$session_id]]);
					}

					return 'success'; //Logged In
				} else {
					return 'wrong'; //Wrong Account Password / Logname
				}
			} else {
				return 'deactivated'; //Account Deactivated
			}
		} else {
			return 'error'; //Account Don't Exist
		}
	}

	/**
	 *
	 * Fuction for Login Validation
	 * The fuction takes, accept form data which passed through CoreLoad Input
	 * 
	 */
	public function loginOTP($formData)
	{
		//Pluralize Module
		$tableName = $this->plural->pluralize($this->Module);
		$column_logname = $this->CoreForm->get_column_name($this->Module, 'logname'); //Logname Column
		$column_default = $this->CoreForm->get_column_name($this->Module, 'default'); //Default Column
		$column_stamp = $this->CoreForm->get_column_name($this->Module, 'stamp'); //Stamp Column
		$column_level = $this->CoreForm->get_column_name($this->Module, 'level'); //Stamp Level
		$column_flg = $this->CoreForm->get_column_name($this->Module, 'flg'); //Stamp FLG
		$column_id = $this->CoreForm->get_column_name($this->Module, 'id'); //Stamp ID

		//Get Array Data
		foreach ($formData as $key => $value) {
			if (strtolower($key) == $column_logname) {
				$logname = $value; //Set user logname
			} elseif (strtolower($key) == $column_default) {
				$default = $value; //Set user Defaulr
			}
		}

		//Get Date Time
		$result = $this->db->select($column_stamp)->from($tableName)->where($column_logname, $logname)->limit(1)->get();
		if ($result->num_rows() === 1) {

			$row = $result->row();
			$stamp = $row->$column_stamp; //Date Time

			//Check If Enabled
			if ($this->db->select($column_flg)->where($column_logname, $logname)->get($tableName)->row()->$column_flg) {
				$where = array($column_logname => $logname, $column_default => $default); // Where Clause
				$query = $this->db->select("$column_id, $column_level")->where($where)->limit(1)->get($tableName)->result(); //Set Query Select

				if ($query) {

					//Session ID
					$session_id = $this->CoreLoad->sessionName('id');
					$newsession[$session_id] = $query[0]->$column_id;

					//Session LEVEL
					$session_level = $this->CoreLoad->sessionName('level');
					$newsession[$session_level] = $query[0]->$column_level;

					//Session LOGGED
					$session_logged = $this->CoreLoad->sessionName('logged');
					$newsession[$session_logged] = TRUE;

					$this->session->set_userdata($newsession); //Create Session

					if (array_key_exists('remember', $formData)) {
						if ($formData['remember'] == 'yes') {

							$value  = $newsession[$session_id];
							$expire = 604800; // 1 week in seconds                                                    
							$secure = False;
							$domain = base_url();

							// CookieName
							$name = $this->CoreLoad->getCookieName();

							// Get Cookie Value
							$value = $this->encryption->encrypt($value);
							set_cookie($name, $value, $expire, $secure);
						}
					}

					return 'success'; //Logged In
				} else {
					return 'wrong'; //Wrong Account Password / Logname
				}
			} else {
				return 'deactivated'; //Account Deactivated
			}
		} else {
			return 'error'; //Account Don't Exist
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
		} elseif (!is_null($this->CoreCrud->selectSingleValue($tableName, 'id', array($check => $str)))) {
			return true;
		} else {
			$this->form_validation->set_message('lognamecheck', 'This {field} account does not exist. Register first.');
			return false;
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
		$number = $str;

		//Check Rule
		$rules_validate = (method_exists('CoreField', 'mobileCheck')) ? $this->CoreField->mobileCheck($number) : false;
		$column_name = (filter_var($number, FILTER_VALIDATE_EMAIL)) ? 'email' : 'logname'; //Look Email / Phone Number
		//Validation
		if (!$rules_validate) {
			//Check First Letter if does not start with 0
			if (0 == substr($number, 0, 1)) {

				//Must be integer
				if (is_numeric($number) && strlen($number) == 10) {

					// Check Default Dial Code
					$default_dial_code = (method_exists(
						'CoreField',
						'defaultDialCode'
					)) ? $this->CoreField->defaultDialCode() : '+1';

					//Dial Code
					$dial_code = (!is_null($dial_code)) ? $dial_code : $default_dial_code; //Set Country Dial Code Here eg +1, by default it is empty
					$max_count = strlen($dial_code) - 1;
					//First Two Character
					$firstTwoNumbers = "+" . substr($number, 0, $max_count);
					//Check If number starts with country code
					if ($firstTwoNumbers != $dial_code) {
						return true;
					} else {
						$this->form_validation->set_message('mobilecheck', 'This {field} make sure your number start with "0"');
						return false;
					}
				} else {
					$this->form_validation->set_message('mobilecheck', '{field} must be 10 numbers and should not include the country code. Example: 07xxxxxxxx');
					return false;
				}
			} else {
				$this->form_validation->set_message('mobilecheck', 'This {field} make sure your number start with "0"');
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
}

/* End of file Login.php */
