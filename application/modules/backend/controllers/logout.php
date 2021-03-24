<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('frontend');
        // $this->load->helper('template');
    }

    public function index()
    {
        $this->session->unset_userdata('ids');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Your account has been logged out!
            </div>');
        redirect('backend/login');
    }
}
