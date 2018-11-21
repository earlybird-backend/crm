<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends MY_Controller {

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
    var $datestring = "%Y-%m-%d";
    var $dateStringWithTime = "%Y-%m-%d %H:%i:%s";
    var $currentDate = '';
    var $currentDateTime = '';

    public function __construct() {
        parent::__construct();
        $this->currentDate = mdate($this->datestring, time());
        $this->currentDateTime = mdate($this->dateStringWithTime, time());
    }

    public function index() {
        $this->checkLogin();
        $this->data['title'] = 'Login';

        $config = array(
            array(
                'field' => 'type',
                'label' => 'type',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'EmailAddress',
                'label' => 'EmailAddress',
                'rules' => 'trim|required|xss_clean|valid_email|callback_wrong_username_check'
            ),
            array(
                'field' => 'Password',
                'label' => 'Password',
                'rules' => 'trim|required|xss_clean'
            )
        );

        $this->form_validation->set_rules($config);


        if ($this->form_validation->run() == FALSE) {

            // $this->middle = 'auth/index';
           // $this->load->view('auth/index');
        } else {

            $table = 'site_users';
            $role = $this->input->post('type');
            $columns = array();
            $columns['Role'] = $this->input->post('type');
            $columns['EmailAddress'] = $this->input->post('EmailAddress');
            $columns['Password'] = md5($this->input->post('Password'));

            //pr($columns); die;
            // Add new columns and get Records

            $result = $this->UniversalModel->checkRecord($table, $columns);
            //echo '<pre>'; print_r($result); echo '</pre>'; die;
            if (!$result) {

                echo $this->session->set_flashdata('message', $this->lang->line('auth_login_error_m'));
                //redirect('auth', 'refresh');
               // $this->middle = 'auth/index';
               // $this->load->view('auth/index');
            } else {

                $result = $this->UniversalModel->getRecords($table, $columns);
                
                $UserType = $table;
                // Update last Login
                $columns = array();
                //$columns['Online'] = '1';
                $columns['LastLogin'] = $this->currentDateTime;
                $where = 'UserId';

                $this->UniversalModel->save($table, $columns, $where, $result[0]['UserId']);

                $this->session->set_userdata('logged', TRUE);
                $this->session->set_userdata('Role', $role);
                $this->session->set_userdata('UserId', $result[0]['UserId']);
                $this->session->set_userdata('ContactName', $result[0]['ContactName']);
                $this->session->set_userdata('EmailAddress', $result[0]['EmailAddress']);
                $this->session->set_flashdata('message', $this->lang->line('auth_login_m'));

                // Redirect to previous any page after login
                if ($this->session->userdata("Role") == 'customer') {

                    redirect('customer/home', 'refresh');

                } elseif ($this->session->userdata("Role") == 'supplier') {

                    redirect('supplier/home', 'refresh');

                } elseif ($this->session->userdata("Role") == 'admin') {

                    redirect('manager/home', 'refresh');

                }

                //$this->checkLogin();
            }
        }

       //$this->load->view('auth/index');
       $this->load->view('auth/index', $this->data);
     
    }

    public function error() {
        $this->data['title'] = 'Error';
        $this->right = 'auth/error';
        parent::innerlayout();
    }

    public function forgetpassword() {
        $this->checkLogin();
        $this->data['title'] = $this->lang->line('auth_forget_password_h');

        $config = array(
            array(
                'field' => 'EmailAddress',
                'label' => 'Email address',
                'rules' => 'trim|required|valid_email|xss_clean'
            ),
            array(
                'field' => 'type',
                'label' => 'you are',
                'rules' => 'trim|required|xss_clean'
            )
        );

        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
           
        } else {

            $table = 'site_users';
            $where = array('EmailAddress' => $this->input->post('EmailAddress'), 'Role' => $this->input->post('type'));

            $result = $this->UniversalModel->checkRecord($table, $where);
            //echo '<pre>'; print_r($result); die;
            if (!$result) {
                $this->session->set_flashdata('forgotmessage', $this->lang->line('auth_forget_password_error'));
                //redirect('auth/forgetpassword', 'refresh');
            } else {


                $emaildata = $this->UniversalModel->getRecords($table, $where);
                $emaildata[0]['siturl'] = site_url('auth');
                $admintable = 'admin_bak';
                $admindata = $this->UniversalModel->getRecords($admintable);


                // Send Email
                $tid = 9;
                $resultTemplate = $this->TemplateModel->getTemplateById($tid);

                // Replace string
                $mixed_search = array("{Role}", "{siteurl}", "{EmailAddress}", "{UserId}", "{CompanyName}");
                $mixed_replace = array($emaildata[0]['Role'], site_url('auth'), $emaildata[0]['EmailAddress'], $emaildata[0]['UserId'], $emaildata[0]['CompanyName']);
                $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);

                $data = array();
                $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
                $data['contents'] = $messagebody;
                $body = $this->parser->parse('emailtemplate/resetpasswords', $data, TRUE);

                //echo $body; die;
                //Send Email
                $return = $this->SendEmailModel->sendEmail($admindata[0]['Email'], $resultTemplate[0]['Subject'], $body, $emaildata[0]['FirstName']);

                $this->data['heading'] = $this->lang->line('auth_forget_password_h');
                $this->data['message'] = $this->lang->line('auth_forget_password_success');
                //$this->middle = 'auth/forgetpassword';
                $this->session->set_flashdata('message', 'Email sent successfully');
                //$this->right = 'auth/forgetpassword';
                redirect("auth/forgetpassword");
            }
        }

        //parent::layout();
        $this->load->view('auth/forgetpassword',$this->data);
    }

    public function logout() {

        // Update Login status
        $columns = array();

        $where = 'UserId';
        $UserId = $this->session->userdata('UserId');
        //$table = 'site_users';
        //$this->UniversalModel->save($table, $columns, $where, $UserId);
        // Unset Session Variables	  
        $this->session->unset_userdata('logged');
        $this->session->unset_userdata('Role');
        $this->session->unset_userdata('UserId');
        $this->session->unset_userdata('EmailAddress');
        $this->session->sess_destroy();

        $this->session->set_flashdata('message', 'You are logged out successfully');
        redirect('auth', 'refresh');
    }

    public function checkLogin() {

        if ($this->session->userdata("logged") == TRUE) {
            if ($this->session->userdata("Role") != null) {

                redirect($this->session->userdata("Role").'/home', 'refresh');

            }
        }
    }

    public function wrong_username_check($EmailAddress) {

        $table = 'site_users';

        $data = array('EmailAddress' => $EmailAddress);
        $result = $this->UniversalModel->checkRecord($table, $data);

        //pr($result);
        if (empty($result)) {
            $this->form_validation->set_message('wrong_username_check', 'Sorry ! Wrong EmailAddress Entered or Email not exists');
            return FALSE;
        } else {

            return TRUE;
        }
    }

    public function wrong_password_check() {

        $table = 'site_users';
        $data = array('EmailAddress' => $this->input->post('EmailAddress'), 'Password' => md5($this->input->post('Password')));
        $result = $this->UniversalModel->checkRecord($table, $data);

        //pr($result);
        if (empty($result)) {
            $this->form_validation->set_message('wrong_password_check', 'Sorry ! Wrong Password Entered.');
            return FALSE;
        } else {

            return TRUE;
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */