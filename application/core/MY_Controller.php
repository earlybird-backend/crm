<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $website;
	protected $data = array();
	var $error     = array();
	
	var $template  = array();
	var $middle   =''; 
	var $left     ='';
	var $right    = '';
	var $header_slider = '';
	var $header_search = '';
	var $breadcrumb = '';
	protected $current_controller ;
	
	protected $langs = array();
    protected $default_lang;
    protected $current_lang;
	function __construct()
	{
		parent::__construct();

        $this->data['MetaTitle'] 		= 'EPFO';
		$this->data['MetaDescription'] 	= '欢迎来到 EPFO';
		$this->data['MetaKeywords'] 	= 'EPFO';
		
		// First of all let's see what languages we have and also get the default language
		$this->load->model('language_model');
		$this->load->model('ConfigurationModel');
        $available_languages = $this->language_model->get_all();

        if(isset($available_languages))
        {
            foreach($available_languages as $language)
            {
                $this->langs[$language->slug] = array(
                    'id'=>$language->id,
                    'slug'=>$language->slug,
                    'name'=>$language->language_name,
                    'language_directory'=>$language->language_directory,
                    'language_code'=>$language->language_code,
                    'alternate_link'=>'/'.$language->slug,
                    'default'=>$language->default);

                if($language->default == '1')
                {
                    $_SESSION['default_lang'] = $language->slug;
                    $this->default_lang = $language->slug;
                    $this->langs[$language->slug]['alternate_link'] = '';
                }
            }
        }

        // Verify if we have a language set in the URL;
        $lang_slug = $this->uri->segment(1);


        // If we do, and we have that languages in our set of languages we store the language slug in the session
        if(isset($lang_slug) && array_key_exists($lang_slug, $this->langs))
        {
            $this->current_lang = $lang_slug;
            $_SESSION['set_language'] = $lang_slug;

            // Let's make sure that if the default language is in url, we remove it from there and redirect
            if($lang_slug===$this->default_lang)
            {
                $segs = $this->uri->segment_array();
                unset($segs[1]);
                $new_url = implode('/',$segs);
                redirect($new_url, 'location', 301);
            }
        }
        //else if a session variable set_language is not set but there exists a cookie named set_language, we will use those
        // If not, we set the language session to the default language
        else
        {
            if(!isset($_SESSION['set_language']))
            {
                $set_language = get_cookie('set_language',TRUE);
                if(isset($set_language)  && array_key_exists($set_language, $this->langs))
                {
                    $this->current_lang = $set_language;
                    $_SESSION['set_language'] = $this->current_lang;
                    $language  = ($this->current_lang==$this->default_lang) ? '' : $this->current_lang;
                    redirect($language);

                } else {
                    # set the default lang when visiting the site for the first time
                    $this->current_lang = $this->default_lang;
                    $_SESSION['set_language'] = $this->default_lang;
                }
            }
            else
            {
                $this->current_lang = $this->default_lang;
                $_SESSION['set_language'] = $this->default_lang;
            }
        }
        // We set a cookie so that if the visitor come again, he will be redirected to its chosen language
        set_cookie('set_language',$_SESSION['set_language'],2600000);

        // Now we store the languages as a $data key, just in case we need them in our views
        $this->data['langs'] = $this->langs;

        // Also let's have our current language in a $data key
        $this->data['current_lang'] = $this->langs[$this->current_lang];

        // For links inside our views we only need the lang slug. If the current language is the default language we don't need to append the language slug to our links
        if($this->current_lang != $this->default_lang)
        {
            $this->data['lang_slug'] = $this->current_lang.'/';
        }
        else
        {
            $this->data['lang_slug'] = '';
        }

        $_SESSION['lang_slug'] = $this->data['lang_slug'];


        $this->load->model('website_model');
        $this->website = $this->website_model->get();
        $this->data['website'] = $this->website;

        // Get the default page description and title from the database
        $this->data['page_title'] = $this->website->page_title;
        $this->data['page_description'] = $this->website->page_title;
		$this->data['before_head'] = '';
		$this->data['before_body'] = '';

        $this->current_controller = $this->uri->segment(1)."/".$this->uri->segment(2);
	}
	
	// This function set the layout for each view
	public function base64url_encode($data) {
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	public function base64url_decode($data) {
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
	 
	public function debuglog($logtext)
	{
	    $file = BASEPATH."log.txt";
	
	    if(is_array($logtext))
	        $logtext = 'This array is => '.serialize($logtext);
	
	    if(file_exists($file))
	    {
	        $tmpContents = file_get_contents($file);
	         
	        file_put_contents($file,'>>>>>> begin debug :'.date('y-m-d h:i:s',time()).' <<<<<<'.PHP_EOL);
	        file_put_contents($file,$logtext.PHP_EOL,FILE_APPEND);
	        file_put_contents($file,'>>>>>> end debug :'.date('y-m-d h:i:s',time()).' <<<<<<'.PHP_EOL,FILE_APPEND);
	        file_put_contents($file,''.PHP_EOL,FILE_APPEND);
	        file_put_contents($file,$tmpContents,FILE_APPEND);
	    }
	}	 
	 
	 public function template(){
			
		$this->checkAdminLogin();
		
		if (!$this->middle){
				
				 $this->middle = 'admin/dashboard';
		}
		
		$template['header'] = $this->load->view('admin/header', $this->data, true);
		//$template['header_slider'] = $this->load->view('admin/slider', $this->data, true);
		//$template['header_search'] = $this->load->view('admin/header_search', $this->data, true);
		$template['left']   = $this->load->view('admin/left', $this->data, true);
		$template['middle'] = $this->load->view($this->middle, $this->data, true);
		$template['right'] = '';
		$template['footer'] = $this->load->view('admin/footer', $this->data, true);
		$this->load->view('admin/template', $template);
	}
	
	
	public function checkAdminLogin()
	{  
		//if($this->session->userdata("adminLogin") && $this->session->userdata("AdminId")!=''){
	if($this->session->userdata("AdminId")!=''){
		
				return true;

		}else{
			redirect('admin/login', 'refresh');
		}
	}
	
	// Front End Layout
	public function layout(){
			
		if (!$this->middle){
				 $this->middle = 'layout/middle';
		}
		
		$template['header'] = $this->load->view('layout/header', $this->data, true);
		
		$template['middle'] = $this->load->view($this->middle, $this->data, true);
		
		$template['footer'] = $this->load->view('layout/footer', '', true);

        $this->load->view('layout/template', $template);

	}
	
	
	// Front End Layout
	public function innerLayout(){
			
		/*if (!$this->left){
				
			$type = $this->session->userdata('type');
			//$this->left = $type.'/left';
		}*/
		
		if (!$this->right){
				
		   //$this->right = 'layout/right';
		}
		
		//$template['header']   		= $this->load->view('layout/header', $this->data, true);
		//$template['breadcrumb']   	= $this->load->view('layout/breadcrumb_inner', $this->data, true);
		//$template['header_search']  = $this->load->view('layout/header_search', $this->data, true);
		
		$template['timesettings'] = $this->ConfigurationModel->getConfiguration();
		
		$template['header_inner'] 		= $this->load->view('layout/header_inner', $this->data, true);
		$template['middle'] 		= $this->load->view($this->right, $this->data, true);
		$template['footer_inner'] 		= $this->load->view('layout/footer_inner', $this->data, true);
		$this->load->view('layout/template_inner', $template);

	}
	
	
	public function loginLayout(){
			
		$this->load->view('auth/index', $this->data, true);

	}
	
	
	protected function render($the_view = NULL, $template = 'master')
	{
        if($template == 'json' || $this->input->is_ajax_request())
		{
			header('Content-Type: application/json');
			echo json_encode($this->data);
		}
		elseif(is_null($template))
		{
			$this->load->view($the_view,$this->data);
		}
		else
		{
			$this->data['the_view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
			$this->load->view('templates/' . $template . '_view', $this->data);
		}
	}
	
	
	/*public function pr($arr)
	{
		echo '<pre>'.print_r($arr).'</pre>';
		
	}*/

	protected  function toJson($data){
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
	
	
}

class Admin_Controller extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
        $this->load->library('postal');
		$this->load->helper('url');
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('admin/user/login', 'refresh');
		}
        $current_user = $this->ion_auth->user()->row();
        $this->user_id = $current_user->id;
		$this->data['current_user'] = $current_user;
		$this->data['current_user_menu'] = '';
		/*if($this->ion_auth->in_group('admin'))
		{
			$this->data['current_user_menu'] = $this->load->view('templates/_parts/user_menu_admin_view.php', NULL, TRUE);
		}*/

		$this->data['page_title'] = $this->website->page_title;
        $this->data['page_description'] = $this->website->page_title;
	}
	protected function render($the_view = NULL, $template = 'admin_master')
	{
		parent::render($the_view, $template);
	}
}

class Public_Controller extends MY_Controller
{
    function __construct()
	{
        parent::__construct();
        $this->load->model('banned_model');
        $ips = $this->banned_model->fields('ip')->set_cache('banned_ips',3600)->get_all();
        $banned_ips = array();
        if(!empty($ips))
        {
            foreach($ips as $ip)
            {
                $banned_ips[] = $ip->ip;
            }
        }
        if(in_array($_SERVER['REMOTE_ADDR'],$banned_ips))
        {
            echo 'You are banned from this site.';
            exit;
        }
        if($this->website->status == '0') {
            $this->load->library('ion_auth');
            if (!$this->ion_auth->logged_in()) {
                redirect('offline', 'refresh', 503);
            }
        }
	}

    protected function render($the_view = NULL, $template = 'public_master')
    {
        /* load a generic language file (this language file will be used across many pages - like in the footer of pages) */
        $this->load->language('app_lang',$this->langs[$this->current_lang]['language_directory']);

        /* you can load a specific language file inside the controller constructor with $this->language_file = ''.
        The file will be loaded from the app_files directory inside specific language directory */
        if(!isset($this->language_file))
        {
            $uri = explode('/', uri_string());
            $calling_class = get_class($this);
            $url = array();
            foreach ($uri as $key => $value) {
                if(trim(strlen($value)>0))
                {
                    if (is_numeric($value) || ($value==$this->current_lang)) unset($uri[$key]);
                    else $url[$key] = str_replace('-', '_', $value);
                }
            }

            $methods = debug_backtrace();

            foreach($methods as $method)
            {
                if($method['function']!=='render' && method_exists($calling_class,$method['function']))
                {
                    $current_method = $method['function'];
                }
            }

            $method_key = array_search($current_method, $url);
            $language_file_array = array_slice($url, 0, ($method_key + 1));

            $calling_class = strtolower($calling_class);
            if (!in_array($calling_class, $language_file_array)) $language_file_array[] = $calling_class;
            if (!in_array($current_method, $language_file_array)) $language_file_array[] = $current_method;
            $this->language_file = implode('_', $language_file_array);
        }

        /* verify if a language file specific to the method exists. If it does, load it. If it doesn't, simply do not load anything */
        if(file_exists(APPPATH.'language/'.$this->langs[$this->current_lang]['language_directory'].'/app_files/'.strtolower($this->language_file).'_lang.php')) {
            $this->lang->load('app_files/'.strtolower($this->language_file).'_lang', $this->langs[$this->current_lang]['language_directory']);
        }

        $this->load->library('menus_creator');
        $this->data['top_menu'] = $this->menus_creator->get_menu('top-menu',$this->current_lang,'bootstrap_menu');
        parent::render($the_view, $template);
    }
}
