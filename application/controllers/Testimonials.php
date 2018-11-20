<?php
class Testimonials extends MY_Controller {

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

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->checkLogin();
		$this->data['title'] = 'Testimonials';

        /* set meta variables */
        $this->data['MetaTitle'] = 'testimonials';
        $this->data['MetaDescription'] = 'Welcome to EPFO';
        $this->data['MetaKeywords'] = 'EPFO';

        $this->middle = 'layout/testimonials';
        parent::layout();
    }
	
	
	public function checkLogin() {

        if ($this->session->userdata("logged") == TRUE) {
            if ($this->session->userdata("Role") == 'customer') {
                redirect('customer/plans', 'refresh');
            } else {

                redirect('supplier/dashboard', 'refresh');
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
 
