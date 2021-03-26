<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    

    public function display_themes($alias, $partial, $data = null) {
        switch ($alias) {
            case 'admin_views':
                if(!$data) {
                    $this->CI->load->view('frontend/admin/header');
                    $this->CI->load->view($partial);
                    $this->CI->load->view('frontend/admin/footer');
                } else {
                    $this->CI->load->view('frontend/admin/header', $data);
                    $this->CI->load->view($partial, $data);
                    $this->CI->load->view('frontend/admin/footer', $data);
                }
                break;
            
            case 'admin-footer':

                break;
            
            case 'auth':
                if(!$data) {
                    $this->CI->load->view('frontend/auth/header');
                    $this->CI->load->view($partial);
                    $this->CI->load->view('frontend/auth/footer');
                } else {
                    $this->CI->load->view('frontend/auth/header', $data);
                    $this->CI->load->view($partial, $data);
                    $this->CI->load->view('frontend/auth/footer', $data);
                }
                break;

            case 'site':
                if(!$data) {
                    $this->CI->load->view('frontend/site/header');
                    $this->CI->load->view($partial);
                    $this->CI->load->view('frontend/site/footer');
                } else {
                    $this->CI->load->view('frontend/site/header', $data);
                    $this->CI->load->view($partial, $data);
                    $this->CI->load->view('frontend/site/footer', $data);
                }
                break;

            default:
                # code...
                break;
        }
    }

    public function foo() {
        echo 'foot';
    }

}