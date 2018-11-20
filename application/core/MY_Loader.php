<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Loader extends CI_Loader {
    function __construct() {
        parent::__construct();
    }
    public function get_loaded_classes()
    {
        return $this->_ci_classes;
    }
    public function get_loaded_helpers()
    {
        $loaded_helpers = array();
        if(sizeof($this->_ci_helpers)!== 0) {
            foreach ($this->_ci_helpers as $key => $value)
            {
                $loaded_helpers[] = $key;
            }
        }
        return $loaded_helpers;
    }
    public function get_loaded_models()
    {
        return $this->_ci_models;
    }
	
	
	// This function set the layout for each view
	public function base64url_encode($data) {
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	public function base64url_decode($data) {
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
	
}
/* End of file 'MY_Loader' */
/* Location: ./application/core/MY_Loader.php */