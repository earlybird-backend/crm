<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	var $data      = array();
	var $error     = array();
	
	var $template  = array();
	var $middle   =''; 
	var $left     ='';
	var $right    = '';
	
	var $datestring = "%Y-%m-%d";
	var $dateStringWithTime = "%Y-%m-%d %H:%i:%s";
	var $currentDate = '';
    var $currentDateTime = '';
	 
	public function __construct()
    {
        parent::__construct();
		$this->currentDate = mdate($this->datestring, time());
        $this->currentDateTime = mdate($this->dateStringWithTime, time());
		
		$this->load->model('slug_model');
        $this->load->model('content_model');
		
		$this->load->model('PackageModel');
		
		$this->load->model('UniversalModel');
		//$this->load->model('CategoryModel');
		$this->load->model('SendEmailModel');
		$this->load->model('TemplateModel');
		$this->load->model('language_model');
		
    }
	
	public function checkLogin()
	{  
		if(empty($this->session->userdata('UserId')) && $this->session->userdata('UserId')=='')
		{
		  $this->session->set_flashdata('message', $this->lang->line('auth_login_error'));
		  redirect('auth', 'refresh');
		} else {
			$this->setUserData();
			return;
		}
	}
	
	
	
	public function setUserData(){
		$columns = array('UserId' => $this->session->userdata('UserId'));
	    $table = 'site_users';
	    $result = $this->UniversalModel->getRecords($table, $columns);
		//print_r($result);
		if($result)
		{	
			$this->userdata = $result[0];
		}
	    
		
	}
	
	
	
	
	
		
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */