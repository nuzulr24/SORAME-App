<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('frontend');
        // $this->load->helper('template');
    }

    public function index()
    {
        $data['title'] = "SORAME APP";
        $data['feature'] = "Dashboard";
        $this->frontend->display_themes('site', 'site/content/index', $data);
    }
}
