<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('frontend');
        // $this->load->helper('template');
    }

    public function index()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data['title'] = "Admin Panel";
            $this->frontend->display_themes('auth', 'backend/auth/login', $data);

        } else {
            $this->_login();
        }
    }

    public function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        //lakukan pengecekan apakah email dari user ada
        $user = $this->db->get_where('users', ['userEmail' => $email])->row_array();

        if ($user) { //jika user active
            if (password_verify($password, $user['userPassword'])) {
                $data = [
                    'idUser' => $user['userID'],
                ];
                $this->session->set_userdata($data);
                redirect('main/dashboard');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
						Wrong password !
				</div>');
                redirect('backend/login');
            }

        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
				Email Not Register
			</div>');
            redirect('backend/login');
        }
    }
}
