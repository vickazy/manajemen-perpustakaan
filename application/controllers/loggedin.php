<?php
class Loggedin extends CI_Controller{

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->form_validation->set_error_delimiters("<span class='label label-danger'>","</span>");
	}
	
	function index()
	{
	
		$username = $this->session->userdata('username'); 
		if (empty($username) && ($this->session->userdata('login') != TRUE)) {
			$this->load->view('form_loggedin');
		} else {
			redirect('pinjaman');
		} 
	}
	
	function opensession()
	{
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');
		
		if ($this->form_validation->run()) {
			$data = array(
							'USERNAME' => $this->input->post('username'),
							'PASSWORD' => $this->input->post('password')
						 );
			$user = $this->user_model->get_by($data);
			if ($user->num_rows() > 0) {
				$this->session->set_userdata('user_login', $this->input->post('username')); 
				redirect('pinjaman');
			} else {
				$this->session->set_flashdata('notice', 'Username dan Password tidak cocok .!');
				redirect('authentication');
			}
		} else {
			$this->load->view('form_loggedin');
		}
	}
	
	function endsession() {
		$this->session->unset_userdata('user_login');
		$this->session->sess_destroy();
		
		$this->session->set_flashdata('notice', 'Anda telah berhasil logout');
		redirect('loggedin');
	}
}