<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopee {

    protected $CI;
    protected $keyword;
    protected $base_path = "https://shopee.co.id/api/v2/";

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function index()
    {

    }

}
?>