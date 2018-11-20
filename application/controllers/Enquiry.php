<?php

class Enquiry extends MY_Controller {

    /**  CI_Controller
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    var $data = array();
    var $error = array();
    var $template = array();
    var $middle = '';
    var $left = '';
    var $right = '';
    var $dateString = "%Y-%m-%d";
    var $dateStringWithTime = "%Y-%m-%d %H:%i:%s";
    var $currentDate = '';
    var $currentDateTime = '';

    public function __construct() {
        parent::__construct();
        $this->currentDate = mdate($this->dateString, time());
        $this->currentDateTime = mdate($this->dateStringWithTime, time());
    }

    /* public function index()
      {
      //die();
      $this->checkLogin();
      $this->data['title'] = 'Enquiry';


      $this->data['MetaTitle'] 		= 'Enquiry For Register';
      $this->data['MetaDescription'] 	= 'Enquiry For Register';
      $this->data['MetaKeywords'] 	= 'Enquiry For Register';

      $this->form_validation->set_message('RequestComment', 'required', 'Enter comments');
      $config = array(

      array(
      'field'   => 'CompanyName',
      'label'   => 'CompanyName',
      'rules'   => 'trim|required|xss_clean'
      ),
      array(
      'field'   => 'ContactPerson',
      'label'   => 'ContactPerson',
      'rules'   => 'trim|required|xss_clean'
      ),
      array(
      'field'   => 'ContactPhone',
      'label'   => 'ContactPhone',
      'rules'   => 'trim|required|xss_clean|numeric'
      ),
      array(
      'field'   => 'ContactEmail',
      'label'   => 'ContactEmail',
      'rules'   => 'trim|required|xss_clean|valid_email|is_unique[users_enquiry.ContactEmail]'
      )
      );

      $this->form_validation->set_rules($config);


      if ($this->form_validation->run() == FALSE)
      {

      $validation_errors = validation_errors();
      $valerrors = explode('</p>', $validation_errors);

      foreach ($valerrors as $index => $error) {
      $error = str_replace('<p>', '', $error);
      $error = trim($error);

      if(!empty($error))
      {
      $errors[$index] = $error;

      }

      }


      //echo json_encode($errors, JSON_FORCE_OBJECT);
      echo json_encode($errors, JSON_FORCE_OBJECT);

      }
      else{

      $table =  'users_enquiry';
      $columns = array();
      $columns['RequestComment']  = $this->input->post('RequestComment');
      $columns['CompanyName']     = $this->input->post('CompanyName');
      $columns['ContactPerson']   = $this->input->post('ContactPerson');
      $columns['ContactPhone']    = $this->input->post('ContactPhone');
      $columns['ContactEmail']    = $this->input->post('ContactEmail');
      $columns['EnquiryDate']     = $this->currentDateTime;

      $this->UniversalModel->save($table,$columns,NULL,NULL);
      $sucess["msg"] = 'You enquiry is submitted successfully';
      echo json_encode($sucess);
      $this->session->set_flashdata('inquirymessage', 'You enquiry is submitted successfully');

      }


      //$this->middle = 'users/enquiry';
      //parent::layout();
      } */

    public function index() {
        $this->checkLogin();
        $this->data['title'] = 'Enquiry';

        /* set meta variables */
        $this->data['MetaTitle'] = 'Enquiry For Register';
        $this->data['MetaDescription'] = 'Enquiry For Register';
        $this->data['MetaKeywords'] = 'Enquiry For Register';
        $this->form_validation->set_message('is_unique', 'Enquiry is already submitted');

        $config = array(
            array(
                'field' => 'CompanyName',
                'label' => 'Company Name',
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'ContactPerson',
                'label' => 'Contact Person',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'ContactPhone',
                'label' => 'Contact Phone',
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            array(
                'field' => 'ContactEmail',
                'label' => 'Contact Email',
                'rules' => 'trim|required|xss_clean|valid_email|is_unique[users_enquiry.ContactEmail]'
            )
        );
        $this->form_validation->set_message('required', '%s required');
        $this->form_validation->set_rules($config);


        if ($this->form_validation->run() == FALSE) {

           // $this->load->view('users/enquiry', $this->data);
        } else {

            $inquiry_table = 'users_enquiry';
            $columns = array();
            $columns['RequestType'] = 'Customer Request';
            $columns['RequestComment'] = $this->input->post('RequestComment');
            $columns['CompanyName'] = $this->input->post('CompanyName');
            $columns['ContactPerson'] = $this->input->post('ContactPerson');
            $columns['ContactPhone'] = $this->input->post('ContactPhone');
            $columns['ContactEmail'] = $this->input->post('ContactEmail');
            $columns['EnquiryDate'] = $this->currentDateTime;
            $columns['Language'] = 'chinese';
            
            $this->UniversalModel->save($inquiry_table, $columns, NULL, NULL);
            $this->session->set_flashdata('inquirymessage', '您的申请已成功提交，我们的工作人员会尽快给您回复邮件。');
            //header("refresh:5;url=" . site_url());
        }


        //$this->middle = 'users/enquiry';
       // $this->load->view('users/enquiry');
        $this->load->view('users/enquiry', $this->data);
       // parent::layout();
    }

    public function companyMessage() {
        $this->form_validation->set_message('companyMessage', 'Company name is required');
    }

    /* public function checkLogin() {

      if ($this->session->userdata("logged") == TRUE) {
      redirect('users', 'refresh');
      }
      } */

    public function checkLogin() {

        if ($this->session->userdata("logged") == TRUE) {
			
			if ($this->session->userdata("Role") == 'customer') {
                redirect('customer/plans', 'refresh');
            } elseif ($this->session->userdata("Role") == 'supplier') {
				redirect('supplier/dashboard', 'refresh');
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
 
